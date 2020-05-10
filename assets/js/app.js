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
    $('#dataTable').DataTable( {
        "order": [],
        "columnDefs": [ {
            "targets"  : 'no-sort',
            "orderable": false,
        }]
    });
    $('select').selectpicker();
    $('#removeElementModal').on('show.bs.modal', function(event){
        var link = $(event.relatedTarget).attr('href');
        $(event.currentTarget).find('a.btn-danger').attr('href', link);
    });
    $('input#nif').on('keypress', function(event){
        var value = event.key;
        if (value.length !== 1){
            return true;
        }
        return /[a-z]|[0-9]|&/i.test(value);
    });
});