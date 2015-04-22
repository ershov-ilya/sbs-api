<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 10.12.2014
 * Time: 14:46
 */

header('Content-Type: text/plain; charset=utf-8');
if(isset($_GET['debug'])) define('DEBUG', true);
else define('DEBUG', false);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require('../../index.php');
