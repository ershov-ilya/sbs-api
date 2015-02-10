<?php
/**
 * Created by PhpStorm.
 * User: ershov-ilya
 * Website: ershov.pw
 * GitHub : https://github.com/ershov-ilya
 * Date: 07.02.2015
 * Time: 0:32
 */

class Database
{
    public $dbh; // Database handler
    public $cache;

    public function errors()
    {
        $info = $this->dbh->errorInfo();
        if(!empty($info[0])){
            if(DEBUG) print $info[2]."\n";
            logMessage($info[2]);
        }
    }

    public function __construct($input)
    {
        $this->cache = false;

        $input_type=gettype($input);
        switch($input_type)
        {
            case 'string':
                /* @var array $pdoconfig */
                require_once($input);
                extract($pdoconfig);
                break;
            case 'array':
                extract($input);
                break;
        }

        if(!(isset($dbtype) && isset($dbhost) && isset($dbname) && isset($dbuser) && isset($dbpass))) return false;
        try
        {
            // Save stream
            $this->dbh = new PDO("$dbtype:host=$dbhost;dbname=$dbname" , $dbuser, $dbpass ,
                array (PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")) ;
        }
        catch (PDOException $e ) {
            if(DEBUG) print 'Exception: ' . $e-> getMessage();
            logMessage('Exception: ' . $e-> getMessage());
            exit();
        }
    } // function __construct

    public function getOne($sql)
    {
        $stmt = $this->dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        if(empty($rows)) return array();
        $result = $rows[0];
//        foreach($rows as $row){} // Изъятие из потока?
        $this->errors();
        return $result;
    }

    public function getAll($sql)
    {
        $stmt = $this->dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        $this->errors();
        return $rows;
    }

    public function getCount($sql)
    {
        // TODO: Неэкономичная функция, надо поправить
        $stmt = $this->dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        $count = count($rows);
        $this->errors();
        return $count;
    }
} // class Database