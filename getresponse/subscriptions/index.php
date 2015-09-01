<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 27.07.2015
 * Time: 16:59
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);
$response=array();
$format='php';

require_once('../../core/config/core.config.php');
require_once(API_CORE_PATH.'/class/restful/restful.class.php');
require_once(API_ROOT_PATH.'/getresponse/func/get_subscriptions.php');
require_once(API_ROOT_PATH . '/getresponse/func/curl_request.php');

//print_r($_SERVER);
//die;
$rest = new RESTful('subscriptions','email');

try {
    $response['data']=get_subscriptions($rest->data);
}
catch(Exception $e){
    $response['message']=$e->getMessage();
    $response['code']=$e->getCode();
}

require_once(API_CORE_PATH.'/class/format/format.class.php');
print Format::parse($response, $format);




