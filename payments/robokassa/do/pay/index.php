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
$order['OutSum'] = 0;
if(isset($_REQUEST['sum'])) $order['OutSum'] = preg_replace('/[^0-9\.]/', '', $_REQUEST['sum']);
if(isset($_REQUEST['OutSum'])) $order['OutSum'] = preg_replace('/[^0-9\.]/', '', $_REQUEST['OutSum']);
$order['Desc'] = "";
if(isset($_REQUEST['desc'])) $order['Desc'] = filter_var($_REQUEST['desc'], FILTER_SANITIZE_STRING);
if(isset($_REQUEST['Desc'])) $order['Desc'] = filter_var($_REQUEST['Desc'], FILTER_SANITIZE_STRING);

// Database save row, and get this row ID
require_once(API_ROOT_PATH.'/core/class/database/database.class.php');
require_once(API_ROOT_PATH.'/core/config/pdo.config.php');
$db = new Database($pdoconfig_lander);
$id = $db->putOne('payments', $order);

// Подготовка запроса в Робокассу
$robokassa_data = array(
    'InvId' => $id,
    'Desc'  => $order['Desc'],
    'IncCurrLabel'  => "",
    'Culture'       => "ru",
    'Encoding'      => "utf-8"
);

$robokassa_data = array_merge($robokassa_data, $order);

$robokassa = new Robokassa($robokassa_data, $payments_config['robokassa']);
$order['SignatureValue'] = $robokassa->genCRC2();
$db->updateOne('payments', $id, $order);

//print "payURL: ".$robokassa->payURL()."\n";
//print_r($robokassa->resultArray());
header('Location: '.$robokassa->payURL());

