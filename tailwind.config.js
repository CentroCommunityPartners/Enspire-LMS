const plugin = require("tailwindcss/plugin");
const settings = require("./tailsettings.json");

module.exports = {
  daisyui: settings.daisyui,
  theme: {
    colors: settings.theme.colors,
    fontSize: settings.theme.fontSize,
    fontFamily: settings.theme.fontFamily,
    screens: settings.theme.screens,
    container: settings.theme.container,
  },
  content: settings.content,
  safelist: [
    "alignfull",
    "alignwide",
    "alignnone",
    "aligncenter",
    { pattern: /^has-/ },
    { pattern: /^btn-/ },
    { pattern: /^wp/ },
  ],
  plugins: [require("daisyui")],
};
