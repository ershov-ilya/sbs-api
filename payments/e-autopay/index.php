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
$id=0;
if(isset($_REQUEST['id'])) $id=$_REQUEST['id'];
$id=preg_replace('/[^\d]/','', $id);
if(empty($id)) die("Ошибка в запросе");


?>
