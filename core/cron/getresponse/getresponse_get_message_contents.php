<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 28.07.2015
 * Time: 14:04
 */

if(isset($_GET['t'])) define('DEBUG',true);
else  define('DEBUG',false);

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('API_ROOT',dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))));
require_once(API_ROOT.'/core/config/core.config.php');
require_once(API_ROOT.'/core/config/pdo.config.php');
require_once(API_CORE_PATH.'/class/format/format.class.php');
require_once(API_CORE_PATH.'/class/database/database.class.php');
require_once(API_ROOT_PATH.'/getresponse/get_messages.php');

$messages=get_messages();
//print_r($messages);

$rows=array();
$i=0;
foreach($messages as $k => $m){
    $row=array();
    $row['message_id']=$k;
    $m=(array)$m; // stdObject => Array

    foreach($m as $li => &$lv){
        if(is_array($lv)) $lv=implode(',',$lv);
    }
    $row=array_merge($row, $m);

    $rows[]=$row;
    $i++;
//    if($i>7) break;
}
//print_r($rows);
//exit(0);

$db=new Database($pdoconfig);

$overlay=array('state'=>'found');
$fields='message_id,state,status,autoresponder_name,days_of_week,flags,action,time_travel,subject,name,content_types,editor_engine,send_on,editor_version,campaign,created_on,type,content';
$db->put('getresponse_tasks',$fields, $rows, DB_FLAG_IGNORE, $overlay);
//$db->putOne('getresponse_tasks', $result, DB_FLAG_IGNORE);
print "Errors:\n";
$db->sayError();

exit(0);

//print Format::parse($_SERVER, 'php');
//exit(0);

if(DEBUG){
    print_r($_SERVER);
}

$log='';
$response='';

if($_SERVER["DOCUMENT_ROOT"]==""){
    $file=API_CORE_PATH.'/cron/cron.log';
    $date=date("d.m.Y G:i:s");
    $log.="tick $date";
    $log.="\n\n";
    if(isset($_SERVER)) $log.=Format::parse($_SERVER, 'php');
    $log.="\n\n";
    if(isset($argv)) $log.=" console\n";
    $log.="\n";
    file_put_contents($file, $log, FILE_APPEND);
    exit(0);
}


if(DEBUG) print Format::parse($response, 'php');
else  print Format::parse($response, 'json');