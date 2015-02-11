<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 09.02.2015
 * Time: 16:52
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);

$log_array=array();
$log_array['data']=$_POST;
$log_array['status']='Fail';

require_once('../../../core/config/core.config.php');
require_once(API_ROOT_PATH.'/core/class/payments/robokassa/robokassa.class.php');
require_once(API_ROOT_PATH.'/core/config/payments.config.php');
$crc2=$_POST['SignatureValue'];
if($crc2==Robokassa::checkCRC2($_POST,$payments_config['robokassa'])) $log_array['status']='Successful';

$json = json_encode($log_array);
$curtime=time()-3600;
$data = date("Y-m-d H:i:s", $curtime);
$content = $data.' incoming POST:'.$json." REMOTE_ADDR:".$_SERVER['REMOTE_ADDR']."\n\n";

file_put_contents('log.txt', $content, FILE_APPEND);

require_once(API_ROOT_PATH.'/core/class/database/database.class.php');
require_once(API_ROOT_PATH.'/core/config/pdo.config.php');
$db = new Database($pdoconfig_lander);

print_r($log_array);

$status = 'Неизвестный статус';
if($log_array['status']=='Fail') $status = 'Ошибка';
if($log_array['status']=='Successful') $status = 'Оплачено';

$db_data = array(
    'Status'    => $status
);
$db->updateOne('payments', $log_array['data']['InvId'], $db_data);


print $log_array['status'];
