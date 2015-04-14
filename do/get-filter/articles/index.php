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
defined('DEBUG') or define('DEBUG', true);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require('../../../../index.php');

$props = array(
    "includeTVs" => 'lecture_theme,speaker,photo,view_count',
    "showHidden" => 0,
    //'parents'=>'[[*id]]',
    'tpl' => 'v3.bz.main-carousel.tpl',
    'where' => "template IN ('41','27','52')",
    'limit' => 12,
    'sortdir' => 'ASC'
    );

$fields= array_merge(array('publishedon'), explode(',', $props["includeTVs"]));
//print_r($fields);

foreach($_REQUEST as $key => $val)
{
    switch($key)
    {
        case 'view':
            if(preg_match('/^col$/i', $val)) $props['tpl']='v3.bz.main-carousel.col.tpl';
            break;
        case 'sortby':
            foreach($fields as $sort_el) { if($val == $sort_el) $props["sortby"] = $sort_el; }
            break;
        case 'filter':
            foreach($fields as $filter_el)
            {
                if($val == $filter_el) $props["sortby"] = $filter_el;
            }
            break;
        case 'sortdir':
            if(preg_match('/^desc$/i', $val)) $props['sortdir']='DESC';
            if($val == 1) $props['sortdir']='DESC';
            break;
    }
}


print_r($props);