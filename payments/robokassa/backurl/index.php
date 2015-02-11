<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 09.02.2015
 * Time: 16:52
 */

header('Content-Type: text/plain; charset=utf-8');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', false);
require_once('../../../core/config/core.config.php');

$log_array=array();
$log_array['data']=$_POST;
$log_array['status']='Fail';

//require_once(API_ROOT_PATH.'/core/class/payments/robokassa/robokassa.class.php');
//require_once(API_ROOT_PATH.'/core/config/payments.config.php');
//$crc2=$_POST['SignatureValue'];
//if($crc2==Robokassa::checkCRC2($_POST,$payments_config['robokassa'])) $log_array['status']='Successful';

// Приём и санитизация ID
$id=0;
if(isset($_REQUEST['id'])) $id=preg_replace('/[^0-9]/', '', $_REQUEST['id']);
if(isset($_REQUEST['InvId'])) $id=preg_replace('/[^0-9]/', '', $_REQUEST['InvId']);
if(!$id) die('ID not exists!');

// Чтение инфо о Заказе из БД
require_once(API_ROOT_PATH.'/core/class/database/database.class.php');
require_once(API_ROOT_PATH.'/core/config/pdo.config.php');
$db = new Database($pdoconfig_lander);
$order = $db->getOne('payments', $id);
//print_r($order);

// Проверка
if($order['SignatureValue'] == $_POST['SignatureValue']) $log_array['status']='Successful';
$log_array['attempt'] = $order['attempts']+1;

// Запись в файл
$json = json_encode($log_array);
$curtime=time()-3600;
$data = date("Y-m-d H:i:s", $curtime);
$content = $data.' incoming POST:'.$json." REMOTE_ADDR:".$_SERVER['REMOTE_ADDR']."\n";
file_put_contents('log.txt', $content, FILE_APPEND);

// Обновление информации в БД
$status = 'Неизвестный статус';
if($log_array['status']=='Fail') $status = 'Ошибка';
if($log_array['status']=='Successful' || $order['Status'] == 'Оплачено') $status = 'Оплачено'; //
$db_data = array(
    'Status'    => $status,
    'attempts'  => $log_array['attempt']
);
$db->updateOne('payments', $id, $db_data);

print $log_array['status'];
