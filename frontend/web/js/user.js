
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
                } else if (r.msgType === "ERR") {
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

$(document).on("submit", "#alert_form", function (e) {
    e.preventDefault();
    var form_data = $("#alert_form").serialize();
    $.ajax({
        url: baseUrl + '/user/add-alerts',
        type: 'post',
        data: form_data,
        dataType: 'JSON',
        success: function (r) {
            if (r.msgType === "SUC") {
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
});
