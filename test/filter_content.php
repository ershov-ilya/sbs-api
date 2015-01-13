<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 13.01.2015
 * Time: 14:28
 */

header('Content-Type: text/plain; charset=utf-8');

error_reporting(E_ALL);
ini_set("display_errors", 1);

$data = array();
//$data["content"]="kjsdhgklslg ahg alwerhg lkeawrhgl irgpobh aoihgl biwaegi";
$data["key"]="sbs";
$data["id"]="76416";

require('../lib/curl.php');
$data['content'] = CURL('http://sbs.edu.ru/api/emarsys/email/get-body/', $data);

$res=CURL('http://sbs.edu.ru/api/filter/content/', $data);
print $res;
