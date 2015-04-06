<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 07.11.2014
 * Time: 12:59
 */
header('Content-Type: text/plain; charset=utf-8');

if(isset($_GET['test']))
{
    define(DEBUG, true);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else  {define(DEBUG, false);}

function post($username,$password,$env,$uri,$data_string){
    $host = "https://$env.emarsys.net";
    $url = $host."/api/v2/".$uri;

    $created  = date('Y-m-d').'T'.date('H:i:s').'Z';
    $nonce = substr(md5(uniqid('nonce_', true)),0,16);
    $passwordDigest = base64_encode(sha1($nonce . $created . $password));

    $header = array(
        "X-WSSE: UsernameToken Username=\"$username\", PasswordDigest=\"$passwordDigest\", Nonce=\"$nonce\", Created=\"$created\"",
        "Content-Type: application/json",
        "Content-Length: ".strlen($data_string));// IF ITS GET Content-Length = 0

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);// You forgot to send the headers!
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
// You used $curl_handle and that isn't defined! You should use $ch

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For Windows machines... some windows misses some CACERT.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    return  $output = json_decode(curl_exec($ch));
    // print_r($output);
}

if(DEBUG)
{
    $username = 'mfpa001';
    $password = 'R3ye66Xv1z6zjDkyNdjq';
    $env = 'suite7';
    $uri = "contact/getdata";

    $params = array("keyId" => "3", "keyValues" => array("bezruchko1999@bk.ru"), "fields"=>array("1","2","3"));
    $data_string = json_encode($params);

    $resp=post($username, $password, $env, $uri, $data_string);
    print_r($resp);
}