<?php

/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 19.01.2015
 * Time: 15:48
 */
header('Content-Type: text/plain; charset=utf-8');

if(isset($_GET['filter'])) define('FILTER', true);
else  define('FILTER', false);
defined('DEBUG') or define('DEBUG', true);

require('../../config/core.config.php');

try {
    // ЧТЕНИЕ текущего состояния
    $file = 'input.txt';
    if(is_file($file)) {
        $file_content = file_get_contents($file);
        $data = unserialize($file_content);
    }
    else
    {
        $data=array();
        $data['last_date'] = '2015-01-13 09:40:39';
    }
    $last_date=strtotime($data['last_date']);
        //strtotime

// ЧТЕНИЕ текущего состояния info кэша
    $file_content = file_get_contents(API_ROOT_PATH.'/emarsys/email/get-ids/cache.dat');
    $obj_list = unserialize($file_content);

    $sorted = array();
    foreach($obj_list->data as $value){
        $sorted[]=$value;
    }

    usort($sorted, function($objA, $objB)
    {
        $a=strtotime($objA->created);
        $b=strtotime($objB->created);
        if ($a == $b)
        {
            echo "a ($a) is same priority as b ($b), keeping the same\n";
            return 0;
        }
        else if ($a > $b)
        {
            echo "a ($a) is higher priority than b ($b), moving b down array\n";
            return 1;
        }
        else {
            echo "b ($b) is higher priority than a ($a), moving b up array\n";
            return -1;
        }
    });

    print_r($sorted);

//    $test = array();
//    $test[]=$obj_list[0];
//    print_r($test);
    exit(0);

    $i = 0;
    $result=array();
    $result['array']=array();
    if(FILTER){
        foreach ($obj_list->data as $el) {
            if(strtotime($el->created)>$last_date){
                print $i . ": ";
                print $el->id . "\t: " . $el->created;
                $result['array'][]=$el->id;
                print "\n";
            }
            $i++;
        }
    }else{
        foreach ($obj_list->data as $el) {
            print $i . ": ";
            print $el->id . "\t: " . $el->created;
            if(strtotime($el->created)>$last_date){
                print " <<<";
                $result['array'][]=$el->id;
            }
            print "\n";

            $i++;
        }
    }
//print_r($obj_list);
} catch (Exception $e) {
    echo 'Поймано исключение: ', $e->getMessage(), "\n";
}

//// ЗАПИСЬ текущего состояния
//$data_content = serialize($data);
//file_put_contents($file, $data_content);

$result['index']=0;
$result_array_serialized=serialize($result);
file_put_contents('output.txt', $result_array_serialized);

