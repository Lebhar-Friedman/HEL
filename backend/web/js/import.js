function keepServerAlive() {
    $.ajax({
        url: baseUrl + "import/server-alive",
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
            if (data.msgType == 'SUC') {
                Msg(data.msg);
                console.log(data.msg);
                
                $('#progress_bar').remove();
                $progress_bar = '<div class="upload" id="progress_bar" ><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">100%</div><div>';
                $('#csv_comp_content').append($progress_bar);
                $('#progress_bar').remove();
                clearInterval(window.checkFile);
                $alert = '<div class="alert alert-success alert-dismissable  upload">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                        '<strong>Success! </strong> '+data.msg+
                        '</div>';
                $('#csv_comp_content').append($alert);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else if (data.msgType == 'ERR') {
                Msg(data.msg, 'ERR');
                console.log(data.msg);
                $('#upload_btn').removeClass('hidden');
                $('#loader').addClass('hidden');
                $('#import').val('');
                $('#filename').val('file.csv');
                clearInterval(window.checkFile);
                $alert = '<div class="alert alert-danger alert-dismissable upload">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                        '<strong>ERROR!</strong> '+data.msg+
                        '</div>';
                $('#progress_bar').remove();
                $('#csv_comp_content').append($alert);
                
            } else if (data.msgType == 'NOT_EXIST') {
                console.log(data.msg);
            } else if (data.msgType == 'PROC') {
                $('#progress_bar').remove();
                $progress_bar = '<div class="upload" id="progress_bar" ><div class="progress" ><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="' + data.msg + '" aria-valuemin="0" aria-valuemax="100" style="width:' + data.msg + '%">' + data.msg + '%</div><div><div>';
                $('#csv_comp_content').append($progress_bar);
                console.log($progress_bar)
            }
             else if (data.msgType == 'EXC') {
                console.log(data.msg); 
                $('#upload_btn').removeClass('hidden');
                $('#loader').addClass('hidden');
                $('#import').val('');
                $('#filename').val('file.csv');
                clearInterval(window.checkFile);
                $alert = '<div class="alert alert-danger alert-dismissable upload">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                        '<strong>Exception! </strong> '+data.msg+
                        '</div>';
                $('#progress_bar').remove();
                $('#csv_comp_content').append($alert);
             }
        },
        error: function (data) {
            console.log('internal server error');
        }
    });
}


