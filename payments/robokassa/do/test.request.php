<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 09.02.2015
 * Time: 16:57
 */

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);
require_once('../../../config/core.config.php');
?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Тест</title>
</head>

<body>
<pre>
<?php
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
print '$mrh_pass1='.$mrh_pass1."\n";
print '$mrh_pass2='.$mrh_pass2."\n";



//$mrh_login = "SBSEDU";
//$inv_id = 0;
//$inv_desc = "Test";
//$out_summ = "12.23";
//$shp_item = 1;
//$in_curr = "";
//$culture = "ru";
//$encoding = "utf-8";

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
print 'CRC: '.$crc."<br>\n";
$strGET = combineGetString($data, $crc);
print 'combineGetString: '.$strGET."<br>\n";;
?>
</pre>
<a href="<?=$strGET?>" target="_blank">Ссылка для перехода на Робокассу</a>
</body>
</html>

