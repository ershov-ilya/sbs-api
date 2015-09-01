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
require_once(API_ROOT_PATH.'/getresponse/func/get_message_contents.php');

//$messages=get_messages();
//print_r($messages);

$db=new Database($pdoconfig);
$task=$db->getOne('getresponse_tasks','new','state');
//print_r($task);
if(empty($task)) exit(0); // Точка останова, если делать ничего не надо


$response=get_message_contents($task['message_id']);
$content=$response['content'];
$plain=$response['plain'];

$db->updateOne('getresponse_tasks',$task['id'],array(
    'state'   => 'content',
    'content' => $content,
    'plain'   => $plain
));

$errors=$db->errors();
if(!empty($errors)){
    $db->sayError();
}

//if(DEBUG) print Format::parse($response, 'php');
//else  print Format::parse($response, 'json');