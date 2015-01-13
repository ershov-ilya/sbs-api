<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 13.01.2015
 * Time: 14:24
 */

function filter_content($url, $data){
    $password='test';
    $username='test';
    $created  = date('Y-m-d').'T'.date('H:i:s').'Z';
    $nonce = substr(md5(uniqid('nonce_', true)),0,16);
    $passwordDigest = base64_encode(sha1($nonce . $created . $password));

    $ch = curl_init($url);

    //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For Windows machines... some windows misses some CACERT.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);
    return  $output;
}
