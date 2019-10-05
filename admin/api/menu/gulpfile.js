var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync');

gulp.task('serve', function() {
    browserSync.init({
        server: {
            baseDir: "./"
        },
        notify: false
    });

    gulp.watch("index.html").on('change', browserSync.reload);

    gulp.watch("sass/**/*.scss", ['sass']);
    gulp.watch("sass/**/*.scss").on('change', browserSync.reload);
    gulp.watch("js/**/*.js").on('change', browserSync.reload);
});

gulp.task('sass', function() {
    gulp.src('sass/styles.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('css/'))
});