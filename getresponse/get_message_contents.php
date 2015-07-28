<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 27.07.2015
 * Time: 16:59
 */

//header('Content-Type: text/html; charset=utf-8');
//require_once('../core/config/core.config.php');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//defined('DEBUG') or define('DEBUG', true);

function get_message_contents($message_id)
{
    require_once(API_CORE_PATH . '/config/getresponse.private.config.php');

    $api_url = $getresponse_config['url'];
    $api_key = $getresponse_config['key'];
    $api_method = 'get_message_contents';

    $request_params = array(
        $api_key,
        array(
            # find by name literally
            'message' => $message_id
        )
    );
    $request = array(
        'method' => $api_method,
        'params' => $request_params,
        'id' => 0
    );

    $curl = curl_init(); #Сохраняем дескриптор сеанса cURL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
#curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_HEADER, false);
#curl_setopt($curl,CURLOPT_COOKIEFILE, $scope['dir'].'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
#curl_setopt($curl,CURLOPT_COOKIEJAR, $scope['dir'].'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl); #Завершаем сеанс cURL

    $response = json_decode($out);
    //print_r($response);

    $content = $response->result->html;
    return $content;
}

//print get_message_contents("0P");





