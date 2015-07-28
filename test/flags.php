<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 28.07.2015
 * Time: 14:58
 */

header('Content-Type: text/plain; charset=utf-8');

define('FLAG_ONE',   1);
define('FLAG_TWO',   2);
define('FLAG_THREE', 4);
define('FLAG_FOUR',  8);
define('FLAG_FIVE',  16);
define('FLAG_SIX',   32);
define('FLAG_SEVEN', 64);
define('FLAG_IGNORE',128);


$combine = FLAG_ONE | FLAG_TWO | FLAG_IGNORE;
print 'combine FLAG_ONE | FLAG_TWO | FLAG_IGNORE:'.PHP_EOL;
print $combine;
print PHP_EOL;

print 'check FLAG_SEVEN:'.PHP_EOL;
print $combine & FLAG_SEVEN;
print PHP_EOL;

print 'check FLAG_IGNORE:'.PHP_EOL;
print $combine & FLAG_IGNORE;
print PHP_EOL;




