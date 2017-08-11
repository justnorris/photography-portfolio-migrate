var
    gulp = require('gulp'),
    zip = require('gulp-zip')


gulp.task('build', function () {
    return gulp.src(['!node_modules', '!node_modules/**', '!gulpfile.js', 'package-lock.json', '*'])

               .pipe(zip('photography-portfolio-migrate.zip'))
               .pipe(gulp.dest('../'))
});
