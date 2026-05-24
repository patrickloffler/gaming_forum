-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 11. kvě 2026, 02:15
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `wa_2026_gaming_forum`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(7) DEFAULT '#6366f1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `color`) VALUES
(1, 'RPG', 'rpg', 'Role-playing games — epické příběhy a světy', '#8b5cf6'),
(2, 'FPS / Akce', 'fps-akce', 'Střílečky a akční hry', '#ef4444'),
(3, 'Strategie', 'strategie', 'Budování, taktika a velení', '#3b82f6'),
(4, 'Sport & Racing', 'sport-racing', 'Sportovní simulátory a závody', '#10b981'),
(5, 'Indie', 'indie', 'Nezávislé herní klenoty', '#f59e0b'),
(6, 'Retro & Klasiky', 'retro', 'Nostalgické herní legendy', '#6b7280'),
(7, 'Novinky', 'novinky', 'Čerstvé herní zprávy a oznámení', '#ec4899'),
(8, 'Ostatní', 'ostatni', 'Vše, co nezapadá jinam', '#64748b');

-- --------------------------------------------------------

--
-- Struktura tabulky `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(3, 3, 4, 'Už se moc těším. :)', '2026-05-10 23:07:41', '2026-05-10 23:07:41'),
(4, 4, 5, 'Šílený!', '2026-05-10 23:23:49', '2026-05-10 23:23:49'),
(5, 5, 4, 'Souhlasím. 10/10', '2026-05-10 23:30:02', '2026-05-10 23:30:02'),
(6, 6, 6, 'Výborný set.', '2026-05-10 23:55:09', '2026-05-10 23:55:09');

-- --------------------------------------------------------

--
-- Struktura tabulky `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `game_name` varchar(150) DEFAULT NULL,
  `platform` enum('PC','PlayStation','Xbox','Nintendo','Mobile','Retro','Ostatní') DEFAULT 'PC',
  `category_id` int(11) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `game_name`, `platform`, `category_id`, `images`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 'Clair Obscur: Expedition 33: Live-Action Adaptace', 'Live-action adaptace hry Clair Obscur: Expedition 33 je skutečně ve vývoji, a to i přesto, že hra samotná (od studia Sandfall Interactive) vyšla teprve v roce 2025. Na projektu spolupracuje produkční společnost Story Kitchen (známá prací na Sonic the Hedgehog) a projekt byl oznámen začátkem roku.', 'Clair Obscur: Expedition 33', 'PC', 5, '[\"img_6a010f92e02e3_4fc0.jpg\"]', 4, '2026-05-10 23:06:58', '2026-05-10 23:29:41'),
(4, 'Falcons porazili K27 při Karriganově debutu', 'Falcons postoupili do švýcarské skupiny s výsledkem 1:0 na hřišti PGL Astana po vítězství 2:0 nad K27 v prvním zápase Finna ´karrigan⁠´ Andersena za tým.\r\nI přes omezený trénink před akcí se Falcons prosadili na pouhých dvou mapách, i když to nebylo úplně hladké. Po pohodlném vítězství na Dust2 si Falcons s jistotou zajistili Ancient až s nepříjemným zpožděním a hru uzavřeli až v posledním kole základní hrací doby, právě když se třetí mapa zdála nevyhnutelná.', 'Counter-Strike 2', 'PC', 2, '[\"img_6a01126f3b6ab_8cf2.jpg\"]', 4, '2026-05-10 23:19:11', '2026-05-10 23:21:34'),
(5, 'Red Dead Redemption II – recenze', 'Red Dead Redemption II je ohromná, konzistentně špičková, technicky takřka bezchybná, plná detailů, překvapení i emocí, a hlavně je nesmírně zábavná. A také je to jedna z nejlepších her nejen pro současnou generaci konzolí.  10/10', 'Red Dead Redemption II', 'PlayStation', 1, '[\"img_6a01148d8e0f2_d56a.jpg\"]', 5, '2026-05-10 23:28:13', '2026-05-10 23:28:13'),
(6, 'TFT Patch 17.1 - Vítejte v novém setu Space Gods!', 'Nový set Space Gods je konečně tady a s ním přichází i kompletní reset Ranked sezóny, nová mechanika The Realm of the Gods, hromada nových jednotek, traitů a artefaktů.', 'League of Legends: Teamfight Tactics', 'Mobile', 3, '[\"img_6a0115c77e1ac_b44a.jpg\"]', 4, '2026-05-10 23:33:27', '2026-05-10 23:33:27');

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('user','moderator','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `nickname`, `avatar`, `bio`, `role`, `created_at`, `updated_at`) VALUES
(4, 'Patrick', 'patrick@tul.cz', '$2y$10$Mf2DRy/1nJbqJTMvOEbYIuupQp98oOJfZ7.J/fG/KPeM/685/Lx9e', 'Patrick', 'Loffler', 'Lefik', 'avatars/avatar_6a010e9f4312b.jpg', 'Rád hraji videohry.', 'moderator', '2026-05-10 23:00:44', '2026-05-10 23:58:15'),
(5, 'Host', 'host@gmail.com', '$2y$10$UvUeoWe7zKhbTyliO4ecZe8D6a23vz.eTDlyelDcCjc7nkKrtFM86', 'Host', 'Gmail', 'Host', 'avatars/avatar_6a0113c968614.jpg', '', 'user', '2026-05-10 23:23:31', '2026-05-10 23:24:57'),
(6, 'Spravce', 'spravce@tul.cz', '$2y$10$ZdYwrbgMtxgE9GD/nRIHM.Ih4nmIiLXTj.GcOEtU2ySe/TeMQKOHy', 'Spravce', 'Admin', 'Spravce', NULL, NULL, 'admin', '2026-05-10 23:53:48', '2026-05-10 23:54:18');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexy pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pro tabulku `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pro tabulku `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
