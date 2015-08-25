// npm install --save-dev gulp gulp-plumber gulp-watch gulp-livereload gulp-minify-css gulp-jshint jshint-stylish gulp-uglify gulp-rename gulp-notify gulp-include gulp-sass

var gulp = require('gulp'),
	plumber = require( 'gulp-plumber' ),
	watch = require( 'gulp-watch' ),
	livereload = require( 'gulp-livereload' ),
	minifycss = require( 'gulp-minify-css' ),
	// jshint = require( 'gulp-jshint' ),
	// stylish = require( 'jshint-stylish' ),
	uglify = require( 'gulp-uglify' ),
	rename = require( 'gulp-rename' ),
	notify = require( 'gulp-notify' ),
	include = require( 'gulp-include' ),
	concat = require('gulp-concat'),
	sass = require( 'gulp-sass' );

var onError = function( error ) {
	console.error( error.message );
	this.emit( 'end' );
}

// Styles
gulp.task( 'styles', function() {
	return gulp.src( './library/sass/style.scss', {
		sourceComments: 'map',
		sourceMap: 'scss',
		outputStyle: 'nested'
	} )
	.pipe( plumber( { errorHandler: onError } ) )
	.pipe( sass() )
	.pipe( gulp.dest( '.' ) )
	.pipe( minifycss() )
	.pipe( rename( { suffix: '.min' } ) )
	.pipe( gulp.dest( './library/css' ) )
	.pipe( livereload() )
	.pipe( notify({ message: 'Styles task complete' }));
} );

// Scripts
// gulp.task('scripts', function() {
//   return gulp.src( 'src/js/**/*.js' )
//     .pipe( concat( 'main.js') )
//     .pipe( gulp.dest( 'assets/js' ) )
//     .pipe( rename( { suffix: '.min' } ) )
//     .pipe( uglify() )
//     .pipe( gulp.dest('assets/js') )
//     .pipe( notify( { message: 'Scripts task complete' } ) );
// });


// Watch
gulp.task('watch', function() {

	// Watch .scss files
	gulp.watch('library/sass/**/*.scss', ['styles']);

	// Watch .js files
	//gulp.watch('library/js/**/*.js', ['scripts']);

	gulp.watch( './**/*.php' ).on( 'change', function( file ) {
		livereload.changed( file );
	} );

	// Create LiveReload server
	livereload.listen();

	// Watch any files in dist/, reload on change
	gulp.watch(['library/**']).on('change', livereload.changed);

});

gulp.task( 'default', [ 'styles', 'watch' ], function() {

} );