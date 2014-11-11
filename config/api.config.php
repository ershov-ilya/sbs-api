<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 05.11.2014
 * Time: 19:02
 */


function getConfig($key = null) {
    $mfpa['username'] = "mfpa001";
    $mfpa['secret'] = "R3ye66Xv1z6zjDkyNdjq";
    $mfpa['suiteApiUrl'] = "http://suite7.emarsys.net/api/v2/";

    $sbs['username'] = "sbs001";
    $sbs['secret'] = "s5Yn66sAJG8RSk6bmRR5";
    $sbs['suiteApiUrl'] = "http://suite7.emarsys.net/api/v2/";

    $egemetr['username'] = "egemetr001";
    $egemetr['secret'] = "5H3l68cFIiXlvtC3kzfV";
    $egemetr['suiteApiUrl'] = "http://suite7.emarsys.net/api/v2/";

    $megacampus['username'] = "megacampus001";
    $megacampus['secret'] = "Os3x68LVq34sMaPUfu5M";
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

$apiconfig = getConfig();
