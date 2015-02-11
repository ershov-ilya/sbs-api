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

require_once('../core/class/database/database.class.php');
require_once('../core/config/pdo.config.php');
$db = new Database($pdoconfig_lander);

$order = array(
    'OutSum'  => 200.50,
    'Desc'    => "Robokassa testing",
);
$InvId = $db->putOne('payments', $order);

print $InvId."\n";
exit(1);

$data=array(
    'OutSum'        => "16.00",
    'InvId'         => 12,
    'Desc'          => "Test",
    'IncCurrLabel'  => "",
    'Culture'       => "ru",
    'Encoding'      => "utf-8"
);
$robokassa = new Robokassa($data, $payments_config['robokassa']);
print "payURL: ".$robokassa->payURL()."\n";
print "check CRC2: ".$robokassa->genCRC2()."\n";

print_r($robokassa->resultArray());

