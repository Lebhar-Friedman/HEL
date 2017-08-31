$(document).ready(function () {
    $('#SubCategoryForm').on('beforeSubmit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $("#loader-sub-category").show();
        $("#overlay-sub-category").show();
        var form = $(this);
        var data = new FormData($('#SubCategoryForm')[0]);
//        console.log(data);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (r) {
                console.log(r);
                if (r.msgType === 'SUC') {
                    $(document).on('pjax:complete', function () {
                        $("#loader-sub-category").hide();
                        $("#overlay-sub-category").hide();
                    });
                    $.pjax.reload({
                        url: baseUrl + 'category',
                        container: '#sub-category-content',
                        replace: false,
                        type: 'get',
//                    data: form_data,
                        timeout: 30000,
                        push: true
                    });
//                    $('#SubCategoryForm')[0].reset();
//                    window.location.href = baseUrl + 'category/add-sub-category';
                } else {
                    toastr.error(r.msg);
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
    $('#CategoryForm').on('beforeSubmit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $("#loader-category").show();
        $("#overlay-category").show();
        var form = $(this);
        var data = new FormData($('#CategoryForm')[0]);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (r) {
                console.log(r);
                if (r.msgType === 'SUC') {
                    $(document).on('pjax:complete', function () {
                        $("#loader-category").hide();
                        $("#overlay-category").hide();
                    });
                    $.pjax.reload({
                        url: baseUrl + 'category',
                        container: '#category-content',
                        replace: false,
                        type: 'get',
                        timeout: 30000,
                        push: true
                    });
                } else {
                    toastr.error(r.msg);
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
//function updateSubCategory(subCategoryID, e) {
$("form[id*='SubCategoryField']").on('beforeSubmit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var form = $(this);
    //console.log(this);
    var data = new FormData($(this)[0]);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: data,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (r2) {
            if (r2.msgType === 'SUC') {
                toastr.success(r2.msg);
                $.pjax.reload({
                    url: baseUrl + 'category',
                    container: '#category-content',
                    replace: false,
                    type: 'get',
                    timeout: 30000,
                    push: true
                });
            } else {
                toastr.success(r2.msg);
            }
        },
        error: function (jqXHR, exception) {
            console.log('Internal server error');
        },
        complete: function (r2) {
            return false;
        }
    });
    return false;
});
function deleteSubCategory(subcategoryID, element) {
    if (!confirm("Are you sure, you want to delete this sub-category?")) {
        return false;
    }
    $("#loader-sub-category").show();
    $("#overlay-sub-category").show();
    $.ajax({
        url: baseUrl + 'category/sub-category-delete',
        type: 'post',
        data: {sub_cat_id: subcategoryID},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                $(document).on('pjax:complete', function () {
                    $("#loader-sub-category").hide();
                    $("#overlay-sub-category").hide();
                });
                $.pjax.reload({
                    url: baseUrl + 'category',
                    container: '#sub-category-content',
                    replace: false,
                    type: 'get',
//                    data: form_data,
                    timeout: 30000,
                    push: true
                });

            } else if(r.msgType === 'INF'){
                 $("#loader-sub-category").hide();
                 $("#overlay-sub-category").hide();
                toastr.info(r.msg);
            }
            else {
                 $("#loader-sub-category").hide();
                 $("#overlay-sub-category").hide();
                toastr.error(r.msg);
            }
        },
        error: function ()
        {
            toastr.error('Internal server error');
        }
    });
}
function addSubCategoryLink(categoryID, element) {

    sub_category = $(element).val();
    $("#loader-category").show();
    $("#overlay-category").show();

    $.ajax({
        url: baseUrl + 'category/add-sub-category-link',
        type: 'post',
        data: {category_id: categoryID, sub_categories: sub_category},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                $(document).on('pjax:complete', function () {
                    $("#loader-category").hide();
                    $("#overlay-category").hide();
                });
                $.pjax.reload({
                    url: baseUrl + 'category',
                    container: '#category-content',
                    replace: false,
                    type: 'get',
//                    data: form_data,
                    timeout: 30000,
                    push: true
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
function deleteCategory(categoryID, element) {
    if (!confirm("Are you sure, you want to delete this sub-category?")) {
        return false;
    }
    $("#loader-category").show();
    $("#overlay-category").show();

    $.ajax({
        url: baseUrl + 'category/category-delete',
        type: 'post',
        data: {cat_id: categoryID},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                $(document).on('pjax:complete', function () {
                    $("#loader-category").hide();
                    $("#overlay-category").hide();
                });
                $.pjax.reload({
                    url: baseUrl + 'category',
                    container: '#category-content',
                    replace: false,
                    type: 'get',
//                    data: form_data,
                    timeout: 30000,
                    push: true
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
$("form[id*='CategoryField']").on('beforeSubmit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var form = $(this);
    //console.log(this);
    var data = new FormData($(this)[0]);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: data,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (r2) {
            if (r2.msgType === 'SUC') {
                toastr.success(r2.msg);
                $.pjax.reload({
                    url: baseUrl + 'category',
                    container: '#category-content',
                    replace: false,
                    type: 'get',
                    timeout: 30000,
                    push: true
                });
            } else {
                toastr.success(r2.msg);
            }
        },
        error: function (jqXHR, exception) {
            console.log('Internal server error');
        },
        complete: function (r2) {
            return false;
        }
    });
    return false;
});
function deleteSubCategoryLink(category, subCategory, element) {
    if (!confirm("Are you sure, you want to delete this link of sub-category?")) {
        return false;
    }
    $("#loader-category").show();
    $("#overlay-category").show();
    $.ajax({
        url: baseUrl + 'category/delete-sub-category-link',
        type: 'post',
        data: {category_id: category, sub_categories: subCategory},
        dataType: "json",
        success: function (r) {
            console.log(r);
            if (r.msgType === 'SUC') {
                toastr.success(r.msg);
                $(document).on('pjax:complete', function () {
                    $("#loader-category").hide();
                    $("#overlay-category").hide();
                });
                $.pjax.reload({
                    url: baseUrl + 'category',
                    container: '#category-content',
                    replace: false,
                    type: 'get',
//                    data: form_data,
                    timeout: 30000,
                    push: true
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