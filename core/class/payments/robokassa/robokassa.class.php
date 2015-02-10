<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 10.02.2015
 * Time: 12:51
 */

class Robokassa{
    public $crc_data;
    public $crc_sum;
    public $data;
    public $link_pay;
    private $config;

    public function __construct($data, $payments_config=array())
    {
        $this->data = $data;
        $this->config=$payments_config['robokassa'];
        print_r($this->data);
        print_r($this->config);

        $this->crc_data = array(
            'MrchLogin' => $this->data['MrchLogin'],
            'OutSum'    => $this->data['OutSum'],
            'InvId'     => $this->data['InvId']
        );
    }

    function getCRC(){
        //$data = $this->crc_data;
        $pass1=$this->config['mrh_pass1'];

        //$str=$data['MrchLogin'].':'.$data['OutSum'].':'.$data['InvId'].':'.$pass1;
        $str=implode(':',$this->crc_data);
        $str= $str.':'.$pass1;

        $this->crc_sum = md5($str);
        return $this->crc_sum;
    }
    function combineGetString(){
        $data = $this->data;
        $crc = $this->crc_sum;
        $url='http://test.robokassa.ru/Index.aspx?';
        $result = '';
        foreach($data as $key => $val)
        {
            $result .= $key.'='.$val.'&';
        }
        $result .= 'SignatureValue='.$crc;
        return $url.$result;
    }

}