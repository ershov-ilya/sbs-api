<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 31.03.2015
 * Time: 17:20
 */

header('Content-Type: application/rss+xml;charset= utf-8 ');
//header('Content-Type: text/plain; charset=utf-8');
defined('DEBUG') or define('DEBUG', true);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

?><rss version="2.0" xmlns:jwplayer="http://rss.jwpcdn.com/">
    <channel>
        <?
        defined('MODX_API_MODE') or define('MODX_API_MODE', true);
        require('../../../index.php');

        // Default
        $props=array(
            'parents'   =>'24',
            'tpl'       =>'v3.bz.jwplayer.item.tpl',
            'where'     =>"template IN ('41','57') AND published='1'",
            'limit'     =>'20',
            'depth'     =>2,
            'includeTVs'=>'code_youtube,photo',
            'sortby'    =>'id',
            'sortdir'    =>'ASC',
        );

        if(isset($_REQUEST['parents']))
        {
            $props['parents']=preg_replace('/\|\|/',',',$_REQUEST['parents']);
            $props['parents']=preg_replace('/[^\d,]/','',$props['parents']);
        }

        $result=$modx->runSnippet('pdoResources',$props);

        print $result;
        ?>
    </channel>
</rss>
