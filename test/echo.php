<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 22.07.2015
 * Time: 11:21
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);

$allow_ips=array('91.212.151.250','10.12.82.26', '10.12.81.31', '10.12.150.23');
if( !in_array($_SERVER['REMOTE_ADDR'], $allow_ips) ) die('Нельзя '.$_SERVER['REMOTE_ADDR']);

function parseRequestHeaders() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}
$headers = parseRequestHeaders();

print('Request method: '.$_SERVER['REQUEST_METHOD'].PHP_EOL);

//if(!empty($_GET)) {
    print("\nGET:\n");
    print_r($_GET);
//}

//if(!empty($_POST)) {
print("\nPOST:\n");
print_r($_POST);
//}

if(!empty($_FILES)) {
    print("\nFILES:\n");
    print_r($_FILES);
}

print "\nHEADERS: \n";
unset($headers['Cookie']);
print_r($headers);

if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    echo "\nPUT:\n";
    parse_str(file_get_contents("php://input"),$post_vars);
    print_r($post_vars);
}

if(!empty($_COOKIE)) {
    $cookie=$_COOKIE;
    unset($cookie['PHPSESSID']);
    unset($cookie['Tickets_User']);
    print("\nCOOKIEs:\n");
    print_r($cookie);
}

//print("\nSERVER:\n");
//print_r($_SERVER);

//print("\nREQUEST:\n");
//print_r($_REQUEST);

