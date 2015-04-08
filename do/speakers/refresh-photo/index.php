<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 07.04.2015
 * Time: 13:29
 */


header('Content-Type: text/plain; charset=utf-8');
defined('DEBUG') or define('DEBUG', true);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require('../../../../index.php');

//$id=$modx->resource->get('id');
//$value = $modx->resource->getTVValue('view_count');
//if($value==NULL) return '';
//
//if(!isset($_SESSION['view_count'])) $_SESSION['view_count']=array();
//
//if(isset($scriptProperties['increment']) && !isset($_SESSION['view_count'][$id])){
//    $value=$value+(int)$scriptProperties['increment'];
//    $modx->resource->setTVValue('view_count',$value);
//    $modx->resource->set('view_count', null);
//    $_SESSION['view_count'][$id]=true;
//}
//
//if($scriptProperties['hidden']) return '';
//
//return $value;

//$id=1429;
$id=1689;
if(isset($_GET['id'])) $id=preg_replace('/[^\d]/','',$_GET['id']);
$resource = $modx->getObject('modResource', $id);

$photo = $resource->getTVValue('photo');
if(DEBUG) print '$photo'.'='.$photo."\n";
$decision=$photo;
if(DEBUG) print "\n";

if(empty($decision)) {
    $big_avatar = $resource->getTVValue('big_avatar');
    if (DEBUG) print '$big_avatar' . '=' . $big_avatar . "\n";
    if (!empty($big_avatar)) {
        preg_match('/src="assets\/images\/(.*)"/iU', $big_avatar, $match);
        $decision = $match[1];
        if($decision) $decision=preg_replace('/speakers\/ New\//','speakers/New/',$decision);
        if (DEBUG) print '$decision' . '=' . $decision . "\n\n";
    }
}

if(empty($decision)) {
// /base/assets/templates/synergybase/img/
// assets/images/base/
    $image = $resource->getTVValue('image');
    if (DEBUG) print '$image' . '=' . $image . "\n";
    if (!empty($image)) {
        $decision = preg_replace('/\/base\/assets\/templates\/synergybase\/img\//', 'base/', $image);
        if (DEBUG) print '$decision' . '=' . $decision . "\n";
    }
}

if(empty($decision)) {
    $avatar = $resource->getTVValue('avatar');
    if (DEBUG) print '$avatar' . '=' . $avatar . "\n";
    if (!empty($avatar)) {
        preg_match('/src="assets\/images\/(.*)"/iU', $avatar, $match);
        $decision = $match[1];
        if (DEBUG) print '$decision' . '=' . $decision . "\n\n";
    }
}

if(empty($decision) || !empty($photo)) return '';

$modx->resource->setTVValue('photo', $decision);
$modx->resource->set('photo', null);
if(DEBUG) print 'Save done.';