import $ from 'jquery';
import 'bootstrap';
import registerHandler from './register';
import adminHandler from './sb-admin'
import 'datatables.net'
import 'datatables.net-bs4'

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    registerHandler().registerEvents();
    adminHandler().registerEvents();
    $('#dataTable').DataTable();
});

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');