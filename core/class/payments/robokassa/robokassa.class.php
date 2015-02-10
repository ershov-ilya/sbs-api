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

    public function __construct($data)
    {
        require_once('../../../config/core.config.php');
        require_once(API_ROOT_PATH.'/core/config/payments.config.php');


    }
}