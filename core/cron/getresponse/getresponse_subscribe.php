<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 31.07.2015
 * Time: 15:03
 */

//define('DEBUG', true);
header('Content-Type: text/plain; charset=utf-8');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', false);

define('API_ROOT',dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))));
require_once(API_ROOT.'/core/config/core.config.php');
require_once(API_ROOT.'/core/config/pdo.config.php');
//require_once(API_CORE_PATH.'/class/format/format.class.php');
require_once(API_CORE_PATH.'/class/database/database.class.php');
define('INCLUSION', true);
require_once(API_ROOT_PATH.'/getresponse/set_subscription.php');


$db=new Database($pdoconfig);
$task=$db->getOne('modx_maillists','changed','done','id,internalKey,done,name,email,free_webinars,knowledge_base,events');
if(empty($task)) exit(0);

// Теперь на что подписываем
$subscript=array();
foreach($task as $k => $v){
    $f=translate_field($k);
    if($f) $subscript[]=array(
        'id'    => $f,
        'name'  => $k,
        'set'   => $v
    );
}

//test($task,$subscript);
set_subscription($task,$subscript, $db);

