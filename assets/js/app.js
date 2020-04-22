import $ from 'jquery';
import 'bootstrap';
import registerHandler from './register';
import adminHandler from './sb-admin'

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    registerHandler().registerEvents();
    adminHandler().registerEvents();
});

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');