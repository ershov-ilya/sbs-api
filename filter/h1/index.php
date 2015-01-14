<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 14.01.2015
 * Time: 11:41
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('../../lib/simplehtmldom_1_5/simple_html_dom.php');
$res='';

if(empty($_POST['content'])) { print "Empty string"; return ''; }

if(empty($res)) {
    preg_match('/<h1>(.*)<\/h1>/i', $_POST['content'], $arr);
    if (!empty($arr[1])) $res = $arr[1];
}

if(empty($res)) {
    preg_match('/<title>(.*)<\/title>/i', $_POST['content'], $arr);
    if (!empty($arr[1])) $res = $arr[1];
}


//if(empty($res)) {
//    $res = $html->plaintext;
//    $res=preg_replace('/&nbsp;/i','',$res);
//    $res=preg_replace('/<[a-z\/]+>/i','',$res);
//    $res=preg_replace('/[ ]+/i'," ",$res);
//    $res=preg_replace('/[\n\r]+/i',"</p>
//    <p>",$res);
//    $res="<p>".$res."</p>";
//}

if(empty($res)) print "Not match";
else print $res;
