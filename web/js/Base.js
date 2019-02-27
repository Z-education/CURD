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
