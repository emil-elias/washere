SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `washere_categories` (
  `id` int NOT NULL,
  `value` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `washere_countries` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `continent` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `valid` int NOT NULL,
  `song` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `washere_places` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name_native` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `suffix` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `aka` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `html_desc` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int NOT NULL DEFAULT '32',
  `country` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `population` int NOT NULL,
  `population_metro` int NOT NULL,
  `date` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status_id` int NOT NULL DEFAULT '1',
  `company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `song` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `valid` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `washere_status` (
  `id` int NOT NULL,
  `value` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `washere_visits` (
  `id` int NOT NULL,
  `place_id` int NOT NULL,
  `date` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `company` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_id` int NOT NULL,
  `mode_arrive` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mode_depart` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `accomodation` text COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `washere_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `washere_countries`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `washere_places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_category` (`category_id`),
  ADD KEY `place_status` (`status_id`);

ALTER TABLE `washere_status`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `washere_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_place` (`place_id`),
  ADD KEY `visit_status` (`status_id`);


ALTER TABLE `washere_countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `washere_places`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `washere_visits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `washere_places`
  ADD CONSTRAINT `place_category` FOREIGN KEY (`category_id`) REFERENCES `washere_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `place_status` FOREIGN KEY (`status_id`) REFERENCES `washere_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `washere_visits`
  ADD CONSTRAINT `visit_place` FOREIGN KEY (`place_id`) REFERENCES `washere_places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_status` FOREIGN KEY (`status_id`) REFERENCES `washere_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
