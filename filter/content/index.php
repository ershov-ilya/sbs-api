<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 13.01.2015
 * Time: 14:52
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('../../lib/simplehtmldom_1_5/simple_html_dom.php');
$res='';

if(empty($_POST['content'])) { print "Empty string"; return ''; }

$html = new simple_html_dom();
$html->load($_POST['content']);

//if(empty($res)) $res=$html->find('table table>tr>td>div>div>span',0)->innertext; // OK
//if(empty($res)) $res=$html->find('table#contentTableOuter',0)->innertext; // OK
//if(empty($res)) $res=$html->find('#contentTableOuter>tr>td',0)->innertext;
//if(empty($res)) $res=$html->find('#contentTableOuter>tr>td #contentTableOuter>tr>td',0)->innertext;
//if(empty($res)) $res=$html->find('body',0)->innertext;
if(empty($res)) {
    $res = $html->plaintext;
    $res=preg_replace('/&nbsp;/i','',$res);
    $res=preg_replace('/<[a-z\/]+>/i','',$res);
    $res=preg_replace('/[ ]+/i'," ",$res);
    $res=preg_replace('/[\n\r]+/i',"</p>
    <p>",$res);
    $res="<p>".$res."</p>";
}

if(empty($res)) print "Not match";
else print $res;
