document.write('<script type="text/javascript" src="js/ksort.js"></script>');
document.write('<script type="text/javascript" src="js/md5.js"></script>');
function api(url, data, callback, type = 'get') {
    var snedData = {};
    var signKey = '~!@#$%^&*()_+';
    //签名
    var param = []; //定义一个数组
    //过滤空数据
    $.each(data, function (key, value) {
        snedData[key] = value;
        if (value !== '' && value !== undefined) {
            param[key] = value;
        }
    });
    param = ksort(param);//ksort 必须传入是一个数组，返回一个对象
    // join = implode; split = explode; join与split 是数组对象的方法
    var query = [];
    $.each(param, function (k, v) {
        query.push(k + '=' + v);
    });
    query = query.join('&');
    var sign = $.md5(query + signKey);
    snedData.sign = sign;
    $.ajax({
        type: type,
        url: url,
        data: snedData,
        dataType: 'json',
        success: function (e) {
            callback(e);
        }
    });
}

function getFormUrl() {
    return $('#data-form').attr('action');
}

function getFormData() {
    var formData = $('#data-form').serializeArray();
    var data = {};
    //将表单中的数据转换为key-value的形式
    for (var i = 0; i < formData.length; i++) {
        data[formData[i]['name']] = formData[i]['value'];
    }
    return data;
}

function getSucUrl() {
    return $('#data-form').attr('suc-url');
}

$('#sub-btn').click(function () {
    var url = getFormUrl();
    var data = getFormData();
    api(url, data, function (e) {
        if (e.code == 200) {
            alert(e.message);
            location.href = getSucUrl();
            return;
        }
        alert(e.message);
    }, 'post');
});

$('#file-input').change(function () {
    var form = new FormData(); //创建表单对象，上传图片
    //获取文件
    var file = $(this);
    form.append('upload_file', file[0].files[0]);
    $.ajax({
        type: 'post',
        url: 'index.php?r=upload/upload',
        data: form,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (e) {
            if (e.code == 200) {
                $('#file-value-input').val(e.data.file_path);
                return;
            }
            alert(e.message);
        }
    });
});
list_param = {};
function getData() {
    var url = $('#data-table').data('url');
    api(url, list_param, function (e) {
        if (e.code == 200) {
            var box = $('#data-box');
            box.empty();
            $.each(e.data.list, function () {
                var template = $('#data-template').html().toString();
                $.each(this, function (key, value) {
                    template = template.replace(eval('/{{' + key + '}}/g'), value);
                });
                box.append(template);
            });
            $('#page-box').html(e.data.page);
        }
    });
}

$(document).on('click', '#page-box a', function () {
    var page = parseInt($(this).attr('data-page')) + 1;
    list_param.page = page;
    getData();
    return false;
});

$('#search-btn').click(function () {
    var input = $('.search-input');
    for (var i = 0; i < input.length; i++) {
        list_param[input.eq(i).data('column')] = input.eq(i).val();
    }
    list_param.page = 1;
    getData();
});

old_data = '';

$(document).on('click', '.edit-td span', function () {
    var span = $(this);
    var data = span.text();
    old_data = data;
    var td = span.parent();
    var input = $('<input type="text" class="edit-input" />');
    td.html(input);
    td.find('input').focus().val(data);
});

$(document).on('blur', '.edit-input', function () {
    edit($(this), function (e, td, data) {
        if (e.code == 200) {
            td.html('<span>' + data + '</span>');
        } else {
            alert(e.message);
        }
    });
});


function edit(obj, callback, type = true) {
    var input = obj;
    var td = input.parents('td');
    var data = input.val();
    if (type == true && (data == old_data || data == '')) {
        td.html('<span>' + old_data + '</span>');
        return false;
    }
    var column = td.data('column');
    var id = input.parents('tr').data('id');
    var table = input.parents('table').data('table');
    var pk = input.parents('table').data('pk');
    api('index.php?r=brand-api/edit', {
        table: table,
        pk: pk,
        id: id,
        column: column,
        data: data
    }, function (e) {
        callback(e, td, data);
    });
}

upload_image = '';
$(document).on('click', '.edit-image', function () {
    upload_image = $(this);
    $('#edit-image-input').trigger('click');
});

$('#edit-image-input').change(function () {
    var form = new FormData(); //创建表单对象，上传图片
    //获取文件
    var file = $(this);
    form.append('upload_file', file[0].files[0]);
    $.ajax({
        type: 'post',
        url: 'index.php?r=upload/upload',
        data: form,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (e) {
            file.val('');
            if (e.code == 200) {
                upload_image.parent().append('<input type="hidden" value="' + e.data.file_path + '"/>');
                edit(upload_image.next(), function (es) {
                    if (es.code == 200) {
                        upload_image.attr('src', e.data.file_path);
                        upload_image.parent().find('input').remove();
                    } else {
                        alert(e.message);
                    }
                }, false);
                return false;
            }
            alert(e.message);
        }
    });
});