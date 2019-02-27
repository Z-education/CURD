<?php

use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style type="text/css">
            .pagination li{
                float: left;
                margin-left: 15px;
                list-style:none;
            }
            #edit-image-input{
                display: none;
            }
        </style>
    </head>
    <body>
        <a href="<?= Url::to(['brand/create']); ?>">新增</a>
        <hr />
        <input type="file" id="edit-image-input"/>
        <input type="text" class="search-input" data-column="brand_name" placeholder="品牌名称"/>
        <select data-column="brand_type" class="search-input">
            <option value="">请选择</option>
            <option value="1">汽车</option>
            <option value="2">飞机</option>
            <option value="3">火箭</option>
        </select>
        <input type="button" id="search-btn" value="搜索"/>
        <table border="1" data-url="<?= Url::to(['brand-api/index']) ?>" id="data-table"  data-pk="id" data-table="brand">
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
                <tr data-id="{{id}}">
                    <td>{{id}}</td>
                    <td class="edit-td" data-column="brand_name"><span>{{brand_name}}</span></td>
                    <td data-column="brand_logo" ><img class="edit-image" src="{{brand_logo}}" height="150" /></td>
                    <td class="edit-td" data-column="brand_type"><span>{{brand_type}}</span></td>
                    <td class="edit-td" data-column="sort"><span>{{sort}}</span></td>
                    <td class="edit-td" data-column="status"><span>{{status}}</span></td>
                    <td><a href="<?= Url::to(['brand-api/delete']) ?>&id={{id}}" class="del-btn">删除</a></td>
                </tr>
            </tbody>
        </table>
        <div id="page-box"></div>
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <script type="text/javascript" src="./js/Base.js"></script>
        <script type="text/javascript">
            $(function () {
                getData();
            });
        </script>
    </body>
</html>
