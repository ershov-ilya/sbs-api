<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 09.07.2015
 * Time: 14:01
 */

// TODO: Access-Control-Allow-Origin
//header("Access-Control-Allow-Origin: http://sbs.edu.ru");
header("Access-Control-Allow-Origin: *");

// Errors control
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
if(isset($_REQUEST['t']))
    define('DEBUG', true);
defined('DEBUG') or define('DEBUG', false);
$response=array();

/* MODX
------------------------------------------------------------------- */
defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require_once('../../../index.php');


/* CONFIG
------------------------------------------------------------------- */
require_once('../../core/config/core.config.php');
require_once(API_CORE_PATH.'/class/restful/restful.class.php');


/* @var modX $modx*/
$user_id=$modx->user->id;
$obj=$modx->getObject('Maillists',array('internalKey'=>$user_id));
if($obj==NULL){
    $obj=$modx->newObject('Maillists',array('internalKey'=>$user_id));
    $obj->save();
    if($obj!=NULL) $response['action']='create';
}
else{
    $response['action']='update';
}

//var_dump($obj->id);
print_r($obj->toArray());


//$response['site_name']=$modx->getOption('site_name');
$response['user_id']=$user_id;

require_once(API_CORE_PATH.'/class/format/format.class.php');
if(DEBUG) print Format::parse($response, 'php');
else  print Format::parse($response, 'json');
/**/