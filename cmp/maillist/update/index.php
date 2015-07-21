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
require_once('../../../../index.php');


/* CONFIG
------------------------------------------------------------------- */
require_once('../../../core/config/core.config.php');
require_once(API_CORE_PATH.'/class/restful/restful.class.php');
/* @var modX $modx*/


try {
    $user_id = $modx->user->id;
    if(!$user_id) throw new Exception('Auth needed', 403);
    $request=$_POST;

    $obj = $modx->getObject('Maillists', array('internalKey' => $user_id));
    if ($obj == NULL) {
        $obj = $modx->newObject('Maillists', array('internalKey' => $user_id));
        $obj->save();
        if ($obj != NULL) $response['action'] = 'create';
    } else {
        if (empty($request)) {
            $response['action'] = 'get';
            $response['data'] = $obj->toArray();
        } else {
            $response['action'] = 'update';
            $response['data'] = $request;
            foreach($request as $k => $v){
                $val=$v;
                if($val == 'false' || $val === false) $val='0';
                if($val == 'true' || $val === true) $val='1';
                $obj->set($k, $val);
            }
            $res=$obj->save();
            $response['done']=$res;
        }
    }

$response['user_id']=$user_id;
$response['code']=200;
//var_dump($obj->id);
//print_r($obj->toArray());
}
catch(Exception $e){
    $response['message']=$e->getMessage();
    $response['code']=$e->getCode();
}

require_once(API_CORE_PATH.'/class/format/format.class.php');
if(DEBUG) print Format::parse($response, 'php');
else  print Format::parse($response, 'json');
/**/