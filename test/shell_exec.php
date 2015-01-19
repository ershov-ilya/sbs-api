<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 19.01.2015
 * Time: 12:49
 */

header('Content-Type: text/plain; charset=utf-8');

error_reporting(E_ALL);
ini_set("display_errors", 1);

$res = shell_exec('php console.php console_param1 "comsole param 2"');
print $res;