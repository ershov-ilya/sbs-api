<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 25.02.2015
 * Time: 12:51
 */

header('Content-Type: text/html; charset=utf-8');
$product=118315;
if(isset($_REQUEST['product'])) $product=$_REQUEST['product'];
$id=preg_replace('/[^\d]/','', $product);
if(empty($id)) die("Ошибка в запросе");

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Оплата</title>
    <style type="text/css">
        .wrapper{
            margin:10% auto 0;
            width:500px;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <script charset="UTF-8" type="text/javascript" src="http://sbsedu.e-autopay.com/js/orderform_11566/<?=$product?>"></script>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="app.js"></script>

</body>
</html>
