const gulp = require('gulp');
const imagemin = require('gulp-imagemin');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const cleancss = require('gulp-clean-css');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');
const uncss = require('gulp-uncss');

// Optimize Images
gulp.task('imageMin', () =>
    gulp.src('assets/img/**/*')
        .pipe(imagemin())
        .pipe(gulp.dest('dist/img'))
);

// Minify JS
gulp.task('minify', async function () {
    gulp.src('assets/js/*.js')
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('dist/js'));
});

// Minify CSS
gulp.task('cleancss', async function () {
    gulp.src('assets/css/*.css')
        .pipe(cleancss())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('dist/css'));
});

// Remove unused CSS
gulp.task('uncss', async function () {
    gulp.src('assets/css/*.css')
        .pipe(uncss({
            html: ['index.html', '/*.html', 'https://cuk-danijela.github.io/']
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('dist/css'));
});

// Concat JS Scripts
// gulp.task('scripts', async function () {
//     gulp.src('assets/js/*.js')
//         .pipe(concat('main.js'))
//         .pipe(uglify())
//         .pipe(gulp.dest('dist/js'));
// })

// Prefixer CSS
gulp.task('autoprefixer', async function () {
    gulp.src('assets/css/*.css')
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('dist/css'));
});

// Copying Fonts to Dist
gulp.task('fonts', function () {
    return gulp.src('assets/font/**/*')
        .pipe(gulp.dest('dist/font'))
})

// gulp.task('watch', async function () {
//     gulp.watch('assets/js/*.js', gulp.series('scripts'));
//     gulp.watch('assets/img/*', gulp.series('imageMin'));
// })

// Default functions
gulp.task('default', gulp.parallel(['imageMin', 'minify', 'cleancss', 'autoprefixer', 'fonts', 'uncss']));
