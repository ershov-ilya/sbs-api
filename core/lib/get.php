<?php
function emarsys_get($username,$password,$env,$uri,$params=array()){
    $host = "https://$env.emarsys.net";
    $url = $host."/api/v2/".$uri;

    $url.=emarsys_get_params($params);

    $created  = date('Y-m-d').'T'.date('H:i:s').'Z';
    $nonce = substr(md5(uniqid('nonce_', true)),0,16);
    $passwordDigest = base64_encode(sha1($nonce . $created . $password));

    $header = array(
        "X-WSSE: UsernameToken Username=\"$username\", PasswordDigest=\"$passwordDigest\", Nonce=\"$nonce\", Created=\"$created\"",
        "Content-Type: application/json",
        "Content-Length: 0");// IF ITS GET Content-Length = 0

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);// You forgot to send the headers!
    curl_setopt($ch, CURLOPT_POST, false);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
// You used $curl_handle and that isn't defined! You should use $ch

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For Windows machines... some windows misses some CACERT.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    return  $output = json_decode(curl_exec($ch));
    // print_r($output);
}

function emarsys_get_params($arr)
{
    if(empty($arr)) return '';
    $str='?';
    foreach($arr as $key => $value)
    {
        $str.=$key.'='.$value.'&';
    }
    $str=preg_replace('/&$/','', $str);
    return $str;
}