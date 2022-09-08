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
		.pipe(gulp.dest('./assets/js'));
}

// Uglify assets/js/app.js
function jsUglify() {
	return gulp.src('assets/js/app.js')
		.pipe(sourcemaps.init())
		.pipe(uglify())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./assets/js'));
}

// Compile & minify protected/scss/app.scss into assets/css/app.css
function cssCompile() {
	return gulp.src('./protected/scss/app.scss')
		.pipe(sourcemaps.init())
		.pipe(sass.sync({outputStyle: 'compressed'}))
		.pipe(postcss([autoprefixer()]))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./assets/css'));
}

// Watch
function watch () {
	// Watch scss files
	gulp.watch('protected/scss/**/*.scss', cssCompile);

	// Watch js files
	gulp.watch(jsFiles, jsConcat);
}

// Public task
exports.css   = cssCompile;
exports.js    = jsConcat;
exports.watch = watch;

// Build task
exports.build = gulp.parallel(cssCompile, gulp.series(jsConcat, jsUglify));

// Default Task
exports.default = gulp.series(cssCompile, jsConcat, watch);
