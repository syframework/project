var gulp = require('gulp');
var sourcemaps = require('gulp-sourcemaps');
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
// CSS
var sass = require('gulp-sass')(require('sass'));
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');

// Compile & minify css
function css() {
    return gulp.src('./protected/scss/app.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(postcss([autoprefixer()]))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./assets/css'));
}

// Compile & minify js
function jsLib() {
    return gulp.src([
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        './node_modules/@fortawesome/fontawesome-free/js/all.min.js',
        './node_modules/smoothscroll-polyfill/dist/smoothscroll.min.js',
        './node_modules/autosize/dist/autosize.min.js',
    ]).pipe(concat('lib.js')).pipe(sourcemaps.init()).pipe(uglify()).pipe(sourcemaps.write('.')).pipe(gulp.dest('./assets/js'));
}

function jsApp() {
    return gulp.src(
        './protected/js/**/*.js'
    ).pipe(concat('app.js')).pipe(sourcemaps.init()).pipe(uglify()).pipe(sourcemaps.write('.')).pipe(gulp.dest('./assets/js'));
}

// Watch
function watch() {
    // Watch scss files
    gulp.watch('protected/scss/**/*.scss', css);

    // Watch js file
    gulp.watch('protected/js/**/*.js', jsApp);
}

// Public task
exports.css = css;
exports.jsLib = jsLib;
exports.jsApp = jsApp;
exports.watch = watch;

// Default Task
exports.default = gulp.series(css, jsLib, jsApp, watch);
