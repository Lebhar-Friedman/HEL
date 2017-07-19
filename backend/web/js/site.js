/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//*************************************************************** functions on document load *********************************
$(document).ready(function () {

    // settings for toster alerts
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1200",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };// alerts end


});
//***************************************************************** $(document).ready end *************************************

//******************************************************* general functions ***********************************************
function Msg(msg, type) {
    if (type == 'ERR')
        toastr.error(msg);
    else
        toastr.success(msg);
}

function numberonly(evt) {
    var theEvent = evt || window.event;
    if (theEvent.keyCode == '08' || theEvent.keyCode == '09')
        return true;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}
//******************************************************* general functions end

//************************** upload CSV ******************************
function setcsvfilename(){
    filename = $('#import').val().replace(/C:\\fakepath\\/i, '');
    if(filename){
        $('#filename').val(filename);
    }
}
function importcsv() {
//    $('#Loader').removeClass('hidden');

    filename = $('#import').val().replace(/C:\\fakepath\\/i, '');
    var extension = filename.split('.').pop();
    $('#file').html(filename);
    $('#file').removeClass('hidden');
    var formData = new FormData($('#fileform')[0]);
    if (extension == 'csv') {
        $.ajax({
            type: "POST",
            url: baseUrl + "import/upload-csv",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    Msg(data.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                } else {
                    Msg(data.msg, 'ERR');
                }
            },
            error: function (data) {
                Msg('Something went wrong, Please try later.', 'ERR');
            },
            complete: function (jqXHR, textStatus) {
                console.log(textStatus);
                $('#Loader').addClass('hidden');
                $('#import').val('');
            }
        });
    } else {
        Msg('Files with only CSV extensions are allowed.', 'ERR');
        $('#Loader').addClass('hidden');
    }

}

