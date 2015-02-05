<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 08.12.2014
 * Time: 14:45
 */
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);

if(isset($_REQUEST['debug']) || isset($_REQUEST['test'])) define('DEBUG', true);
else  define('DEBUG', false);

require('../../../config/api.config.php');
require('../../lib/get.php');

/* CONFIG
------------------------------------------------------------------- */
//$key='mfpa';
$key=(isset($_REQUEST['key']))?($_REQUEST['key']):('sbs');

$config=getConfig($key);
// extract($config, EXTR_OVERWRITE);

$username = $config['username'];
$password = $config['secret'];
$env = 'suite7';


/* Prepare
------------------------------------------------------------------- */
if(DEBUG) print_r($config);

$camp_id=(isset($_REQUEST['id']))?($_REQUEST['id']):(72804);

//$uri='email';
$uri=(isset($_REQUEST['uri']))?($_REQUEST['uri']):('email');

if(DEBUG) print $uri."\n";

/* Action
------------------------------------------------------------------- */
$resp=emarsys_get($username, $password, $env, $uri);

// Вывести IDшники в файл кэша
$file_content = serialize($resp);
file_put_contents($key.'-cache'.'.dat', $file_content);
print "<h1>Выгрузка успешно завершёна.</h1>";
print '<p>Можно переходить к <a href="http://sbs.edu.ru/api/do/check/">этапу поиска новых неопубликованных писем</a></p>';

exit(0);

// Вывести IDшники на экран
if(DEBUG) {
    print "\n\nResponse:\n";
    print_r($resp);
}
else
{
    $output='';
    foreach($resp->data as $obj)
    {
        $output.=$obj->id.';';
        $output.=$obj->email_category.';';
        $output.=$obj->created.';';
        $output.=$obj->subject;
        $output.="\n";
    }
    print $output;
}
