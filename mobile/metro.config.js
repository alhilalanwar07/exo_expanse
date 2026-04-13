const { getDefaultConfig } = require('expo/metro-config');
const { withNativewind } = require('nativewind/metro');

/** @type {import('expo/metro-config').MetroConfig} */
const config = getDefaultConfig(__dirname);

module.exports = withNativewind(config, {
  // Inline variables can break platformColor-based CSS variables.
  inlineVariables: false,
  // We wrap components manually to use className where needed.
  globalClassNamePolyfill: false,
});
