function logoChange(logo) {
    var preview = document.getElementById('logo_img');
    var reader = new FileReader();
    if (logo.value.length === 0) {
        preview.src = baseUrl+'images/upload-logo.png';
        return false;
    } else {
        reader.onload = function (e) {
            preview.src = e.target.result;
        };
    }
    reader.readAsDataURL(logo.files[0]);
}
$(document).ready(function () {
    $(document).on('submit','#companyForm',function(e){
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

function deleteCompany(companyID,element){
    if (! confirm("Are you sure?")) {
        return false;
    }
    $.ajax({
            url: baseUrl+'company/delete',
            type: 'post',
            data: {cid:companyID},
            dataType: "json",
            success: function (r) {
                console.log(r);
                if (r.msgType === 'SUC') {
                    toastr.success(r.msg);
                    $(element).closest('.main-table').hide(1000, function () {
                        $(element).closest('.main-table').remove();
                    });
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