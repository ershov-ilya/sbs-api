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
    'where'     =>"template IN ('41','27') AND published='1'",
    'limit'     =>'12',
    'depth'     =>1,
    'includeTVs'=>'lecture_theme,speaker,photo,view_count',
    'sortby'    =>'publishedon',
    'page'      =>1
);

if(isset($_REQUEST['page'])) $props['page']=preg_replace('/[^\d]/','',$_REQUEST['page']);
if(isset($_REQUEST['parents'])) $props['parents']=preg_replace('/[^\d,]/','',$_REQUEST['parents']);
if(isset($_REQUEST['template']))
{
    switch($_REQUEST['template']){
        case 'dispatch':
            $props['tpl']='v3.bz.dispatch-list.item.tpl';
            break;
        case 'schedule':
            $props['tpl']   ='v3.bz.schedule-list.item.tpl';
            $props['where'] ="template IN ('9') AND published='1'";
            break;
        case 'speakers-list':
            $props['tpl']='v3.bz.speakers-list.item.tpl';
            $props['where'] ="template IN ('55','31','38') AND published='1'";
            $props['includeTVs'] ="avatar";
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