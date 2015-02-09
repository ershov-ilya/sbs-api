<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 09.02.2015
 * Time: 16:52
 */

$json = json_encode($_POST);
$content = $json."\n\n";

file_put_contents('log.txt', $content);