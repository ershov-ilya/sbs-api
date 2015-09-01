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
    $rest = new RESTful('subscriptions','email');
    if(empty($rest->data['email'])) throw new Exception('No email passed', 400);

    // Инициализация клиента
    $account=$getresponse_config;
    $rpcClient = new jsonRPCClient($getresponse_config['url']);

    // Вычислительный процесс
    // Список клиентов
    $contacts = $rpcClient->get_contacts(
        $account['key'],
        array(
            # find by name literally
            'email' => array('EQUALS' => $rest->data['email'])
        )
    );
//    print_r($contacts);

    $campaigns=array();
    $campaign_ids=array();
    foreach($contacts as $k=>$contact){
        $campaign=array(
            'id'=>$contact['campaign'],
            'contact_id' => $k
        );

        $camp_data = $rpcClient->get_campaign(
            $account['key'],
            array(
                # find by name literally
                'campaign' => $campaign['id']
            )
        );
        foreach($camp_data as $camp) {
            $campaign = array_merge($campaign, $camp);
            break;
        }
        $campaign_ids[]=$campaign['id'];
        $campaigns[]=$campaign;
    }

//    print_r($campaign_ids);
//    print_r($campaigns);

    // Переобор кампаний
    if(empty($campaigns)) throw new Exception('Campaigns not found', 404);
    $i=0;
    foreach($campaigns as $c){
        $data[$i]=array(
            'campaign_id' => $c['id'],
            'campaign_name'=>$c['name'],
            'user_id'=>$c['contact_id']
        );
        if(isset($c['description'])) $data[$i]['campaign_desc']=$c['description'];
        $i++;
    };
    $response['code']=200;
    $response['message']='OK';
}
catch(Exception $e){
    $response['message']=$e->getMessage();
    $response['code']=$e->getCode();
}

$response['data']=$data;
require_once(API_CORE_PATH.'/class/format/format.class.php');
print Format::parse($response, $format);




