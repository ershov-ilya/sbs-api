<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Тест</title>
</head>

<body>
<pre>
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

function combineSignatureString($data, $pass1){
    $result = '';
    foreach($data as $key => $val)
    {
        $result .= $val.':';
        if($key=='InvId') $result .= $pass1.':';
    }

    return $result;
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

$mrh_login = "SBSEDU";
$mrh_pass1 = "hvlygtQupVM6";
$mrh_pass2 = "Fuaf3Hxrtfiu";
$inv_id = 0;
$inv_desc = "Test";
$out_summ = "12.23";
$shp_item = 1;
$in_curr = "";
$culture = "ru";
$encoding = "utf-8";

$data=array(
    'MrchLogin'     => $mrh_login,
    'OutSum'        => $out_summ,
    'InvId'         => $inv_id,
    'Desc'          => $inv_desc,
    //'IncCurrLabel'  => $in_curr,
    'Culture'       => $culture,
    'Encoding'      => $encoding
);

$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:$in_curr:$inv_desc:$shp_item:$culture:$encoding");
$SignatureValue1 = strtoupper(md5($mrh_login.":".$out_summ.":".$inv_id.":".$mrh_pass1));
$SignatureValue2 = strtoupper(md5($out_summ.":".$inv_id.":".$mrh_pass2));

$SignatureString = implode(':', $data);
print '$SignatureString:         '.$SignatureString."<br>\n";
$crc = md5($SignatureString);
print 'CRC: '.$crc."<br>\n";
$combinedSignatureString = combineSignatureString($data, $mrh_pass1);
print '$combinedSignatureString: '.$combinedSignatureString."<br>\n";;
$strGET = combineGetString($data, $crc);
print 'combineGetString: '.$strGET."<br>\n";;
?>
</pre>
<?php
/*
<a href="<?php print "http://test.robokassa.ru/Index.aspx?".
    "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&IncCurrLabel=$in_curr".
    "&Desc=$inv_desc&Shp_item=$shp_item&Culture=$culture&Encoding=$encoding".
    "&SignatureValue=$crc";
?>" target="_blank">Ссылка для перехода на Робокассу</a>

<?php
*/
?>
</body>
</html>

