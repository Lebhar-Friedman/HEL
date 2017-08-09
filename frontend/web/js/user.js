
$(document).ready(function () {
    $('.alerts-multi-chosen-select').chosen({placeholder_text_multiple: 'Type Alert Name'});
});

function saveAlert() {
    var selected_alert = $('#alert_select').val();
    if (selected_alert === '0') {
        alert('Select an alert');
    } else {
        $.ajax({
            url: baseUrl + 'user/add-alerts',
            type: 'post',
            data: {alert: selected_alert},
            success: function (r) {
                if (r.msgType === "SUC") {
                    toastr.success(r.msg);
                }else if(r.msgType === "ERR"){
                    toastr.error(r.msg);
                }
                $.pjax.reload({
                    url: baseUrl + 'user/alerts',
                    container: '#alerts-view',
                    replace: false,
                    timeout: 30000
                });
            },
            error: function ()
            {
                console.log('internal server error');
            }
        });
    }
}

function delete_alert(alert_del, row_id) {
    if (!confirm('Are you sure ')) {
        return false;
    }
    $.ajax({
        url: baseUrl + 'user/delete-alert',
        type: 'post',
        dataType: 'json',
        data: {alert: alert_del},
        success: function (r) {
            if (r.msgType === "SUC") {
                $('#alert_' + row_id).hide('slow');
                toastr.success(r.msg);
            } else if (r.msgType === "ERR") {
                toastr.error(r.msg);
            }
        },
        error: function ()
        {
            console.log('internal server error');
        }
    });
}
