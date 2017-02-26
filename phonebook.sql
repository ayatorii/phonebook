-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Фев 09 2017 г., 05:56
-- Версия сервера: 5.7.14
-- Версия PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `phonebook`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `city_name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`id`, `city_name`) VALUES
(1, 'Владивосток'),
(2, 'Южно-Сахалинск');

-- --------------------------------------------------------

--
-- Структура таблицы `phonebook`
--

CREATE TABLE `phonebook` (
  `ID` int(6) NOT NULL,
  `Phone` varchar(11) NOT NULL,
  `Credentials` text NOT NULL,
  `Birthday` date NOT NULL,
  `city_id` int(11) NOT NULL,
  `street_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `phonebook`
--

INSERT INTO `phonebook` (`ID`, `Phone`, `Credentials`, `Birthday`, `city_id`, `street_id`) VALUES
(49, '89146796549', 'Фролов Дмитрий Игоревич', '2017-02-07', 2, 5),
(50, '89146796549', 'Зобов Климентий Сергеевич', '2017-02-03', 0, 0),
(57, '89146796549', '&lt;img src=&quot;x&quot; onerror=&quot;alert(\'xss!\')&quot;&gt;', '2017-02-03', 2, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `streets`
--

CREATE TABLE `streets` (
  `id` int(11) NOT NULL,
  `street_name` varchar(50) NOT NULL,
  `city_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `streets`
--

INSERT INTO `streets` (`id`, `street_name`, `city_id`) VALUES
(1, 'Светланская', 1),
(2, 'Комарова', 1),
(3, 'Есенина', 1),
(4, 'Есенина', 2),
(5, 'Пуркаева', 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `phonebook`
--
ALTER TABLE `phonebook`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `streets`
--
ALTER TABLE `streets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `phonebook`
--
ALTER TABLE `phonebook`
  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT для таблицы `streets`
--
ALTER TABLE `streets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
