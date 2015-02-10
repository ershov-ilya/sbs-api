<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 10.02.2015
 * Time: 17:08
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);
require_once('../core/config/core.config.php');

require_once(API_ROOT_PATH.'/core/class/database/database.class.php');
require_once(API_ROOT_PATH.'/core/config/pdo.config.php');

/* @var array $pdoconfig_lander */
$db = new Database($pdoconfig_lander);

$sql = 'SELECT * FROM log;';
$res = $db->getOne($sql);
print_r($res);

$data=array();
$data['message']='foreach';
//$data['order']='test/database.php';
//$data['desc']='test/database.php';
$lastID = $db->putOne('log', $data);
var_dump($lastID);
