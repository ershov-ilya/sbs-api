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
error_reporting(E_ERROR | E_WARNING);
ini_set("display_errors", 1);

define('API_ROOT',dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))));
require_once(API_ROOT.'/core/config/core.config.php');
require_once(API_ROOT.'/core/config/pdo.config.php');
require_once(API_CORE_PATH.'/class/format/format.class.php');
require_once(API_CORE_PATH.'/class/database/database.class.php');
require_once(API_ROOT_PATH.'/getresponse/func/get_message_contents.php');

function _substr($text, $length)
{
    $length = strripos(substr($text, 0, $length), ' ');
    return substr($text, 0, $length);
}

$db=new Database($pdoconfig);
$task=$db->getOne('getresponse_tasks','parsed','state');
if(empty($task)) exit(0); // Точка останова, если делать ничего не надо
//print_r($task);

// Подключаем
/* @var modX $modx */
/* @var modResource $resource */
define('MODX_API_MODE', true);
require(dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))))).'/index.php');
//print $modx->getOption('site_name');

// Включаем обработку ошибок
$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_FATAL);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
$modx->error->message = null; // Обнуляем переменную

// Логинимся в админку
//$response = $modx->runProcessor('security/login', array('username' => '***', 'password' => '******'));
//print_r($response->response);
//if ($response->isError()) {
//    $modx->log(modX::LOG_LEVEL_ERROR, $response->getMessage());
//    return;
//}
//$modx->initialize('mgr');

//$resource=$db->getOne('modx_site_content',1383);
//print_r($resource);

if(empty($task['plain'])) $introtext='';
else $introtext=_substr($task['plain'], 250);

$time=strtotime($task['send_on']);

$config=array(
    'type' => 'document',
    'contentType' => 'text/html',
    'pagetitle' => $task['subject'],
    'longtitle' => $task['subject'],
    'description' => $introtext,
    'alias' => $task['message_id'].'-'.$task['name'],
    'introtext' => $introtext.'…',
    'published' => '1',
    'pub_date' => '0',
    'unpub_date' => '0',
    'parent' => '973',
    'isfolder' => '0',
    'content' => $task['content'],
    'richtext' => '1',
    'template' => '60',
    'menuindex' => '0',
    'searchable' => '1',
    'cacheable' => '1',
    'createdby' => '0',
    'editedby' => '0',
    'createdon' => $time,
    'editedon' => $time,
    'deleted' => '0',
    'deletedon' => '0',
    'deletedby' => '0',
    'publishedon' => $time,
    'publishedby' => '0',
    'hidemenu' => '0',
    'class_key' => 'modDocument',
    'context_key' => 'web',
    'content_type' => '1',
    'uri' => 'archive/'.$task['message_id'].'-'.$task['name'],
    'uri_override' => '1',
    'hide_children_in_tree' => '0',
    'show_in_tree' => '0'
);
//print_r($config);

$resource=$modx->newObject('modResource', $config);
$res=$resource->save();
//print $res;

if($res){
    $data['state']='saved';
}else{
    $data['state']='failed';
}
$db->updateOne('getresponse_tasks',$task['id'],$data);
