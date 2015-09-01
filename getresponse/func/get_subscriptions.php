<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 01.09.2015
 * Time: 12:24
 */

function get_subscriptions($email='')
{
    if(empty($email)) return false;
    require(API_CORE_PATH . '/config/getresponse.private.config.php');
    require_once(API_CORE_PATH . '/config/getresponse.private.config.php');
    require_once(API_ROOT_PATH . '/getresponse/func/curl_request.php');

    $api_key = $getresponse_config['key'];
    $api_method = 'get_messages';

    $emptyObj =new stdClass();

    $request_params = array(
        $api_key,
        $emptyObj
    );
    $request = array(
        'method' => $api_method,
        'params' => $request_params,
        'id' => 0
    );

    $response = curlRequest($request, $getresponse_config);
    return $response->result;
}

