-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 02:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anonfeedbackwall`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `display_name` varchar(100) DEFAULT 'Anon',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `room_id`, `user_id`, `message`, `display_name`, `created_at`, `image`) VALUES
(16, 9, 7, 'Hot take: Our Monday morning sync could be an email 90% of the time. We spend 45 minutes reciting updates that are already in the project tracker. Let\'s use that time for actual deep work instead.', 'AnonUser', '2026-01-16 12:58:11', NULL),
(17, 9, 7, 'I feel like our \'flexible hours\' policy is becoming a myth. There’s an unspoken expectation to respond to pings until 8:00 PM just because the green light is on. Can we set some \'dark hours\' where no one is expected to reply?', 'AnonUser', '2026-01-16 13:00:14', 'Screenshot 2026-01-16 184500.png'),
(18, 10, 7, 'To get that \'wow\' factor, maybe we should actually subtract something. The landing page feels a bit crowded. If we stripped away two of the secondary sidebars and went for a bold, high-contrast hero section, the core value of the project would hit much harder.', 'AnonUser', '2026-01-16 13:05:12', NULL),
(19, 10, 9, 'I’d love to see one small, unexpected feature that rewards power users—maybe a hidden dark mode, a personalized greeting, or a shortcut that saves them 3 clicks. People love sharing \'did you know\' tips about the apps they use.', 'Ram', '2026-01-16 13:06:23', NULL),
(20, 11, 9, 'Spam feedback', 'Ram', '2026-01-16 13:07:21', 'Screenshot 2026-01-07 223436.png');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `user_id`, `title`, `description`, `created_at`, `image`) VALUES
(9, 6, 'Dump & Reality Checks', 'Got a \'hot take\' or a wild idea? Throw it on the wall. This is our digital suggestion box for anything that needs saying. No egos, no judgment—just pure, unvarnished feedback.', '2026-01-16 12:57:19', '677cf3284af44de456dd3396_team-brainstorming-5.png'),
(10, 8, 'The Missing Piece', 'This project is 90% there, but it’s missing that final \'wow\' factor. Use this wall to dump any inspiration, features, or tweaks you think would take it to the next level. Let\'s finish the picture together.', '2026-01-16 13:04:02', NULL),
(11, 9, 'Spam room', 'Spam keywords', '2026-01-16 13:06:55', 'Screenshot 2026-01-06 191408.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `created_at`, `email`) VALUES
(2, 'Admin', '$2y$10$OF1RCPvAq./pdXrXO6f9q.bkazZA6RMcg/II9fpHV86tL/9CHbNk6', 'admin', '2026-01-03 13:27:39', 'admin@gmail.com'),
(5, 'Administrator', '$2y$10$hwuDQ0OEsCBZ8rPKWlxJjOxbAYS/ZhTlWW3OmwOTilXICzID4adn.', 'admin', '2026-01-16 12:50:57', 'administrator@gmail.com'),
(6, 'User', '$2y$10$.AfmujUJzm3khYuEzTt4h.d2VZYp.1sAP8A2kWau3P44T5.ci8o/i', 'user', '2026-01-16 12:55:52', 'user@gmail.com'),
(7, 'AnonUser', '$2y$10$49eI4ZLklzWMkTB04QAPLeLgPXAyURWHoVdoyQsD2q2zu8nxigtCG', 'user', '2026-01-16 12:57:58', 'anonuser@gmail.com'),
(8, 'Brainstorm', '$2y$10$1vLhuN2U9HS0lM4uLgDJi.LH3Odjs6hW3oVdP5HH4NoyvsQIM1eUO', 'user', '2026-01-16 13:00:52', 'brainstormer@gmail.com'),
(9, 'Ram', '$2y$10$HqvxEkGa/7ER/ugcGFm7Ke6iK6i0s7clFlx19j4BY4QxK7Wkhdz6W', 'user', '2026-01-16 13:05:47', 'ram@gmai.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
