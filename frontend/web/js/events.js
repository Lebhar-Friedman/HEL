
$(document).ready(function () {
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

$(document).on("submit", "#events_search_form", function (e) {
    e.preventDefault();
    var values = $(this).serialize();
    searchResult(values);
});



function searchResult(form_data) {

    $(document).on('pjax:send', function () {
        $("#loader").show();
        $("#overlay").show();
    });
    $(document).on('pjax:complete', function () {
        $("#loader").hide();
        $("#overlay").hide();
        $('.filters-multi-chosen-selected').chosen().change(function (event) {
            selectedFilters(event);
        });

        moreEvents(form_data);


    });

    $.pjax.reload({
        url: baseUrl + 'event/index',
        container: '#result-view',
        replace: false,
        type: 'post',
        data: form_data,
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
    var url = baseUrl + 'event/more-events';
    $('#more_events').load(url);
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

function addAlertSession(){
    var form_data = $("#events_search_form").serialize();
    var email = $("#email").val();
    $.ajax({
        url: baseUrl + '/site/add-alerts-session',
        type: 'post',
        data: form_data,
        success: function (r) {
            location.href=baseUrl+"site/login?email="+email;
        },
        error: function (jqXHR, exception) {
            console.log('Internal server error');
        }
    });
}
