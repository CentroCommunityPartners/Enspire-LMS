const mix = require("laravel-mix");
let path = require("path");
mix.setPublicPath(path.resolve("./"));

const filesCSS = [
  "theme",
  "theme-admin",
  "theme-gutenberg",
  "theme-member",
  "theme-inline",
];

const filesJS = ["theme", "theme-admin", "theme-gutenberg", "theme-member"];

const blocksCSS = [
  "map",
  "pricing",
  "steps",
  "iconbox-columns",
  "slider-testimonials",
];

const blocksJS = ["map", "pricing", "steps"];

filesCSS.map((name) => {
  mix.sass(`./src/scss/${name}.scss`, "./assets/css");
});
filesJS.map((name) => {
  mix.js(`./src/js/${name}.js`, "./assets/js");
});

blocksJS.map((name) => {
  const path = "./inc/acf-blocks/" + name;
  mix.js(path + "/src/script.js", path + "/assets");
});

blocksCSS.map((name) => {
  const path = "./inc/acf-blocks/" + name;
  mix.sass(path + "/src/style.scss", path + "/assets");
});

//Load More Modules
mix.js(
  "./inc/modules/more-filters/src/more-filters.js",
  "./inc/modules/more-filters/assets/more-filters.js"
);

mix.options({
  processCssUrls: false,
  postCss: [
    require("postcss-nested-ancestors"),
    require("postcss-nested"),
    require("postcss-mixins"),
    require("postcss-simple-vars"),
    require("postcss-import"),
    require("tailwindcss"),
    require("autoprefixer"),
  ],
});

//BrowserSync Config. Change this depending on your local environment
mix.browserSync({
  proxy: "https://accespoint.local",
  https: true,
  // port: 3002,
  injectChanges: true,
  files: ["src/**/*.{scss,js}"],
});
