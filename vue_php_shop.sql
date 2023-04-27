-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 27 2023 г., 16:27
-- Версия сервера: 8.0.27
-- Версия PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vue_php_shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 AVG_ROW_LENGTH=2730 DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id`, `brand`) VALUES
(1, 'Apple'),
(2, 'Samsung'),
(3, 'Acer'),
(4, 'Lenovo'),
(5, 'Asus'),
(6, 'Gigabyte');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 AVG_ROW_LENGTH=5461 DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'Ноутбуки'),
(2, 'Смартфоны'),
(3, 'Видеокарты');

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

DROP TABLE IF EXISTS `goods`;
CREATE TABLE IF NOT EXISTS `goods` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `good` varchar(255) NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `brand_id` int UNSIGNED NOT NULL,
  `price` int UNSIGNED NOT NULL,
  `rating` int UNSIGNED NOT NULL DEFAULT '0',
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_goods_brands_id` (`brand_id`),
  KEY `FK_goods_categories_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 AVG_ROW_LENGTH=1170 DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `good`, `category_id`, `brand_id`, `price`, `rating`, `photo`) VALUES
(1, 'Ноутбук Apple MacBook Air', 1, 1, 60000, 8, 'apple_macbook_air.jpg'),
(2, 'Ноутбук Apple MacBook Pro', 1, 1, 70000, 9, 'apple_macbook_pro.jpg'),
(3, 'Ноутбук Lenovo IdeaPad', 1, 4, 17000, 5, 'lenovo_idea_pad.jpg'),
(4, 'Ноутбук Lenovo G5030', 1, 4, 16000, 7, 'lenovo_g5030.jpg'),
(5, 'Ноутбук Acer Aspire', 1, 3, 21000, 8, 'acer_aspire.jpg'),
(6, 'Смартфон Samsung Galaxy A7', 2, 2, 30000, 9, 'samsung_galaxy_a7.jpg'),
(7, 'Смартфон Samsung Galaxy A5', 2, 2, 17000, 8, 'samsung_galaxy_a5.jpg'),
(8, 'Смартфон Apple iPhone SE', 2, 1, 38000, 10, 'apple_iphone_se.jpg'),
(9, 'Смартфон Asus Zenfone Laser', 2, 5, 12000, 6, 'asus_zenfone_laser.jpg'),
(10, 'Смартфон Lenovo A5000', 2, 4, 11000, 3, 'lenovo_a5000.jpg'),
(11, 'Смартфон Lenovo P90', 2, 4, 16000, 5, 'lenovo_p90.jpg'),
(12, 'Видеокарта ASUS', 3, 5, 2000, 8, 'asus_video.jpg'),
(13, 'Видеокарта GIGABYTE GT-740', 3, 6, 6000, 9, 'gigabyte_video_gt740.jpg'),
(14, 'Видеокарта GIGABYTE GTX-960', 3, 6, 14000, 10, 'gigabyte_video_gtx960.jpg');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `FK_goods_brands_id` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_goods_categories_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
