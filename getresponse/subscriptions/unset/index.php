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
    $rest = new RESTful('subscription_unset','id',array('id'=>FILTER_SANITIZE_STRING));
    if(empty($rest->data['id'])) throw new Exception('No ID passed', 400);

    // Инициализация клиента
    $account=$getresponse_config;
    $rpcClient = new jsonRPCClient($getresponse_config['url']);

    // Вычислительный процесс
    // Список клиентов
    $data = $rpcClient->delete_contact(
        $account['key'],
        array(
            # find by name literally
            'contact' => $rest->data['id']
        )
    );
    if(!empty($data['deleted'])) {
        $response['code'] = 200;
        $response['message'] = 'deleted';
    }
}
catch(Exception $e){
    $response['message']=$e->getMessage();
    $response['code']=$e->getCode();
    if(empty($response['code'])) $response['code']=404;
}

$response['data']=$data;
require_once(API_CORE_PATH.'/class/format/format.class.php');
print Format::parse($response, $format);




