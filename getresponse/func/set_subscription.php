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
defined('INCLUSION') or define('INCLUSION', false);

if(!INCLUSION) {
    header('Content-Type: text/plain; charset=utf-8');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
    if (isset($_GET['t'])) define('DEBUG', true);
    defined('DEBUG') or define('DEBUG', false);

    define('API_ROOT', dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
    require_once(API_ROOT . '/core/config/core.config.php');
    require_once(API_ROOT . '/core/config/pdo.config.php');
//require_once(API_CORE_PATH.'/class/format/format.class.php');
    require_once(API_CORE_PATH . '/class/database/database.class.php');
    require_once(API_ROOT_PATH.'/getresponse/func/jsonRPCClient.php');


    $db = new Database($pdoconfig);
    $task = $db->getOne('modx_maillists', 'changed', 'done', 'id,internalKey,done,name,email,free_webinars,knowledge_base,events');
    if (empty($task)) exit(0);

// Теперь на что подписываем
    $subscript = array();
    foreach ($task as $k => $v) {
        $f = translate_field($k);
        if ($f) $subscript[] = array(
            'id' => $f,
            'name' => $k,
            'set' => $v
        );
    }

//test($task,$subscript);
    set_subscription($task, $subscript, $db);

}


/* Функции
 --------------------------------------------------- */
function test($task, $subscript){
    if(DEBUG) {
        print "function test()\n";
    }
    require_once(API_CORE_PATH.'/config/getresponse.private.config.php');
    $account=$getresponse_config;

    require_once(API_ROOT_PATH.'/getresponse/func/jsonRPCClient.php');
    $rpcClient = new jsonRPCClient($getresponse_config['url']);

//    var_dump(getresponse_add_contact_to_cid($task, 'J', $account, $rpcClient));
    var_dump(getresponse_get_contacts($task, $account, $rpcClient));
//    var_dump(translate_field('free_webinars'));
//    var_dump(getresponse_get_contact_ids($task, $account, $rpcClient));
//    var_dump(getresponse_get_campaign_name('a', $account, $rpcClient));
//    var_dump(getresponse_get_campaign_id('J', $account, $rpcClient));
//    var_dump(getresponse_add_contact($task, $campaign_name, $account, $rpcClient));
//    var_dump(getresponse_delete_contact('LHEr', $account, $rpcClient));
//    var_dump(getresponse_delete_email($task, $account, $rpcClient));
}

function set_subscription($task, $subscript, &$db){
    require_once(API_CORE_PATH.'/config/getresponse.private.config.php');
    $account=$getresponse_config;

    require_once(API_ROOT_PATH.'/getresponse/func/jsonRPCClient.php');
    $rpcClient = new jsonRPCClient($getresponse_config['url']);

    $contacts=getresponse_get_contacts($task, $account, $rpcClient);

    if(empty($contacts)){
        foreach($subscript as $s){
            if($s['set']){
                $res=getresponse_add_contact_to_cid($task, $s['id'], $account, $rpcClient);
                if(DEBUG) {
                    print "Add ".$task['email'].' to '.$s['name'].' Result:'.PHP_EOL;
                    var_dump($res);
                }
            }
        }
    }else{
        foreach($contacts as $k=>&$v){
            $v['contact_id']=$k;
        }
        if(DEBUG) {
            print "Найдены контакты:\n";
            print_r($contacts);
        }
        // Запоминаем куда подписан
        $subscribed_arr=array();
        foreach($contacts as $contact){
            $subscribed_arr[]=$contact['campaign'];
        }
        if(DEBUG) {
            print "Подписан:\n";
            print_r($subscribed_arr);
            print "Цель:\n";
            print_r($subscript);
        }
        // Определяем куда подписать, где отписать
        $on_arr=array();
        $off_arr=array();
        foreach($subscript as $need){
            if(!in_array($need['id'], $subscribed_arr) && $need['set']==1){
                // Подписать
                $on_arr[]=$need['id'];
            }
            if(in_array($need['id'], $subscribed_arr) && $need['set']==0){
                // Отписать
                $off_arr[]=$need['id'];
            }
        }
        if(DEBUG) {
            print "Подписать:\n";
            print_r($on_arr);
            print "Отписать:\n";
            print_r($off_arr);
        }
        // Отписываем
        foreach($contacts as $c){
            if(in_array($c['campaign'], $off_arr)) {
                $res=getresponse_delete_contact($c['contact_id'], $account, $rpcClient);
                if (DEBUG) {
                    print "Результат отписки:\n";
                    print_r($res);
                }
            }
        }
        // Подписываем
        foreach($on_arr as $cid){
            $res=getresponse_add_contact_to_cid($task, $cid, $account, $rpcClient);
            if(DEBUG) {
                print "Add ".$task['email'].' to '.$cid.' Result:'.PHP_EOL;
                var_dump($res);
            }
        }
    }

    $db->updateOne('modx_maillists', $task['id'], array('done' => 'done'));
}



function translate_field($field, $dir='form-id'){
    $campaigns=array(
        'free_webinars' =>  'webinar',
        'knowledge_base'=>  'base',
        'events'        =>  'open_program'
    );

    // Узнавать через функцию test / getresponse_get_campaign_id()
    $campaign_ids=array(
        'webinar'   =>  'R',
        'base'      =>  'l',
        'open_program' =>  'J'
    );

    switch($dir){
        case 'form-campaign':
            if(isset($campaigns[$field])) return $campaigns[$field];
            break;
        case 'form-id':
            if(isset($campaigns[$field]) && isset($campaign_ids[$campaigns[$field]])) return $campaign_ids[$campaigns[$field]];
            break;
    }
    return false;
}


function getresponse_add_contact($user, $campaign_name, $account, &$rpcClient)
{
    $CAMPAIGN_ID = getresponse_get_campaign_id($campaign_name, $account, &$rpcClient);
    $CAMPAIGN_ID=$CAMPAIGN_ID[0];
    $result = $rpcClient->add_contact(
        $account['key'],
        array(
            # identifier of 'test' campaign
            'campaign' => $CAMPAIGN_ID,

            # basic info
            'name' => $user['name'],
            'email' => $user['email'],
        )
    );
    return $result;
}

function getresponse_add_contact_to_cid($user, $CAMPAIGN_ID, $account, &$rpcClient)
{
    if(DEBUG) {
        print "function getresponse_add_contact_to_cid()\n";
        print_r($user);
    }
    $result = $rpcClient->add_contact(
        $account['key'],
        array(
            # identifier of 'test' campaign
            'campaign' => $CAMPAIGN_ID,

            # basic info
            'name' => $user['name'],
            'email' => $user['email'],
        )
    );
    return $result;
}

function getresponse_get_contacts($user, $account, &$rpcClient)
{
    $contacts = $rpcClient->get_contacts(
        $account['key'],
        array(
            # find by name literally
            'email' => array('EQUALS' => $user['email'])
        )
    );
    return $contacts;
}

function getresponse_get_contact_ids($user, $account, &$rpcClient)
{
    $contacts = $rpcClient->get_contacts(
        $account['key'],
        array(
            # find by name literally
            'email' => array('EQUALS' => $user['email'])
        )
    );
    $CONTACT_IDs = array_keys($contacts);
    return $CONTACT_IDs;
}

function getresponse_get_campaign_name($campaign_id, $account, &$rpcClient)
{
    $campaigns = $rpcClient->get_campaign(
        $account['key'],
        array(
            # find by name literally
            'campaign' => $campaign_id
        )
    );
    foreach($campaigns as $campaign){
        return $campaign['name'];
        break;
    }
    return false;
}

function getresponse_get_campaign_id($campaign_name, $account, &$rpcClient)
{
    if(DEBUG) {
        print "getresponse_get_campaign_id\n";
        print "campaign_name $campaign_name\n";
    }
    $campaigns = $rpcClient->get_campaigns(
        $account['key'],
        array(
            # find by name literally
            'name' => array(
            "EQUALS" => $campaign_name
            )
        )
    );
    if(empty($campaigns)) return false;
    $campaign_ids = array_keys($campaigns);
    return $campaign_ids[0];
}

function getresponse_delete_contact($contact_id, $account, &$rpcClient)
{
    $result = $rpcClient->delete_contact(
        $account['key'],
        array(
            'contact' => $contact_id,
        )
    );
    return $result;
}

function getresponse_delete_email($user, $account, &$rpcClient)
{
    $contacts=getresponse_get_contacts($user, $account, $rpcClient);
    $result=array();
    foreach($contacts as $contact_id){
        $result[]=getresponse_delete_contact($contact_id, $account, $rpcClient);
    }
    return $result;
}
