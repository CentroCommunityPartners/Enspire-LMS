var enspireTheme = (function () {
  "use strict";

  function adjustBlockStyles(settings, name) {
    switch (name) {
      case "core/heading":
        addStyles(settings, [
          {
            name: "accent",
            label: "Accent Text",
          },
        ]);
        return settings;
      case "core/list":
        addStyles(settings, [
          {
            name: "checked",
            label: "Checked",
          },
        ]);
        return settings;
      case "core/image":
        removeStyles(settings, ["rounded"]);
        addStyles(settings, [
          {
            name: "ratio-16-9",
            label: "Ratio 16:9",
          },
          {
            name: "ratio-4-3",
            label: "Ratio 4:3",
          },
          {
            name: "ratio-3-4",
            label: "Ratio 3:4",
          },
          {
            name: "ratio-1-1",
            label: "Ratio 1:1",
          },
        ]);
        return settings;
      case "core/quote":
        removeStyles(settings, ["large"]);
        return settings;
      case "core/button":
        removeStyles(settings, ["fill"]);
        removeStyles(settings, ["outline"]);

        addStyles(settings, [
          {
            name: "secondary",
            label: "Secondary",
          },
          {
            name: "white",
            label: "White",
          },
          {
            name: "transparent",
            label: "Transparent",
          },
          {
            name: "big",
            label: "Big",
          },
        ]);

        return settings;
      default:
        return settings;
    }
  }

  function setDefaultLabel(settings, newLabel) {
    settings["styles"] = settings["styles"].map(function (style) {
      if (style.isDefault) style.label = newLabel;
      return style;
    });
  }

  function addStyles(settings, styles) {
    settings["styles"] = settings["styles"].concat(styles);
  }

  function removeStyles(settings, styles) {
    settings["styles"] = settings["styles"].filter(function (style) {
      return styles.indexOf(style.name) === -1;
    });
  }

  return {
    adjustBlockStyles: adjustBlockStyles,
  };
})();

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "yourtheme/editor",
  enspireTheme.adjustBlockStyles
);
