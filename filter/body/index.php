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

$res='';

if(empty($_POST['content'])) { print "Empty string"; return ''; }

$content=$_POST['content'];

$content=preg_replace('/<!--.*-->/sU','',$content);
$content=preg_replace('/<head>.*<\/head>/sUi','',$content);
$content=preg_replace('/^.*<html>/sUi','',$content);
$content=preg_replace('/<\/html>/sUi','',$content);
$content=preg_replace('/<body /i','<div ',$content);
$content=preg_replace('/<\/body/i','</div',$content);

print $content;
