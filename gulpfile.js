var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
del = require('del');

var paths = {
    scripts: ['assets/js/*.js'],
};

gulp.task('clean', function(cb) {
    del(['assets/js/*.min.js', 'assets/css/*.min.css'], cb)
});

gulp.task('default', [ 'clean', 'watch' ], function() {
    return gulp.src( paths.scripts )
        // This will minify and rename to *.min.js
        .pipe(uglify())
        .pipe(rename({ extname: '.min.js' }))
        .pipe( gulp.dest( 'assets/js/' ));
});


gulp.task('watch', function() {

    // Watch .js files
    gulp.watch('assets/js/*.js', ['default']);

});