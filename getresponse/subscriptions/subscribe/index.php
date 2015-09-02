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
$required_campaigns=array('R','l','J');

require_once('../../../core/config/core.config.php');
require_once(API_CORE_PATH.'/class/restful/restful.class.php');
require_once(API_ROOT_PATH.'/getresponse/func/jsonRPCClient.php');
require_once(API_CORE_PATH.'/config/getresponse.private.config.php');

define('INCLUSION', true);
require_once(API_ROOT_PATH . '/getresponse/func/set_subscription.php');

try {
    if(!DEBUG) throw new Exception('Not Implemented', 501);
    if(DEBUG) die;

    // Парсинг параметров
    $rest = new RESTful('subscriptions','email,contact_id');
    if(empty($rest->data['email'])) throw new Exception('No email passed', 400);
    if(empty($rest->data['contact_id'])) throw new Exception('No contact_id passed', 400);
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
    if(DEBUG) print_r($contacts);

    $campaigns=array();
    $campaign_ids=array();
    $contact_ids=array();

    foreach($contacts as $k=>$contact){
        $campaign=array(
            'id'=>$contact['campaign'],
            'contact_id' => $k
        );
        $contact_ids[]=$k;

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

    // Добавляем обязательные компании
    $add_required_campaigns=array();
    foreach($required_campaigns as $req){
        if(!in_array($req, $campaign_ids)) {

            $camp_data = $rpcClient->get_campaign(
                $account['key'],
                array(
                    # find by name literally
                    'campaign' => $req
                )
            );
            foreach($camp_data as $camp) {
                $add_required_campaigns[] = array_merge(array(
                    'id'=>null,
                    'contact_id' => null
                ),$camp);
                break;
            }
        }
    }

    $campaigns=array_merge($add_required_campaigns,$campaigns);

    if(DEBUG) {
//        print_r($campaign_ids);
        print_r($campaigns);
//        print_r($add_required_campaigns);
//        print_r($contact_ids);
        print $rest->data['contact_id'].PHP_EOL;
    }
    if(!in_array($rest->data['contact_id'], $contact_ids))  throw new Exception('Campaigns not found', 404);
    if (DEBUG) print_r($campaigns);

    // Переобор кампаний
    if(empty($campaigns)) throw new Exception('Campaigns not found', 404);
    $i=0;
    foreach($campaigns as $c){
        $data[$i]=array(
            'campaign_id' => $c['id'],
            'campaign_name'=>$c['name'],
            'user_id'=>$c['contact_id']
        );
        if(isset($c['description'])) $data[$i]['description']=$c['description'];
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




