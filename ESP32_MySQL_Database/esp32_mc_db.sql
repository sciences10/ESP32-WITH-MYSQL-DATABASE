-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-07-24 10:41:55
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `esp32_mc_db`
--

-- --------------------------------------------------------

--
-- 資料表結構 `esp32_table_dht11_leds_record`
--

CREATE TABLE `esp32_table_dht11_leds_record` (
  `id` varchar(255) NOT NULL,
  `board` varchar(255) NOT NULL,
  `temperature` float(10,2) NOT NULL,
  `humidity` int(3) NOT NULL,
  `status_read_sensor_dht11` varchar(255) NOT NULL,
  `LED_01` varchar(255) NOT NULL,
  `LED_02` varchar(255) NOT NULL,
  `time` time NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `esp32_table_dht11_leds_update`
--

CREATE TABLE `esp32_table_dht11_leds_update` (
  `id` varchar(255) NOT NULL,
  `temperature` float(10,2) NOT NULL,
  `humidity` int(3) NOT NULL,
  `status_read_sensor_dht11` varchar(255) NOT NULL,
  `LED_01` varchar(255) NOT NULL,
  `LED_02` varchar(255) NOT NULL,
  `time` time NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `esp32_table_dht11_leds_update`
--

INSERT INTO `esp32_table_dht11_leds_update` (`id`, `temperature`, `humidity`, `status_read_sensor_dht11`, `LED_01`, `LED_02`, `time`, `date`) VALUES
('esp32_01', 32.00, 50, 'SUCCESS', 'OFF', 'OFF', '15:57:57', '2023-07-24');

-- --------------------------------------------------------

--
-- 資料表結構 `esp32_table_test`
--

CREATE TABLE `esp32_table_test` (
  `id` varchar(255) NOT NULL,
  `temperature` float(10,2) NOT NULL,
  `humidity` int(3) NOT NULL,
  `status_read_sensor_dht11` varchar(255) NOT NULL,
  `LED_01` varchar(255) NOT NULL,
  `LED_02` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `esp32_table_test`
--

INSERT INTO `esp32_table_test` (`id`, `temperature`, `humidity`, `status_read_sensor_dht11`, `LED_01`, `LED_02`) VALUES
('esp32_01', 0.00, 0, 'SUCCESS', 'OFF', 'OFF');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `esp32_table_dht11_leds_record`
--
ALTER TABLE `esp32_table_dht11_leds_record`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `esp32_table_dht11_leds_update`
--
ALTER TABLE `esp32_table_dht11_leds_update`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `esp32_table_test`
--
ALTER TABLE `esp32_table_test`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
