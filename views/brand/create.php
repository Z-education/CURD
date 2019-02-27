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
        <form action="<?= Url::to(['brand-api/create']) ?>" suc-url="<?= Url::to(['brand/index']) ?>" id="data-form">
            <table border="1">
                <tr>
                    <td>品牌名称</td>
                    <td><input type="text" name="brand_name"/></td>
                </tr>
                <tr>
                    <td>类型</td>
                    <td>
                        <select name="brand_type">
                            <option value="">请选择</option>
                            <option value="1">汽车</option>
                            <option value="2">飞机</option>
                            <option value="3">火箭</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>品牌LOGO</td>
                    <td>
                        <input type="file" id="file-input"/>
                        <input type="hidden" name="brand_logo" id="file-value-input"/>
                    </td>
                </tr>
                <tr>
                    <td>排序</td>
                    <td><input type="text" name="sort" /></td>
                </tr>
                <tr>
                    <td>状态</td>
                    <td>
                        <input type="radio" name="status" value="0"/>关闭
                        <input type="radio" name="status" value="1" checked/>开启
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="button" value="提交" id="sub-btn"/>
                    </td>
                </tr>
            </table>
        </form>
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <script type="text/javascript" src="./js/Base.js"></script>
    </body>
</html>
