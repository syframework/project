const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const svgSprite = require('gulp-svg-sprite');

// Javascript files to concat into assets/js/app.js
const jsFiles = [
	'node_modules/jquery/dist/jquery.min.js',
	'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
	'node_modules/@fortawesome/fontawesome-free/js/all.min.js',
	'node_modules/smoothscroll-polyfill/dist/smoothscroll.min.js',
	'node_modules/autosize/dist/autosize.min.js',
	'node_modules/luxon/build/global/luxon.min.js',
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

// Concat plugin scss: protected/scss/_bootstrap-*.scss -> protected/scss/_plugin.scss
function cssPluginConcat() {
	return gulp.src('protected/scss/_bootstrap-*.scss')
		.pipe(concat('_plugin.scss'))
		.pipe(gulp.dest('protected/scss'));
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

// Transpile & minify: protected/templates/**/*.scss -> protected/templates/**/*.css
function cssTplTranspile() {
	return gulp.src('protected/templates/**/*.scss')
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(postcss([autoprefixer()]))
		.pipe(gulp.dest('protected/templates'));
}

// Transpile & minify: protected/src/**/*.scss -> protected/src/**/*.css
function cssSrcTranspile() {
	return gulp.src('protected/src/**/*.scss')
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(postcss([autoprefixer()]))
		.pipe(gulp.dest('protected/src'));
}

// Compile all the svg in a sprite
function svgCompile() {
	let config = {
		mode: {
			symbol: {
				dest: '',
				sprite: 'sprite.svg'
			}
		}
	};
	return gulp.src('protected/svg/**/*.svg')
		.pipe(svgSprite(config))
		.pipe(gulp.dest('assets/img'));
}

// Watch scss & js files
function watch() {
	// Watch scss files in scss folder
	gulp.watch('protected/scss/**/*.scss', gulp.parallel(cssAppTranspile, cssTplTranspile, cssSrcTranspile));

	// Watch scss files in templates folder
	gulp.watch('protected/templates/**/*.scss', cssTplTranspile);

	// Watch scss files in src folder
	gulp.watch('protected/src/**/*.scss', cssSrcTranspile);

	// Watch js files
	gulp.watch(jsFiles, jsConcat);

	// Watch svg files
	gulp.watch('protected/svg/**/*.svg', svgCompile);
}

// Public tasks
exports.css   = gulp.series(cssPluginConcat, gulp.parallel(cssAppTranspile, cssTplTranspile, cssSrcTranspile));
exports.js    = jsConcat;
exports.svg   = svgCompile;
exports.watch = watch;

// Build task
exports.build = gulp.series(cssPluginConcat, gulp.parallel(svgCompile, cssAppTranspile, cssTplTranspile, cssSrcTranspile, gulp.series(jsConcat, jsUglify)));

// Default Task
exports.default = gulp.series(gulp.parallel(svgCompile, cssAppTranspile, cssTplTranspile, cssSrcTranspile, jsConcat), watch);
