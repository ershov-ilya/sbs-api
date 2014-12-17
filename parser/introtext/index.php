<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 16.12.2014
 * Time: 15:46
 */
header('Content-Type: text/plain; charset=utf-8');



error_reporting(E_ALL);
ini_set("display_errors", 1);

require('../../config/pdo.config.php');
require('parsecontent.php');

extract($config, EXTR_OVERWRITE);

try {
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("set names utf8");
}
catch(PDOException $e) {
    echo $e->getMessage();
}

$sql='SELECT id FROM `temp`';
$stmt = $db->query($sql);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$res=$stmt->fetchAll();

$i=0;
foreach($res as $el)
{
    $id=$el['id'];
    //$id=1001;

    $stmt=null;
    $sql="SELECT content FROM `temp` WHERE id=?";
    $stmt=$db->prepare($sql);
    $stmt->bindValue(1,$id);
    $stmt->execute();
    $res_content=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $content=$res_content['0']['content'];
    print "\n-------------------------------------------\n";
    print $id.") \n";
    parseContent($content,$id);

    $i++;
    //break;
}

// Завершение работы
$db = null;