/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');


$(document).ready(function(){
    console.log(document.body.scrollHeight );
    console.log(window.screen.height );
    if(document.body.scrollHeight+45 < window.screen.height) {
            $(".footer").css("position","fixed");
        }
});