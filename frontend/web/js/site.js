$('.html-multi-chosen-select').chosen({placeholder_text_multiple: 'Keywords'});

$(document).ready(function () {
//    $(".html-multi-chosen-select").chosen().change(alert());

    $('.html-multi-chosen-select').chosen().change(function (event) {
        console.log('change', event, $(event.target).val());
    });

    $('.search-choice-close').onclick = function () {
//        alert('hiiii');
    }
});

function checkEnterPress(e, input_value) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code === 13) { //Enter keycode
        searchByzip(input_value);
    }
}
function getZipCodeForSearch() {
    zip_code = document.getElementById('zipcode_input').value;
    searchByzip(zip_code);
}
function searchByzip(zip_code) {
    window.location = baseUrl + '\event?zipcode=' + zip_code;
}