<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 09.02.2015
 * Time: 19:52
 */

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);

function getCRC($data, $pass1){
    $str=$data['MrchLogin'].':'.$data['OutSum'].':'.$data['InvId'].':'.$pass1;
    return md5($str);
}
function combineGetString($data, $crc=''){
    $url='http://test.robokassa.ru/Index.aspx?';
    $result = '';
    foreach($data as $key => $val)
    {
        $result .= $key.'='.$val.'&';
    }
    $result .= 'SignatureValue='.$crc;
    return $url.$result;
}

$mrh_pass1 = "hvlygtQupVM6";
$mrh_pass2 = "Fuaf3Hxrtfiu";

$data=array(
    'MrchLogin'     => "SBSEDU",
    'OutSum'        => "12.23",
    'InvId'         => 0,
    'Desc'          => "Test",
    'IncCurrLabel'  => "",
    'Culture'       => "ru",
    'Encoding'      => "utf-8"
);

$crc = getCRC($data, $mrh_pass1);
$strGET = combineGetString($data, $crc);

header( 'Location: '.$strGET );
