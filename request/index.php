<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 16.12.2014
 * Time: 15:46
 */

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

header('Content-type: text/plain;charset=utf8');


//Simple method (Works only with apache and, as of PHP 5.4, for FastCGI)
//$headers = apache_request_headers();
// else use:
$headers = parseRequestHeaders();

print "HEADERS: \n";
print_r($headers);

print "GET: \n";
print_r($_GET);

print "POST: \n";
print_r($_POST);

print "COOKIE: \n";
print_r($_COOKIE);

print "REQUEST: \n";
print_r($_REQUEST);

