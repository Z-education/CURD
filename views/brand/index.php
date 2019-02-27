<?php

use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="<?= Url::to(['brand/create']); ?>">新增</a>
        <hr />
        <input type="text" class="search-input" data-column="brand_name" placeholder="品牌名称"/>
        <select data-column="brand_type" class="search-input">
            <option value="">请选择</option>
            <option value="1">汽车</option>
            <option value="2">飞机</option>
            <option value="3">火箭</option>
        </select>
        <input type="button" id="search-btn" value="搜索"/>
        <table border="1">
            <tr>
                <td>ID</td>
                <td>品牌</td>
                <td>LOGO</td>
                <td>分类</td>
                <td>排序</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
            <tbody id="data-box"></tbody>
            <tbody id="data-template" style="display: none">
                <tr>
                    <td>{{id}}</td>
                    <td>{{brand_name}}</td>
                    <td><img src="{{brand_logo}}" height="150" /></td>
                    <td>{{brand_type}}</td>
                    <td>{{sort}}</td>
                    <td>{{status}}</td>
                    <td><a href="<?= Url::to(['brand-api/delete']) ?>&id={{id}}" class="del-btn">删除</a></td>
                </tr>
            </tbody>
        </table>
        <div id="page-box"></div>
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <script type="text/javascript" src="./js/Base.js"></script>
        <script type="text/javascript">
            $(function () {
                list_param = {};
                getData();
                function getData() {
                    api('<?= Url::to(["brand-api/index"]) ?>', list_param, function (e) {
                        if (e.code == 200) {
                            var box = $('#data-box');
                            box.empty();
                            $.each(e.data.list, function () {
                                var template = $('#data-template').html().toString();
                                $.each(this, function (key, value) {
                                    template = template.replace('{{' + key + '}}', value);
                                });
                                box.append(template);
                            });
                            $('#page-box').html(e.data.page);
                        }
                    });
                }
                
                $(document).on('click', '#page-box a', function(){
                    var page = parseInt($(this).attr('data-page')) + 1;
                    list_param.page = page;
                    getData();
                    return false;
                });
                
                $('#search-btn').click(function(){
                    var input = $('.search-input');
                    for(var i = 0; i < input.length; i++){
                        list_param[input.eq(i).data('column')] = input.eq(i).val();
                    }
                    list_param.page = 1;
                    getData();
                });
            });
        </script>
    </body>
</html>
