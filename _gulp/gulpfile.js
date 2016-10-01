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
gulp.task('default', ['jshint', 'javascript', 'sass']);

// JS hint task - check for js errors
gulp.task('jshint', function() {
  gulp.src('./src/js/**/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
});

// Process javascript task - concat, uglify
gulp.task('javascript', function() {
  gulp.src(['./src/js-vendors/**/*.js', './src/js/app.js','./src/js/**/*.js'])
    .pipe(concat('bundle.js'))
    .pipe(uglify())
    .pipe(size())
    .pipe(gulp.dest('./../_static/js/'));
});

// Sass task - concat
gulp.task('sass', function () {
  gulp.src('./src/sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('bundle.css'))
    .pipe(cleanCSS())
    .pipe(size())
    .pipe(gulp.dest('./../_static/css/'));
});