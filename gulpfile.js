const gulp = require( 'gulp' );
/*const babel = require('gulp-babel');
// const webpack = require('webpack-stream');
// import gulp from 'gulp';
// import webpack from 'webpack-stream';
*/
const zip         = require( 'gulp-zip' );
const replace     = require( 'gulp-replace' );
const deleteLines = require( 'gulp-delete-lines' );


gulp.task(
	'babel-transpile',
	() =>
	gulp.src( 'src/js/*.cjs' )
		.pipe(
			babel(
				{
					presets: ['@babel/preset-env']
				}
			)
		)
		.pipe( gulp.dest( 'assets/js/' ) )
);

gulp.task( 'default', defaultTask );

function defaultTask(done){
	done();
}

gulp.task(
	'webpack-task',
	function () {
		return gulp.src( 'src/js/*.js' )
		.pipe(
			webpack(
				{
					output: {
						filename: '[name].js',
					}
				}
			)
		)
		.pipe( gulp.dest( 'assets/js/' ) );
	}
);

gulp.task(
	'zip-plugin',
	function () {
		return gulp.src( ['assets*/js*/*'] )
		.pipe(
			deleteLines(
				{   // to remove console.log statements from JS code.
					'filters': [/console\.log/i]
				}
			)
		)
		// adding more files in-between the stream.
		.pipe( gulp.src( ['readme.txt', 'bcloud-elementor-extender.php', 'classes*/**', 'assets*/css*/*'] ) )
		.pipe( replace( "microtime()", '"1.1.0"' ) ) // update with newest version of plugin.
		.pipe(
			deleteLines(
				{
					'filters': [/var_dump/i]
				}
			)
		)
		.pipe( zip( 'bcloud-elementor-extender.zip' ) )
		.pipe( gulp.dest( '.' ) )
	}
)