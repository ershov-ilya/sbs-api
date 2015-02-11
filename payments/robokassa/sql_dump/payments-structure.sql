-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 11 2015 г., 15:33
-- Версия сервера: 5.1.63
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `suniver_robokassa`
--

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `OutSum` float NOT NULL,
  `Desc` varchar(100) NOT NULL,
  `IncCurrLabel` char(3) DEFAULT NULL,
  `Status` char(10) NOT NULL DEFAULT 'Не оплачен',
  `SignatureValue` char(32) DEFAULT NULL,
  `attempts` int(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `payments`
--

