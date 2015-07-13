<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 13.07.2015
 * Time: 17:25
 */
if(isset($_GET['t'])) define('DEBUG',true);
else  define('DEBUG',false);

$file='cron.log';
$date=date("d.m.Y G:i:s");
$text="tick $date";
if(isset($_SERVER)) $text.=" SERVER;\n";
if(isset($argv)) $text.=" argv;\n";
$text.="\n";
file_put_contents($file, $text, FILE_APPEND);
