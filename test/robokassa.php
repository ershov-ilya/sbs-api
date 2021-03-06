<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 10.02.2015
 * Time: 13:00
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);
require_once('../core/config/core.config.php');

require_once(API_ROOT_PATH.'/core/class/payments/robokassa/robokassa.class.php');
require_once(API_ROOT_PATH.'/core/config/payments.config.php');

// Get order data
$order = array();
$order['OutSum']    = 0;
if(isset($_REQUEST['sum'])){
    $_REQUEST['sum'] = preg_replace('/[^0-9\.]/', '', $_REQUEST['sum']);
    $order['OutSum'] = $_REQUEST['sum'];
}
$order['Desc']      = "";
if(isset($_REQUEST['desc'])){
    $_REQUEST['desc'] = filter_var($_REQUEST['desc'], FILTER_SANITIZE_STRING);
    $order['Desc'] = $_REQUEST['desc'];
}


// Database save row, and get this row ID
require_once(API_ROOT_PATH.'/core/class/database/database.class.php');
require_once(API_ROOT_PATH.'/core/config/pdo.config.php');
$db = new Database($pdoconfig_lander);
$InvId = $db->putOne('payments', $order);

$robokassa_data = array(
    'InvId' => $InvId,
    'Desc'  => "",
    'IncCurrLabel'  => "",
    'Culture'       => "ru",
    'Encoding'      => "utf-8"
);

$robokassa_data = array_merge($robokassa_data, $order);

$robokassa = new Robokassa($robokassa_data, $payments_config['robokassa']);
print "payURL: ".$robokassa->payURL()."\n";

$order['SignatureValue'] = $robokassa->genCRC2();
$db->updateOne('payments', $InvId, $order);

print_r($robokassa->resultArray());

