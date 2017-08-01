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
    });

    $.pjax.reload({
        url: baseUrl + 'event/index',
        container: '#result-view',
        replace: false,
        type: 'post',
        data: form_data,
        timeout: 30000
    });

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
