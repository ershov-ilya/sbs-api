<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 12.08.2015
 * Time: 17:18
 */

header('Content-Type: text/plain; charset=utf-8');
require_once('../core/config/core.config.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);
defined('DEBUG') or define('DEBUG', true);

require_once(API_CORE_PATH.'/class/format/format.class.php');
