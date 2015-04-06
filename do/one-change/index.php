<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 06.11.2014
 * Time: 17:26
 */
/* DEBUG
------------------------------------------------------------------- */
if (isset($_GET['test'])) {
    header('Content-Type: text/plain; charset=utf-8');
    define(DEBUG, true);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    emarsysOneChange(array('a_s_w_4@mail.ru','irishapo@gmail.com'), 'mfpa', '31', '2');
    // Все поля контакта в Emarsys
    // http://documentation.emarsys.com/?page_id=573
    //emarsysOneChange('irishapo@gmail.com', 'mfpa', '31', '1');
}
else {
    define(DEBUG, false);
}

function emarsysOneChange($email, $campaign='mfpa', $field=null, $value=null)
{
    /* CONFIG
    ------------------------------------------------------------------- */
    define('CONFIG_PATH', '/home/synsu/domains/api.syn.su/public_html/config/');
    define('LIBRARY_PATH', '/home/synsu/domains/api.syn.su/public_html/lib/');
    require(CONFIG_PATH . 'api.config.php');
    require(LIBRARY_PATH . 'post.php');
    require(LIBRARY_PATH . 'put.php');


    $key = $campaign;

    $config = getConfig($key);
// extract($config, EXTR_OVERWRITE);

    $username = $config['username'];
    $password = $config['secret'];
    $env = 'suite7';

    $list=array();
    if(is_array($email)) {$list=array_merge($list, $email);}
    else {array_push($list, $email);}
    if(DEBUG)print_r($list);
    //exit(0);


    /* Prepare
    ------------------------------------------------------------------- */
    $uri = "contact/getdata";

    //$list = file('../config/list3.txt', FILE_IGNORE_NEW_LINES);
//print_r($list);
    /**/

    $params = array("keyId" => "3", "keyValues" => $list, "fields" => array("1", "2", "3"));
    $data_string = json_encode($params);

    /* Action
    ------------------------------------------------------------------- */
    $resp = emarsys_post($username, $password, $env, $uri, $data_string);

    if (!empty($resp->data->errors)) {
        print "Errors:\n";
        print_r($resp->data->errors);
    }

    /* Разбор по id;email
     * ------------------------------------------------- */
    $res = $resp->data->result;
    $i = 0;
    $contacts_arr=array();
    foreach ($res as $row) {
        $contact=array("keyId" => $row->id, "3"=>$row->{'3'}, $field=>$value);
        $contacts_arr[]=$contact;
        //if($i>10) break;
    }

    if(DEBUG)print_r($contacts_arr);

    // ЗАПИСЬ
    $uri = "contact";

    $params = array('contacts'=>$contacts_arr);
    $data_string = json_encode($params);
    if(DEBUG)
    {
        var_dump($username);
        var_dump($password);
        var_dump($env);
        var_dump($uri);

        print_r($params);
        print "json:\n".$data_string;
    }

    /* Action
    ------------------------------------------------------------------- */
    $resp=emarsys_put($username, $password, $env, $uri, $data_string);

    if(DEBUG) {
        print "\n\nResponse:\n";
        print_r($resp);
        /**/
    }
}
