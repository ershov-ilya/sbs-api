<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 07.11.2014
 * Time: 12:46
 */

echo "Inserting contact\n";

$username = "bob001";   // TODO change with _your_ data
$secret = "XXX06uv21JlSfuiHv4mM9vA";  // TODO change with _your_ data
$suiteApiUrl = "https://suite5.emarsys.net/api/v2/"; // TODO change with _your_ data

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, true);

$requestUri = $suiteApiUrl . "contact";
curl_setopt($ch, CURLOPT_URL, $requestUri);

$nonce = '123';    // Always use random 'nounce' for increased security.
$timestamp = gmdate("c");
$passwordDigest = base64_encode(sha1($nonce . $timestamp . $secret, false));
curl_setopt($ch,CURLOPT_HTTPHEADER, array("X-WSSE: UsernameToken ".
    "Username=\"$username\", ".
    "PasswordDigest=\"$passwordDigest\", ".
    "Nonce=\"$nonce\", ".
    "Created=\"$timestamp\", ",
    'Content-Type: application/json'));

$data = "{".
    "\"1\":\"Max\",".
    "\"2\":\"Mustermann\",".
    "\"3\":\"Max.Mustermann@mustermann.at\"".
    "}";
curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

$output = curl_exec($ch);
curl_close($ch);

echo $output;
?>