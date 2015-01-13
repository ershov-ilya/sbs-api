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
$data["content"]="kjsdhgklslg ahg alwerhg lkeawrhgl irgpobh aoihgl biwaegi";

require('../lib/filter_content.php');
print filter_content('http://sbs.edu.ru/api/filter/content/', $data);