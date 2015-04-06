<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 09.12.2014
 * Time: 12:29
 */
if(isset($_GET['debug']))
{
    header('Content-Type: text/plain; charset=utf-8');
    define('DEBUG', true);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else
{
    header('Content-Type: text/html; charset=utf-8');
    define('DEBUG', false);
}

if(isset($_GET['reset'])) define('RESET', true);
else  define('RESET', false);

/* Config
 ----------------------------- */
$state_file="data/status.dat";
$res=0;

/* Load
------------------------------ */
$cntnt=file_get_contents($state_file);
$state=unserialize($cntnt);


/* Modify
------------------------------ */
if(!RESET)
{
    if($state['last_operation']=='fail' && !RESET)
    {
        print "Последняя операция была выполнена с ошибкой, завершение работы.\n";
        exit(1);
    }

    /* PDO
    ------------------------------ */
    require('data/pdo.config.php');
    //print_r($pdoconfig);
    $state['last_operation']='OK';
    try {
        $pdoref="mysql:host=".$pdoconfig['host'].";dbname=".$pdoconfig['dbname'];
        //if(DEBUG) print $pdoref;
        $db = new PDO($pdoref, $pdoconfig['user'], $pdoconfig['pass']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("set names utf8");
    }
    catch(PDOException $e) {
        $state['last_operation']='fail';
        echo $e->getMessage();
    }

    /* Чтение
    ------------------------------ */
    $sql="SELECT alias FROM temp WHERE id='".$state['current']."'";
    $stmt=$db->query($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $rows = $stmt->fetchAll();
    //$count = count($rows);
    //print_r($rows);
    $alias = $rows[0]['alias'];
    if(DEBUG) print "Alias = $alias\n";


    /* Emarsys
    ------------------------------ */
    require('../emarsys_api_connector_php_class/emarsys_api_connector.class.php');
    $emarsys = new Emarsys_API_Connector('sbs');
    $uri='email/'.$alias.'/preview';
    $params=array("version" => "html");
    $data_string = json_encode($params);
    $resp=$emarsys->post($uri, $data_string);
    $content = $resp->data;


    /* Запись
    ------------------------------ */
    try {
        //$sql="UPDATE temp SET content='+++' WHERE id='".$state['current']."'";
        $stmt = $db->prepare("UPDATE temp SET content=? WHERE id=?");
        $stmt->bindValue(1, $content, PDO::PARAM_STR);
        $stmt->bindValue(2, $state['current'], PDO::PARAM_INT);
        $res=$stmt->execute();
        //$res=$db->exec($sql);
    }
    catch(PDOException $e) {
        //$state['last_operation']='fail';
        echo $e->getMessage();
    }

    if(DEBUG)
    {
        print "\nРезультат: $res ";
        if($res===true) print "true";
        if($res===false) print "flase";
        print "\n";
    }
    $state['current']++;

    //Закрытие соединения
    $db = null;
}

/* Save
------------------------------ */
if(RESET) {
    unset($state);
    $state = array(
        'current' => '1',
        'key' => 'sbs',
        'last_operation' => 'OK'
    );
}

if(DEBUG)
{
    print "Текущее состояние\n";
    print_r($state);
}
file_put_contents($state_file, serialize($state));



