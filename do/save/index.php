<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 14.01.2015
 * Time: 11:22
 */

header('Content-Type: text/plain; charset=utf-8');
require('../../config/core.config.php');

function get_element_with_id($id, &$arr){
    foreach($arr as $el)
    {
        if($el->id==$id) return $el;
    }
}

error_reporting(E_ALL);
ini_set("display_errors", 1);
require(API_ROOT_PATH.'/lib/curl.php');
defined('DEBUG') or define('DEBUG', true);

$data = array();

/*
 * Получение ВХОДНЫХ данных
 * --------------------------------------------------------
 */
try{
    // ЧТЕНИЕ текущего состояния
    $file = 'data.txt';
    if(is_file($file)) {
        $file_content = file_get_contents($file);
        $data = unserialize($file_content);
    }


    // ЧТЕНИЕ текущего состояния info кэша
    $file_content = file_get_contents(API_ROOT_PATH.'/emarsys/email/get-ids/cache.dat');
    $obj_list = unserialize($file_content);

    $check_config_file=API_ROOT_PATH.'/do/check/output.txt';
    if(is_file($check_config_file)) {
        $file_content = file_get_contents($check_config_file);
        $check_config = unserialize($file_content);
        //print_r($check_config);
    }
    else{
        die('Check config file not found');
    }

    // Сброс
    $check_config["index"]=0;

    $list =$check_config['array'];
    //$list = array(76416,76811,77310,77337,77340,77364,77367,77818,77833,77864,77905,77915,77939,78365,78367,78530,79510,79523,79529,79546,79549,79577,79583,79586,79610,79616,79645,79655,80045,80113,80127,80323,80346,80584,80674,80710,80720,80740,80821,81384,82037,82100,82110,82176,82423,82431,83183,83196,83198,83395,83849,84014,84449,84492,84495,84497,85641,85689,86331,86867,86993,86997,87029,87036,87044,87065,88047,88071,88192,88351,88415,88418,88428,88430,89384,89980,89983,90004,90005,90006,90323,90372,90373,90374,90623,90713,90714,90716,90717,90718,91518,91638,91643,91649,92313,92338,92342,92345,92348,92362,92363,92365,92385,92386,92387,92433,92437,92440,92454,99813);

    print '$check_config["index"]: '.$check_config["index"];
    print "\n";

    print 'Size of $list ';
    print sizeof($list);
    print "\n";
    if($check_config["index"]>=sizeof($list))
    {
        throw new Exception( "End of list");;
    }

    $data["id"]=$list[$check_config["index"]];
    $data["key"]='sbs';

    print '$data:'."\n";
    print_r($data);
    /*
     * Выполнение ЗАДАЧИ
     * -----------------------------------------------------------
     */

    $info = get_element_with_id($data["id"], $obj_list->data);
    print '$info:'."\n";
    print_r($info);

    $RESULT = array();
    // Запрос в Emarsys
    $RESULT['content']   = CURL(API_ROOT_URL.'/emarsys/email/get-body/', $data);


    // Фильтрация контента
    $RESULT['pagetitle'] = $info->subject;
    $RESULT['content']   = CURL(API_ROOT_URL.'/filter/body/', $RESULT);
    $RESULT['introtext'] = CURL(API_ROOT_URL.'/filter/introtext/', $RESULT);
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


    print_r($RESULT['content']);

    //exit(0);
    /*
     * вывод РЕЗУЛЬТАТА
     * ------------------------------------------------------------
     */

    define('SAVE', false);
    if(SAVE) {
        // Отправка документа в MODX
        print CURL(API_ROOT_URL.'/modx/create/', $RESULT);

        if(is_file(API_ROOT_PATH.'/do/check/input.txt')) {
            $check_inbox=unserialize(file_get_contents(API_ROOT_PATH.'/do/check/input.txt'));
        }
        else $check_inbox=array();
        if(isset($check_inbox['last_date'])){
            print '$check_inbox[\'last_date\']: ';
            print $check_inbox['last_date'];
            print "\n";

            print '$info->created: ';
            print $info->created;
            print "\n";



            $check_inbox['last_date']=(strtotime($info->created) > strtotime($check_inbox['last_date'])) ? $info->created : $check_inbox['last_date'];

            print '$check_inbox[\'last_date\']: ';
            print $check_inbox['last_date'];
            print "\n";

        }else
        $check_inbox['last_date']=$info->created;
        $check_inbox_content = serialize($check_inbox);
        file_put_contents(API_ROOT_PATH.'/do/check/input.txt', $check_inbox_content);
    }

} catch (Exception $e) {
    echo 'Поймано исключение: '. $e->getMessage()."\n";
}

// ЗАПИСЬ текущего состояния
$check_config["index"]++;
$check_config_file_content = serialize($check_config);
file_put_contents($check_config_file, $check_config_file_content);
