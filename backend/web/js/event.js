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
                    setTimeout(function(){window.location.href = baseUrl + '' + redirect;},2000);                    
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
    if (!confirm("Are you sure?")) {
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
                toastr.success(r.msg);
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