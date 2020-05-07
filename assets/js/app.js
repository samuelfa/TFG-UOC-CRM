import $ from 'jquery';
import 'bootstrap';
import registerHandler from './register';
import adminHandler from './sb-admin'
import 'datatables.net'
import 'datatables.net-bs4'
import 'bootstrap-select'

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    registerHandler().registerEvents();
    adminHandler().registerEvents();
    $('#dataTable').DataTable();
    $('select').selectpicker();
    $('#removeElementModal').on('show.bs.modal', function(event){
        var link = $(event.relatedTarget).attr('href');
        $(event.currentTarget).find('a.btn-danger').attr('href', link);
    });
});

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');