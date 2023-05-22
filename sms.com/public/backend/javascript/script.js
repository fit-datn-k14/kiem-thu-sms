/**
 * Created by LYNV on 01-03-2017.
 */
$(function () {
    $(".select2").select2();
    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
        showInputs: false
    });
    //Date picker
    $('.datepicker').datepicker({
        autoclose: true
    });
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal, input[type="radio"], input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#flash-overlay-modal').modal();
});

$('.check-all').on('ifChecked', function(event) {
    $('.check').iCheck('check');
});
$('.check-all').on('ifUnchecked', function(event) {
    $('.check').iCheck('uncheck');
});
// Removed the checked state from "All" if any checkbox is unchecked
$('.check-all').on('ifChanged', function(event){
    if(!this.changed) {
        this.changed=true;
        $('.check-all').iCheck('check');
    } else {
        this.changed=false;
        $('.check-all').iCheck('uncheck');
    }
    $('.check-all').iCheck('update');
});