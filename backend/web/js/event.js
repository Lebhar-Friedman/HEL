$(document).ready(function () {
    $(".chosen-select").chosen();
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
    });
    $("#eventform-date_end").datepicker('option', 'minDate', $("#eventform-date_start").val());
    $("#eventform-date_start").datepicker('option', 'onSelect', function () {
        $("#eventform-date_end").datepicker('option', 'minDate', $("#eventform-date_start").val());
    });

    $('#edit_btn_event').click(function () {
        showEventForm();
    });
    $('#cancel_btn_event').click(function () {
        showEventDetail();
    });
});
function selectAll() {
    if ($("#check_all").is(':checked')) {
        $('.table-chk-h1 input:checkbox').prop('checked', true);
    } else {
        $('.table-chk-h1 input:checkbox').prop('checked', false);
    }
}
function parentUnselect(element) {
    if (!$(element).is(':checked')) {
        $('#check_all').prop('checked', false);
    }
}
function deleteEvent(eventID, element, redirect) {
    if (!confirm("Are you sure, you want to delete this event?")) {
        return false;
    }
    $.ajax({
        url: baseUrl + 'event/delete',
        type: 'post',
        data: {eid: eventID},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                $(element).closest('.csv-table-row1').hide(1000, function () {
                    $(element).closest('.csv-table-row1').remove();
                });
                if (redirect) {
                    setTimeout(function () {
                        window.location.href = baseUrl + '' + redirect;
                    }, 2000);
                }
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
function deleteSelectedEvent() {
    if (!confirm("Are you sure?")) {
        return false;
    }
    var eventsIds = [];
    $(".table-chk-h1 input[name='checkEvent']:checked").each(function () {
        eventsIds.push($(this).attr('id'));
    });
    $.ajax({
        url: baseUrl + 'event/delete-selected',
        type: 'post',
        data: {eids: eventsIds},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
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
function postEvent(eventID, element) {
    actionText = $(element).text();
    if (!confirm("Are you sure you  want to " + actionText + " this event?")) {
        return false;
    }
    $.ajax({
        url: baseUrl + 'event/post',
        type: 'post',
        data: {eid: eventID},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                $(element).addClass('hidden');
                $('#unpost-' + eventID).removeClass('hidden');
                toastr.success(r.msg.replace('post', actionText));
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
function unpostEvent(eventID, element) {
    actionText = $(element).text();
    if (!confirm("Are you sure you  want to " + actionText + " this event?")) {
        return false;
    }
    $.ajax({
        url: baseUrl + 'event/unpost',
        type: 'post',
        data: {eid: eventID},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                $(element).addClass('hidden');
                $('#post-' + eventID).removeClass('hidden');
                toastr.success(r.msg.replace('unpost', actionText));
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

function postSelectedEvent(eventID, element) {
    if (!confirm("Are you sure?")) {
        return false;
    }
    var eventsIds = [];
    $(".table-chk-h1 input[name='checkEvent']:checked").each(function () {
        eventsIds.push($(this).attr('id'));
    });
    $.ajax({
        url: baseUrl + 'event/post-selected',
        type: 'post',
        data: {eids: eventsIds},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
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

function showEventForm() {
    $('#detailEvent').hide();
    $('#edit_btn_event').hide();
    $('#editEvent').show();
    $('#cancel_btn_event').show();
}
function showEventDetail() {
    $('form')[0].reset();
    $('#editEvent').hide();
    $('#cancel_btn_event').hide();
    $('#detailEvent').show();
    $('#edit_btn_event').show();
}
