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

define('ALLOW_IP', '10.12.82.18');
if($_SERVER['REMOTE_ADDR'] != ALLOW_IP ) exit(0);

print('Request method: '.$_SERVER['REQUEST_METHOD'].PHP_EOL);

if(!empty($_GET)) {
    print("GET:\n");
    print_r($_GET);
}

if(!empty($_POST)) {
    print("POST:\n");
    print_r($_POST);
}
if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    echo "PUT:\n";
    parse_str(file_get_contents("php://input"),$post_vars);
    print_r($post_vars);
}

if(!empty($_COOKIE)) {
    print("COOKIEs:\n");
    print_r($_COOKIE);
}
print("SERVER:\n");
print_r($_SERVER);

print("REQUEST:\n");
print_r($_REQUEST);

