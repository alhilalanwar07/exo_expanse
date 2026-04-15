const expoConfig = require('eslint-config-expo/flat');

const baseConfig = Array.isArray(expoConfig)
  ? expoConfig
  : Array.isArray(expoConfig.default)
    ? expoConfig.default
    : [];

module.exports = [
  ...baseConfig,
  {
    ignores: ['.expo/**', 'dist/**', 'build/**'],
  },
];
