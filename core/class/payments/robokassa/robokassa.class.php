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
        $this->config=$payments_config['robokassa'];
        print_r($this->config);

    }
}