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

require('../emarsys/lib/filter_content.php');


$data = array(
    "content" => "kjsdhgklslg ahg alwerhg lkeawrhgl irgpobh aoihgl biwaegi"
);
print filter_content('http://sbs.edu.ru/api/filter/content/', $data);