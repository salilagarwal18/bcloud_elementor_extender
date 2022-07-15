const gulp = require('gulp');
//const babel = require('gulp-babel');
//const webpack = require('webpack-stream');
//import gulp from 'gulp';
//import webpack from 'webpack-stream';
 
gulp.task('babel-transpile', () =>
    gulp.src('src/js/*.cjs')
        .pipe(babel({
            presets: ['@babel/preset-env']
        }))
        .pipe(gulp.dest('assets/js/'))
);

gulp.task('default', defaultTask);

function defaultTask(done){
    done();
}

gulp.task('webpack-task', function() {
  return gulp.src('src/js/*.js')
    .pipe(webpack({
        output: {
            filename: '[name].js',
        }
    }))
    .pipe(gulp.dest('assets/js/'));
});