const gulp = require('gulp');
//const babel = require('gulp-babel');
//const webpack = require('webpack-stream');
//import gulp from 'gulp';
//import webpack from 'webpack-stream';
const zip = require('gulp-zip');
const replace = require('gulp-replace');
const stripDebug = require('gulp-strip-debug');

 
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

gulp.task('zip-plugin', function(){
    return gulp.src(['assets*/js*/*'])
        .pipe(stripDebug()) // to remove console.log statements from JS code
        .pipe(replace("void 0", '')) // stripDebug adds void 0 in place of console.log. So removing them as well.
        // adding more files in-between the stream
        .pipe(gulp.src(['readme.txt', 'bcloud-elementor-extender.php', 'classes*/**', 'assets*/css*/*'])) 
        .pipe(replace("microtime()", '"1.1"')) // update with newest version of plugin.
		.pipe(zip('bcloud-elementor-extender.zip'))
		.pipe(gulp.dest('.'))
})