<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 16.01.2015
 * Time: 11:09
 */

// Засечки времени
$start_time=microtime(true);
function time_marker(){$timepoint=microtime(true); print "Time: "; global $start_time; print ($timepoint-$start_time);  print "\n";}

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

$parent = $modx->getObject('modResource',array('id'=>'973'));
time_marker();
print $parent->get('title');