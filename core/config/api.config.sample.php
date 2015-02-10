<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 13.01.2015
 * Time: 10:53
 */


function getConfig($key = null) {
    $mfpa['username'] = "xxxxxxx";
    $mfpa['secret'] = "xxxxxxx";
    $mfpa['suiteApiUrl'] = "http://suite7.emarsys.net/api/v2/";

    $sbs['username'] = "xxxxxxx";
    $sbs['secret'] = "xxxxxxx";
    $sbs['suiteApiUrl'] = "http://suite7.emarsys.net/api/v2/";

    $egemetr['username'] = "xxxxxxx";
    $egemetr['secret'] = "xxxxxxx";
    $egemetr['suiteApiUrl'] = "http://suite7.emarsys.net/api/v2/";

    $megacampus['username'] = "xxxxxxx";
    $megacampus['secret'] = "xxxxxxx";
    $megacampus['suiteApiUrl'] = "http://suite7.emarsys.net/api/v2/";


    $result = array(
        'mfpa' => $mfpa,
        'sbs' => $sbs,
        'egemetr' => $egemetr,
        'megacampus' => $megacampus
    );

    if($key==null) return $result;
    else return $$key;
}