import $ from 'jquery';
import 'bootstrap';
import registerHandler from './register';
import './sb-admin'

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    registerHandler().registerEvents();
});

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');