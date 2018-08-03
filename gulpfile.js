var gulp = require('gulp');
var phpcs = require('gulp-phpcs');
var phpunit = require('gulp-phpunit');
var notify = require('gulp-notify');

gulp.task('phplint', function () {
    return gulp.src(['src/**/*.php', 'tests/**/*.php'])
        .pipe(phpcs({
            bin: 'vendor/bin/phpcs',
            standard: 'PSR2',
            warningSeverity: 0
        }))
        .pipe(phpcs.reporter('log'));
});

gulp.task('phpunit', function () {
    var options = {
        debug: false,
        coverageHtml: 'tests/logs/report'
    };

    return gulp.src('phpunit.xml')
        .pipe(phpunit('./vendor/bin/phpunit', options));
});

gulp.task('test', ['phpunit', 'phplint']);