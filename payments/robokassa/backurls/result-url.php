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
$log_array['status']='false';

require_once('../../../core/config/core.config.php');
require_once(API_ROOT_PATH.'/core/class/payments/robokassa/robokassa.class.php');
require_once(API_ROOT_PATH.'/core/config/payments.config.php');
$crc2=$_POST['SignatureValue'];
if($crc2==Robokassa::checkCRC2($_POST,$payments_config['robokassa'])) $log_array['status']='true';

$json = json_encode($log_array);
$curtime=time()-3600;
$data = date("Y-m-d H:i:s", $curtime);
$content = $data.' incoming POST:'.$json."\n\n";

file_put_contents('log.txt', $content, FILE_APPEND);
//$answer = array(
//    'status'    => 'OK'
//);
//print (json_encode($answer));
print $log_array['status'];