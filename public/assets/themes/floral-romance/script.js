(() => {
    const register = () => {
        if (typeof Alpine === 'undefined') return false;

        // Prevent re-registration
        try {
            Alpine.data('floralRomance', (config) => ({
                opened: false,
                audioPlaying: false,
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                copied: null,

                init() {
                    this.countdown();
                    setInterval(() => this.countdown(), 1000);

                    if (!this.opened) {
                        document.body.style.overflow = 'hidden';
                    }
                },

                countdown() {
                    if (!config.akadDate) return;

                    const target = new Date(config.akadDate).getTime();
                    const now = new Date().getTime();
                    const diff = target - now;

                    if (diff > 0) {
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    }
                },

                open() {
                    this.opened = true;
                    this.audioPlaying = true;
                    document.body.style.overflow = 'auto';
                    if (this.$refs.audio) {
                        this.$refs.audio.play().catch(() => this.audioPlaying = false);
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                toggleAudio() {
                    if (this.audioPlaying) {
                        this.$refs.audio.pause();
                    } else {
                        this.$refs.audio.play();
                    }
                    this.audioPlaying = !this.audioPlaying;
                },

                copy(text) {
                    navigator.clipboard.writeText(text);
                    this.copied = text;
                    setTimeout(() => this.copied = null, 2000);
                }
            }));
            return true;
        } catch (e) {
            console.warn('Floral Romance: Registration failed', e);
            return false;
        }
    };

    if (!register()) {
        document.addEventListener('alpine:init', register);
        // Fallback for Livewire navigation
        document.addEventListener('livewire:navigated', register);
    }
})();
