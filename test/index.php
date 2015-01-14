<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 14.01.2015
 * Time: 11:22
 */

header('Content-Type: text/plain; charset=utf-8');

error_reporting(E_ALL);
ini_set("display_errors", 1);
//define('ROOT_PATH', 'http://ershov.pw/api/sbs');
define('ROOT_PATH', 'http://sbs.edu.ru/api');
require('../lib/curl.php');
$file = 'data.txt';
$data = array();

$file_content = file_get_contents($file);
$data = unserialize($file_content);

$list = array(76416,76811,77310,77337,77340,77364,77367,77818,77833,77864,77905,77915,77939,78365,78367,78530,79510,79523,79529,79546,79549,79577,79583,79586,79610,79616,79645,79655,80045,80113,80127,80323,80346,80584,80674,80710,80720,80740,80821,81384,82037,82100,82110,82176,82423,82431,83183,83196,83198,83395,83849,84014,84449,84492,84495,84497,85641,85689,86331,86867,86993,86997,87029,87036,87044,87065,88047,88071,88192,88351,88415,88418,88428,88430,89384,89980,89983,90004,90005,90006,90323,90372,90373,90374,90623,90713,90714,90716,90717,90718,91518,91638,91643,91649,92313,92338,92342,92345,92348,92362,92363,92365,92385,92386,92387,92433,92437,92440,92454,99813);
//$data["index"]=array_search($data['id'], $list);
// $index=0;

print '$data["index"]: '.$data["index"];
print "\n";

$data["key"]="sbs";
$data["id"]=$list[$data["index"]];
$RESULT = array();

$RESULT['content']   = CURL(ROOT_PATH.'/emarsys/email/get-body/', $data);
$RESULT['title']     = CURL(ROOT_PATH.'/filter/h1/', $RESULT);
$RESULT['content']   = CURL(ROOT_PATH.'/filter/content/', $RESULT);
$RESULT['introtext'] = CURL(ROOT_PATH.'/filter/introtext/', $RESULT);

print_r($RESULT);

//$current = file_get_contents($file);
$data["index"]++;
$file_content = serialize($data);
file_put_contents($file, $file_content);
