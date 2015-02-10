<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 10.02.2015
 * Time: 12:51
 */

class Robokassa{
    public $crc_sum1;
    public $crc_sum2;
    public $data;

    private $config;
    private $payURL;
    private $baseURL;


    public function __construct($data, $config=array())
    {
        if(empty($config)) die('Lost Robokassa config!');
        $this->data = $data;
        $this->config=$config;
        $this->baseURL = 'http://test.robokassa.ru/Index.aspx?';
        $this->genCRC1();

        print_r($this->data);
        print_r($this->config);
    }

    private function genCRC1(){
        $pass1=$this->config['mrh_pass1'];
        $crc_data = array(
            'MrchLogin' => $this->data['MrchLogin'],
            'OutSum'    => $this->data['OutSum'],
            'InvId'     => $this->data['InvId']
        );
        $str=implode(':',$crc_data);
        $str= $str.':'.$pass1;
        $this->crc_sum1 = md5($str);
    }

    function payURL(){
        if(empty($this->payURL)) {
            $data = $this->data;
            $crc = $this->crc_sum1;
            $url = $this->baseURL;
            $result = '';

            foreach ($data as $key => $val) {
                $result .= $key . '=' . $val . '&';
            }
            $result .= 'SignatureValue=' . $crc;
            $this->payURL = $url.$result;
        }
        return $this->payURL;
    }

}