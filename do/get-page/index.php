<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 30.03.2015
 * Time: 13:00
 */

header('Content-Type: text/plain; charset=utf-8');
defined('DEBUG') or define('DEBUG', true);

if(DEBUG){
error_reporting(E_ALL);
ini_set("display_errors", 1);
}

defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require('../../../index.php');

// Default
$props=array(
    'parents'   =>'24,929',
    'tpl'       =>'v3.bz.main-carousel.tpl',
    'where'     =>"template IN ('41','27','50','52') AND published='1'",
    'limit'     =>'12',
    'depth'     =>1,
    'includeTVs'=>'lecture_theme,speaker,photo,view_count',
    'sortby'    =>'publishedon',
    'page'      =>1
);

$props['filtervalue']=1653;
if(isset($_REQUEST['parents'])) $props['parents']=preg_replace('/[^\d,]/','',$_REQUEST['parents']);
if(isset($_REQUEST['page'])) $props['page']=preg_replace('/[^\d]/','',$_REQUEST['page']);
if(isset($_REQUEST['filtervalue'])) $props['filtervalue']=preg_replace('/[^\d]/','',$_REQUEST['filtervalue']);

/* Templates
41  lecture
57  Video
27  Text.v2
52  Article
*/
if(isset($_REQUEST['template']))
{
    switch($_REQUEST['template']){
        case 'dispatch':
            $props['tpl']='v3.bz.dispatch-list.item.tpl';
            break;
        case 'article':
            $props['where'] ="template IN ('41','27','52')";
            break;
        case 'theme':
            $props['parents']='24,929';
            $props['tpl']='v3.bz.theme-item.tpl';
            $props['where'] ="template IN ('41','57','27','52') AND lecture_theme='".$props['filtervalue']."' AND published='1'";
            $props['includeTVs'] = "lecture_theme,speaker,photo,view_count";
            $props['showHidden']=0;
            $props['limit']=12;
            $props['depth']=10;
            break;
        case 'schedule':
            $today=date('Y-m-d H:i:s');
            $props['tpl']   ='v3.bz.schedule-list.old-style.item.tpl';
            $props['includeTVs'] ="lecture_theme,speaker,view_count,start_date,programm.land,cost,currency";
            $props['sortby'] ="start_date";
            $props['sortdir'] ="ASC";
            $props['where'] ="template IN ('9') AND published='1' AND start_date>'$today'";
            $props['showHidden']=1;
            $props['depth']=10;
            break;
        case 'speakers-list':
/*
        &parents=`24`
        &tpl=`v3.bz.speakers-list.item.tpl`
        &limit=`12`
        &includeTVs=`avatar,photo`
        &where=`template IN ('55','31','38') AND published='1'`
        &sortby=`pagetitle`
        &sortdir=`ASC`
*/
            $props['tpl']='v3.bz.speakers-list.item.tpl';
            $props['where'] ="template IN ('55','31','38') AND published='1'";
            $props['includeTVs'] ="avatar,photo";
            $props['sortby'] ="menuindex";
            $props['sortdir'] ="ASC";
            $props['showHidden']=0;
            $props['showUnpublished']=0;
            break;
        case 'video-list':
            $props['parents']='24';
            $props['tpl']='v3.bz.video-list.item.tpl';
            $props['where'] ="template IN ('41','57') AND published='1'";
            $props['includeTVs'] = "photo,speaker,view_count,lecture_theme,youtube_duration";
            break;
    }
}

$result=$modx->runSnippet('pdoPage',$props);
//print $modx->getOption('site_name');

print $result;