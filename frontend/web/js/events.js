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





function openModal() {
//    alert('Hello');
//    var url='evet/display-map';
//    var despId=12;
//    var $modal = jQuery('.modal');
//    
//    var data = { despId: despId};
//    $modal.load(baseUrl + url, data, function () {
//        
//    }
//    );
}