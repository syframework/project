const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');

// Javascript files to concat into assets/js/app.js
const jsFiles = [
	'node_modules/jquery/dist/jquery.min.js',
	'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
	'node_modules/@fortawesome/fontawesome-free/js/all.min.js',
	'node_modules/smoothscroll-polyfill/dist/smoothscroll.min.js',
	'node_modules/autosize/dist/autosize.min.js',
	'protected/js/**/*.js'
];

// Concat all js files into assets/js/app.js
function jsConcat() {
	return gulp.src(jsFiles)
		.pipe(concat('app.js'))
		.pipe(sourcemaps.init())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('assets/js'));
}

// Uglify assets/js/app.js
function jsUglify() {
	return gulp.src('assets/js/app.js')
		.pipe(sourcemaps.init())
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('assets/js'));
}

// Transpile & minify: protected/scss/app.scss -> assets/css/app.css
function cssAppTranspile() {
	return gulp.src('protected/scss/app.scss')
		.pipe(sourcemaps.init())
		.pipe(sass.sync({outputStyle: 'compressed'}))
		.pipe(postcss([autoprefixer()]))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('assets/css'));
}

// Transpile & minify: protected/templates/Application/Page/css/*.scss -> protected/templates/Application/Page/css/*.css
function cssPageTranspile() {
	return gulp.src('protected/templates/Application/Page/css/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(postcss([autoprefixer()]))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('protected/templates/Application/Page/css'));
}

// Transpile & minify: protected/src/Component/**/*.scss -> protected/src/Component/**/*.css
function cssComponentTranspile() {
	return gulp.src('protected/src/Component/**/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(postcss([autoprefixer()]))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('protected/src/Component'));
}

// Watch
function watch () {
	// Watch scss files
	gulp.watch('protected/scss/**/*.scss', gulp.parallel(cssAppTranspile, cssComponentTranspile, cssPageTranspile));

	// Watch js files
	gulp.watch(jsFiles, jsConcat);
}

// Public task
exports.css   = gulp.parallel(cssAppTranspile, cssComponentTranspile, cssPageTranspile);
exports.js    = jsConcat;
exports.watch = watch;

// Build task
exports.build = gulp.parallel(cssAppTranspile, cssComponentTranspile, cssPageTranspile, gulp.series(jsConcat, jsUglify));

// Default Task
exports.default = gulp.series(gulp.parallel(cssAppTranspile, cssComponentTranspile, cssPageTranspile, jsConcat), watch);
