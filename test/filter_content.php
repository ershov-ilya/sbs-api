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
//define('ROOT_PATH', 'http://ershov.pw/api/sbs');
define('ROOT_PATH', 'http://sbs.edu.ru/api');

$data = array();
$data["key"]="sbs";
$data["id"]="76811";

require('../lib/curl.php');
$data['content'] = CURL(ROOT_PATH.'/emarsys/email/get-body/', $data);

$res=CURL(ROOT_PATH.'/filter/content/', $data);
print $res;
