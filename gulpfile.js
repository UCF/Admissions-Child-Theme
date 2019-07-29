const fs           = require('fs');
const browserSync  = require('browser-sync').create();
const gulp         = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS     = require('gulp-clean-css');
const include      = require('gulp-include');
const eslint       = require('gulp-eslint');
const isFixed      = require('gulp-eslint-if-fixed');
const babel        = require('gulp-babel');
const rename       = require('gulp-rename');
const sass         = require('gulp-sass');
const sassLint     = require('gulp-sass-lint');
const uglify       = require('gulp-uglify');
const merge        = require('merge');


let config = {
  src: {
    scssPath: './src/scss'
  },
  dist: {
    cssPath: './static/css'
  },
  devPath: './dev',
  packagesPath: './node_modules',
  sync: false,
  syncTarget: 'http://localhost/wordpress/'
};

/* eslint-disable no-sync */
if (fs.existsSync('./gulp-config.json')) {
  const overrides = JSON.parse(fs.readFileSync('./gulp-config.json'));
  config = merge(config, overrides);
}
/* eslint-enable no-sync */


//
// Helper functions
//

// Base SCSS linting function
function lintSCSS(src) {
  return gulp.src(src)
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError());
}

// Base SCSS compile function
function buildCSS(src, dest, extname = '.min.css') {
  dest = dest || config.dist.cssPath;

  return gulp.src(src)
    .pipe(sass({
      includePaths: [config.src.scssPath, config.packagesPath]
    })
      .on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      // Supported browsers added in package.json ("browserslist")
      cascade: false
    }))
    .pipe(rename({
      extname: extname
    }))
    .pipe(gulp.dest(dest));
}

// Base JS linting function (with eslint). Fixes problems in-place.
function lintJS(src, dest) {
  dest = dest || config.src.jsPath;

  return gulp.src(src)
    .pipe(eslint({
      fix: true
    }))
    .pipe(eslint.format())
    .pipe(isFixed(dest));
}

// Base JS compile function
function buildJS(src, dest, extname = '.min.js') {
  dest = dest || config.dist.jsPath;

  return gulp.src(src)
    .pipe(include({
      includePaths: [config.packagesPath, config.src.jsPath]
    }))
    .on('error', console.log) // eslint-disable-line no-console
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename({
      extname: extname
    }))
    .pipe(gulp.dest(dest));
}

// Returns source and destination paths for use by functions that
// process Dev assets.
function getDevWatchSrcDest(eventPath, srcExt) {
  let src;
  let dest;

  if (eventPath) {
    src = eventPath;
    dest = src.slice(0, (src.lastIndexOf('/') > -1 ? src.lastIndexOf('/') : src.lastIndexOf('\\')) + 1);
  } else {
    src = `${config.devPath}/**/*.${srcExt}`;
    dest = config.devPath;
  }

  return {
    src: src,
    dest: dest
  };
}

// BrowserSync reload function
function serverReload(done) {
  if (config.sync) {
    browserSync.reload();
  }
  done();
}

// BrowserSync serve function
function serverServe(done) {
  if (config.sync) {
    browserSync.init({
      proxy: {
        target: config.syncTarget
      }
    });
  }
  done();
}


//
// CSS
//

// Lint all theme scss files
gulp.task('scss-lint-theme', () => {
  return lintSCSS(`${config.src.scssPath}/*.scss`);
});

// Compile theme stylesheet
gulp.task('scss-build-theme', () => {
  return buildCSS(`${config.src.scssPath}/style.scss`);
});

// All theme css-related tasks
gulp.task('css', gulp.series('scss-lint-theme', 'scss-build-theme'));


//
// Rerun tasks when files change
//
gulp.task('watch', (done) => {
  serverServe(done);

  // Dev Scss files
  gulp.watch(`${config.devPath}/**/*.scss`).on('change', (eventPath) => {
    const srcDest = getDevWatchSrcDest(eventPath, 'scss');
    const src     = srcDest.src;
    const dest    = srcDest.dest;

    lintSCSS(src);
    // Use -min.css instead of .min.css for Dev files as a workaround
    // for WP mimetype filtering issues
    return buildCSS(src, dest, '-min.css');
  });

  // Dev js files
  gulp.watch([`${config.devPath}/**/*.js`, `!${config.devPath}/**/*-min.js`]).on('change', (eventPath) => {
    const srcDest = getDevWatchSrcDest(eventPath, 'js');
    const src     = srcDest.src;
    const dest    = srcDest.dest;

    lintJS(src, dest);
    // Use -min.js instead of .min.js for Dev files as a workaround
    // for WP mimetype filtering issues
    return buildJS(src, dest, '-min.js');
  });

  // Theme scss files
  gulp.watch(`${config.src.scssPath}/**/*.scss`, gulp.series('css', serverReload));

  // Theme PHP files
  gulp.watch('./**/*.php', gulp.series(serverReload));
});


//
// Default task
//
gulp.task('default', gulp.series('css'));
