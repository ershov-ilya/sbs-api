<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 14.01.2015
 * Time: 11:52
 */


header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);

function crop_str($string, $limit)
{
    $substring_limited = mb_substr($string,0, $limit*2);        //режем строку от 0 до limit
    return substr($substring_limited, 0, strrpos($substring_limited, ' ' ));    //берем часть обрезанной строки от 0 до последнего пробела
}

include('../../lib/simplehtmldom_1_5/simple_html_dom.php');
$res='';

if(empty($_POST['content'])) { print "Empty string"; return ''; }

$html = new simple_html_dom();
$html->load($_POST['content']);

if(empty($res)) {
    $res = $html->plaintext;
    $res=preg_replace('/&nbsp;/i','',$res);
    $res=preg_replace('/<[a-z\/]+>/i','',$res);
    $res=preg_replace('/^[\s]+/i','',$res);
    $res=preg_replace('/[\s]+/i'," ",$res);
    $res=crop_str($res, 300);
}

if(empty($res)) print "Not match";
else print $res;
