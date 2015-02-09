<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 09.02.2015
 * Time: 16:52
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);

$json = json_encode($_POST);

$curtime=time()-3600;
$data = date("Y-m-d H:i:s", $curtime);
$content = $data.' json:'.$json."\n\n";

file_put_contents('log.txt', $content, FILE_APPEND);
$answer = array(
    'status'    => 'OK'
);

print (json_encode($answer));