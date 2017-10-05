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
function setcsvfilename() {
    filename = $('#import').val().replace(/C:\\fakepath\\/i, '');
    if (filename) {
        $('#filename').val(filename);
    }
}
function importcsv() {
    $('#upload_btn').addClass('hidden');
    $('#loader').removeClass('hidden');

    filename = $('#import').val().replace(/C:\\fakepath\\/i, '');
    var extension = filename.split('.').pop();
    $('#file').html(filename);
    $('#file').removeClass('hidden');
    var formData = new FormData($('#fileform')[0]);
    window.checkFile = setInterval(function () {
        keepServerAlive();
    }, 5000);
    if (extension == 'csv') {

        $.ajax({
            type: "POST",
            url: baseUrl + "import/upload-csv",
            data: formData,
            processData: false,
            contentType: false,
            async: true,
            dataType: "json",
            success: function (data) {
                if (data.msgType == 'SUC') {
                    Msg(data.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                } else if (data.msgType == 'VALID') {
                    Msg(data.msg);
                } else {
                    Msg(data.msg, 'ERR');
                }
            },
            error: function (data) {
                Msg('Something went wrong, Please try later.', 'ERR');
            },
            complete: function (jqXHR, textStatus) {
                console.log(textStatus);
//                $('#upload_btn').removeClass('hidden');
//                $('#loader').addClass('hidden');
//                $('#import').val('');
//                $('#filename').val('file.csv');
            }
        });
    } else {
        Msg('Files with only CSV extensions are allowed.', 'ERR');
        $('#upload_btn').removeClass('hidden');
        $('#loader').addClass('hidden');
    }

}
function gotoURL(address, toHide, loader) {
    $(toHide).addClass('hidden');
    $('#' + loader).removeClass('hidden');
    window.location.href = baseUrl + address;
}

window.import_status = setInterval(function () {
    keepServerAlive();
}, 5000);

function keepServerAlive() {
    $.ajax({
        url: baseUrl + "import/server-alive",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
            if (data.msgType == 'SUC') {
                Msg(data.msg);
                console.log(data.msg);

                $('#progress_bar').remove();
                $progress_bar = '<div class="upload" id="progress_bar" ><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">100%</div><div>';
                $('#csv_comp_content').append($progress_bar);
                $('#progress_bar').remove();
                clearInterval(window.checkFile);
                clearInterval(window.import_status);
                $alert = '<div class="alert alert-success alert-dismissable  upload">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                        '<strong>Success! </strong> ' + data.msg +
                        '</div>';
                $('#csv_comp_content').append($alert);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else if (data.msgType == 'ERR') {
                Msg(data.msg, 'ERR');
                console.log(data.msg);
                $('#upload_btn').removeClass('hidden');
                $('#loader').addClass('hidden');
                $('#import').val('');
                $('#filename').val('file.csv');
                clearInterval(window.checkFile);
                clearInterval(window.import_status);
                $alert = '<div class="alert alert-danger alert-dismissable upload">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                        '<strong>ERROR!</strong> ' + data.msg +
                        '</div>';
                $('#progress_bar').remove();
                $('#csv_comp_content').append($alert);

            } else if (data.msgType == 'NOT_EXIST') {
                clearInterval(window.import_status);
                console.log(data.msg);
                $('#upload_btn').removeClass('hidden');
                $('#loader').addClass('hidden');
                $('#import').val('');
                $('#filename').val('file.csv');
                $('#progress_bar').remove();
            } else if (data.msgType == 'PROC') {
                $('#upload_btn').addClass('hidden');
                $('#loader').removeClass('hidden');
                $('#file').removeClass('hidden');
                $('#progress_bar').remove();
                $progress_bar = '<div class="upload" id="progress_bar" ><div class="progress" ><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="' + data.msg + '" aria-valuemin="0" aria-valuemax="100" style="width:' + data.msg + '%">' + data.msg + '%</div><div><div>';
                $('#csv_comp_content').append($progress_bar);
                console.log($progress_bar)
            } else if (data.msgType == 'EXC') {
                console.log(data.msg);
                $('#upload_btn').removeClass('hidden');
                $('#loader').addClass('hidden');
                $('#import').val('');
                $('#filename').val('file.csv');
                clearInterval(window.checkFile);
                clearInterval(window.import_status);
                $alert = '<div class="alert alert-danger alert-dismissable upload">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                        '<strong>Exception! </strong> ' + data.msg +
                        '</div>';
                $('#progress_bar').remove();
                $('#csv_comp_content').append($alert);
            }
        },
        error: function (data) {
            console.log('internal server error');
        }
    });
}

