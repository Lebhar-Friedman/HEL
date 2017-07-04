function logoChange(logo) {

    var preview = document.getElementById('logo_img');
    var reader = new FileReader();
    if (logo.value.length === 0) {
        // get loaded data and render thumbnail.
        preview.src = '../images/upload-logo.png';
    } else {
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            preview.src = e.target.result;
        };
    }
    // read the image file as a data URL.
    reader.readAsDataURL(logo.files[0]);
}  