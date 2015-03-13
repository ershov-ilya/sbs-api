<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 13.03.2015
 * Time: 13:37
 */
header('Content-Type: text/html; charset=utf-8');

// config
require_once('../../core/config/core.config.php');

// Init
$output='';

// Build index
$data=file(BASE_URL.'/do/get-email-ids/'); // Параметр ?re для сброса кэша
$index=array();
foreach($data as $str)
{
    $arr = explode(';', $str, 3);
    $index[$arr[0]] = $arr;
}

// Get data
$content=file('input.csv');
unset($content[0]);

// Вычисления
foreach($content as $str)
{
    $str=preg_replace('/\r\n/','',$str);
    $arr = explode(';', $str);
    $output.=$str.';'.$index[$arr[0]][2];
}

// Вывод шаблона
include(TEMPLATE_PATH.'/base.tpl.php');

