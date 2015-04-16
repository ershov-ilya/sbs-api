<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 14.04.2015
 * Time: 16:47
 */


header('Content-Type: text/plain; charset=utf-8');
if(isset($_GET['debug'])) define('DEBUG', true);
else define('DEBUG', false);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require('../../../../index.php');

// Значения о умолчанию
$props = array(
    'parents'=>'973',
    'tpl' => 'v3.bz.dispatch-list.item.tpl',
    'where' => "template IN ('41','27','50') AND published='1'",
    'limit' => 12,
    'depth' => 1,
    "includeTVs" => 'lecture_theme,speaker,photo,view_count',
    "showHidden" => 0,
    'sortdir' => 'ASC'
    );

// Список полей разрешённых к фильтрации и сортировке
$fields= array_merge(array('publishedon'), explode(',', $props["includeTVs"]));
//print_r($fields);
$DEFAULT_YEAR=intval(date('Y'));
$YEAR=$DEFAULT_YEAR;
$MONTH=0;

$DATA = $_REQUEST;
if(isset($DATA['filter_month']) && ($DATA['filter_month'] <= 0 || $DATA['filter_month'] > 12)) unset($DATA['filter_month']);
if(DEBUG) print_r($DATA);

// Поступающие значения
foreach($DATA as $key => $val)
{
    if(empty($val)) continue;
    switch($key)
    {
//        case 'parents':
//            $props['parents'] = preg_replace('/[^\d,]/','',$val);
//            break;
//        case 'page':
//            $props['page'] = preg_replace('/[^\d]/','',$val);
//            //$props['page']=$val;
//            break;
        case 'sortby':
            foreach($fields as $sort_el) { if($val == $sort_el) $props["sortby"] = $sort_el; }
            break;
        case 'lecture_theme':
            $clean_val = preg_replace('/[^\d]/', '', $val);
            if(!empty($clean_val)) $props['where'] = $props['where'] . " AND lecture_theme = '" . $clean_val . "'";
            break;
        case 'filter_year':
            $clean_val = preg_replace('/[^\d]/','',$val);
            $YEAR=(int)$clean_val;
            if(empty($YEAR)) $YEAR=$DEFAULT_YEAR;

            //mktime(hour,minute,second,month,day,year,is_dst[opt]);
            $stampFrom =mktime(0,0,0,1,1,$YEAR);
            $stampTo =mktime(0,0,0,1,1,$YEAR+1);
            if(!isset($DATA['filter_month'])) $props['where'] = $props['where']." AND publishedon > '".$stampFrom."' AND publishedon < '".$stampTo."'";
            break;
        case 'filter_month':
            //if(empty($DATA['filter_year'])) $YEAR=2015;

            $clean_val = preg_replace('/[^\d]/','',$val);
            $MONTH=(int)$clean_val;

            //mktime(hour,minute,second,month,day,year,is_dst[opt]);
            $stampFrom =mktime(0,0,0,$MONTH,1,$YEAR);
            $stampTo =mktime(0,0,0,$MONTH+1,1,$YEAR);
            $props['where'] = $props['where']." AND publishedon > '".$stampFrom."' AND publishedon < '".$stampTo."'";
            break;
        case 'sortdir':
            if(preg_match('/^desc$/i', $val)) $props['sortdir']='DESC';
            if($val == 1) $props['sortdir']='DESC';
            break;
    }
}

//if($props['page']==1) unset($props['page']);

if(DEBUG) print_r($props);
else {
    $result = $modx->runSnippet('pdoPage', $props);
    print $result;
}