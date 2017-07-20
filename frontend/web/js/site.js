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