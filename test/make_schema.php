<?php
/**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 10.07.2015
 * Time: 17:54
 */

/*******************************************************/
/* Конфиги нашей схемы                 */
/*******************************************************/
// Имя Класса. Так будет потом называться Класс при вызове $modx->addPackage()
$obj = 'GetResponse';

/*
Префикс таблиц. Если префикс не отличается от системного, то можно вообще не указывать.
К сожалению, xPDO при генерации не позволяет перечислять имена конкретных таблиц,
которые нам нужны, а позволяет отсеять только по префиксу.
*/
$tablePrefix='modx_custom_';

// Папка, где будет записана XML-схема и все файлы создаваемого объекта
// Путь к файлам класса вы будете потом прописывать в вызове метода $modx->addPackage();
$Path = dirname(__FILE__).'/model/';

// Файл-схема
$Schema = $Path.'/'.$obj.'.mysql.schema.xml';

/*******************************************************/
// Подгружаем основной файл-конфиг сайта или самим придется прописывать все основные настройки
require_once dirname(dirname(dirname(__FILE__))).'/core/config/config.inc.php';

// Подружаем основной класс MODx
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';

// Инициализируем класс MODx
$modx= new modX();

// Инициализируем контекст, если принципиально
// $modx->initialize('mgr');

// Устанавливаем настройки логирования
// Не обязательно
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

// !!! Обязательно!
// Подгружаем основной класс-пакер
$modx->addPackage('transport.modPackageBuilder', '', false, true);

// Указатель типа базы данных (MySQL / MsSQL и т.п.)
$manager = $modx->getManager();

// Класс-генератор схем
$generator = $manager->getGenerator();

// Генерируем файл-XML
// /xpdo/om/mysql/xpdogenerator.class.php
// public function writeSchema($schemaFile, $package= '', $baseClass= '', $tablePrefix= '', $restrictPrefix= false)
// $tablePrefix - указываем, если хотим только те таблицы, которые начинаются с этого префикса.
// $restrictPrefix - указывает true, если хотим получить таблицы только по префиксу

$xml= $generator->writeSchema($Schema, $obj, 'xPDOObject', $tablePrefix ,$restrictPrefix=true );
// Создает классы и мапы (php) по схеме xml
$generator->parseSchema($Schema, $Path);

print "<br /><br />Выполнено";