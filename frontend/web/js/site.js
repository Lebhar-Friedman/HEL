//*************************************************************** functions on document load *********************************
$(document).ready(function () {
    $('a[href="#home"]').on('click', function (event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;
            $('html, body').animate({scrollTop: $(hash).offset().top}, 800, function () {
                window.location.hash = hash;
                $('#zipcode_input').focus()
            });
        }
    });
    $('a[href="#map"]').on('click', function (event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;
            $('html, body').animate({scrollTop: $(hash).offset().top}, 800, function () {
                window.location.hash = hash;
            });
        }
    });

    $(".show_menu").click(function () {
        $(".account_dd").toggle({
            duration: 500,
        });
    });
    $('body').click(function (e) {
        var target = $(e.target);
        if (!target.is('.show_menu') && !target.is('.show_admin_menu') && target.closest('.show_admin_menu').length == 0) {
            if ($('.account_dd').is(':visible'))
                $('.account_dd').hide();
            if ($('.account_dd_admin').is(':visible'))
                $(".account_dd_admin").toggle({
                    duration: 500,
                });
        }
    });

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
if ($('.html-multi-chosen-select').length > 0) {
    $('.html-multi-chosen-select').chosen({placeholder_text_multiple: ' '});
//$('.html-multi-chosen-select').chosen({placeholder_text_multiple: 'Keywords'});
}

$(document).ready(function () {
//    $(".html-multi-chosen-select").chosen().change(alert());
    if ($('.html-multi-chosen-select').length > 0) {
        $('.html-multi-chosen-select').chosen().change(function (event) {
            console.log('change', event, $(event.target).val());
        });

        $('.search-choice-close').onclick = function () {
//        alert('hiiii');
        }
    }
});

function checkEnterPress(e, input_value) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code === 13 && input_value.length > 4) { //Enter keycode
        searchByzip(input_value);
    }
}
function getCity(zip, callback) {
    window.city_name = '';
    $.ajax({
        url: baseUrl + 'event/get-city',
        type: 'post',
        data: {zipcode: zip},
        success: function (r) {
            window.city_name = r.city;
            console.log(r.city);
            if (typeof callback === 'function') {
                callback(window.city_name);
            }
        },
        error: function ()
        {
            if (typeof callback === 'function') {
                callback(window.city_name);
            }
            console.log('internal server error');
        }
    });
}


function getZipCodeForSearch() {
    zip_code = document.getElementById('zipcode_input').value;
    if ($('#zipcode_input').val().length < 3) {
        $('#zipcode_input').css("border", "1px solid red");
        return false;
    }
    searchByzip(zip_code);
}
function searchByzip(zip_code) {
//    $('.search-content').hide();
    getCity(zip_code, function (city) {
        window.location = baseUrl + 'free-healthcare-events/' + city + '/?zipcode=' + zip_code;
    });
}
function saveEvent(eventID, element, zipcode, store) {
//    if (! confirm("Are you sure?")) {
//        return false;
//    }
    $.ajax({
        url: baseUrl + 'site/save-event',
        type: 'get',
        data: {eid: eventID, zipcode: zipcode, store_number: store},
        dataType: "json",
        success: function (r) {
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
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
interval = setInterval(function () {
    if ($('g').length > 0) {
        $('.at-icon-wrapper').css({'overflow': 'initial', 'height': 'initial', 'width': 'initial', 'padding': '0px'});
        $('.addthis_inline_share_toolbox').css({'clear': 'none'});
        $('span.at-icon-wrapper').html('<a href="#" style="margin-right: 0px !important;"><img src="' + image_url + 'share-icon.png" alt="" />SHARE</a>');
        $('.save-share-btn').css({'display': 'block'});
        clearInterval(interval);
    }
}, 100);

function showAddalertForm(){
    $('#form-create-alert').removeClass('hidden');
}

