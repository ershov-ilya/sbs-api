<?php

/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 19.01.2015
 * Time: 15:48
 */
header('Content-Type: text/html; charset=utf-8');

if(isset($_GET['filter'])) define('FILTER', true);
else  define('FILTER', false);
defined('DEBUG') or define('DEBUG', true);

$key='sbs';

require('../../core/config/core.config.php');

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
        print "Default date from...<br>\n";
        $data['last_date'] = '2015-02-10 11:00:00';
    }
    $last_date=strtotime($data['last_date']);

// ЧТЕНИЕ текущего состояния info кэша
    $file_content = file_get_contents(API_ROOT_PATH."/emarsys/email/get-ids/$key-cache.dat");
    $obj_list = unserialize($file_content);

//    print_r($obj_list);
//    exit(0);

    $sorted = array();
    foreach($obj_list->data as $value){
        $sorted[]=$value;
    }

    // Сортировка по дате
    usort($sorted, function($objA, $objB)
    {
        $a=strtotime($objA->created);
        $b=strtotime($objB->created);
        if ($a == $b)
        {
//            echo "a ($a) is same priority as b ($b), keeping the same\n";
            return 0;
        }
        else if ($a > $b)
        {
//            echo "a ($a) is higher priority than b ($b), moving b down array\n";
            return 1;
        }
        else {
//            echo "b ($b) is higher priority than a ($a), moving b up array\n";
            return -1;
        }
    });

    $i = 0;
    $result=array();
    $result['array']=array();

    $uniqueID=array();
    foreach ($sorted as $el) {

        // Проверка на уникальность писем
        preg_match('/^([0-9]{4,6})-/', $el->name, $matches);
        if($el->status==1)  continue;
        if(!isset($matches[1])) continue;
        if(isset($uniqueID[$matches[1]])) continue;
        $uniqueID[$matches[1]] = true;


        print $i . ": ";
        print $el->id . "\t: " . $el->created . " = ". $el->name;
        if(strtotime($el->created)>$last_date){
            print " <<< Новое неопубликованное письмо";
            $result['array'][]=$el->id;
        }
        print "<br>\n";

        $i++;
    }

//print_r($obj_list);
}
catch (Exception $e)
{
    echo 'Поймано исключение: ', $e->getMessage(), "\n";
}

print '<p>Можно переходить к <a href="http://sbs.edu.ru/api/do/save/">публикации писем</a></p>'."\n";

$result['index']=0;
$result_array_serialized=serialize($result);
file_put_contents('output.txt', $result_array_serialized);

