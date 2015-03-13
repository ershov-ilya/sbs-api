<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 13.03.2015
 * Time: 13:37
 */

// config
require_once('../../core/config/core.config.php');
$content=file('input.csv');

//print_r($content[0]);

$output="=-==================";
include(TEMPLATE_PATH.'/base.template.php');

