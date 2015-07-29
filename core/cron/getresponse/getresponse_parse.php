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

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('API_ROOT',dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))));
require_once(API_ROOT.'/core/config/core.config.php');
require_once(API_ROOT.'/core/config/pdo.config.php');
require_once(API_CORE_PATH.'/class/format/format.class.php');
require_once(API_CORE_PATH.'/class/database/database.class.php');
require_once(API_ROOT_PATH.'/getresponse/get_message_contents.php');

$db=new Database($pdoconfig);
$task=$db->getOne('getresponse_tasks','content','state');
if(empty($task)) exit(0); // Точка останова, если делать ничего не надо

$content=$task['content'];
$plain=$task['plain'];

// Фильтрация плохих писем
if(preg_match('/Вы зарегистрировались/ism',$content)){
    $db->updateOne('getresponse_tasks',$task['id'],array(
        'state'   => 'bad'
    ));
}


$data=array();

// Парсинг
if(isset($content)) {
    $content=preg_replace('/\<!DOCTYPE html>/smUi','',$content);
    $content=preg_replace('/\<!--.*--\>/smUi','',$content);
    $content=preg_replace('/\<head.*head\>/smUi','',$content);
    $content=preg_replace('/html\>/im','div>',$content);
    $content=preg_replace('/\<body/im','<div',$content);
    $content=preg_replace('/\<\/body/im','</div',$content);
    $content=preg_replace('/\{\{.*\}\}/smUi','',$content);
    $content=preg_replace('/\<table.{1,400}(Не отображается письмо)+.*table\>/smUi','',$content);
    $content=preg_replace('/\<table.{1,400}(Поделитесь этим письмом)+.*table\>/smUi','',$content);
    $content=preg_replace('/\<table.{1,600}(Вы получили это письмо)+.*table\>/smUi','',$content);
    $content=preg_replace('/\<table.{1,600}(С уважением)+.*table\>/smUi','',$content);
    $content=preg_replace('/\<table.{1,600}(© 1988-)+.*table\>/smUi','',$content);
    $content=preg_replace('/\<table.{1,600}(\<a href="" title="Twitter")+.*table\>/smUi','',$content);
    $content=preg_replace('/\<table.{1,600}(\<a href="" title="LinkedIn")+.*table\>/smUi','',$content);
    $content=preg_replace('/\<table.{1,600}(веб-версию письма)+.*table\>/smUi','',$content);
    $content=preg_replace('/^[\s\r\n]+$/m','',$content); // Убираем пустые строки
    $data['content'] = $content;
}

// Вывод
//print $content;
if(isset($plain)) {
    $plain = preg_replace('/^Смотрите веб-версию.*$/mi', '', $plain);
    $plain = preg_replace('/^веб-версию.*$/mi', '', $plain);
    $plain = preg_replace('/Прехедер.*$/mi', '', $plain);
    $plain = preg_replace('/Не отображается письмо.*$/mi', '', $plain);
    $plain = preg_replace('/""/mi', '', $plain);
    $plain = preg_replace('/^[\s\r\n]+$/m', '', $plain); // Убираем пустые строки
    $plain = preg_replace('/^[\s\r\n]+/sm', '', $plain); // Убираем пустые строки
    $data['plain'] = $plain;
    print $plain;
}

// Запись
$data['state']='parsed';
$db->updateOne('getresponse_tasks',$task['id'],$data);
/**/
