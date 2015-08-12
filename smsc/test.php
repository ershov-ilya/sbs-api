<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 12.08.2015
 * Time: 17:18
 */

header('Content-Type: text/plain; charset=utf-8');
require_once('../core/config/core.config.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);

require_once(API_CORE_PATH.'/config/smsc.private.config.php');
require_once(API_ROOT_PATH.'/smsc/send.func.php');

$data=array(
    'mes' => 'API test',
    'phone' => '+79257123457'
);
$res=send_sms($data, $smsc_config);
var_dump($res);