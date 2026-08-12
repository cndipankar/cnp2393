const gulp = require("gulp");
const browserSync = require("browser-sync").create();
const sass = require('gulp-sass');
const filter = require( 'gulp-filter' );
const rename = require('gulp-rename');
const combinemq = require('gulp-combine-mq');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const imagemin = require('gulp-imagemin');
// const sourcemaps = require('gulp-sourcemaps');

const sitename = 'crocinorthamerica';
const username = 'ihfaz';

function build_theme_styles() {
    return gulp
        .src(['assets/scss/*.scss'])        
        .pipe(sass().on('error', sass.logError))        
        .pipe(autoprefixer())

        // If you need sourcemaps, please uncomment const sourcemaps and two lines below, insert this line to package.json file in "devDependencies" - "gulp-sourcemaps": "^2.6.5",
        // .pipe(sourcemaps.init())
        // .pipe(sourcemaps.write('./sourcemaps'))
        
        .pipe(gulp.dest('assets/css/'))
        .pipe(browserSync.stream())
        .pipe(filter('**/*.css'))
        .pipe(combinemq())
        .pipe(autoprefixer())
        .pipe(cleanCSS({
          level: {
            2: {
              all: true,
              removeDuplicateRules: true
            }
          }
        }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('assets/css/'))
        .pipe(browserSync.stream()) 
}

gulp.task ( 'watch', function () {
    browserSync.init({
        proxy: 'https://' + sitename + '.otmdev.co',
        host: sitename + '.otmdev.co',
        open: 'external',
        port: 8000,
        /*https: {
            key:
                '/Users/' + username + '/.config/valet/Certificates/' + sitename + '.test.key',
            cert:
                '/Users/' + username + '/.config/valet/Certificates/' + sitename + '.test.crt',
        },*/
        snippetOptions: {
            ignorePaths: ["wp-admin/*"]
        },
    });

    // Watched files paths
    gulp.watch('assets/scss/**/*.scss', build_theme_styles);    
    gulp.watch('./*.php').on('change',browserSync.reload);
    gulp.watch('./assets/scss/**/*.scss', browserSync.reload);
	gulp.watch('./assets/js/*.js').on('change', browserSync.reload);
	gulp.watch('./assets/css/*.css').on('change', browserSync.reload);
    gulp.watch('./*css').on('change', browserSync.reload);
} );

gulp.task ( 'imagemin', function (cb) {
    gulp.src('assets/img/**/*')
    .pipe(imagemin([
        imagemin.svgo({
            plugins: [
                {
                    removeViewBox: false,
                    removeUselessDefs: false,
                    cleanupIDs: false
                }
            ]
        })
    ], {
        verbose: true
    }))
    .pipe(gulp.dest('assets/img'));
    cb();
});