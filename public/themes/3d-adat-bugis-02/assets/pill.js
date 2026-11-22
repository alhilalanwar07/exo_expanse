/* Zehan Invitation Pill 1.0.12. Uses existing media/camera/scroll runtimes only. */
(function () {
    'use strict';
    if (window.ZehanInvitationPill) { window.ZehanInvitationPill.boot(); return; }

    var controllers = [], scanFrame = 0, globalObserver = null, hooked = false;
    var ROOTS = '[data-zipill-source], [data-tlwc-root], .apeiron-autoscroll-wrap, .apeiron-scroll-btn, .elementor-widget-wds_audio, #wds-audio-box';
    var VARIABLES = ['bg', 'fg', 'active-bg', 'active-fg', 'width', 'button-height', 'icon-size', 'radius', 'padding', 'opacity', 'top', 'gap', 'z'];

    function all(selector, root) { try { return Array.prototype.slice.call((root || document).querySelectorAll(selector)); } catch (e) { return []; } }
    function one(selector, root) { return all(selector, root)[0] || null; }
    function matches(el, selector) { try { return !!el && !!el.matches && el.matches(selector); } catch (e) { return false; } }
    function closest(el, selector) { try { return el && el.closest ? el.closest(selector) : null; } catch (e) { return null; } }
    function hidden(el, value) {
        if (!el || el.hasAttribute('hidden') === value) return;
        if (value) el.setAttribute('hidden', ''); else el.removeAttribute('hidden');
    }
    function attr(el, name, value) { if (el && el.getAttribute(name) !== String(value)) el.setAttribute(name, String(value)); }
    function style(el, name, value) { if (el && el.style.getPropertyValue(name) !== value) el.style.setProperty(name, value); }
    function parse(el, name) { try { return JSON.parse(el.getAttribute(name) || '{}'); } catch (e) { return {}; } }
    function editor() {
        try { return document.body.classList.contains('elementor-editor-active') || !!(window.elementorFrontend && window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode()); } catch (e) { return false; }
    }
    function present(el) {
        if (!el || !el.isConnected || el.hidden) return false;
        var cs = getComputedStyle(el), rect = el.getBoundingClientRect();
        return cs.display !== 'none' && cs.visibility !== 'hidden' && Number(cs.opacity) > 0.02 && rect.width > 0 && rect.height > 0 && rect.bottom > 1 && rect.top < window.innerHeight;
    }
    // Resolve CSS colors only while the skin changes, never on every scroll frame.
    // A local canvas supports both classic rgb/hex and browser-supported color syntax.
    var colorContext = null;
    function rgba(value) {
        if (!value || (window.CSS && CSS.supports && !CSS.supports('color', value))) return null;
        try {
            if (!colorContext) colorContext = document.createElement('canvas').getContext('2d', { willReadFrequently: true });
            if (!colorContext) return null;
            colorContext.clearRect(0, 0, 1, 1);
            colorContext.fillStyle = value;
            colorContext.fillRect(0, 0, 1, 1);
            var pixel = colorContext.getImageData(0, 0, 1, 1).data;
            return [pixel[0], pixel[1], pixel[2], pixel[3] / 255];
        } catch (e) {
            // Privacy settings may block canvas readback. Keep a no-canvas rgb fallback.
            var match = String(value).match(/^rgba?\(\s*([\d.]+)[, ]+([\d.]+)[, ]+([\d.]+)(?:\s*[,/]\s*([\d.]+))?\s*\)$/i);
            return match ? [Number(match[1]), Number(match[2]), Number(match[3]), match[4] === undefined ? 1 : Number(match[4])] : null;
        }
    }
    function composite(foreground, background) {
        var alpha = foreground[3];
        return [0, 1, 2].map(function (i) { return foreground[i] * alpha + background[i] * (1 - alpha); }).concat(1);
    }
    function luminance(color) {
        var linear = color.slice(0, 3).map(function (channel) {
            var v = channel / 255;
            return v <= 0.04045 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
        });
        return linear[0] * 0.2126 + linear[1] * 0.7152 + linear[2] * 0.0722;
    }
    function contrast(color, background) {
        if (!color) return 0;
        var a = luminance(composite(color, background)), b = luminance(background);
        return (Math.max(a, b) + 0.05) / (Math.min(a, b) + 0.05);
    }
    function readableColor(requested, background, preferred) {
        if (contrast(rgba(requested), background) >= 4.5) return requested;
        if (preferred && contrast(rgba(preferred), background) >= 4.5) return preferred;
        return contrast([0, 0, 0, 1], background) >= contrast([255, 255, 255, 1], background) ? '#000000' : '#FFFFFF';
    }
    // Find the visible paint of the existing WeddingSaas or Apeiron control.
    // The elementor-icon child is often transparent while its parent owns the color.
    function nativePaint(node) {
        var current = node, depth = 0;
        while (current && current !== document.body && depth < 5) {
            var computed = getComputedStyle(current);
            var color = computed.backgroundColor, channels = rgba(color);
            if (channels && channels[3] > 0.02) {
                return { background: color, sample: channels, foreground: computed.color };
            }
            // Some native controls use a gradient instead of background-color.
            var gradient = computed.backgroundImage;
            if (gradient && /(?:linear|radial|conic)-gradient\(/i.test(gradient)) {
                var sample = samplePaint(gradient);
                if (sample) return { background: gradient, sample: sample, foreground: computed.color };
            }
            current = current.parentElement;
            depth++;
        }
        return null;
    }
    function samplePaint(value) {
        var parsed = rgba(value);
        if (parsed) return parsed;
        var colors = String(value || '').match(/(?:rgba?|hsla?)\([^()]*\)|#[a-f\d]{3,8}\b/ig) || [];
        for (var i = 0; i < colors.length; i++) {
            parsed = rgba(colors[i]);
            if (parsed) return parsed;
        }
        return null;
    }

    function on(el, type, fn, options, cleanups) {
        if (!el) return;
        el.addEventListener(type, fn, options || false);
        cleanups.push(function () { el.removeEventListener(type, fn, options || false); });
    }

    // UI-only label controller. No audio, camera or scroll engine is replaced.
    var hintMemory = Object.create(null), tooltipSequence = 0;
    function media(query) { return !!(window.matchMedia && window.matchMedia(query).matches); }
    function touchHints() { return media('(hover: none), (pointer: coarse)'); }
    function description(button, id, add) {
        if (!button) return;
        var ids = (button.getAttribute('aria-describedby') || '').split(/\s+/).filter(function (v) { return v && v !== id; });
        if (add) ids.push(id);
        if (ids.length) attr(button, 'aria-describedby', ids.join(' ')); else button.removeAttribute('aria-describedby');
    }
    function LabelHints(pill) {
        this.pill = pill; this.config = pill.config;
        this.allowed = false; this.canIntroduce = false; this.introDone = false;
        this.hovered = null; this.focused = null; this.anchor = null;
        this.overTip = false; this.dismissed = false; this.keyboard = false; this.pointerUsed = false;
        this.openTimer = 0; this.closeTimer = 0; this.introTimer = 0; this.expiryTimer = 0;
        this.key = 'zehan-pill:photobooth-seen:' + window.location.pathname;
        this.seen = !!hintMemory[this.key];
        try { this.seen = this.seen || window.sessionStorage.getItem(this.key) === '1'; } catch (e) { /* Storage may be disabled. In-page memory still works. */ }
        var tip = this.tip = document.createElement('div');
        tip.className = 'zipill-tooltip';
        tip.id = 'zipill-tooltip-' + (++tooltipSequence);
        attr(tip, 'role', 'tooltip'); attr(tip, 'aria-hidden', 'true'); attr(tip, 'data-visible', 'false');
        this.title = document.createElement('span'); this.title.className = 'zipill-tooltip-title';
        this.detail = document.createElement('span'); this.detail.className = 'zipill-tooltip-detail';
        tip.appendChild(this.title); tip.appendChild(this.detail); pill.panel.appendChild(tip);
        var self = this;
        Object.keys(pill.buttons).forEach(function (key) {
            var button = pill.buttons[key];
            on(button, 'pointerenter', function (event) {
                if (self.config.labels === false || event.pointerType === 'touch' || !media('(any-hover: hover)')) return;
                self.hovered = button; self.dismissed = false;
                self.cancelIntro(); self.request(button, false);
            }, false, pill.cleanups);
            on(button, 'pointerleave', function (event) {
                if (event.pointerType === 'touch') return;
                if (self.hovered === button) self.hovered = null;
                self.stopTimer('openTimer'); self.planClose();
            }, false, pill.cleanups);
            on(button, 'focusin', function () {
                // Do not turn a mobile tap or focus restoration into a sticky hover label.
                var keyboardFocus = self.keyboard || (!self.pointerUsed && matches(button, ':focus-visible'));
                if (self.config.labels === false || !keyboardFocus) return;
                self.focused = button; self.dismissed = false;
                self.cancelIntro(); self.request(button, true);
            }, false, pill.cleanups);
            on(button, 'focusout', function () {
                if (self.focused === button) self.focused = null;
                self.planClose();
            }, false, pill.cleanups);
        });
        on(tip, 'pointerenter', function (event) {
            if (event.pointerType === 'touch') return;
            self.overTip = true; self.stopTimer('closeTimer');
        }, false, pill.cleanups);
        on(tip, 'pointerleave', function (event) {
            if (event.pointerType === 'touch') return;
            self.overTip = false; self.planClose();
        }, false, pill.cleanups);
        on(document, 'pointerdown', function () {
            self.pointerUsed = true; self.keyboard = false; self.focused = null;
            self.cancelIntro();
        }, true, pill.cleanups);
        on(document, 'keydown', function (event) {
            if (event.key === 'Escape') {
                self.dismissed = true; self.cancelIntro(); self.stopTimer('openTimer'); self.hide();
                // No preventDefault/stopPropagation: other invitation controls still receive Escape.
            } else if (/^(Tab|ArrowUp|ArrowDown|ArrowLeft|ArrowRight|Enter| )$/.test(event.key)) {
                self.keyboard = true;
            }
        }, true, pill.cleanups);
    }
    LabelHints.prototype.stopTimer = function (name) {
        if (this[name]) clearTimeout(this[name]);
        this[name] = 0;
    };
    LabelHints.prototype.markSeen = function () {
        if (this.pill.isEditor) return;
        this.seen = true; hintMemory[this.key] = true;
        try { window.sessionStorage.setItem(this.key, '1'); } catch (e) { /* No storage permission required for the control itself. */ }
    };
    LabelHints.prototype.cameraUsed = function () {
        this.introDone = true; this.markSeen(); this.stopTimer('introTimer'); this.hide();
    };
    LabelHints.prototype.cancelIntro = function () {
        if (!this.introTimer && this.mode !== 'intro') return;
        this.introDone = true; this.stopTimer('introTimer');
        if (this.mode === 'intro') this.hide();
    };
    LabelHints.prototype.request = function (button, immediate) {
        if (!this.allowed || button.hidden || this.dismissed || this.config.labels === false) return;
        if (button && button.dataset && button.dataset.action === 'camera' && this.pill.config.cameraBadge) return;
        this.stopTimer('openTimer'); this.stopTimer('closeTimer');
        var self = this;
        if (immediate) this.show(button, 'label');
        else this.openTimer = setTimeout(function () {
            self.openTimer = 0;
            if (!self.pill.destroyed && !self.dismissed && (self.hovered === button || self.focused === button)) self.show(button, 'label');
        }, 180);
    };
    LabelHints.prototype.planClose = function () {
        if (this.mode === 'intro') return;
        this.stopTimer('closeTimer');
        var self = this;
        this.closeTimer = setTimeout(function () {
            self.closeTimer = 0;
            if (self.pill.destroyed || self.overTip) return;
            var button = self.hovered || self.focused;
            if (button && !self.dismissed) self.show(button, 'label'); else self.hide();
        }, 180);
    };
    LabelHints.prototype.show = function (button, mode) {
        if (this.pill.destroyed || !this.allowed || !button || button.hidden || this.pill.panel.hidden) return false;
        this.stopTimer('openTimer'); this.stopTimer('closeTimer');
        if (this.mode === 'intro' && mode !== 'intro') this.stopTimer('expiryTimer');
        if (this.anchor !== button) description(this.anchor, this.tip.id, false);
        this.anchor = button; this.mode = mode;
        attr(this.tip, 'data-mode', mode); this.updateText(); this.position();
        description(button, this.tip.id, true);
        attr(this.tip, 'aria-hidden', 'false'); attr(this.tip, 'data-visible', 'true');
        return true;
    };
    LabelHints.prototype.updateText = function () {
        if (!this.anchor) return;
        var text = this.anchor.getAttribute('data-tooltip') || this.anchor.getAttribute('aria-label') || '';
        if (this.title.textContent !== text) this.title.textContent = text;
        var detail = this.mode === 'intro' ? (typeof this.config.hintText === 'string' ? this.config.hintText : 'Foto dengan bingkai') : '';
        if (this.detail.textContent !== detail) this.detail.textContent = detail;
        hidden(this.detail, !detail);
    };
    LabelHints.prototype.position = function () {
        if (!this.anchor) return;
        var panel = this.pill.panel.getBoundingClientRect(), button = this.anchor.getBoundingClientRect();
        var viewport = window.visualViewport;
        var viewportLeft = viewport ? viewport.offsetLeft : 0;
        var viewportTop = viewport ? viewport.offsetTop : 0;
        var viewportRight = viewportLeft + (viewport ? viewport.width : window.innerWidth);
        var viewportBottom = viewportTop + (viewport ? viewport.height : window.innerHeight);
        var leftSide = this.pill.config.side === 'left';
        var room = Math.max(40, (leftSide ? viewportRight - panel.right : panel.left - viewportLeft) - 26).toFixed(1) + 'px';
        if (room !== this.lastRoom) { style(this.tip, '--zipill-label-room', room); this.lastRoom = room; }
        // Measure at the final transform, not half-way through a fade animation.
        var height = this.tip.offsetHeight, anchorY = button.top + button.height / 2;
        var centerY = Math.min(viewportBottom - 8 - height / 2, Math.max(viewportTop + 8 + height / 2, anchorY));
        var nextTop = (centerY - panel.top).toFixed(1) + 'px';
        if (nextTop !== this.lastTop) { style(this.tip, 'top', nextTop); this.lastTop = nextTop; }
        var nextArrow = Math.max(8, Math.min(height - 8, anchorY - centerY + height / 2)).toFixed(1) + 'px';
        if (nextArrow !== this.lastArrow) { style(this.tip, '--zipill-label-arrow', nextArrow); this.lastArrow = nextArrow; }
    };
    LabelHints.prototype.hide = function () {
        this.stopTimer('expiryTimer'); this.stopTimer('closeTimer');
        description(this.anchor, this.tip.id, false);
        attr(this.tip, 'data-visible', 'false'); attr(this.tip, 'aria-hidden', 'true');
        this.anchor = null; this.mode = ''; this.overTip = false;
        this.lastTop = ''; this.lastArrow = ''; this.lastRoom = '';
    };
    LabelHints.prototype.update = function (visible, cameraReady) {
        this.allowed = !!visible && !document.hidden;
        this.canIntroduce = this.allowed && cameraReady && !this.pill.isEditor && this.config.mobileHint !== false && this.config.cameraBadge !== true && touchHints();
        if (!this.allowed) {
            this.stopTimer('openTimer'); this.stopTimer('introTimer'); this.hide();
            this.hovered = null; this.focused = null;
            return;
        }
        if (this.anchor && (this.anchor.hidden || (this.mode === 'intro' && !cameraReady))) this.hide();
        if (!this.anchor && this.focused && document.activeElement === this.focused && !this.dismissed && this.config.labels !== false) this.show(this.focused, 'label');
        if (this.anchor) { this.updateText(); this.position(); }
        if (!this.canIntroduce) { this.stopTimer('introTimer'); return; }
        if (this.seen || this.introDone || this.introTimer || this.anchor || this.focused || this.hovered) return;
        var self = this;
        this.introTimer = setTimeout(function () {
            self.introTimer = 0;
            if (self.pill.destroyed || !self.canIntroduce || document.hidden || self.introDone || self.seen || self.anchor || !present(self.pill.panel)) return;
            if (!self.show(self.pill.buttons.camera, 'intro')) return;
            self.introDone = true; self.markSeen();
            var seconds = Number(self.config.hintSeconds);
            seconds = Number.isFinite(seconds) && seconds > 0 ? Math.min(15, Math.max(3, seconds)) : 6;
            self.expiryTimer = setTimeout(function () {
                self.expiryTimer = 0;
                if (!self.pill.destroyed && self.mode === 'intro') self.hide();
            }, seconds * 1000);
        }, 500);
    };
    LabelHints.prototype.destroy = function () {
        this.stopTimer('openTimer'); this.stopTimer('closeTimer'); this.stopTimer('introTimer'); this.hide();
        this.tip.remove();
    };

    function Pill(source) {
        this.source = source;
        this.config = parse(source, 'data-zipill-config');
        this.panel = one('[data-zipill-panel]', source);
        this.message = one('[data-zipill-message]', source);
        this.buttons = {};
        this.cleanups = []; this.nativeCleanups = []; this.observers = []; this.nativeObservers = [];
        this.timers = []; this.masked = [];
        this.frame = 0; this.needsDiscover = true; this.needsSkin = true;
        this.camera = null; this.scroller = null; this.nativeScrollButton = null; this.music = null; this.audio = null;
        this.modalHeld = false; this.cameraWasOpen = false; this.returnFocus = false;
        this.holdUntil = 0; this.pressSnapshot = null; this.progressValue = -1;
        this.isEditor = editor(); this.destroyed = false;
        this.idleTimer = 0;
        var self = this;
        all('[data-action]', this.panel).forEach(function (b) { self.buttons[b.dataset.action] = b; });
        attr(this.panel, 'data-side', this.config.side === 'left' ? 'left' : 'right');
        if (this.config.luxuryMode !== false) this.panel.classList.add('zipill-luxury');
        this.panel.classList.toggle('zipill-camera-animated', this.config.cameraTabMotion !== false);
        source._zipill = this;
        // Elementor may update CSS variables inline without rebuilding the widget.
        // Watch only the source and its widget wrapper (not the portaled panel).
        if (window.MutationObserver) {
            var skinObserver = new MutationObserver(function () { self.schedule(true); });
            skinObserver.observe(source, { attributes: true, attributeFilter: ['style', 'class', 'data-zipill-config'] });
            var elementorWrapper = closest(source, '.elementor-element');
            if (elementorWrapper && elementorWrapper !== source) {
                skinObserver.observe(elementorWrapper, { attributes: true, attributeFilter: ['style', 'class'] });
            }
            this.observers.push(skinObserver);
        }
        attr(this.buttons.camera, 'data-tooltip', typeof this.config.cameraLabel === 'string' && this.config.cameraLabel ? this.config.cameraLabel : 'Photobooth');
        this.hints = new LabelHints(this);

        if (this.isEditor) {
            source.classList.add('zipill-is-editor');
            hidden(one('.zipill-editor-note', source), false);
            Object.keys(this.buttons).forEach(function (key) { hidden(self.buttons[key], self.config[key] === false); });
            hidden(one('.zipill-progress', this.panel), !this.config.progress);
            this.skin();
            hidden(this.panel, false);
            this.hints.update(true, false);
            return;
        }

        // Portal only the pill, never move or duplicate a native widget or its media.
        document.body.appendChild(this.panel);
        document.body.appendChild(this.message);
        this.panel.classList.add('zipill-live');
        this.wake = function () {
            self.panel.classList.remove('zipill-idle');
            if (self.idleTimer) clearTimeout(self.idleTimer);
            if (self.config.mobileIdle === false || !touchHints()) return;
            self.idleTimer = setTimeout(function () {
                if (!self.destroyed && !self.panel.hidden && !self.cameraWasOpen) self.panel.classList.add('zipill-idle');
            }, 3200);
        };
        on(this.panel, 'click', function (event) { self.wake(); self.handleClick(event); }, false, this.cleanups);
        on(this.panel, 'pointerenter', function () { self.wake(); }, false, this.cleanups);
        on(window, 'pointerdown', function () { self.wake(); }, { passive: true }, this.cleanups);
        on(window, 'touchstart', function () { self.wake(); }, { passive: true }, this.cleanups);
        on(window, 'pointerdown', function (event) { self.rememberPress(event); }, true, this.cleanups);
        on(window, 'keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ' || event.key === 'Space') self.rememberPress(event);
        }, true, this.cleanups);
        on(document, 'click', function (event) {
            if (closest(event.target, self.config.openSelector)) {
                self.holdUntil = Date.now() + 1600;
                self.later(function () { self.schedule(true); }, 1650);
                self.schedule();
            }
        }, true, this.cleanups);
        on(document, 'apeiron:cover:opened', function () { self.schedule(true); }, false, this.cleanups);
        on(document, 'visibilitychange', function () { self.schedule(true); }, false, this.cleanups);
        on(window, 'resize', function () { self.schedule(true); }, { passive: true }, this.cleanups);
        on(window, 'orientationchange', function () { self.schedule(true); }, { passive: true }, this.cleanups);
        on(window, 'scroll', function () { self.wake(); self.schedule(); }, { passive: true }, this.cleanups);
        on(window, 'pageshow', function () { self.schedule(true, true); }, false, this.cleanups);
        on(window, 'load', function () { self.schedule(true, true); }, false, this.cleanups);
        on(document, 'load', function (event) {
            if (matches(event.target, 'link[rel=\"stylesheet\"]')) self.schedule(true);
        }, true, this.cleanups);
        if (window.visualViewport) on(window.visualViewport, 'resize', function () { self.schedule(true); }, { passive: true }, this.cleanups);
        on(this.buttons.scroll, 'pointerenter', function () {
            var api = self.api();
            if (api && api.pause && self.scrollConfig && self.scrollConfig.pauseOnHover && window.matchMedia('(hover:hover)').matches) {
                api.pause('zehan-pill-hover', false);
            }
        }, false, this.cleanups);
        on(this.buttons.scroll, 'pointerleave', function () { var api = self.api(); if (api && api.resume) api.resume('zehan-pill-hover'); }, false, this.cleanups);

        if (window.MutationObserver) {
            var bodyObserver = new MutationObserver(function () { self.schedule(); });
            bodyObserver.observe(document.body, { attributes: true, attributeFilter: ['class', 'style'] });
            this.observers.push(bodyObserver);
        }
        if (window.ResizeObserver) {
            var resizeObserver = new ResizeObserver(function () { self.schedule(); });
            resizeObserver.observe(document.documentElement);
            resizeObserver.observe(document.body);
            this.observers.push(resizeObserver);
        }
        this.discover(); this.sync(); this.wake();
        // Bounded readiness retries cover delayed optimizer/Elementor initialization.
        [100, 400, 1200, 3000, 7000].forEach(function (delay) { self.later(function () { self.schedule(true, true); }, delay); });
    }

    Pill.prototype.later = function (fn, ms) {
        var self = this;
        var id = setTimeout(function () {
            self.timers = self.timers.filter(function (entry) { return entry !== id; });
            if (!self.destroyed) fn();
        }, ms);
        this.timers.push(id); return id;
    };
    Pill.prototype.schedule = function (skin, discover) {
        if (this.destroyed || (this.isEditor && !skin)) return;
        this.needsSkin = this.needsSkin || !!skin;
        if (!this.isEditor) this.needsDiscover = this.needsDiscover || !!discover;
        if (this.frame) return;
        var self = this;
        this.frame = requestAnimationFrame(function () {
            self.frame = 0;
            if (self.destroyed) return;
            if (self.isEditor) { if (self.needsSkin) self.skin(); return; }
            if (self.needsDiscover) self.discover();
            self.sync();
        });
    };
    Pill.prototype.api = function () {
        var api = this.scroller && this.scroller.isConnected && this.scroller._apeironAutoScroll;
        return api && typeof api.start === 'function' && typeof api.stop === 'function' && typeof api.getState === 'function' ? api : null;
    };
    Pill.prototype.scrollState = function () {
        var api = this.api();
        if (api) return api.getState();
        if (!this.scroller || !this.scroller.isConnected || !this.nativeScrollButton) return 'unavailable';
        var raw = this.scroller.getAttribute('data-scroll-state') || '';
        if (raw) return raw;
        if (this.scroller.classList.contains('is-scrolling') || this.nativeScrollButton.classList.contains('is-active') || this.nativeScrollButton.getAttribute('aria-pressed') === 'true') return 'running';
        return 'stopped';
    };

    Pill.prototype.discover = function () {
        this.needsDiscover = false;
        var self = this, c = this.config, camera = null, scroller = null, music = null;
        if (c.camera !== false) {
            var target = c.cameraSelector ? one(c.cameraSelector) : null;
            if (target) camera = matches(target, '[data-tlwc-root]') ? target : (closest(target, '[data-tlwc-root]') || one('[data-tlwc-root]', target));
            if (!c.cameraSelector) camera = all('[data-tlwc-root]').filter(function (root) { return !parse(root, 'data-tlwc-config').demo; })[0] || null;
        }
        if (c.scroll !== false) {
            var scrollScope = c.scrollSelector ? one(c.scrollSelector) : document;
            if (scrollScope) {
                var roots = [];
                if (matches(scrollScope, '.apeiron-autoscroll-wrap')) roots = [scrollScope];
                else if (matches(scrollScope, '.apeiron-scroll-btn')) {
                    var parentRoot = closest(scrollScope, '.apeiron-autoscroll-wrap');
                    if (parentRoot) roots = [parentRoot];
                } else {
                    roots = all('.apeiron-autoscroll-wrap', scrollScope);
                }
                scroller = roots.filter(function (root) { return !!root._apeironAutoScroll; })[0] || roots[0] || null;
            }
        }
        if (c.music !== false) {
            if (c.musicSelector) {
                music = one(c.musicSelector);
                if (music && !one('#wds-audio-box', music)) music = closest(music, '.elementor-widget-wds_audio') || music;
            } else {
                var box = one('#wds-audio-box');
                music = box ? (closest(box, '.elementor-widget-wds_audio') || box.parentElement) : null;
            }
        }
        var audio = music ? one('audio#song', music) : null;
        var covers = all(c.coverSelector || '#cover');
        var coverChanged = !this.covers || covers.length !== this.covers.length || covers.some(function (cover, i) { return cover !== self.covers[i]; });
        if (camera === this.camera && scroller === this.scroller && music === this.music && audio === this.audio && !coverChanged) return;

        // Release only classes/listeners owned by this add-on.
        this.mask([]);
        this.nativeCleanups.splice(0).forEach(function (fn) { fn(); });
        this.nativeObservers.splice(0).forEach(function (observer) { observer.disconnect(); });
        if (scroller !== this.scroller && this.modalHeld) { var oldApi = this.api(); if (oldApi && oldApi.resume) oldApi.resume('zehan-pill-camera'); this.modalHeld = false; }
        if (this.camera && this.camera !== camera) this.camera.classList.remove('zipill-camera-linked');
        if (camera) camera.classList.add('zipill-camera-linked');
        this.camera = camera; this.scroller = scroller; this.music = music; this.audio = audio; this.covers = covers;
        this.launcher = camera && one('[data-tlwc-launcher]', camera);
        this.modal = camera && one('[data-tlwc-modal]', camera);
        this.nativeScrollButton = scroller && one('.apeiron-scroll-btn', scroller);
        this.scrollConfig = scroller ? parse(scroller, 'data-config') : {};
        this.nativePlay = music && one('#wds_audio_play .elementor-icon', music);
        this.nativePause = music && one('#wds_audio_pause .elementor-icon', music);
        if (audio) {
            ['play', 'pause', 'ended', 'volumechange', 'loadedmetadata', 'emptied'].forEach(function (event) { on(audio, event, function () { self.schedule(); }, false, self.nativeCleanups); });
            on(audio, 'error', function () { if (self.audioRequested) self.toast('Musik belum bisa diputar. Coba lagi atau periksa lagu undangan.'); self.schedule(); }, false, self.nativeCleanups);
        }
        if (window.MutationObserver) {
            var watch = function (node, options) {
                if (!node) return;
                var observer = new MutationObserver(function () { self.schedule(); });
                observer.observe(node, options);
                self.nativeObservers.push(observer);
            };
            watch(camera, { attributes: true, attributeFilter: ['data-tlwc-ready'] });
            watch(this.modal, { attributes: true, attributeFilter: ['hidden', 'aria-hidden', 'class', 'style'] });
            watch(scroller, { attributes: true, attributeFilter: ['data-scroll-state', 'data-apeiron-auto-init', 'class'] });
            if (music) all('#wds_audio_play, #wds_audio_pause', music).forEach(function (node) { watch(node, { attributes: true, attributeFilter: ['style', 'class', 'hidden'] }); });
            covers.forEach(function (node) { watch(node, { attributes: true, attributeFilter: ['hidden', 'style', 'class', 'aria-hidden'] }); });
        }
        this.needsSkin = true;
    };

    Pill.prototype.mask = function (nodes) {
        this.masked.forEach(function (node) { if (nodes.indexOf(node) < 0) node.classList.remove('zipill-native-hidden'); });
        nodes.forEach(function (node) { if (node && !node.classList.contains('zipill-native-hidden')) node.classList.add('zipill-native-hidden'); });
        this.masked = nodes;
    };
    Pill.prototype.skin = function () {
        this.needsSkin = false;
        // Control switchers can update the HTML config without replacing the node
        // in the Elementor preview. Read the current choice on each skin refresh.
        var newConfig = parse(this.source, 'data-zipill-config');
        if (Object.keys(newConfig).length) {
            this.config = newConfig;
            this.hints.config = newConfig;
            this.panel.classList.toggle('zipill-luxury', newConfig.luxuryMode !== false);
            this.panel.classList.toggle('zipill-camera-animated', newConfig.cameraTabMotion !== false);
            attr(this.panel, 'data-side', newConfig.side === 'left' ? 'left' : 'right');
        }
        var self = this, cs = getComputedStyle(this.source), preferred = '';
        VARIABLES.forEach(function (key) {
            var name = '--zipill-' + key, value = cs.getPropertyValue(name).trim();
            if (value) style(self.panel, name, value); else if (self.panel.style.getPropertyValue(name)) self.panel.style.removeProperty(name);
        });
        if (this.config.theme) {
            var colorTargets = [
                this.nativePlay, this.nativePause,
                this.music && one('#wds_audio_play, #wds_audio_pause', this.music),
                this.scroller && one('.apeiron-scroll-btn', this.scroller)
            ];
            // Elementor editor does not bind native controls, but the preview
            // should still show the actual theme color when sources are present.
            if (this.isEditor) {
                colorTargets.push(
                    one('#wds_audio_play .elementor-icon'),
                    one('#wds_audio_pause .elementor-icon'),
                    one('#wds_audio_play'),
                    one('#wds_audio_pause'),
                    one('.apeiron-scroll-btn')
                );
            }
            for (var i = 0; i < colorTargets.length; i++) {
                if (!colorTargets[i]) continue;
                var paint = nativePaint(colorTargets[i]);
                if (paint && paint.sample[3] > 0.02) {
                    style(this.panel, '--zipill-bg', paint.background);
                    preferred = paint.foreground;
                    break;
                }
            }
        }
        // 1.0.0 copied only the background: a white native button produced white
        // SVG strokes on a white pill. Keep an authored color when it is readable;
        // otherwise use the native foreground or a contrasting dark/light color.
        if (this.config.autoContrast !== false) {
            var panelStyle = getComputedStyle(this.panel);
            var background = samplePaint(panelStyle.backgroundColor);
            // For gradient skins, backgroundColor is transparent; sample the paint.
            if (!background || background[3] < 0.02) {
                background = samplePaint(panelStyle.getPropertyValue('--zipill-bg'));
            }
            if (background) {
                // Semi-transparent colors are evaluated against a neutral light base.
                // This is a color guard, not a claim about every background image.
                background = composite(background, [255, 255, 255, 1]);
                var foreground = panelStyle.getPropertyValue('--zipill-fg').trim() || panelStyle.color;
                style(this.panel, '--zipill-fg', readableColor(foreground, background, preferred));
                var activeBackground = rgba(panelStyle.getPropertyValue('--zipill-active-bg').trim() || 'rgba(255,255,255,0.14)');
                var activeSurface = activeBackground ? composite(activeBackground, background) : background;
                var activeColor = panelStyle.getPropertyValue('--zipill-active-fg').trim() || '#FFFFFF';
                style(this.panel, '--zipill-active-fg', readableColor(activeColor, activeSurface, preferred));
            }
        }
    };
    Pill.prototype.musicPlaying = function () {
        if (this.audio) return !this.audio.paused && !this.audio.ended;
        // WeddingSaas calls this wrapper "play" while its pause icon is showing.
        var wrapper = this.nativePlay && this.nativePlay.parentElement;
        return !!wrapper && wrapper.style.display !== 'none' && !wrapper.hidden;
    };
    Pill.prototype.coverOpen = function () {
        if (this.config.showWhen === 'always') return true;
        if (Date.now() < this.holdUntil) return false;
        var covers = (this.covers || []).filter(function (node) { return node.isConnected; });
        if (covers.some(present)) return false;
        return !document.body.classList.contains('stop-scrolling');
    };
    Pill.prototype.sync = function () {
        if (this.destroyed || this.isEditor) return;
        if (this.needsSkin) this.skin();
        var api = this.api();
        var ready = {
            camera: !!(this.config.camera !== false && this.camera && this.camera.isConnected && this.camera.dataset.tlwcReady === '1' && this.launcher && this.modal),
            scroll: !!(this.config.scroll !== false && this.scroller && this.scroller.isConnected && (api || this.nativeScrollButton)),
            music: !!(this.config.music !== false && this.music && this.music.isConnected && (this.audio || (this.nativePlay && this.nativePause && one('#youtube-audio', this.music))))
        };
        var cameraOpen = document.body.classList.contains('tlwc-camera-open') || !!(this.modal && !this.modal.hasAttribute('hidden') && this.modal.getAttribute('aria-hidden') !== 'true');
        if (this.config.pauseCamera && api && api.pause && api.resume) {
            var state = api.getState();
            if (cameraOpen && (state === 'running' || (!this.modalHeld && state === 'paused'))) { api.pause('zehan-pill-camera', false); this.modalHeld = true; }
            else if (!cameraOpen && this.modalHeld) { this.modalHeld = false; api.resume('zehan-pill-camera'); }
        }
        var self = this, masked = [];
        Object.keys(this.buttons).forEach(function (key) { hidden(self.buttons[key], !ready[key]); });
        if (this.config.hideNative) {
            if (ready.camera) masked.push(this.launcher);
            if (ready.scroll) masked.push(this.scroller);
            if (ready.music) masked = masked.concat(all('#wds_audio_play, #wds_audio_pause', this.music));
        }
        this.mask(masked);
        var scrollState = this.scrollState(), activeScroll = scrollState === 'running' || scrollState === 'paused';
        attr(this.buttons.scroll, 'aria-pressed', activeScroll);
        attr(this.buttons.scroll, 'data-state', scrollState);
        attr(this.buttons.scroll, 'aria-label', activeScroll ? 'Hentikan gulir' : 'Mulai gulir otomatis');
        attr(this.buttons.scroll, 'data-tooltip', activeScroll ? 'Hentikan gulir' : 'Gulir otomatis');
        hidden(one('[data-icon-idle]', this.buttons.scroll), activeScroll);
        hidden(one('[data-icon-active]', this.buttons.scroll), !activeScroll);
        hidden(one('.zipill-progress', this.panel), !this.config.progress);
        var musicPlaying = ready.music && this.musicPlaying();
        attr(this.buttons.music, 'aria-pressed', musicPlaying);
        attr(this.buttons.music, 'aria-label', musicPlaying ? 'Jeda musik' : 'Putar musik');
        attr(this.buttons.music, 'data-tooltip', musicPlaying ? 'Jeda musik' : 'Putar musik');
        var show = !!(ready.camera || ready.scroll || ready.music) && this.coverOpen() && !(this.config.hideCamera && cameraOpen);
        hidden(this.panel, !show);
        if (!show || cameraOpen) this.panel.classList.remove('zipill-idle');
        if (show && this.config.progress) {
            var height = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight) - window.innerHeight;
            var value = height > 0 ? Math.max(0, Math.min(100, window.scrollY / height * 100)) : 0;
            if (Math.abs(value - this.progressValue) >= 0.15 || value === 0 || value === 100) {
                style(this.panel, '--zipill-progress', value.toFixed(2)); this.progressValue = value;
            }
        }
        if (this.cameraWasOpen && !cameraOpen && this.returnFocus) {
            this.returnFocus = false;
            if (show && ready.camera) { try { this.buttons.camera.focus({ preventScroll: true }); } catch (e) { /* Do not force page scroll. */ } }
        }
        this.cameraWasOpen = cameraOpen;
        this.hints.update(show && !cameraOpen, ready.camera);
    };

    Pill.prototype.rememberPress = function (event) {
        var button = closest(event.target, '[data-action="scroll"]');
        if (button !== this.buttons.scroll) return;
        this.pressSnapshot = { active: /^(running|paused)$/.test(this.scrollState()), time: Date.now() };
    };
    Pill.prototype.handleClick = function (event) {
        var button = closest(event.target, '[data-action]');
        if (!button || !this.panel.contains(button)) return;
        event.preventDefault();
        var kind = button.dataset.action, self = this;
        if (kind === 'camera') {
            if (!this.launcher || !this.launcher.isConnected) { this.schedule(true, true); return; }
            this.returnFocus = true;
            this.hints.cameraUsed();
            this.launcher.click();
        } else if (kind === 'scroll') {
            var api = this.api();
            if (api) {
                var active = /^(running|paused)$/.test(api.getState());
                // A native stop-on-interaction handler can fire before the pill click.
                if (this.pressSnapshot && Date.now() - this.pressSnapshot.time < 1800) active = this.pressSnapshot.active;
                this.pressSnapshot = null;
                if (active) api.stop(false); else api.start(false);
                if (api.getState() === 'completed') this.toast('Sudah sampai di akhir undangan.');
            } else if (this.nativeScrollButton && this.nativeScrollButton.isConnected) {
                // Fallback for pages where Apeiron renders the native control before exposing its public API.
                this.pressSnapshot = null;
                this.nativeScrollButton.click();
                this.later(function () { self.schedule(true, true); }, 80);
                this.later(function () { self.schedule(true, true); }, 450);
            } else {
                this.toast('Auto scroll belum siap. Coba beberapa saat lagi.'); return;
            }
        } else if (kind === 'music') {
            this.audioRequested = true;
            if (this.audio) {
                // Reuse WeddingSaas' exact <audio>; its trim/loop listeners stay attached.
                if (!this.audio.paused) this.audio.pause();
                else {
                    try {
                        var result = this.audio.play();
                        if (result && typeof result.catch === 'function') result.catch(function () {
                            self.toast('Musik belum bisa diputar. Ketuk lagi atau periksa lagu undangan.'); self.schedule();
                        });
                    } catch (e) { this.toast('Musik belum bisa diputar. Coba lagi.'); }
                }
            } else {
                // YouTube remains under WeddingSaas' own API and click handler.
                var nativeButton = this.musicPlaying() ? this.nativePlay : this.nativePause;
                if (nativeButton) nativeButton.click();
            }
        }
        this.schedule();
    };
    Pill.prototype.toast = function (text) {
        this.message.textContent = text; hidden(this.message, false);
        if (this.toastTimer) clearTimeout(this.toastTimer);
        var self = this;
        this.toastTimer = this.later(function () { hidden(self.message, true); self.toastTimer = 0; }, 4500);
    };
    Pill.prototype.destroy = function () {
        if (this.destroyed) return;
        this.destroyed = true;
        this.hints.destroy();
        if (this.frame) cancelAnimationFrame(this.frame);
        if (this.idleTimer) clearTimeout(this.idleTimer);
        this.timers.forEach(clearTimeout);
        this.cleanups.concat(this.nativeCleanups).forEach(function (fn) { fn(); });
        this.observers.concat(this.nativeObservers).forEach(function (observer) { observer.disconnect(); });
        var api = this.api();
        if (api && api.resume) { api.resume('zehan-pill-hover'); if (this.modalHeld) api.resume('zehan-pill-camera'); }
        this.mask([]);
        if (this.camera) this.camera.classList.remove('zipill-camera-linked');
        if (!this.isEditor) { this.panel.remove(); this.message.remove(); }
        delete this.source._zipill;
    };

    function scan() {
        scanFrame = 0;
        controllers = controllers.filter(function (controller) {
            if (!controller.source.isConnected || (controller.isEditor !== editor())) { controller.destroy(); return false; }
            return true;
        });
        all('[data-zipill-source]').forEach(function (source) {
            if (source._zipill || !one('[data-zipill-panel]', source)) return;
            // There is one controller per public page, even with duplicate templates.
            if (!editor() && controllers.some(function (controller) { return !controller.isEditor; })) return;
            controllers.push(new Pill(source));
        });
        registerElementor();
    }
    function queueScan() { if (!scanFrame) scanFrame = requestAnimationFrame(scan); }
    function relevant(node) { return node.nodeType === 1 && (matches(node, ROOTS) || !!one(ROOTS, node)); }
    function registerElementor() {
        if (hooked || !window.elementorFrontend || !window.elementorFrontend.hooks) return;
        hooked = true;
        window.elementorFrontend.hooks.addAction('frontend/element_ready/zehan-invitation-pill.default', queueScan);
    }
    function boot() {
        if (!document.body) return;
        scan();
        if (!globalObserver && window.MutationObserver) {
            globalObserver = new MutationObserver(function (records) {
                var rescan = false, rediscover = false, skin = false;
                records.forEach(function (record) {
                    if (matches(record.target, 'style') || closest(record.target.parentElement, 'style')) skin = true;
                    Array.prototype.forEach.call(record.addedNodes, function (node) {
                        if (relevant(node)) { rescan = true; rediscover = true; }
                        if (node.nodeType === 1 && matches(node, 'style, link[rel="stylesheet"]')) skin = true;
                    });
                    Array.prototype.forEach.call(record.removedNodes, function (node) { if (relevant(node)) { rescan = true; rediscover = true; } });
                });
                if (rescan) queueScan();
                if (rediscover || skin) controllers.forEach(function (controller) { controller.schedule(true, rediscover); });
            });
            globalObserver.observe(document.body, { childList: true, subtree: true });
            if (document.head) globalObserver.observe(document.head, { childList: true, subtree: true });
        }
    }
    window.ZehanInvitationPill = { version: '1.0.12', boot: boot };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot, { once: true }); else boot();
    window.addEventListener('elementor/frontend/init', function () { registerElementor(); queueScan(); });
    if (window.jQuery) window.jQuery(window).on('elementor/frontend/init.zipill', function () { registerElementor(); queueScan(); });
}());
