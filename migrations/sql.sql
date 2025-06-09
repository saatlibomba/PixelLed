-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 09 Haz 2025, 12:06:39
-- Sunucu sürümü: 10.6.21-MariaDB
-- PHP Sürümü: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `u2252740_backend`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `commands`
--

CREATE TABLE `commands` (
  `device_id` int(10) UNSIGNED NOT NULL COMMENT 'References devices.id',
  `frame_json` text NOT NULL COMMENT 'Latest JSON frame',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Last update',
  `delay_ms` int(11) NOT NULL DEFAULT 500,
  `brightness` tinyint(4) NOT NULL DEFAULT 80
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores one current frame per device';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `mac` varchar(17) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mirror_commands`
--

CREATE TABLE `mirror_commands` (
  `id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `raw_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`raw_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mirror_shapes`
--

CREATE TABLE `mirror_shapes` (
  `id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `shape_name` varchar(50) NOT NULL,
  `led_indices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`led_indices`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` char(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pixel_art`
--

CREATE TABLE `pixel_art` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL COMMENT 'Kullanıcı tanımlı isim',
  `type` enum('static','anim') NOT NULL DEFAULT 'static',
  `frame_delay` int(10) UNSIGNED DEFAULT NULL,
  `pixel_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '16×16 pikseller için 256’lık hex renk dizisi' CHECK (json_valid(`pixel_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `redirect_to` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `commands`
--
ALTER TABLE `commands`
  ADD PRIMARY KEY (`device_id`),
  ADD KEY `idx_cmd_dev` (`device_id`);

--
-- Tablo için indeksler `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mac` (`mac`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `migration` (`migration`);

--
-- Tablo için indeksler `mirror_commands`
--
ALTER TABLE `mirror_commands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Tablo için indeksler `mirror_shapes`
--
ALTER TABLE `mirror_shapes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Tablo için indeksler `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `pixel_art`
--
ALTER TABLE `pixel_art`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `mirror_commands`
--
ALTER TABLE `mirror_commands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `mirror_shapes`
--
ALTER TABLE `mirror_shapes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `pixel_art`
--
ALTER TABLE `pixel_art`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `commands`
--
ALTER TABLE `commands`
  ADD CONSTRAINT `fk_commands_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
