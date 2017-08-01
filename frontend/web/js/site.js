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
$('.html-multi-chosen-select').chosen({placeholder_text_multiple:'Keywords'});
function checkEnterPress(e, input_value) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code === 13) { //Enter keycode
        searchByzip(input_value);
    }
}
function getZipCodeForSearch(){
    zip_code=document.getElementById('zipcode_input').value;
    searchByzip(zip_code);
}
function searchByzip(zip_code){
        window.location=baseUrl+'\event?zipcode='+zip_code;
}
function saveEvent(eventID,element){
//    if (! confirm("Are you sure?")) {
//        return false;
//    }
    $.ajax({
            url: baseUrl+'site/save-event',
            type: 'post',
            data: {eid:eventID},
            dataType: "json",
            success: function (r) {
                if (r.msgType === 'SUC') {
                    toastr.success(r.msg);
//                    $(element).closest('.main-table').hide(1000, function () {
//                        $(element).closest('.main-table').remove();
//                    });
                } else {
                    toastr.error(r.msg);
                }
            },
            error: function ()
            {
                toastr.error('Internal server error');
            }
        });
}