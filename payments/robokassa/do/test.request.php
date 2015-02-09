<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 09.02.2015
 * Time: 16:57
 */

header('Content-Type: text/html; charset=windows-1251');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);

function CURL($url, $data){
    $ch = curl_init($url);

    //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Следовать по редиректам

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For Windows machines... some windows misses some CACERT.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);
    return  $output;
}

$mrh_login = "SBSEDU";
$mrh_pass1 = "hvlygtQupVM6";
$inv_id = 0;
$inv_desc = "Test";
$out_summ = "12.23";
$shp_item = 1;
$in_curr = "";
$culture = "ru";
$encoding = "utf-8";
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

$data=array(
    'MrchLogin' => 'SBSEDU',
    'nOutSum'   => '1000',
    'nInvId'    => '0',
    'sInvDesc'  => 'Test',
    'sIncCurrLabel' => 'RUR',
    'sCulture'  => 'ru'
);

$answer = CURL('http://test.robokassa.ru/Index.aspx', $data);
print $answer;