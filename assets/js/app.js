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
    
    //Modal
    //
    //
    //Footer fix when the body is smaller than the user's screen
    if(document.body.scrollHeight+20 < window.screen.height) {
        $(".footer").css("position","fixed");
    }
});