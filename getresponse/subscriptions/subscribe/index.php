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
if(isset($_GET['t'])) define('DEBUG', true);
defined('DEBUG') or define('DEBUG', false);
$response=array(
    'code'  => 204,
    'message' => 'Not modified'
);
$data=array();
$format='json';

require_once('../../../core/config/core.config.php');
require_once(API_CORE_PATH.'/class/restful/restful.class.php');
require_once(API_ROOT_PATH.'/getresponse/func/jsonRPCClient.php');
require_once(API_CORE_PATH.'/config/getresponse.private.config.php');

define('INCLUSION', true);
require_once(API_ROOT_PATH . '/getresponse/func/set_subscription.php');

try {
    // Парсинг параметров
    $rest = new RESTful('subscribe','email,campaign');
    if(empty($rest->data['email'])) throw new Exception('No email passed', 400);
    // Инициализация клиента
    $account=$getresponse_config;
    $rpcClient = new jsonRPCClient($getresponse_config['url']);

    $campaignID=translate_field($rest->data['campaign'],'campaign-id');

    // Вычислительный процесс
    // Список клиентов
    $answer = $rpcClient->add_contact(
        $account['key'],
        array(
            'campaign'  => $campaignID,
            'email' => $rest->data['email']
        )
    );
    if(DEBUG) print_r($answer);
    if(!empty($answer['queued'])){
        $response['code']=200;
        $response['message']='OK';
        $data=$answer;
    }
}
catch(Exception $e){
    $response['message']=$e->getMessage();
    $response['code']=$e->getCode();
}

$response['data']=$data;
require_once(API_CORE_PATH.'/class/format/format.class.php');
print Format::parse($response, $format);




