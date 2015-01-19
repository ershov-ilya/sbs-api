<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 14.01.2015
 * Time: 11:22
 */

header('Content-Type: text/plain; charset=utf-8');

function get_element_with_id($id, &$arr){
    foreach($arr as $el)
    {
        if($el->id==$id) return $el;
    }
}

error_reporting(E_ALL);
ini_set("display_errors", 1);
//define('ROOT_PATH', 'http://ershov.pw/api/sbs');
define('ROOT_PATH', 'http://sbs.edu.ru/api');
require('../lib/curl.php');
$data = array();

/*
 * Получение ВХОДНЫХ данных
 * --------------------------------------------------------
 */
try{
    // ЧТЕНИЕ текущего состояния
    $file = 'data.txt';
    $file_content = file_get_contents($file);
    $data = unserialize($file_content);

    // Сброс
    $data["index"]=0;

    // ЧТЕНИЕ текущего состояния info кэша
    $file_content = file_get_contents('../emarsys/email/get-ids/cache.dat');
    $obj_list = unserialize($file_content);

    $list = array(76416,76811,77310,77337,77340,77364,77367,77818,77833,77864,77905,77915,77939,78365,78367,78530,79510,79523,79529,79546,79549,79577,79583,79586,79610,79616,79645,79655,80045,80113,80127,80323,80346,80584,80674,80710,80720,80740,80821,81384,82037,82100,82110,82176,82423,82431,83183,83196,83198,83395,83849,84014,84449,84492,84495,84497,85641,85689,86331,86867,86993,86997,87029,87036,87044,87065,88047,88071,88192,88351,88415,88418,88428,88430,89384,89980,89983,90004,90005,90006,90323,90372,90373,90374,90623,90713,90714,90716,90717,90718,91518,91638,91643,91649,92313,92338,92342,92345,92348,92362,92363,92365,92385,92386,92387,92433,92437,92440,92454,99813);

    //$data["key"]="sbs";

    print '$data["id"]: '.$data["id"];
    print "\n";

    print '$data["index"]: '.$data["index"];
    print "\n";

    print 'Size of $list ';
    print sizeof($list);
    print "\n";
    if($data["index"]>=sizeof($list))
    {
        throw new Exception( "End of list");;
    }
    $data["id"]=$list[$data["index"]];

    /*
     * Выполнение ЗАДАЧИ
     * -----------------------------------------------------------
     */

    $info = get_element_with_id($data["id"], $obj_list->data);
    print_r($info);

    $RESULT = array();
    // Запрос в Emarsys
    $RESULT['content']   = CURL(ROOT_PATH.'/emarsys/email/get-body/', $data);


    // Фильтрация контента
    $RESULT['pagetitle'] = $info->subject;
    $RESULT['content']   = CURL(ROOT_PATH.'/filter/content/', $RESULT);
    $RESULT['introtext'] = CURL(ROOT_PATH.'/filter/introtext/', $RESULT);
    $RESULT['alias'] =  $data["id"];
    //$RESULT['alias'] =  $info->name;
    //$RESULT['date'] = $info->created;

    $date =  date_parse ($info->created);
//    $datestr=$date['year'].'-'.$date['month'].'-'.$date['day'];
//    $rounddate = strtotime($datestr);
//    $exactdate = strtotime($info->created);
//    print_r($date);
//    print($rounddate);
//    print "\n ^^^^^^^^^^^^^^ \n";
//    print(date('Y-m-d H:i:s',$rounddate));
//    print "\n ^^^^^^^^^^^^^^ \n";

    $RESULT['createdon'] = $info->created;
    $RESULT['publishedon'] = $info->created;


    //print_r($RESULT);

    //exit(0);
    /*
     * вывод РЕЗУЛЬТАТА
     * ------------------------------------------------------------
     */

    //print CURL(ROOT_PATH.'/modx/create/', $RESULT);

} catch (Exception $e) {
    echo 'Поймано исключение: ', $e->getMessage(), "\n";
}

// ЗАПИСЬ текущего состояния
$data["index"]++;
$file_content = serialize($data);
file_put_contents($file, $file_content);
