<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 21.04.2015
 * Time: 13:18
 */

header('Content-Type: text/plain; charset=utf-8');
if(isset($_GET['debug'])) define('DEBUG', true);
else define('DEBUG', false);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require('../../index.php');

//// Значения о умолчанию
//$props = array(
//    'parents'=>'263',
//    'tpl' => 'v3.bz.schedule-list.old-style.item.tpl',
//    'where' => "template IN ('9','59') AND published='1' AND start_date>CURDATE()",
//    'limit' => 12,
//    "includeTVs" => 'lecture_theme,speaker,view_count,start_date,programm.land,cost,currency',
//    'sortby' => 'start_date',
//    'sortdir' => 'ASC',
//    "showHidden" => 1,
//    'depth' => 1
//);
//
//
////if($props['page']==1) unset($props['page']);
//
//if(DEBUG) print_r($props);
//else {
//    $result = $modx->runSnippet('pdoPage', $props);
//    print $result;
//}

$speaker=51;
if(isset($_GET['id'])) $speaker=$_GET['id'];


//$q = $modx->newQuery('modResource');
////$q->where(array('parent' => 1));
//$q->where("parent ='1'");
//$q->select('id');
//$result = array();
//$res=array();
//if ($q->prepare() && $q->stmt->execute()) {
//    while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
//        $res['id'][] = $row['id'];
//    }
//    $result = "'".implode("','", array_unique($res['id']))."'" ;
//}
//print_r($result);

if(empty($speaker)) return '()';

$q = $modx->newQuery('modTemplateVarResource');
$q->where("tmplvarid ='10' AND (value = '".$speaker."' OR value LIKE '%||".$speaker."||%'  OR value LIKE '%||".$speaker."' OR value LIKE '".$speaker."||%'  )");
$q->select('id, contentid, value');
$result = array();
$res=array();
if ($q->prepare() && $q->stmt->execute()) {
    while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
        $res[] = $row['contentid'];
    }
    $result = "('".implode("','", array_unique($res))."')" ;
}
print $result."\n";


$props=array(
    'parents'=>'263',
    'level'=>'2',
    'limit'=>'3',
    'sortby'=>'start_date',
    'sortdir'=>'ASC',
    'processTVs'=>'1',
    'includeTVs'=>'start_date,speaker,lecture_theme,view_count,programm.land',
    'where'=>"start_date>CURDATE() AND `modResource`.`id` IN $result",
    'tplWrapper'=>'v3.bz.speaker-events.outer',
    //'showLog'=>1,
    'tpl'=>'v3.bz.speaker-event.item.tpl'
);

$result = $modx->runSnippet('pdoResources', $props);
print $result;


