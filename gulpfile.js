/*
 * Happytime
 * 
 * @description     Gulp file for combining WP core with src files
 * @file            gulpfile.js
 * @author          Joanna Ong
 * @required        gulpconfig.json
 * 
 * @usage
 *  -- local development
 *    ``` gulp ```
 *
 *
 */

var gulp        = require('gulp'),
    args        = require('yargs').argv,
    gutil       = require('gulp-util'),
    watch       = require('gulp-watch'),
    runSequence = require('run-sequence'),
    browserSync = require('browser-sync'),
    del         = require('del'),
    reload      = browserSync.reload;

// default args, use local if no environment is speified
args.env = args.env ? args.env : 'local';

gulp.task('copy', function() {
  gulp.src("wordpress_module/core/**")
      .pipe(gulp.dest("dist"))

  gulp.src("wordpress_module/plugins/**")
      .pipe(gulp.dest("dist/wp-content/plugins/"));

  gulp.src("src/plugins/**")
      .pipe(gulp.dest("dist/wp-content/plugins/"));

  gulp.src("src/themes/**")
      .pipe(gulp.dest("dist/wp-content/themes/"));

  gulp.src("src/uploads/**")
      .pipe(gulp.dest("dist/wp-content/uploads/"));

  gulp.src("src/wp-config.php")
      .pipe(gulp.dest("dist"));
  
});

gulp.task('browser-sync', function() {
  browserSync({
    server: {
      baseDir: "dist"
    }
  });
});

gulp.task('clean', function(cb) {
  del(["dist/**"], cb);
});

gulp.task('watch',function(){
  gulp.watch("src/**/*.php", ['copy']);
});

// LOCAL DEV
gulp.task('default', function() {
  runSequence("build", "watch");
});

// BUILD TO ENV
gulp.task('build', function(){
  runSequence(
    'clean',
    'copy'
  );
});