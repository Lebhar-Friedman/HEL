function logoChange(logo) {

    var preview = document.getElementById('logo_img');
    var reader = new FileReader();
    if (logo.value.length === 0) {
        preview.src = baseUrl+'images/upload-logo.png';
        return false;
    } else {
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            preview.src = e.target.result;
        };
    }
    reader.readAsDataURL(logo.files[0]);
}
$(document).ready(function () {
    $(document).on('submit','#companyForm',function(e){
        
//$("#companyForm").submit(function (e) {
        e.preventDefault();
//        $("#loading").show();

        var form = $(this);
        var data = new FormData($('#companyForm')[0]);
         console.log(data);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (r) {
                if (r.msgType === 'SUC') {
                    window.location.href = baseUrl + 'company/detail?cid=' + r.companyId;
                } else {
//                    $('#success-msg').show();
//                    $('#loading').hide();
//                    document.location.reload(true);

                }
            },
            error: function ()
            {
                console.log('internal server error');
            }
        });
        return false;
    });
});