// include gulp
var gulp = require('gulp'); 

// include plug-ins
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

// default task - run all tasks
gulp.task('default', ['jshint', 'javascript']);

// JS hint task - check for js errors
gulp.task('jshint', function() {
  gulp.src('./src/scripts/**/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
});

// Process javascript task - concat, uglify
gulp.task('javascript', function() {
  gulp.src(['./src/script-vendors/**/*.js', './src/scripts/app.js','./src/scripts/**/*.js'])
    .pipe(concat('bundle.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./../_static/js/'));
});