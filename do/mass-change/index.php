<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 06.11.2014
 * Time: 17:26
 */
header('Content-Type: text/plain; charset=utf-8');

/* DEBUG
------------------------------------------------------------------- */
if(isset($_GET['test']))
{
    define(DEBUG, true);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else  {define(DEBUG, false);}


/* CONFIG
------------------------------------------------------------------- */
require('../config/api.config.php');
require('../lib/post.php');
require('../lib/put.php');

$key='mfpa';
$config=getConfig($key);
// extract($config, EXTR_OVERWRITE);

$username = $config['username'];
$password = $config['secret'];
$env = 'suite7';

/* Prepare
------------------------------------------------------------------- */
$uri = "contact";
$optin="1"; // 2 - false, 1 - true

$list= file('../config/list3-id.txt', FILE_IGNORE_NEW_LINES);
$i=0;
$contacts_arr=array();
foreach($list as $str)
{
    $arr=explode(';', $str);
    $contact=array("keyId" => $arr[0], "3"=>$arr[1], "31"=>$optin);
    $contacts_arr[]=$contact;
    $i++;
    //if($i>10) break;
}
//$contacts_arr=array( array("keyId" => "5898748", "3"=>"bezruchko1999@bk.ru", "31"=>$optin) , array("keyId" => "12050580", "3"=>"vahrushevo-os@yandex.ru", "31"=>$optin) );
//if(DEBUG) print_r($contacts_arr);
//exit(0);
print "Done $i elements\n";

$params = array('contacts'=>$contacts_arr);
$data_string = json_encode($params);
if(DEBUG)
{
    print_r($params);
    print "json:\n".$data_string;
}

//exit(0);
/* Action
------------------------------------------------------------------- */
$resp=emarsys_put($username, $password, $env, $uri, $data_string);

print "\n\nResponse:\n";
print_r($resp);

