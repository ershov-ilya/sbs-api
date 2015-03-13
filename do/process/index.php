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
if(isset($_GET['csv']))
{
    define('CSV', true);
}
elseif(isset($_GET['plain']))
{
    define('PLAIN', true);
}
else
{
    define('HTML', true);
}

defined('CSV') or define('CSV', false);
defined('HTML') or define('HTML', false);
defined('PLAIN') or define('PLAIN', false);

if(CSV)
{
    header('Content-type: text/csv');
    header('Content-disposition: attachment;filename=output.csv');
}
elseif(HTML)
{
    header('Content-Type: text/html; charset=utf-8');
}
else
{
    header('Content-Type: text/plain; charset=utf-8');
}

// config
require_once('../../core/config/core.config.php');

// Init
$output='';

// Build index
$data=file(BASE_URL.'/do/get-email-ids/'); // Параметр ?re для сброса кэша
$index=array();
foreach($data as $str)
{
    $str=preg_replace("/[\n\r]+/",'',$str);
    $arr = explode(';', $str);
    $index[$arr[0]] = $arr;

//   print_r($arr);
//    print $str."\n";
}
//exit(0);

// Get data
$content=file('input.csv');

$headers=$content[0];
$headers=preg_replace('/.*ID/','ID',$headers);
$headers=preg_replace('/\r\n/','',$headers);
$headers.=";Дата;Тема\n";

unset($content[0]);

// Вычисления
foreach($content as $str)
{
    $str=preg_replace('/\r\n/','',$str);
    $arr = explode(';', $str);
    $id=$arr[0];

    $output.=$id.";".$arr[1].";".$index[$id][2].";".$index[$id][3]."\n";
}

$output=$headers.$output;

if(CSV) {
    $output=mb_convert_encoding($output, "windows-1251", "utf-8");
}

if(CSV || PLAIN) {
    print $output;
}

// Вывод шаблона
if(HTML) include(TEMPLATE_PATH.'/base.tpl.php');

