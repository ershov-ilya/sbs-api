<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 09.12.2014
 * Time: 11:29
 */

$content=file('../config/letters-date');
$output='';
foreach($content as $el)
{
    $arr=explode(';',$el);
//    $output.=$arr[1].';';
    $output.=$arr[0].';';
    $output.=strtotime($arr[1])."\n";
}
print($output);