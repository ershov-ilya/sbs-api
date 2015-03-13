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

// config
require_once('../../core/config/core.config.php');

// Init
$output='';

// Get data
$content=file('input.csv');

//print_r($content[0]);

// Вычисления
foreach($content as $str)
{
    $arr = explode(';', $str);
    $output.=$arr[0]."\n";
}

// Вывод шаблона
include(TEMPLATE_PATH.'/base.tpl.php');

