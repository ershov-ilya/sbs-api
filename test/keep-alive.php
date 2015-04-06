<?php
/**
 * Created by PhpStorm.
 * User: IErshov
 * Date: 07.11.2014
 * Time: 10:51
 */
$start=microtime(true);
$resp='';
$ch = curl_init();
for ($i = 0; $i < 10; $i += 1) {
    curl_setopt_array($ch, array(
        CURLOPT_URL => "http://ershov.pw/robots.txt",
        CURLOPT_VERBOSE => True,
        CURLOPT_RETURNTRANSFER => True,
    ));
    $resp .= curl_exec($ch);
}
curl_close($ch);
print $resp;
print "\n";
$finish=microtime(true);
print "Time: ".($finish-$start)."\n";


