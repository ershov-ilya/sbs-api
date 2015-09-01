<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 01.09.2015
 * Time: 12:27
 */

function curlRequest($request, $getresponse_config){
    $api_url = $getresponse_config['url'];

    $curl = curl_init(); // Сохраняем дескриптор сеанса cURL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_HEADER, false);
    //curl_setopt($curl,CURLOPT_COOKIEFILE, $scope['dir'].'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    //curl_setopt($curl,CURLOPT_COOKIEJAR, $scope['dir'].'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    $out = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl); // Завершаем сеанс cURL

    $response = json_decode($out);
    return $response;
}