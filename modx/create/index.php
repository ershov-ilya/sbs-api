<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 14.01.2015
 * Time: 15:17
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ERROR | E_WARNING);
ini_set("display_errors", 1);

// Подключаем
define('MODX_API_MODE', true);
require('../../../index.php');

/* @var modX $modx */

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

$config=array(
    'parent' => '973',
    'template' => '27',
    'published' => '1'
);

$config=array_merge($config,$_REQUEST);

print_r($config);

// Создаем ресурс
//$response = $modx->runProcessor('resource/create', $config);
//print_r($response->response);

//if ($response->isError()) {
//    $modx->log(modX::LOG_LEVEL_ERROR, $response->getMessage());
//    return;
//}
//else {
//    print_r($response->response);
//}

/* @var modResource $resource */
$resource=$modx->newObject('modResource', $config);

print 'New doc: ';
print "\n";

print $resource->get('pagetitle');
print "\n";
print "ID: ";
print $resource->get('id');
print "\n";

// TODO: $doc->save();
var_dump($resource->save());