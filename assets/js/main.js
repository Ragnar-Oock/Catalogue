/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file
import '../scss/main.scss';
import 'tempusdominus-bootstrap-4/src/sass/tempusdominus-bootstrap-4-build.scss'

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
const $ = require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');

const moment = require('moment');
global.moment = moment;
require('moment/locale/fr');

require('tempusdominus-bootstrap-4');
