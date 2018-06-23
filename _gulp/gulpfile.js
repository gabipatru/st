// include gulp
var gulp = require('gulp'); 

// include plug-ins
var jshint      = require('gulp-jshint');
var concat      = require('gulp-concat');
var uglify      = require('gulp-uglify');
var size        = require('gulp-size');
var sass        = require('gulp-sass');
var cleanCSS    = require('gulp-clean-css');
var gzip        = require('gulp-gzip');
var cacheBuster = require('gulp-buster');
var chalk       = require('chalk');
const del       = require('del');

// default task - run all tasks
gulp.task('default', ['cleanup', 'jshint', 'javascript-admin', 'javascript-website', 'sass-admin', 'sass-website']);

// cleanup task - delete old css and js files
gulp.task('cleanup', function() {
  del(['./../public_html/_static/js/**', '!./../public_html/_static/js'], {force: true}).then(paths => {
    console.log(chalk.green('Deleted js files:\n', paths.join('\n')));
  });
  del(['./../public_html/_static/css/**', '!./../public_html/_static/css'], {force: true}).then(paths => {
    console.log(chalk.green('Deleted css files:\n', paths.join('\n')));
  });
});

// JS hint task - check for js errors
gulp.task('jshint', function() {
  gulp.src(['./../_js/translations.js', './../_js/js-admin/**/*', './../_js/js-website/**/*.js'])
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
});

// Process javascript task for website - concat, uglify
gulp.task('javascript-website', function() {
  gulp.src(['./../_js/translations.js', './../_js/js-vendors/**/*.js', './../_js/js-website/**/*.js'])
    .pipe(concat('bundle.js'))
    .pipe(uglify())
    .pipe(gzip())
    .pipe(size({title: 'JavaScript Website Size: '}))
    .pipe(gulp.dest('./../public_html/_static/js/'))
    .pipe(cacheBuster())
    .pipe(gulp.dest('./../public_html/_static/'));
});

// Process javascript task for admin - concat, uglify
gulp.task('javascript-admin', function() {
  gulp.src(['./../_js/translations.js', './../_js/js-vendors/**/*.js', './../_js/js-admin/**/*.js'])
    .pipe(concat('bundle-admin.js'))
    .pipe(uglify())
    .pipe(gzip())
    .pipe(size({title: 'JavaScript Admin Size: '}))
    .pipe(gulp.dest('./../public_html/_static/js/'))
    .pipe(cacheBuster())
    .pipe(gulp.dest('./../public_html/_static/'));
});

// Sass task for website - concat, minify
gulp.task('sass-website', function () {
  gulp.src(['./../_css/sass-vendors/**/*.css', './../_css/sass-website/**/*.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('bundle.css'))
    .pipe(cleanCSS())
    .pipe(gzip())
    .pipe(size({title: 'CSS Website Size: '}))
    .pipe(gulp.dest('./../public_html/_static/css/'))
    .pipe(cacheBuster())
    .pipe(gulp.dest('./../public_html/_static/'));
});

//Sass task for admin - concat, minify
gulp.task('sass-admin', function () {
  gulp.src(['./../_css/sass-vendors/**/*.css', './../_css/sass-admin/**/*.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('bundle-admin.css'))
    .pipe(cleanCSS())
    .pipe(gzip())
    .pipe(size({title: 'CSS Admin Size: '}))
    .pipe(gulp.dest('./../public_html/_static/css/'))
    .pipe(cacheBuster())
    .pipe(gulp.dest('./../public_html/_static/'));
});

//Watch task - re-build on every save
gulp.task('watch', function() {
  gulp.watch('./src/**/*', ['default']);
});