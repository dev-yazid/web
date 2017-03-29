var gulp = require('gulp'),
    browserSync = require('browser-sync'),
    reload = browserSync.reload,
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify');
    


gulp.task('lint', function() {
    return gulp.src('public/app/js/**/*.js').pipe(jshint()).pipe(jshint.reporter('default'));
});

gulp.task('scripts',function(){
    gulp.src('public/app/js/**/*.js')
            //.pipe(uglify())
            .pipe(reload({stream:true}))
            .pipe(gulp.dest('public/app'));
}); 
gulp.task('watch',function(){
    gulp.watch('public/app/js/**/*.js',['scripts']);
            
}); 
gulp.task('default',['watch','lint']);
