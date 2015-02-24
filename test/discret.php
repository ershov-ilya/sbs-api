<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 24.02.2015
 * Time: 18:23
 */

header('Content-Type: text/plain; charset=utf-8');

define('CONST_1', 1);
define('CONST_2', 2);
define('CONST_4', 4);
define('CONST_8', 8);

//print CONST_1 | CONST_2;

$flags = CONST_1 | CONST_4 | CONST_8;

print "flags: $flags\n";

print "CONST_2: ";
print $flags &  CONST_2;
print "\n";

print "CONST_4: ";
print $flags &  CONST_4;
print "\n";

