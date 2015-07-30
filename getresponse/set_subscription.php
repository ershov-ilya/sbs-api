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
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//defined('DEBUG') or define('DEBUG', true);

define('API_ROOT',dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
require_once(API_ROOT.'/core/config/core.config.php');

$user=array(
    'name'      => 'Tester',
    'email'     => 'tester@effetto.pro',
    'campaign'  => 'test1'
);

set_subscription($user,'');

/* @define API_CORE_PATH /core */
function set_subscription($user, $subscriptions){
    require_once(API_CORE_PATH.'/config/getresponse.private.config.php');
    $account=$getresponse_config;

    require_once(API_ROOT_PATH.'/getresponse/jsonRPCClient.php');
    $rpcClient = new jsonRPCClient($getresponse_config['url']);

    var_dump(getresponse_get_contacts($user, $account, $rpcClient));
//    var_dump(getresponse_get_contact_ids($user, $account, $rpcClient));
//    var_dump(getresponse_get_campaign_name('a', $account, $rpcClient));
//    var_dump(getresponse_add_contact($user, $account, $rpcClient));
//    var_dump(getresponse_delete_contact('LHEr', $account, $rpcClient));
//    var_dump(getresponse_delete_email($user, $account, $rpcClient));
}


function getresponse_add_contact($user, $account, &$rpcClient)
{
    $campaigns = $rpcClient->get_campaigns(
        $account['key'],
        array(
            # find by name literally
            'name' => array('EQUALS' => $user['campaign'])
        )
    );
    $CAMPAIGN_ID = array_keys($campaigns);
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