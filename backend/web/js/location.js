$(document).ready(function () {
    $('#company').change(function () {
        this.form.submit();
    });
    $('#edit_btn_location').click(function () {
        showLocationForm();
    });
    $('#cancel_btn_location').click(function () {
        showLocationDetail();
    });
    
});
function deleteLocation(locationID, element) {
    if (!confirm("Are you sure?")) {
        return false;
    }
    $.ajax({
        url: baseUrl + 'location/delete',
        type: 'post',
        data: {lid: locationID},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                $(element).closest('.location-table-row1').hide(1000, function () {
                    $(element).closest('.location-table-row1').remove();
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
function searchLocation(locationID, element) {

}

function showLocationForm() {
    $('#detailLocation').hide();
    $('#edit_btn_location').hide();
    $('#editLocation').show();
    $('#cancel_btn_location').show();
}
function showLocationDetail() {
    $('form')[0].reset();
    $('#editLocation').hide();
    $('#cancel_btn_location').hide();
    $('#detailLocation').show();
    $('#edit_btn_location').show();
}
