<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 13.01.2015
 * Time: 10:52
 */

/* DEBUG
------------------------------------------------------------------- */
if(isset($_GET['test']) || isset($_GET['debug']))
{
    header('Content-Type: text/plain; charset=utf-8');
    define(DEBUG, true);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else
{
    header('Content-Type: text/html; charset=utf-8');
    define(DEBUG, false);
}


/* CONFIG
------------------------------------------------------------------- */
require('../../../config/api.config.php');
require('../../lib/post.php');
//require('../lib/put.php');

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
$uri='email/'.$camp_id.'/preview';

if(DEBUG) print $uri."\n";

$params=array("version" => "html");
$data_string = json_encode($params);

if(DEBUG)
{
    print_r($params);
    print "json:\n".$data_string;
}

/* Action
------------------------------------------------------------------- */
$resp=emarsys_post($username, $password, $env, $uri, $data_string);

if(DEBUG) {
    print "\n\nResponse:\n";
    print_r($resp);
}
else
{
    print $resp->data;
}
