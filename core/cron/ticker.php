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

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('API_ROOT',dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))));
require_once(API_ROOT.'/core/config/core.config.php');

if(isset($argv)){
    $file=API_ROOT.'/core/cron/cron.log';
    $date=date("d.m.Y G:i:s");
    $text="tick $date";
//if(isset($_SERVER)) $text.=" SERVER;\n";
    if(isset($argv)) $text.=" console\n";
    $text.="\n";
    $text.=$_SERVER['DOCUMENT_ROOT']."\n";
    $text.=$_SERVER['SCRIPT_FILENAME']."\n";
    file_put_contents($file, $text, FILE_APPEND);
    if(DEBUG) print '$argv isset!'.PHP_EOL;
    exit(0);
}

