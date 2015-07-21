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

if(DEBUG){
    print_r($_SERVER);
}

$log='';
$response='';

if(isset($argv)){
    $file=API_CORE_PATH.'/cron/cron.log';
    $date=date("d.m.Y G:i:s");
    $log.="tick $date";
//if(isset($_SERVER)) $log.=" SERVER;\n";
    if(isset($argv)) $log.=" console\n";
    $log.="\n";
    file_put_contents($file, $log, FILE_APPEND);
    exit(0);
}


require_once(API_CORE_PATH.'/class/format/format.class.php');
if(DEBUG) print Format::parse($response, 'php');
else  print Format::parse($response, 'json');