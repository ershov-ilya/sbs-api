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

$test=array(
    'partner'   => 'ershov',
    'product'   => 118315
);

header('Content-Type: text/html; charset=utf-8');
$product=118315;
if(isset($_REQUEST['product'])) $product=$_REQUEST['product'];
$_REQUEST['product']=preg_replace('/[^\d]/','', $product);

$field=array();
foreach($_REQUEST as $key => $val){
    switch($key) {
        case 'name':
        case 'partner':
        case 'phone':
        case 'program':
        case 'speaker':
        case 'land':
            $field[$key] = filter_var($val, FILTER_SANITIZE_STRING);
            break;
        case 'product':
        $field[$key] = preg_replace('/[^\d]/','', $val);
            break;
        case 'email':
            $field[$key] = filter_var($val, FILTER_SANITIZE_EMAIL);
            break;
        case 'cost':
            $field[$key] = filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT);
            break;
//            $field[$key] = $val;
//            break;
    }
}

$field=array_merge($test, $field);
if(empty($field['product'])) die("Ошибка в запросе");

$url="http://";
if(isset($field['partner'])) $url.=$field['partner'].'.';
$url.="sbsedu.e-autopay.com/js/orderform_11566/$product"
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
    <script charset="UTF-8" type="text/javascript" src="<?=$url?>"></script>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="app.js"></script>
<script type="text/javascript">
    docState.data=<?=json_encode($field)?>;
</script>
</body>
</html>
