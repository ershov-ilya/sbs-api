<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 08.12.2014
 * Time: 14:45
 */
header('Content-Type: text/plain; charset=utf-8');
require_once('../../core/config/core.config.php');
require(CORE_PATH.'/config/api.config.php');
require(CORE_PATH.'/lib/get.php');
//require('../lib/put.php');

/* DEBUG
------------------------------------------------------------------- */
if(isset($_GET['test']) || isset($_GET['debug']))
{
    define(DEBUG, true);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else
{
//    header('Content-Type: text/html; charset=utf-8');
    define(DEBUG, false);
}


/* CONFIG
------------------------------------------------------------------- */
//$key='mfpa';
$key=(isset($_GET['key']))?($_GET['key']):('mfpa');

$config=getConfig($key);
// extract($config, EXTR_OVERWRITE);

$username = $config['username'];
$password = $config['secret'];
$env = 'suite7';


/* Prepare
------------------------------------------------------------------- */
$camp_id=(isset($_GET['id']))?($_GET['id']):(72804);

//$uri='email';
$uri=(isset($_GET['uri']))?($_GET['uri']):('email');

if(DEBUG) print $uri."\n";

/* Action
------------------------------------------------------------------- */
if(isset($_GET['re']))
{
    $resp=emarsys_get($username, $password, $env, $uri);
    $resp_content=serialize($resp);
    file_put_contents('cache.dat', $resp_content);
}
else
{
    if(is_file('cache.dat')){
        $resp=unserialize(file_get_contents('cache.dat'));
    }
}


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
