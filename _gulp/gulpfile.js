// include gulp
var gulp = require('gulp'); 

// include plug-ins
var jshint      = require('gulp-jshint');
var concat      = require('gulp-concat');
var uglify      = require('gulp-uglify');
var size        = require('gulp-size');
var sass        = require('gulp-sass');
var cleanCSS    = require('gulp-clean-css');

// default task - run all tasks
gulp.task('default', ['jshint', 'javascript-admin', 'javascript-website', 'sass-admin', 'sass-website']);

// JS hint task - check for js errors
gulp.task('jshint', function() {
  gulp.src(['./src/js/app.js','./src/js-admin/**/*','./src/js/**/*.js'])
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
});

// Process javascript task for website - concat, uglify
gulp.task('javascript-website', function() {
  gulp.src(['./src/js-vendors/**/*.js', './src/js/app.js','./src/js/**/*.js'])
    .pipe(concat('bundle.js'))
    .pipe(uglify())
    .pipe(size({title: 'JavaScript Website Size: '}))
    .pipe(gulp.dest('./../_static/js/'));
});

// Process javascript task for admin - concat, uglify
gulp.task('javascript-admin', function() {
  gulp.src(['./src/js-vendors/**/*.js', './src/js/app.js','./src/js-admin/**/*.js'])
    .pipe(concat('bundle-admin.js'))
    .pipe(uglify())
    .pipe(size({title: 'JavaScript Admin Size: '}))
    .pipe(gulp.dest('./../_static/js/'));
});

// Sass task for website - concat, minify
gulp.task('sass-website', function () {
  gulp.src('./src/sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('bundle.css'))
    .pipe(cleanCSS())
    .pipe(size({title: 'CSS Website Size: '}))
    .pipe(gulp.dest('./../_static/css/'));
});

//Sass task for admin - concat, minify
gulp.task('sass-admin', function () {
  gulp.src('./src/sass-admin/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('bundle-admin.css'))
    .pipe(cleanCSS())
    .pipe(size({title: 'CSS Admin Size: '}))
    .pipe(gulp.dest('./../_static/css/'));
});

//Watch task - re-build on every save
gulp.task('watch', function() {
  gulp.watch('./src/**/*', ['default']);
});