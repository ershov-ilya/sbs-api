<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 30.10.2014
 * Time: 15:02
 */

/**
 * Connection data. Change with your own ones.
 */
/* CONFIG
------------------------------------------------------------------- */
require('../config/api.config.php');
if(isset($_GET['key']))
{
    $config=getConfig($_GET['key']);
    extract($config, EXTR_OVERWRITE);
}
else{
    $username = "mfpa001";
    $secret = "R3ye66Xv1z6zjDkyNdjq";
    $suiteApiUrl = "http://suite7.emarsys.net/api/v2/";
}


/* DEBUG
------------------------------------------------------------------- */
if(isset($_GET['test'])) {define(DEBUG, true);} else  {define(DEBUG, false);}

if(DEBUG){
    header('Content-Type: text/plain; charset=utf-8');

    //print_r($apiconfig);
    print_r($config);
    var_dump($username);
    var_dump($secret);
    var_dump($suiteApiUrl);
    //exit(0);
}

/* PUBLIC
------------------------------------------------------------------- */
if(!DEBUG) header('Content-Type: text/html; charset=utf-8');

$ch = curl_init();
if(DEBUG){ print_r($ch); print "\n";}


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_HEADER, false);
// CURLOPT_MUTE
curl_setopt($ch, CURLOPT_MUTE, 1);

$requestUri = $suiteApiUrl . "event";

if(DEBUG){ print '$requestUri='.$requestUri."\n"; }

curl_setopt($ch, CURLOPT_URL, $requestUri);

/**
 * We add X-WSSE header for authentication.
 * Always use random 'nonce' for increased security.
 * timestamp: the current date/time in UTC format encoded as
 *   an ISO 8601 date string like '2010-12-31T15:30:59+00:00' or '2010-12-31T15:30:59Z'
 * passwordDigest looks sg like 'MDBhOTMwZGE0OTMxMjJlODAyNmE1ZWJhNTdmOTkxOWU4YzNjNWZkMw=='
 */
$timestamp = gmdate("c");
//$nonce = 'd36e316282959a9ed4c89851497a717f';
$nonce = hash('md5', $timestamp);

$passwordDigest = base64_encode(sha1($nonce . $timestamp . $secret, false));

$curl_opts=array("X-WSSE: UsernameToken ".
    "Username=\"$username\", ".
    "PasswordDigest=\"$passwordDigest\", ".
    "Nonce=\"$nonce\", ".
    "Created=\"$timestamp\", ",
    'Content-Type: application/json');
if(DEBUG){
    print "\nCURLOPT_HTTPHEADER: \n";
    print_r($curl_opts);
}

curl_setopt($ch,CURLOPT_HTTPHEADER, $curl_opts);

$answer = curl_exec($ch);
curl_close($ch);

echo $answer;

?>


