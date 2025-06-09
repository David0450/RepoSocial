-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql.cifpceuta.es
-- Generation Time: Jun 08, 2025 at 07:15 PM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 8.1.2-1ubuntu2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dperal`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `icon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `icon`) VALUES
(1, 'Desarrollo Web', 'fa-solid fa-code'),
(5, 'Desarrollo Móvil', 'fa-solid fa-mobile-screen'),
(6, 'Ciberseguridad', 'fa-solid fa-user-secret');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `created_at`) VALUES
(1, '2025-05-22 10:10:04'),
(6, '2025-05-25 16:33:24'),
(7, '2025-06-08 10:31:39'),
(9, '2025-06-08 10:47:05'),
(10, '2025-06-08 10:47:29'),
(11, '2025-06-08 20:18:35');

-- --------------------------------------------------------

--
-- Table structure for table `chat_members`
--

CREATE TABLE `chat_members` (
  `chat_id` int NOT NULL,
  `user_id` int NOT NULL,
  `joined_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_members`
--

INSERT INTO `chat_members` (`chat_id`, `user_id`, `joined_at`) VALUES
(1, 1, '2025-05-22 10:10:04'),
(1, 2, '2025-05-22 10:10:04'),
(2, 2, '2025-05-25 16:20:19'),
(2, 5, '2025-05-25 16:20:19'),
(3, 2, '2025-05-25 16:21:12'),
(3, 5, '2025-05-25 16:21:12'),
(4, 2, '2025-05-25 16:21:26'),
(4, 5, '2025-05-25 16:21:26'),
(5, 2, '2025-05-25 16:32:23'),
(5, 5, '2025-05-25 16:32:23'),
(6, 2, '2025-05-25 16:33:24'),
(6, 5, '2025-05-25 16:33:24'),
(7, 42, '2025-06-08 10:31:39'),
(7, 43, '2025-06-08 10:31:39'),
(9, 41, '2025-06-08 10:47:05'),
(9, 44, '2025-06-08 10:47:05'),
(10, 42, '2025-06-08 10:47:29'),
(10, 44, '2025-06-08 10:47:29'),
(11, 2, '2025-06-08 20:18:35'),
(11, 42, '2025-06-08 20:18:35');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `chat_id` int NOT NULL,
  `user_id` int NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`chat_id`, `user_id`, `body`, `created_at`, `id`) VALUES
(1, 1, 'hola', '2025-05-22 10:34:51', 1),
(1, 1, 'hola', '2025-05-22 10:35:33', 2),
(1, 2, 'hola', '2025-05-22 10:36:12', 3),
(1, 1, 'prueba', '2025-05-22 11:01:24', 4),
(1, 1, 'sisi', '2025-05-22 11:02:55', 5),
(1, 1, 'e', '2025-05-22 11:05:24', 6),
(1, 1, 'as', '2025-05-22 11:06:10', 7),
(1, 1, 'hola', '2025-05-22 11:20:47', 8),
(1, 2, 'hyoolal', '2025-05-22 12:07:44', 9),
(1, 1, 'probando', '2025-05-22 18:49:49', 10),
(1, 2, 'asd', '2025-05-22 23:07:46', 11),
(1, 2, 'as', '2025-05-22 23:09:03', 12),
(1, 2, 'esto es una prueba de un mensaje medio largo', '2025-05-24 12:29:46', 13),
(1, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-05-24 12:31:53', 14),
(1, 2, 'hola', '2025-05-24 12:47:54', 15),
(1, 2, 'prueba', '2025-05-24 12:48:54', 16),
(1, 2, 'prueba2', '2025-05-24 12:49:34', 17),
(1, 2, 'prueba', '2025-05-24 12:50:57', 18);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `posted_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_subcomment`
--

CREATE TABLE `comment_subcomment` (
  `subcomment_id` int NOT NULL,
  `parent_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `title` varchar(80) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `uploaded_at` datetime NOT NULL,
  `status` enum('deleted','public','private','hidden') NOT NULL,
  `html_url` varchar(255) NOT NULL,
  `owner_avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `private`, `description`, `category_id`, `user_id`, `uploaded_at`, `status`, `html_url`, `owner_avatar`) VALUES
(765625787, 'libro-git', 0, 'Prueba', 1, 2, '2025-06-06 17:26:07', 'private', 'https://github.com/David0450/libro-git', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(770901360, 'Formulario-HTML', 0, NULL, 1, 2, '2025-06-06 17:26:03', 'private', 'https://github.com/David0450/Formulario-HTML', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771185208, 'ED', 0, NULL, 1, 2, '2025-06-06 17:25:55', 'private', 'https://github.com/David0450/ED', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771185864, 'SI', 0, NULL, 1, 2, '2025-06-06 17:26:19', 'private', 'https://github.com/David0450/SI', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771186226, 'BD', 0, NULL, 1, 2, '2025-06-06 17:25:31', 'private', 'https://github.com/David0450/BD', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771186351, 'LMSGI', 0, NULL, 1, 2, '2025-06-06 17:26:12', 'private', 'https://github.com/David0450/LMSGI', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771186536, 'PROGRAMACION', 0, NULL, 1, 2, '2025-06-06 17:26:15', 'private', 'https://github.com/David0450/PROGRAMACION', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771186570, 'FOL', 0, NULL, 1, 2, '2025-06-06 17:25:59', 'private', 'https://github.com/David0450/FOL', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771213612, 'DAW', 0, NULL, 1, 2, '2025-06-06 17:25:44', 'private', 'https://github.com/David0450/DAW', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(771216153, 'David0450', 0, NULL, 1, 2, '2025-06-06 17:25:39', 'private', 'https://github.com/David0450/David0450', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(858920651, 'Proyecto', 0, NULL, 1, 2, '2025-05-20 13:53:38', 'private', 'https://github.com/David0450/Proyecto', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(868070568, 'Terraria', 0, NULL, 1, 42, '2025-06-08 12:40:09', 'private', 'https://github.com/rayancbyb/Terraria', 'https://avatars.githubusercontent.com/u/146780450?v=4'),
(884811786, 'Buscaminas', 0, NULL, 1, 2, '2025-05-20 10:27:26', 'private', 'https://github.com/David0450/Buscaminas', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(889259971, 'Codigos-php', 0, NULL, 5, 44, '2025-06-08 12:39:23', 'private', 'https://github.com/LilAitor/Codigos-php', 'https://avatars.githubusercontent.com/u/186881457?v=4'),
(902527772, 'Proyecto-Ajedrez', 0, 'Juego de ajedrez realizado enteramente en PHP', 1, 2, '2025-05-20 13:53:32', 'private', 'https://github.com/David0450/Proyecto-ajedrez', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(923170702, 'ProyectoTienda', 0, NULL, 1, 2, '2025-05-20 10:43:18', 'private', 'https://github.com/David0450/ProyectoTienda', 'https://avatars.githubusercontent.com/u/161823005?v=4'),
(933834158, 'Ahorcados', 0, NULL, 1, 44, '2025-06-08 12:39:12', 'private', 'https://github.com/LilAitor/Ahorcados', 'https://avatars.githubusercontent.com/u/186881457?v=4');

-- --------------------------------------------------------

--
-- Table structure for table `project_comments`
--

CREATE TABLE `project_comments` (
  `comment_id` int NOT NULL,
  `project_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_likes`
--

CREATE TABLE `project_likes` (
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `liked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_likes`
--

INSERT INTO `project_likes` (`project_id`, `user_id`, `liked_at`) VALUES
(902527772, 2, '2025-06-06 10:31:10'),
(884811786, 2, '2025-06-06 10:35:10'),
(923170702, 2, '2025-06-06 10:35:06'),
(858920651, 2, '2025-06-05 10:28:07'),
(0, 2, '2025-06-05 10:37:10'),
(923170702, 0, '2025-06-05 10:49:24'),
(884811786, 0, '2025-06-05 10:49:26'),
(902527772, 0, '2025-06-05 10:49:29'),
(858920651, 0, '2025-06-05 10:49:30'),
(771186536, 0, '2025-06-07 18:57:19'),
(858920651, 44, '2025-06-08 10:56:39'),
(771186226, 44, '2025-06-08 10:56:40'),
(884811786, 44, '2025-06-08 10:56:41'),
(902527772, 44, '2025-06-08 10:56:42'),
(923170702, 44, '2025-06-08 10:56:43'),
(771216153, 44, '2025-06-08 10:56:44'),
(771213612, 44, '2025-06-08 10:56:46'),
(771185208, 44, '2025-06-08 10:56:47'),
(771186570, 44, '2025-06-08 10:56:48'),
(771186536, 44, '2025-06-08 10:56:49'),
(771186351, 44, '2025-06-08 10:56:50'),
(765625787, 44, '2025-06-08 10:56:51'),
(770901360, 44, '2025-06-08 10:56:52'),
(771185864, 44, '2025-06-08 10:56:53'),
(933834158, 44, '2025-06-08 10:56:54'),
(889259971, 44, '2025-06-08 10:56:55'),
(868070568, 44, '2025-06-08 10:56:57'),
(889259971, 0, '2025-06-08 20:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `user_id` int NOT NULL,
  `project_id` int NOT NULL,
  `joined_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_tags`
--

CREATE TABLE `project_tags` (
  `project_id` int NOT NULL,
  `tag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_tags`
--

INSERT INTO `project_tags` (`project_id`, `tag_id`) VALUES
(858920651, 1),
(889259971, 1),
(902527772, 1),
(923170702, 1),
(933834158, 1),
(858920651, 3),
(884811786, 3),
(923170702, 3),
(868070568, 4);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `title` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`) VALUES
(2, 'superadmin'),
(1, 'usuario');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int NOT NULL,
  `title` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `title`) VALUES
(12, '#C'),
(5, '#C#'),
(8, '#C++'),
(4, '#Java'),
(3, '#JavaScript'),
(1, '#PHP'),
(10, '#Python'),
(11, '#Ruby');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `avatar_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Public/assets/icons/User_light.svg',
  `github_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `name`, `last_name`, `avatar_url`, `github_id`) VALUES
(1, 'Prueba123', 'prueba123@gmail.com', '$2y$10$eOP44VYfaCKDj2FtPlDtrOLW16eTCjqwF5ptOUNtRaqMXfRIjA3yu', 'Prueba', 'Prueba', 'Public/assets/images/profiles/Prueba123_68383ef412377.jpg', NULL),
(2, 'David0450', 'peraldavid2005@gmail.com', NULL, 'David', '', 'Public/assets/images/profiles/David0450_68384875993c1.png', 161823005),
(5, 'PruebaChat', 'PruebaChat@gmail.com', '$2y$10$AAIDYXCFs4INY2lWYjBh6epa647sNirZ8dTj6R8YdHh8s5D9l.84u', 'PruebaChat', 'PruebaChat', 'Public/assets/icons/User_light.svg', NULL),
(41, 'David2-22', 'peraldavid2002@gmail.com', NULL, NULL, NULL, 'https://avatars.githubusercontent.com/u/215363017?v=4', 215363017),
(42, 'rayancbyb', 'rayancbyb@gmail.com', NULL, 'Rayan Chairi Ben Yamna Boulaic', NULL, 'https://avatars.githubusercontent.com/u/146780450?v=4', 146780450),
(43, 'samiabvb', 'samiaboulaichhassani@gmail.com', NULL, NULL, NULL, 'https://avatars.githubusercontent.com/u/215361856?v=4', 215361856),
(44, 'Lilaitor', 'aitors2003@gmail.com', NULL, '', '', 'Public/assets/images/profiles/LilAitor_68456a6877487.png', 186881457);

-- --------------------------------------------------------

--
-- Table structure for table `user_follows`
--

CREATE TABLE `user_follows` (
  `following_user_id` int NOT NULL,
  `followed_user_id` int NOT NULL,
  `follows_since` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_follows`
--

INSERT INTO `user_follows` (`following_user_id`, `followed_user_id`, `follows_since`) VALUES
(1, 2, '2025-05-21 10:42:32'),
(42, 2, '2025-06-09 05:18:28'),
(43, 42, '2025-06-08 19:31:24'),
(44, 42, '2025-06-08 19:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(5, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(1, 2),
(2, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_members`
--
ALTER TABLE `chat_members`
  ADD PRIMARY KEY (`chat_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `comment_subcomment`
--
ALTER TABLE `comment_subcomment`
  ADD PRIMARY KEY (`subcomment_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project_likes`
--
ALTER TABLE `project_likes`
  ADD PRIMARY KEY (`project_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`user_id`,`project_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project_tags`
--
ALTER TABLE `project_tags`
  ADD PRIMARY KEY (`project_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `github_id` (`github_id`);

--
-- Indexes for table `user_follows`
--
ALTER TABLE `user_follows`
  ADD PRIMARY KEY (`following_user_id`,`followed_user_id`),
  ADD KEY `followed_user_id` (`followed_user_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment_subcomment`
--
ALTER TABLE `comment_subcomment`
  ADD CONSTRAINT `comment_subcomment_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_subcomment_ibfk_2` FOREIGN KEY (`subcomment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD CONSTRAINT `project_comments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_comments_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_tags`
--
ALTER TABLE `project_tags`
  ADD CONSTRAINT `project_tags_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_follows`
--
ALTER TABLE `user_follows`
  ADD CONSTRAINT `user_follows_ibfk_1` FOREIGN KEY (`followed_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_follows_ibfk_2` FOREIGN KEY (`following_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
