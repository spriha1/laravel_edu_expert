const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
	.js('resources/js/validate.js', 'public/js')
	.js('resources/js/footer.js', 'public/js')
	.js('resources/js/goals.js', 'public/js')
	.js('resources/js/users_list.js', 'public/js')
	.js('resources/js/edit.js', 'public/js')
	.js('resources/js/holiday.js', 'public/js')
	.js('resources/js/manage_subjects.js', 'public/js')
	.js('resources/js/manage_class.js', 'public/js')
	.js('resources/js/task.js', 'public/js')
	.js('resources/js/daily_timetable.js', 'public/js')
	.js('resources/js/timetable.js', 'public/js')
	.js('resources/js/admin_timetable.js', 'public/js')
	.js('resources/js/timesheet.js', 'public/js')
	.js('resources/js/admin_lte_dashboard.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .version();
