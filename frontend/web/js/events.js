function getQueryParams(name) {
    qs = location.search;
    var params = [];
    var tokens;
    var re = /[?&]?([^=]+)=([^&]*)/g;
    while (tokens = re.exec(qs))
    {
        if (decodeURIComponent(tokens[1]) == name)
            params.push(decodeURIComponent(tokens[2]).replace('+', ' '));
    }
    return params;
}
$(document).ready(function () {

    if (window.history && window.history.pushState) {
        $(window).on('popstate', function () {
            $("#zip_code").val(getQueryParams("zipcode"));
            $("#sortBy").val(getQueryParams("sortBy"));
            $("#keywords").val(getQueryParams("keywords[]")).trigger("chosen:updated");
        });

    }
    $('.filters-multi-chosen-selected').chosen().change(function (event) {
        selectedFilters(event);
    });
    var userPosition = function (position) {
        $.ajax({
            url: baseUrl + '/event/set-long-lat',
            type: 'post',
            data: {longitude: position.coords.longitude, latitude: position.coords.latitude},
            success: function (r) {
                console.log("Long lat has been set");
            },
            error: function ()
            {
                console.log('internal server error');
            }
        });
    };

    var error_handler = function (err) {
        console.log(err.message);
        if (err.code == 1) {
            console.log('Need your location for accurate results');
        }
    };
    if ("geolocation" in navigator) { //check geolocation available 
        navigator.geolocation.getCurrentPosition(userPosition, error_handler);
    } else {
        console.log("Browser doesn't support geolocation!");
    }

});
function getCity(zip, callback) {
    closeNavOnMobile();
    $("#loader").show();
    $("#overlay").show();
    window.city = '';
    $.ajax({
        url: baseUrl + 'event/get-city',
        type: 'post',
        data: {zipcode: zip},
        success: function (r) {
            window.city = r.city;
            console.log(r.city);
            if (typeof callback === 'function') {
                callback(window.city);
            }
        },
        error: function ()
        {
            if (typeof callback === 'function') {
                callback(window.city);
            }
            console.log('internal server error');
        }
    });
}
$(document).on("submit", "#events_search_form", function (e) {
    e.preventDefault();
    $('#zip_code').css("border", "1px solid #dbdbdb");
    var zipCode = $('#zip_code').val().length;
    if (zipCode === 0) {
        $('#zip_code').css('border-color', 'red');
        return false;
    }

//    var values = $(this).serialize();
    var values = $(this).serializeArray();

    var city_name = getCity(zipCode, function (city) {
        searchResult(values, city);
    });

//    searchResult(values);
});

function closeNavOnMobile() {
    if ($(window).width() < 767) {
        closeNav();
    }
}

function searchResult(form_data, city_name) {
    var options = $("#keywords option:selected");
    var categories = '';
    var services = '';
    var sign_cat = '';
    var sign_sub = '';
    for (var i = 0; i < options.length; i++) {
        var property = $(options[i]).attr('data-option-category');
        var value_cat_sub = $(options[i]).val();
        if (property == 'sub') {
            services = services + sign_cat + value_cat_sub;
            sign_cat = '-';
        } else if (property == 'cat') {
            categories = categories + sign_sub + value_cat_sub;
            sign_sub = '-';
        }
    }
    $(document).on('pjax:send', function () {
        closeNavOnMobile();
        $("#loader").show();
        $("#overlay").show();
    });
    $(document).on('pjax:complete', function () {
        $("#loader").hide();
        $("#overlay").hide();
        $('.filters-multi-chosen-selected ').chosen().change(function (event) {
            selectedFilters(event);
        });

        moreEvents(form_data);


    });
    var query = '';
    var operator = '';
    if (categories !== '') {
        query = 'categories=' + categories;
        operator = '&';
    }
    if (services !== '') {
        query = query + operator + 'services=' + services;
    }
    var dataObj = {};
    $(form_data).each(function (i, field) {
        dataObj[field.name] = field.value;
    });
    $.pjax.reload({
        url: baseUrl + 'free-healthcare-events/' + city_name + '?' + query,
        container: '#result-view',
        replace: false,
        type: 'get',
//        data: form_data,
        data: {zipcode: dataObj['zipcode'], sortBy: dataObj['sortBy']},
        timeout: 30000,
        push: true
    });

}

function selectedFilters(event) {
    console.log($(event.target).val());
    $('input[name^="filters"]').each(function () {
        $(this).prop('checked', false);
        if (jQuery.inArray($(this).val(), $(event.target).val()) !== -1) {
            $(this).prop('checked', true);
        }
        ;
    });
    $('#events_search_form').submit();
}

$(document).ready(function () {

    function checkWidth() {
        if ($(window).width() < 767) {
            $('#event_near').addClass('mobile-event-near');
        } else {
            $('#event_near').removeClass('mobile-event-near');
            $('.search-result-content').css('display', 'block');
        }
    }
    checkWidth();
    $(window).resize(checkWidth);

    $(".mobile-event-near").click(function () {
        $('.search-result-content').show();
    });


});

function closeNav() {
    $('.search-result-content').hide();
}
function showNav() {
    $('.search-result-content').show();
}

function openModal(event) {
    var url = baseUrl + 'event/display-map';
    var $modal = $("<div>");
    $modal.append(event);
    $('#myModal').modal('show');
    $modal.load(url, {events: event}, function () {
//        console.log(event);
        $('#myModal').on('shown.bs.modal', function () {
            window.dispatchEvent(new Event('resize'));
        });
    });
    $modal.appendTo('body');
}

function moreEvents(form_data) {
    var zipCode = $('#zip_code').val();
    var url = baseUrl + 'event/more-events';
    $('#more_events').load(url, form_data);
}

function event_detail(event_id) {
    window.location = baseUrl + 'event/detail?eid=' + event_id;
}

function add_new_alert() {
    var form_data = $("#events_search_form").serialize();
    $.ajax({
        url: baseUrl + '/user/add-alerts',
        type: 'post',
        data: form_data,
        dataType: 'JSON',
        success: function (r) {
            if (r.msgType === "SUC") {
                $('#add_alert').hide();
                toastr.success(r.msg);
            } else if (r.msgType === "ERR") {
                toastr.error(r.msg);
            }
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg);
        }
    });
}

function addAlertSession() {
    var form_data = $("#events_search_form").serialize();
    var email = $("#email").val();
    $.ajax({
        url: baseUrl + '/site/add-alerts-session',
        type: 'post',
        data: form_data,
        success: function (r) {
            location.href = baseUrl + "site/signup?email=" + email;
        },
        error: function (jqXHR, exception) {
            console.log('Internal server error');
        }
    });
}

function alertZipCodeSession() {

    var email = $("#email").val();
    var zip_code = $("#c_zipcode").val();
    var street = $("#c_street").val();
    var city = $("#c_city").val();
    var state = $("#c_state").val();
    var event_id = $("#event_id").val();
    var store_number = $("#store_number").val();

    $.ajax({
        url: baseUrl + '/site/add-alerts-session',
        type: 'post',
        data: {zipcode: zip_code, only_zip: 'y', event_id: event_id, street: street, city: city, state: state, store_number: store_number},
        success: function (r) {
            location.href = baseUrl + "site/signup?email=" + email;
        },
        error: function () {
            console.log('Internal server error');
        }
    });
}

function alertZipCode() {
    var zip_code = $("#c_zipcode").val();
    var street = $("#c_street").val();
    var city = $("#c_city").val();
    var state = $("#c_state").val();
    var event_id = $("#event_id").val();
    var store_number = $("#store_number").val();
    $.ajax({
        url: baseUrl + '/user/add-alerts',
        type: 'post',
        data: {zipcode: zip_code, only_zip: 'y', event_id: event_id, street: street, city: city, state: state, store_number: store_number},
        dataType: 'JSON',
        success: function (r) {
            if (r.msgType === "SUC") {
                $('#add_alert').hide();
                toastr.success(r.msg);
            } else if (r.msgType === "ERR") {
                toastr.error(r.msg);
            }
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg);
        }
    });
}

$(document).ready(function () {
    var form_data = $("#events_search_form").serialize();
    moreEvents(form_data);
});
