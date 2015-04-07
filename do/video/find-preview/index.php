<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 07.04.2015
 * Time: 11:06
 */


header('Content-Type: text/plain; charset=utf-8');
defined('DEBUG') or define('DEBUG', true);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

defined('MODX_API_MODE') or define('MODX_API_MODE', true);
require('../../../../index.php');

/*
$q = $modx->newQuery('modResource');
$q->select(array("id","uri","pagetitle","content"));
$q->limit(2000);
$q->where("id IN ('1890','1891','1892','1893')");
$query = $q->prepare();
$query->execute();
while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    print $row['pagetitle'] . "\n";
}
*/
/*
$q = $modx->newQuery('modTemplateVarResource');
$q->select(array("id","value", "tmplvarid", "contentid"));
$q->limit(1000);
$q->where("tmplvarid='57' AND contentid IN ('1890','1891','1892','1893') AND value<>''");
$query = $q->prepare();
$query->execute();
while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}*/

$q = $modx->newQuery('modTemplateVarResource');
$q->select(array("value"));
$q->limit(1);
$q->where("tmplvarid='57' AND contentid IN ('1890','1891','1892','1893') AND value<>''");
$query = $q->prepare();
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
//print_r($rows);
$result=$rows[0]['value'];
print $result;