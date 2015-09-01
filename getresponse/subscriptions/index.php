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

/*  Контроллер анонимной отписки
// ------------------------------------*/
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', false);
$response=array();
$format='php';

require_once('../../core/config/core.config.php');
require_once(API_CORE_PATH.'/class/restful/restful.class.php');
//require_once(API_ROOT_PATH.'/getresponse/func/get_subscriptions.php');
//require_once(API_ROOT_PATH.'/getresponse/func/curl_request.php');
require_once(API_ROOT_PATH.'/getresponse/func/jsonRPCClient.php');
require_once(API_CORE_PATH.'/config/getresponse.private.config.php');

//define('INCLUSION', true);
//require_once(API_ROOT_PATH . '/getresponse/func/set_subscription.php');

//print_r($_SERVER);
//die;
try {
    $rest = new RESTful('subscriptions','email');
    if(empty($rest->data['email'])) throw new Exception('No email passed', 400);

    $account=$getresponse_config;
    $rpcClient = new jsonRPCClient($getresponse_config['url']);

    $contacts = $rpcClient->get_contacts(
        $account['key'],
        array(
            # find by name literally
            'email' => array('EQUALS' => $rest->data['email'])
        )
    );
    print_r($contacts);
    die;


//    $response['data']=get_subscriptions($rest->data);
}
catch(Exception $e){
    $response['message']=$e->getMessage();
    $response['code']=$e->getCode();
}

require_once(API_CORE_PATH.'/class/format/format.class.php');
print Format::parse($response, $format);




