-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 04, 2026 at 02:10 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `solar_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('task','milestone','meeting','delivery','review','other') NOT NULL DEFAULT 'task',
  `status` enum('planned','in_progress','completed','on_hold','cancelled') NOT NULL DEFAULT 'planned',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `phase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `planned_start_date` datetime DEFAULT NULL,
  `planned_end_date` datetime DEFAULT NULL,
  `actual_start_date` datetime DEFAULT NULL,
  `actual_end_date` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `estimated_hours` int(11) NOT NULL DEFAULT 0,
  `actual_hours` int(11) NOT NULL DEFAULT 0,
  `progress_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `progress_notes` text DEFAULT NULL,
  `dependencies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dependencies`)),
  `blockers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`blockers`)),
  `resources` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`resources`)),
  `deliverables` text DEFAULT NULL,
  `acceptance_criteria` text DEFAULT NULL,
  `completion_notes` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_milestone` tinyint(1) NOT NULL DEFAULT 0,
  `is_billable` tinyint(1) NOT NULL DEFAULT 1,
  `estimated_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `actual_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `activity_code`, `title`, `description`, `type`, `status`, `priority`, `project_id`, `phase_id`, `assigned_to`, `created_by`, `approved_by`, `planned_start_date`, `planned_end_date`, `actual_start_date`, `actual_end_date`, `approved_at`, `estimated_hours`, `actual_hours`, `progress_percentage`, `progress_notes`, `dependencies`, `blockers`, `resources`, `deliverables`, `acceptance_criteria`, `completion_notes`, `notes`, `attachments`, `tags`, `is_milestone`, `is_billable`, `estimated_cost`, `actual_cost`, `created_at`, `updated_at`) VALUES
(1, 'ACT2025120001', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 4, NULL, 1, 13, NULL, '2025-11-11 19:51:29', '2025-11-16 19:51:29', '2025-11-11 19:51:29', '2025-11-16 19:51:29', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(2, 'ACT2025120002', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 2, NULL, 8, 8, NULL, '2025-11-21 19:51:29', '2025-12-01 19:51:29', '2025-11-21 19:51:29', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(3, 'ACT2025120003', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 1, NULL, 2, 10, NULL, '2025-11-26 19:51:29', '2025-12-06 19:51:29', '2025-11-26 19:51:29', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(4, 'ACT2025120004', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 2, NULL, 6, 2, NULL, '2025-12-13 19:51:29', '2025-12-13 19:51:29', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(5, 'ACT2025120005', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 2, NULL, 10, 10, NULL, '2025-12-16 19:51:29', '2025-12-26 19:51:29', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(6, 'ACT2025120006', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 1, NULL, 1, 5, NULL, '2025-12-21 19:51:29', '2025-12-31 19:51:29', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(7, 'ACT2025120007', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 1, NULL, 4, 2, NULL, '2026-01-05 19:51:29', '2026-01-10 19:51:29', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(8, 'ACT2025120008', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 4, NULL, 1, 7, NULL, '2026-01-11 19:51:29', '2026-02-09 19:51:29', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(9, 'ACT2025120009', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 2, NULL, 11, 2, NULL, '2025-11-11 19:52:41', '2025-11-16 19:52:41', '2025-11-11 19:52:41', '2025-11-16 19:52:41', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'ACT2025120010', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 2, NULL, 1, 1, NULL, '2025-11-21 19:52:41', '2025-12-01 19:52:41', '2025-11-21 19:52:41', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'ACT2025120011', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 2, NULL, 1, 1, NULL, '2025-11-26 19:52:41', '2025-12-06 19:52:41', '2025-11-26 19:52:41', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(12, 'ACT2025120012', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 1, NULL, 4, 11, NULL, '2025-12-13 19:52:41', '2025-12-13 19:52:41', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(13, 'ACT2025120013', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 1, NULL, 9, 10, NULL, '2025-12-16 19:52:41', '2025-12-26 19:52:41', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(14, 'ACT2025120014', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 4, NULL, 10, 5, NULL, '2025-12-21 19:52:41', '2025-12-31 19:52:41', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(15, 'ACT2025120015', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 1, NULL, 3, 6, NULL, '2026-01-05 19:52:41', '2026-01-10 19:52:41', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(16, 'ACT2025120016', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 4, NULL, 4, 11, NULL, '2026-01-11 19:52:41', '2026-02-09 19:52:41', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(17, 'ACT2025120017', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 2, NULL, 10, 8, NULL, '2025-11-11 19:52:47', '2025-11-16 19:52:47', '2025-11-11 19:52:47', '2025-11-16 19:52:47', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(18, 'ACT2025120018', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 4, NULL, 7, 5, NULL, '2025-11-21 19:52:47', '2025-12-01 19:52:47', '2025-11-21 19:52:47', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(19, 'ACT2025120019', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 5, NULL, 5, 2, NULL, '2025-11-26 19:52:47', '2025-12-06 19:52:47', '2025-11-26 19:52:47', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(20, 'ACT2025120020', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 3, NULL, 11, 10, NULL, '2025-12-13 19:52:47', '2025-12-13 19:52:47', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(21, 'ACT2025120021', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 4, NULL, 13, 4, NULL, '2025-12-16 19:52:47', '2025-12-26 19:52:47', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(22, 'ACT2025120022', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 3, NULL, 7, 3, NULL, '2025-12-21 19:52:47', '2025-12-31 19:52:47', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(23, 'ACT2025120023', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 4, NULL, 12, 2, NULL, '2026-01-05 19:52:47', '2026-01-10 19:52:47', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(24, 'ACT2025120024', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 4, NULL, 9, 6, NULL, '2026-01-11 19:52:47', '2026-02-09 19:52:47', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(25, 'ACT2025120025', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 3, NULL, 4, 3, NULL, '2025-11-11 19:52:56', '2025-11-16 19:52:56', '2025-11-11 19:52:56', '2025-11-16 19:52:56', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(26, 'ACT2025120026', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 1, NULL, 7, 7, NULL, '2025-11-21 19:52:56', '2025-12-01 19:52:56', '2025-11-21 19:52:56', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(27, 'ACT2025120027', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 2, NULL, 6, 13, NULL, '2025-11-26 19:52:56', '2025-12-06 19:52:56', '2025-11-26 19:52:56', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(28, 'ACT2025120028', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 1, NULL, 1, 8, NULL, '2025-12-13 19:52:56', '2025-12-13 19:52:56', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(29, 'ACT2025120029', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 2, NULL, 10, 11, NULL, '2025-12-16 19:52:56', '2025-12-26 19:52:56', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(30, 'ACT2025120030', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 3, NULL, 12, 13, NULL, '2025-12-21 19:52:56', '2025-12-31 19:52:56', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(31, 'ACT2025120031', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 4, NULL, 12, 6, NULL, '2026-01-05 19:52:56', '2026-01-10 19:52:56', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(32, 'ACT2025120032', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 4, NULL, 3, 13, NULL, '2026-01-11 19:52:56', '2026-02-09 19:52:56', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(33, 'ACT2025120033', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 4, NULL, 13, 11, NULL, '2025-11-11 19:53:10', '2025-11-16 19:53:10', '2025-11-11 19:53:10', '2025-11-16 19:53:10', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(34, 'ACT2025120034', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 4, NULL, 9, 6, NULL, '2025-11-21 19:53:10', '2025-12-01 19:53:10', '2025-11-21 19:53:10', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(35, 'ACT2025120035', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 1, NULL, 9, 3, NULL, '2025-11-26 19:53:10', '2025-12-06 19:53:10', '2025-11-26 19:53:10', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(36, 'ACT2025120036', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 5, NULL, 11, 8, NULL, '2025-12-13 19:53:10', '2025-12-13 19:53:10', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(37, 'ACT2025120037', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 1, NULL, 2, 12, NULL, '2025-12-16 19:53:10', '2025-12-26 19:53:10', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(38, 'ACT2025120038', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 4, NULL, 8, 5, NULL, '2025-12-21 19:53:10', '2025-12-31 19:53:10', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(39, 'ACT2025120039', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 2, NULL, 10, 12, NULL, '2026-01-05 19:53:10', '2026-01-10 19:53:10', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(40, 'ACT2025120040', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 2, NULL, 13, 8, NULL, '2026-01-11 19:53:10', '2026-02-09 19:53:10', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(41, 'ACT2025120041', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 1, NULL, 6, 9, NULL, '2025-11-11 19:53:22', '2025-11-16 19:53:22', '2025-11-11 19:53:22', '2025-11-16 19:53:22', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(42, 'ACT2025120042', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 2, NULL, 8, 4, NULL, '2025-11-21 19:53:22', '2025-12-01 19:53:22', '2025-11-21 19:53:22', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(43, 'ACT2025120043', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 5, NULL, 1, 3, NULL, '2025-11-26 19:53:22', '2025-12-06 19:53:22', '2025-11-26 19:53:22', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(44, 'ACT2025120044', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 4, NULL, 8, 2, NULL, '2025-12-13 19:53:22', '2025-12-13 19:53:22', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(45, 'ACT2025120045', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 5, NULL, 3, 8, NULL, '2025-12-16 19:53:22', '2025-12-26 19:53:22', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(46, 'ACT2025120046', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 5, NULL, 2, 5, NULL, '2025-12-21 19:53:22', '2025-12-31 19:53:22', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(47, 'ACT2025120047', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 5, NULL, 7, 4, NULL, '2026-01-05 19:53:22', '2026-01-10 19:53:22', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(48, 'ACT2025120048', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 3, NULL, 2, 9, NULL, '2026-01-11 19:53:22', '2026-02-09 19:53:22', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(49, 'ACT2025120049', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 2, NULL, 11, 2, NULL, '2025-11-11 19:53:30', '2025-11-16 19:53:30', '2025-11-11 19:53:30', '2025-11-16 19:53:30', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(50, 'ACT2025120050', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 5, NULL, 3, 8, NULL, '2025-11-21 19:53:30', '2025-12-01 19:53:30', '2025-11-21 19:53:30', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(51, 'ACT2025120051', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 4, NULL, 2, 4, NULL, '2025-11-26 19:53:30', '2025-12-06 19:53:30', '2025-11-26 19:53:30', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(52, 'ACT2025120052', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 3, NULL, 5, 11, NULL, '2025-12-13 19:53:30', '2025-12-13 19:53:30', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(53, 'ACT2025120053', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 1, NULL, 5, 10, NULL, '2025-12-16 19:53:30', '2025-12-26 19:53:30', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(54, 'ACT2025120054', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 2, NULL, 7, 10, NULL, '2025-12-21 19:53:30', '2025-12-31 19:53:30', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(55, 'ACT2025120055', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 4, NULL, 12, 7, NULL, '2026-01-05 19:53:30', '2026-01-10 19:53:30', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(56, 'ACT2025120056', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 5, NULL, 5, 11, NULL, '2026-01-11 19:53:30', '2026-02-09 19:53:30', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(57, 'ACT2025120057', 'Project Setup and Planning', 'Initial project setup, team formation, and planning phase', 'task', 'completed', 'high', 3, NULL, 8, 12, NULL, '2025-11-11 19:53:43', '2025-11-16 19:53:43', '2025-11-11 19:53:43', '2025-11-16 19:53:43', NULL, 40, 42, 100.00, NULL, NULL, NULL, NULL, 'Project charter, team structure, initial timeline', 'All planning documents approved by stakeholders', 'Successfully completed with minor adjustments to timeline', NULL, NULL, '[\"planning\",\"setup\",\"milestone\"]', 1, 1, 50000.00, 52000.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(58, 'ACT2025120058', 'System Design and Architecture', 'Design system architecture and create technical specifications', 'task', 'in_progress', 'high', 5, NULL, 11, 10, NULL, '2025-11-21 19:53:43', '2025-12-01 19:53:43', '2025-11-21 19:53:43', NULL, NULL, 60, 35, 65.00, 'Core architecture completed, working on detailed specifications', NULL, NULL, NULL, 'System architecture document, technical specifications', 'Architecture approved by technical lead and stakeholders', NULL, NULL, NULL, '[\"design\",\"architecture\",\"technical\"]', 0, 1, 75000.00, 45000.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(59, 'ACT2025120059', 'Development Sprint 1', 'First development sprint focusing on core functionality', 'task', 'in_progress', 'high', 2, NULL, 4, 3, NULL, '2025-11-26 19:53:43', '2025-12-06 19:53:43', '2025-11-26 19:53:43', NULL, NULL, 80, 55, 70.00, 'Most core features completed, working on edge cases', NULL, NULL, NULL, 'Core modules, basic functionality', 'All core features implemented and tested', NULL, NULL, NULL, '[\"development\",\"sprint\",\"core\"]', 0, 1, 100000.00, 75000.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(60, 'ACT2025120060', 'Client Review Meeting', 'Present progress to client and gather feedback', 'meeting', 'planned', 'medium', 4, NULL, 12, 8, NULL, '2025-12-13 19:53:43', '2025-12-13 19:53:43', NULL, NULL, NULL, 2, 0, 0.00, NULL, NULL, NULL, NULL, 'Progress presentation, feedback documentation', 'Client approval for current progress', NULL, NULL, NULL, '[\"meeting\",\"client\",\"review\"]', 0, 1, 5000.00, 0.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(61, 'ACT2025120061', 'Quality Assurance Testing', 'Comprehensive testing of all developed features', 'task', 'planned', 'high', 5, NULL, 8, 8, NULL, '2025-12-16 19:53:43', '2025-12-26 19:53:43', NULL, NULL, NULL, 50, 0, 0.00, NULL, NULL, NULL, NULL, 'Test reports, bug fixes, quality metrics', 'All tests pass, no critical bugs', NULL, NULL, NULL, '[\"testing\",\"qa\",\"milestone\"]', 1, 1, 60000.00, 0.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(62, 'ACT2025120062', 'Documentation Creation', 'Create user manuals and technical documentation', 'task', 'planned', 'medium', 1, NULL, 8, 5, NULL, '2025-12-21 19:53:43', '2025-12-31 19:53:43', NULL, NULL, NULL, 30, 0, 0.00, NULL, NULL, NULL, NULL, 'User manual, technical documentation, API docs', 'Documentation reviewed and approved', NULL, NULL, NULL, '[\"documentation\",\"manual\",\"technical\"]', 0, 1, 35000.00, 0.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(63, 'ACT2025120063', 'Deployment and Go-Live', 'Deploy system to production and go live', 'delivery', 'planned', 'critical', 2, NULL, 13, 12, NULL, '2026-01-05 19:53:43', '2026-01-10 19:53:43', NULL, NULL, NULL, 20, 0, 0.00, NULL, NULL, NULL, NULL, 'Live system, deployment documentation', 'System running smoothly in production', NULL, NULL, NULL, '[\"deployment\",\"production\",\"milestone\"]', 1, 1, 25000.00, 0.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(64, 'ACT2025120064', 'Post-Launch Support', 'Provide support and maintenance after go-live', 'task', 'planned', 'medium', 3, NULL, 10, 7, NULL, '2026-01-11 19:53:43', '2026-02-09 19:53:43', NULL, NULL, NULL, 40, 0, 0.00, NULL, NULL, NULL, NULL, 'Support tickets resolution, system monitoring', 'All support requests resolved within SLA', NULL, NULL, NULL, '[\"support\",\"maintenance\",\"post-launch\"]', 0, 1, 50000.00, 0.00, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `advances`
--

CREATE TABLE `advances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advance_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `advance_type` enum('employee','vendor','project') NOT NULL DEFAULT 'employee',
  `amount` decimal(15,2) NOT NULL,
  `advance_date` date NOT NULL,
  `expected_settlement_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','settled','partially_settled') NOT NULL DEFAULT 'pending',
  `approval_level` enum('manager','hr','admin','approved','rejected') NOT NULL DEFAULT 'manager',
  `settled_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `purpose` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `manager_approved_at` timestamp NULL DEFAULT NULL,
  `hr_approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `hr_approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `manager_rejection_reason` text DEFAULT NULL,
  `hr_rejection_reason` text DEFAULT NULL,
  `admin_rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advances`
--

INSERT INTO `advances` (`id`, `advance_number`, `title`, `description`, `employee_id`, `vendor_id`, `project_id`, `advance_type`, `amount`, `advance_date`, `expected_settlement_date`, `status`, `approval_level`, `settled_amount`, `purpose`, `notes`, `created_by`, `approved_by`, `manager_approved_by`, `approved_at`, `manager_approved_at`, `hr_approved_by`, `hr_approved_at`, `rejection_reason`, `manager_rejection_reason`, `hr_rejection_reason`, `admin_rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 'ADV-2025-0001', 'ab', NULL, NULL, 3, 2, 'vendor', 1000.00, '2025-12-18', NULL, 'approved', 'approved', 0.00, NULL, NULL, 4, 1, NULL, '2025-12-18 14:50:09', NULL, 13, '2025-12-18 14:49:04', NULL, NULL, NULL, NULL, '2025-12-18 13:25:30', '2025-12-18 14:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `appraisals`
--

CREATE TABLE `appraisals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `appraisal_type` varchar(255) NOT NULL,
  `appraisal_date` date NOT NULL,
  `next_review_date` date NOT NULL,
  `performance_score` int(11) NOT NULL,
  `strengths` text NOT NULL,
  `weaknesses` text NOT NULL,
  `development_plan` text NOT NULL,
  `manager_feedback` text NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') NOT NULL,
  `appraiser_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appraisals`
--

INSERT INTO `appraisals` (`id`, `employee_id`, `appraisal_type`, `appraisal_date`, `next_review_date`, `performance_score`, `strengths`, `weaknesses`, `development_plan`, `manager_feedback`, `status`, `appraiser_name`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'Annual', '2025-11-26', '2026-12-11', 4, 'Technical expertise, problem-solving skills, teamwork', 'Public speaking, time management', 'Attend communication workshops, time management training', 'Consistent performer, reliable team member', 'completed', 'HR Manager', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', 'Mid-Year', '2025-12-03', '2026-06-11', 5, 'Leadership, project management, innovation', 'None identified', 'Continue current trajectory, consider leadership role', 'Exceptional performance, role model for others', 'completed', 'Project Manager', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', 'Annual', '2025-12-21', '2026-12-11', 0, '', '', '', '', 'scheduled', 'Sales Manager', '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `total_hours` int(11) DEFAULT NULL,
  `status` enum('present','absent','late','half_day') NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `employee_id`, `date`, `check_in`, `check_out`, `total_hours`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', '2025-12-10', '09:00:00', '18:00:00', 9, 'present', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', '2025-12-10', '09:15:00', '18:30:00', 9, 'late', 'Traffic jam', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', '2025-12-10', NULL, NULL, 0, 'absent', 'Sick leave', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'EMP002', '2025-12-11', '09:00:00', NULL, NULL, 'present', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'EMP003', '2025-12-11', '09:00:00', NULL, NULL, 'present', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `a_m_c_s`
--

CREATE TABLE `a_m_c_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amc_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_location` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `contract_value` decimal(15,2) NOT NULL,
  `status` enum('active','expired','renewed','cancelled') NOT NULL,
  `services_included` text NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `a_m_c_s`
--

INSERT INTO `a_m_c_s` (`id`, `amc_number`, `customer_name`, `customer_email`, `project_name`, `project_location`, `start_date`, `end_date`, `contract_value`, `status`, `services_included`, `contact_person`, `contact_phone`, `created_at`, `updated_at`) VALUES
(1, 'AMC-2025-0001', 'ABC Industries', 'contact@abcindustries.com', 'Solar Farm Project', 'Mumbai, Maharashtra', '2025-06-11', '2026-06-11', 500000.00, 'active', 'Regular maintenance, cleaning, monitoring, repairs', 'Rajesh Kumar', '+91-9876543210', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'AMC-2025-0002', 'XYZ Corporation', 'contact@xyzcorp.com', 'Residential Solar', 'Delhi, NCR', '2025-09-11', '2026-09-11', 250000.00, 'active', 'Quarterly maintenance, emergency repairs', 'Priya Sharma', '+91-9876543211', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'AMC-2025-0003', 'DEF Enterprises', 'contact@defenterprises.com', 'Commercial Solar', 'Bangalore, Karnataka', '2024-12-11', '2025-10-11', 750000.00, 'expired', 'Annual maintenance, monitoring, repairs', 'John Doe', '+91-9876543212', '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `budget_category_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `budget_amount` decimal(12,2) NOT NULL,
  `actual_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `budget_period` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `budget_number`, `title`, `description`, `budget_category_id`, `project_id`, `budget_amount`, `actual_amount`, `currency`, `budget_period`, `start_date`, `end_date`, `status`, `is_approved`, `approved_by`, `approved_at`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'BUD-000001', 'Solar Panel Installation Budget', 'Budget for installing solar panels on residential property', 5, 3, 15000.00, 7500.00, 'EUR', 'quarterly', '2024-01-01', '2024-03-31', 'approved', 1, 11, '2024-03-15 03:38:55', 'Initial installation budget', 4, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'BUD-000002', 'Equipment Purchase Budget', 'Budget for purchasing solar equipment and tools', 1, 1, 25000.00, 25000.00, 'EUR', 'yearly', '2024-01-01', '2024-12-31', 'completed', 1, 2, '2025-02-24 09:10:35', 'Equipment procurement completed on time', 6, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'BUD-000003', 'Marketing Campaign Budget', 'Budget for digital marketing and advertising', 10, 3, 5000.00, 3200.00, 'USD', 'monthly', '2024-02-01', '2024-02-29', 'completed', 1, 6, '2024-03-29 23:04:06', 'Marketing campaign delivered good ROI', 8, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'BUD-000004', 'Staff Training Budget', 'Budget for employee training and certifications', 7, 5, 12000.00, 8500.00, 'INR', 'quarterly', '2024-02-01', '2024-04-30', 'approved', 1, 4, '2024-08-02 01:23:26', 'Ongoing training program', 4, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'BUD-000005', 'Maintenance Budget', 'Budget for equipment maintenance and repairs', 5, 3, 8000.00, 4500.00, 'EUR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 4, '2024-08-21 00:03:06', 'Preventive maintenance program', 5, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'BUD-000006', 'New Office Setup Budget', 'Budget for setting up new branch office', 7, 2, 30000.00, 35000.00, 'EUR', 'custom', '2024-03-01', '2024-05-31', 'pending', 0, NULL, NULL, 'Over budget due to additional requirements', 10, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'BUD-000007', 'Research & Development Budget', 'Budget for R&D activities and innovation', 9, 5, 20000.00, 6500.00, 'EUR', 'yearly', '2024-01-01', '2024-12-31', 'draft', 0, NULL, NULL, 'Under review by management', 6, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'BUD-000008', 'Transportation Fleet Budget', 'Budget for vehicle fleet maintenance and fuel', 6, 4, 15000.00, 12000.00, 'USD', 'quarterly', '2024-01-01', '2024-03-31', 'completed', 1, 13, '2024-07-28 15:33:14', 'Q1 transportation costs within budget', 6, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'BUD-000009', 'Software Licensing Budget', 'Budget for software licenses and subscriptions', 6, 1, 10000.00, 10000.00, 'EUR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 8, '2025-02-06 09:47:21', 'All licenses renewed successfully', 9, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'BUD-000010', 'Insurance Policy Budget', 'Budget for company insurance policies', 3, 2, 18000.00, 18500.00, 'EUR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 3, '2025-03-28 18:16:49', 'Insurance premium slightly over budget', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'BUD-000011', 'Solar Panel Installation Budget', 'Budget for installing solar panels on residential property', 4, 2, 15000.00, 7500.00, 'INR', 'quarterly', '2024-01-01', '2024-03-31', 'approved', 1, 9, '2025-10-28 12:36:54', 'Initial installation budget', 12, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(12, 'BUD-000012', 'Equipment Purchase Budget', 'Budget for purchasing solar equipment and tools', 3, 3, 25000.00, 25000.00, 'USD', 'yearly', '2024-01-01', '2024-12-31', 'completed', 1, 13, '2024-10-18 01:20:31', 'Equipment procurement completed on time', 5, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(13, 'BUD-000013', 'Marketing Campaign Budget', 'Budget for digital marketing and advertising', 7, 5, 5000.00, 3200.00, 'INR', 'monthly', '2024-02-01', '2024-02-29', 'completed', 1, 3, '2024-07-07 20:57:29', 'Marketing campaign delivered good ROI', 4, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(14, 'BUD-000014', 'Staff Training Budget', 'Budget for employee training and certifications', 7, 1, 12000.00, 8500.00, 'EUR', 'quarterly', '2024-02-01', '2024-04-30', 'approved', 1, 10, '2025-05-27 04:39:26', 'Ongoing training program', 12, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(15, 'BUD-000015', 'Maintenance Budget', 'Budget for equipment maintenance and repairs', 1, 4, 8000.00, 4500.00, 'EUR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 12, '2024-09-19 04:07:34', 'Preventive maintenance program', 5, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(16, 'BUD-000016', 'New Office Setup Budget', 'Budget for setting up new branch office', 9, 2, 30000.00, 35000.00, 'EUR', 'custom', '2024-03-01', '2024-05-31', 'pending', 0, NULL, NULL, 'Over budget due to additional requirements', 12, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(17, 'BUD-000017', 'Research & Development Budget', 'Budget for R&D activities and innovation', 4, 3, 20000.00, 6500.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'draft', 0, NULL, NULL, 'Under review by management', 10, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(18, 'BUD-000018', 'Transportation Fleet Budget', 'Budget for vehicle fleet maintenance and fuel', 4, 5, 15000.00, 12000.00, 'EUR', 'quarterly', '2024-01-01', '2024-03-31', 'completed', 1, 6, '2024-05-24 21:00:16', 'Q1 transportation costs within budget', 11, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(19, 'BUD-000019', 'Software Licensing Budget', 'Budget for software licenses and subscriptions', 7, 1, 10000.00, 10000.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 8, '2024-11-15 05:21:22', 'All licenses renewed successfully', 11, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(20, 'BUD-000020', 'Insurance Policy Budget', 'Budget for company insurance policies', 3, 1, 18000.00, 18500.00, 'EUR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 4, '2024-01-21 08:24:21', 'Insurance premium slightly over budget', 5, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(21, 'BUD-000021', 'Solar Panel Installation Budget', 'Budget for installing solar panels on residential property', 2, 5, 15000.00, 7500.00, 'INR', 'quarterly', '2024-01-01', '2024-03-31', 'approved', 1, 1, '2024-06-18 09:41:14', 'Initial installation budget', 13, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(22, 'BUD-000022', 'Equipment Purchase Budget', 'Budget for purchasing solar equipment and tools', 2, 5, 25000.00, 25000.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'completed', 1, 3, '2024-04-01 08:52:34', 'Equipment procurement completed on time', 4, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(23, 'BUD-000023', 'Marketing Campaign Budget', 'Budget for digital marketing and advertising', 10, 4, 5000.00, 3200.00, 'USD', 'monthly', '2024-02-01', '2024-02-29', 'completed', 1, 13, '2025-07-08 20:18:44', 'Marketing campaign delivered good ROI', 7, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(24, 'BUD-000024', 'Staff Training Budget', 'Budget for employee training and certifications', 8, 1, 12000.00, 8500.00, 'EUR', 'quarterly', '2024-02-01', '2024-04-30', 'approved', 1, 11, '2024-07-23 00:45:03', 'Ongoing training program', 5, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(25, 'BUD-000025', 'Maintenance Budget', 'Budget for equipment maintenance and repairs', 7, 1, 8000.00, 4500.00, 'USD', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 12, '2024-10-19 11:09:21', 'Preventive maintenance program', 2, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(26, 'BUD-000026', 'New Office Setup Budget', 'Budget for setting up new branch office', 4, 3, 30000.00, 35000.00, 'EUR', 'custom', '2024-03-01', '2024-05-31', 'pending', 0, NULL, NULL, 'Over budget due to additional requirements', 3, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(27, 'BUD-000027', 'Research & Development Budget', 'Budget for R&D activities and innovation', 6, 5, 20000.00, 6500.00, 'USD', 'yearly', '2024-01-01', '2024-12-31', 'draft', 0, NULL, NULL, 'Under review by management', 12, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(28, 'BUD-000028', 'Transportation Fleet Budget', 'Budget for vehicle fleet maintenance and fuel', 7, 1, 15000.00, 12000.00, 'INR', 'quarterly', '2024-01-01', '2024-03-31', 'completed', 1, 8, '2024-11-06 18:56:32', 'Q1 transportation costs within budget', 10, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(29, 'BUD-000029', 'Software Licensing Budget', 'Budget for software licenses and subscriptions', 6, 1, 10000.00, 10000.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 5, '2024-06-19 04:06:24', 'All licenses renewed successfully', 2, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(30, 'BUD-000030', 'Insurance Policy Budget', 'Budget for company insurance policies', 9, 1, 18000.00, 18500.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 4, '2025-07-09 16:33:24', 'Insurance premium slightly over budget', 11, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(31, 'BUD-000031', 'Solar Panel Installation Budget', 'Budget for installing solar panels on residential property', 1, 5, 15000.00, 7500.00, 'EUR', 'quarterly', '2024-01-01', '2024-03-31', 'approved', 1, 6, '2025-06-11 05:40:50', 'Initial installation budget', 5, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(32, 'BUD-000032', 'Equipment Purchase Budget', 'Budget for purchasing solar equipment and tools', 8, 3, 25000.00, 25000.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'completed', 1, 2, '2024-01-08 06:16:39', 'Equipment procurement completed on time', 2, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(33, 'BUD-000033', 'Marketing Campaign Budget', 'Budget for digital marketing and advertising', 8, 4, 5000.00, 3200.00, 'USD', 'monthly', '2024-02-01', '2024-02-29', 'completed', 1, 5, '2025-10-28 02:45:14', 'Marketing campaign delivered good ROI', 6, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(34, 'BUD-000034', 'Staff Training Budget', 'Budget for employee training and certifications', 4, 3, 12000.00, 8500.00, 'INR', 'quarterly', '2024-02-01', '2024-04-30', 'approved', 1, 13, '2025-09-27 17:56:37', 'Ongoing training program', 12, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(35, 'BUD-000035', 'Maintenance Budget', 'Budget for equipment maintenance and repairs', 4, 2, 8000.00, 4500.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 5, '2024-02-23 23:06:14', 'Preventive maintenance program', 7, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(36, 'BUD-000036', 'New Office Setup Budget', 'Budget for setting up new branch office', 9, 5, 30000.00, 35000.00, 'INR', 'custom', '2024-03-01', '2024-05-31', 'pending', 0, NULL, NULL, 'Over budget due to additional requirements', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(37, 'BUD-000037', 'Research & Development Budget', 'Budget for R&D activities and innovation', 3, 1, 20000.00, 6500.00, 'USD', 'yearly', '2024-01-01', '2024-12-31', 'draft', 0, NULL, NULL, 'Under review by management', 11, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(38, 'BUD-000038', 'Transportation Fleet Budget', 'Budget for vehicle fleet maintenance and fuel', 5, 3, 15000.00, 12000.00, 'USD', 'quarterly', '2024-01-01', '2024-03-31', 'completed', 1, 2, '2025-05-11 14:38:58', 'Q1 transportation costs within budget', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(39, 'BUD-000039', 'Software Licensing Budget', 'Budget for software licenses and subscriptions', 9, 4, 10000.00, 10000.00, 'INR', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 11, '2024-06-03 05:44:25', 'All licenses renewed successfully', 7, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(40, 'BUD-000040', 'Insurance Policy Budget', 'Budget for company insurance policies', 9, 4, 18000.00, 18500.00, 'USD', 'yearly', '2024-01-01', '2024-12-31', 'approved', 1, 1, '2024-07-10 14:45:17', 'Insurance premium slightly over budget', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `budget_categories`
--

CREATE TABLE `budget_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#3B82F6',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_categories`
--

INSERT INTO `budget_categories` (`id`, `name`, `description`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Equipment', 'Solar equipment, panels, inverters, and installation tools', '#3B82F6', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(2, 'Marketing', 'Digital marketing, advertising, and promotional activities', '#10B981', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(3, 'Staff', 'Employee salaries, training, and benefits', '#F59E0B', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(4, 'Transportation', 'Vehicle fleet, fuel, and transportation costs', '#EF4444', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(5, 'Operations', 'Office rent, utilities, and operational expenses', '#8B5CF6', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(6, 'Technology', 'Software licenses, hardware, and IT infrastructure', '#06B6D4', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(7, 'Maintenance', 'Equipment maintenance, repairs, and servicing', '#84CC16', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(8, 'Insurance', 'Company insurance policies and coverage', '#EC4899', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(9, 'Research & Development', 'R&D projects, innovation, and technology advancement', '#6B7280', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(10, 'Administrative', 'Legal fees, accounting, and administrative costs', '#1F2937', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `channel_partners`
--

CREATE TABLE `channel_partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partner_code` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `alternate_phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'India',
  `gst_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `partner_type` enum('distributor','dealer','installer','consultant','other') NOT NULL DEFAULT 'distributor',
  `status` enum('active','inactive','suspended','pending') NOT NULL DEFAULT 'pending',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `outstanding_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `agreement_start_date` date DEFAULT NULL,
  `agreement_end_date` date DEFAULT NULL,
  `specializations` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `bank_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bank_details`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `channel_partners`
--

INSERT INTO `channel_partners` (`id`, `partner_code`, `company_name`, `contact_person`, `email`, `phone`, `alternate_phone`, `address`, `city`, `state`, `pincode`, `country`, `gst_number`, `pan_number`, `website`, `partner_type`, `status`, `commission_rate`, `credit_limit`, `outstanding_amount`, `agreement_start_date`, `agreement_end_date`, `specializations`, `notes`, `bank_details`, `documents`, `assigned_to`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'CP-202512-0001', 'SolarTech Solutions', 'Rajesh Kumar', 'rajesh@solartech.com', '9876543210', '9876543211', '123, MG Road, Sector 15', 'Mumbai', 'Maharashtra', '400001', 'India', '27ABCDE1234F1Z5', 'ABCDE1234F', 'https://solartech.com', 'distributor', 'active', 12.50, 500000.00, 125000.00, '2025-06-11', '2027-06-11', '[\"Residential Solar\",\"Commercial Solar\",\"Maintenance\"]', 'Top performing distributor with excellent customer service.', '{\"account_holder\":\"SolarTech Solutions\",\"account_number\":\"1234567890\",\"ifsc_code\":\"SBIN0001234\",\"bank_name\":\"State Bank of India\"}', '{\"agreement\":\"agreement_solartech.pdf\",\"gst_certificate\":\"gst_solartech.pdf\",\"pan_card\":\"pan_solartech.pdf\"}', 1, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(2, 'CP-202512-0002', 'Green Energy Partners', 'Priya Sharma', 'priya@greenenergy.com', '9876543220', '9876543221', '456, Park Street, Connaught Place', 'New Delhi', 'Delhi', '110001', 'India', '07FGHIJ5678K2L6', 'FGHIJ5678K', 'https://greenenergy.com', 'dealer', 'active', 10.00, 300000.00, 75000.00, '2025-09-11', '2027-09-11', '[\"Residential Solar\",\"Solar Water Heaters\"]', 'Specializes in residential solar installations.', '{\"account_holder\":\"Green Energy Partners\",\"account_number\":\"2345678901\",\"ifsc_code\":\"HDFC0002345\",\"bank_name\":\"HDFC Bank\"}', '{\"agreement\":\"agreement_greenenergy.pdf\",\"gst_certificate\":\"gst_greenenergy.pdf\"}', 1, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(3, 'CP-202512-0003', 'EcoPower Installations', 'Amit Patel', 'amit@ecopower.com', '9876543230', NULL, '789, Brigade Road, Koramangala', 'Bangalore', 'Karnataka', '560034', 'India', '29KLMNO9012P3M7', 'KLMNO9012P', 'https://ecopower.com', 'installer', 'pending', 8.50, 200000.00, 0.00, '2025-12-18', '2027-12-11', '[\"Commercial Solar\",\"Industrial Solar\",\"Installation\"]', 'New installer partner, awaiting final approval.', '{\"account_holder\":\"EcoPower Installations\",\"account_number\":\"3456789012\",\"ifsc_code\":\"ICIC0003456\",\"bank_name\":\"ICICI Bank\"}', '{\"agreement\":\"agreement_ecopower.pdf\",\"gst_certificate\":\"gst_ecopower.pdf\",\"pan_card\":\"pan_ecopower.pdf\"}', 1, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(4, 'CP-202512-0004', 'Solar Consultants India', 'Dr. Sunita Reddy', 'sunita@solarconsultants.com', '9876543240', '9876543241', '321, Anna Salai, Teynampet', 'Chennai', 'Tamil Nadu', '600018', 'India', '33QRSTU3456V4N8', 'QRSTU3456V', 'https://solarconsultants.com', 'consultant', 'active', 15.00, 100000.00, 25000.00, '2024-12-11', '2026-12-11', '[\"Solar Consulting\",\"Project Planning\",\"Technical Support\"]', 'Expert solar consultant with 10+ years experience.', '{\"account_holder\":\"Solar Consultants India\",\"account_number\":\"4567890123\",\"ifsc_code\":\"AXIS0004567\",\"bank_name\":\"Axis Bank\"}', '{\"agreement\":\"agreement_solarconsultants.pdf\",\"gst_certificate\":\"gst_solarconsultants.pdf\"}', 1, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(5, 'CP-202512-0005', 'PowerMax Distributors', 'Vikram Singh', 'vikram@powermax.com', '9876543250', NULL, '654, Salt Lake, Sector V', 'Kolkata', 'West Bengal', '700091', 'India', '19VWXYZ7890W5O9', 'VWXYZ7890W', 'https://powermax.com', 'distributor', 'suspended', 11.00, 400000.00, 200000.00, '2024-06-11', '2025-06-11', '[\"Commercial Solar\",\"Industrial Solar\"]', 'Suspended due to payment issues. Agreement expired.', '{\"account_holder\":\"PowerMax Distributors\",\"account_number\":\"5678901234\",\"ifsc_code\":\"PNB0005678\",\"bank_name\":\"Punjab National Bank\"}', '{\"agreement\":\"agreement_powermax.pdf\",\"gst_certificate\":\"gst_powermax.pdf\"}', 1, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(6, 'CP-202512-0006', 'SunRise Solar', 'Neha Gupta', 'neha@sunrise.com', '9876543260', NULL, '987, MG Road, Banjara Hills', 'Hyderabad', 'Telangana', '500034', 'India', '36ABCD1234E6P1', 'ABCD1234E', 'https://sunrise.com', 'dealer', 'inactive', 9.50, 150000.00, 45000.00, '2025-03-11', '2027-03-11', '[\"Residential Solar\",\"Solar Inverters\"]', 'Temporarily inactive due to business restructuring.', '{\"account_holder\":\"SunRise Solar\",\"account_number\":\"6789012345\",\"ifsc_code\":\"KOTAK0006789\",\"bank_name\":\"Kotak Mahindra Bank\"}', '{\"agreement\":\"agreement_sunrise.pdf\",\"gst_certificate\":\"gst_sunrise.pdf\"}', 1, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commission_number` varchar(255) NOT NULL,
  `channel_partner_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `base_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `commission_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `pending_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','paid','cancelled','disputed') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `commission_number`, `channel_partner_id`, `project_id`, `invoice_id`, `quotation_id`, `reference_type`, `reference_number`, `base_amount`, `commission_rate`, `commission_amount`, `paid_amount`, `pending_amount`, `status`, `payment_status`, `due_date`, `paid_date`, `description`, `notes`, `payment_details`, `documents`, `approved_by`, `approved_at`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'COMM-202512-0001', 1, NULL, NULL, NULL, 'project', 'PROJ-202501-0001', 500000.00, 12.50, 62500.00, 0.00, 62500.00, 'pending', 'unpaid', '2026-01-10', NULL, 'Commission for Solar Installation Project - Residential Complex', 'Commission based on project completion milestone.', NULL, '{\"project_agreement\":\"project_agreement.pdf\",\"commission_letter\":\"commission_letter.pdf\"}', NULL, NULL, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(2, 'COMM-202512-0002', 1, NULL, NULL, NULL, 'invoice', 'INV-202501-0001', 250000.00, 10.00, 25000.00, 25000.00, 0.00, 'paid', 'paid', '2025-11-26', '2025-12-11', 'Commission for Commercial Solar Invoice', 'Commission paid via bank transfer.', '{\"method\":\"Bank Transfer\",\"transaction_id\":\"TXN123456789\",\"notes\":\"Payment processed successfully\",\"paid_at\":\"2025-12-01T14:23:43.901923Z\",\"paid_by\":\"Super Administrator\"}', '{\"invoice_copy\":\"invoice_copy.pdf\",\"payment_receipt\":\"payment_receipt.pdf\"}', 1, '2025-11-21 14:23:43', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(3, 'COMM-202512-0003', 1, NULL, NULL, NULL, 'quotation', 'QT-202501-0001', 750000.00, 8.50, 63750.00, 30000.00, 33750.00, 'approved', 'partial', '2025-12-26', NULL, 'Commission for Industrial Solar Quotation', 'Partial payment made, remaining amount due.', '{\"method\":\"Cheque\",\"transaction_id\":\"CHQ987654321\",\"notes\":\"First installment payment\",\"paid_at\":\"2025-12-06T14:23:43.901962Z\",\"paid_by\":\"Super Administrator\"}', '{\"quotation_copy\":\"quotation_copy.pdf\",\"partial_payment_receipt\":\"partial_payment_receipt.pdf\"}', 1, '2025-12-01 14:23:43', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(4, 'COMM-202512-0004', 1, NULL, NULL, NULL, 'manual', 'MAN-202501-0001', 100000.00, 15.00, 15000.00, 0.00, 15000.00, 'approved', 'unpaid', '2025-12-06', NULL, 'Manual commission entry for special project', 'Special commission for exceptional performance.', NULL, '{\"special_agreement\":\"special_agreement.pdf\"}', 1, '2025-11-21 14:23:43', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(5, 'COMM-202512-0005', 1, NULL, NULL, NULL, 'project', 'PROJ-202501-0002', 300000.00, 11.00, 33000.00, 0.00, 33000.00, 'pending', 'unpaid', '2026-01-25', NULL, 'Commission for School Solar Project', 'Awaiting project completion for commission approval.', NULL, '{\"project_proposal\":\"project_proposal.pdf\"}', NULL, NULL, 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(6, 'COMM-202512-0006', 1, NULL, NULL, NULL, 'invoice', 'INV-202501-0002', 150000.00, 9.50, 14250.00, 0.00, 14250.00, 'disputed', 'unpaid', '2025-12-01', NULL, 'Commission for Residential Solar Invoice', 'Commission disputed due to project quality issues.', NULL, '{\"dispute_letter\":\"dispute_letter.pdf\",\"quality_report\":\"quality_report.pdf\"}', 1, '2025-12-06 14:23:43', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `company_policies`
--

CREATE TABLE `company_policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `policy_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `category` enum('hr_policies','safety_policies','it_policies','financial_policies','operational_policies','quality_policies','environmental_policies','other') NOT NULL DEFAULT 'other',
  `status` enum('draft','active','inactive','archived') NOT NULL DEFAULT 'draft',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `effective_date` date NOT NULL,
  `review_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `version` varchar(255) NOT NULL DEFAULT '1.0',
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `approval_workflow` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`approval_workflow`)),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 0,
  `requires_acknowledgment` tinyint(1) NOT NULL DEFAULT 0,
  `acknowledgment_instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `complaint_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `complaint_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `priority` enum('low','medium','high','critical') NOT NULL,
  `status` enum('open','in_progress','resolved','closed') NOT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `reported_date` date NOT NULL,
  `resolved_date` date DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `complaint_number`, `customer_name`, `customer_email`, `customer_phone`, `project_name`, `complaint_type`, `description`, `priority`, `status`, `assigned_to`, `reported_date`, `resolved_date`, `resolution_notes`, `created_at`, `updated_at`) VALUES
(1, 'COMP-2025-0001', 'ABC Industries', 'contact@abcindustries.com', '+91-9876543210', 'Solar Farm Project', 'Technical Issue', 'Solar panels not generating expected power output', 'high', 'open', 'Rajesh Kumar', '2025-12-09', NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'COMP-2025-0002', 'XYZ Corporation', 'contact@xyzcorp.com', '+91-9876543211', 'Residential Solar', 'Maintenance', 'Inverter showing error code E001', 'medium', 'in_progress', 'Priya Sharma', '2025-12-10', NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'COMP-2025-0003', 'DEF Enterprises', 'contact@defenterprises.com', '+91-9876543212', 'Commercial Solar', 'Billing', 'Incorrect billing for maintenance services', 'low', 'resolved', 'John Doe', '2025-12-06', '2025-12-10', 'Billing corrected and refund processed', '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE `contractors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contractor_code` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `alternate_phone` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'India',
  `contractor_type` enum('individual','company','partnership','subcontractor') NOT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `aadhar_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `experience_description` text DEFAULT NULL,
  `years_of_experience` int(11) NOT NULL DEFAULT 0,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `daily_rate` decimal(10,2) DEFAULT NULL,
  `monthly_rate` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'INR',
  `availability` enum('available','busy','unavailable','on_project') NOT NULL DEFAULT 'available',
  `availability_notes` text DEFAULT NULL,
  `status` enum('active','inactive','suspended','blacklisted') NOT NULL DEFAULT 'active',
  `status_notes` text DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `total_projects` int(11) NOT NULL DEFAULT 0,
  `total_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `certifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certifications`)),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `costings`
--

CREATE TABLE `costings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `costing_number` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `client_phone` varchar(255) DEFAULT NULL,
  `project_description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `material_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `labor_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `equipment_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `transportation_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `overhead_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `profit_margin` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'INR',
  `status` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `validity_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `cost_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cost_breakdown`)),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `costings`
--

INSERT INTO `costings` (`id`, `costing_number`, `project_name`, `project_id`, `client_name`, `client_email`, `client_phone`, `project_description`, `location`, `total_cost`, `material_cost`, `labor_cost`, `equipment_cost`, `transportation_cost`, `overhead_cost`, `profit_margin`, `tax_rate`, `discount`, `currency`, `status`, `validity_date`, `notes`, `cost_breakdown`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 'COST-202512-0001', 'Solar Panel Installation - Residential', 1, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 98765 43210', 'Complete solar panel installation for residential property including panels, inverter, and battery backup system.', 'Delhi, India', 272285.00, 150000.00, 25000.00, 15000.00, 5000.00, 10000.00, 15.00, 18.00, 5000.00, 'INR', 'approved', '2026-01-10', 'High priority project with premium components.', NULL, 4, 1, '2025-11-18 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'COST-202512-0002', 'Commercial Solar Farm Setup', 2, 'Green Energy Solutions Pvt Ltd', 'contact@greenenergy.com', '+91 98765 43211', 'Large scale commercial solar farm installation with 500kW capacity including grid connection.', 'Rajasthan, India', 3846800.00, 2500000.00, 200000.00, 150000.00, 50000.00, 100000.00, 12.00, 18.00, 100000.00, 'INR', 'pending', '2026-01-25', 'Government tender project with strict compliance requirements.', NULL, 11, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'COST-202512-0003', 'Solar Water Heater Installation', 3, 'Priya Sharma', 'priya.sharma@email.com', '+91 98765 43212', 'Solar water heater installation for residential use with 200L capacity.', 'Mumbai, India', 84016.00, 45000.00, 8000.00, 3000.00, 2000.00, 3000.00, 20.00, 18.00, 2000.00, 'INR', 'draft', '2025-12-26', 'Standard residential installation.', NULL, 9, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'COST-202512-0004', 'Industrial Solar Power Plant', 4, 'Industrial Solutions Ltd', 'projects@industrialsolutions.com', '+91 98765 43213', '1MW industrial solar power plant with battery storage and monitoring system.', 'Gujarat, India', 6552540.00, 4500000.00, 300000.00, 200000.00, 80000.00, 150000.00, 10.00, 18.00, 200000.00, 'INR', 'approved', '2026-02-09', 'Large scale industrial project with advanced monitoring.', NULL, 1, 9, '2025-11-26 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'COST-202512-0005', 'Solar Street Light Installation', 5, 'Municipal Corporation', 'projects@municipal.gov.in', '+91 98765 43214', 'Installation of 100 solar street lights across the city with automatic controls.', 'Bangalore, India', 1266376.00, 800000.00, 120000.00, 50000.00, 30000.00, 40000.00, 8.00, 18.00, 50000.00, 'INR', 'pending', '2026-01-10', 'Government project with specific quality standards.', NULL, 3, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'COST-202512-0006', 'Solar Pump Installation', NULL, 'Farmers Cooperative Society', 'info@farmerscoop.com', '+91 98765 43215', 'Solar water pump installation for agricultural irrigation with 5HP capacity.', 'Punjab, India', 322376.00, 180000.00, 25000.00, 12000.00, 8000.00, 15000.00, 18.00, 18.00, 10000.00, 'INR', 'draft', '2025-12-31', 'Agricultural project with subsidy benefits.', NULL, 11, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'COST-202512-0007', 'Solar Panel Installation - Residential', 1, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 98765 43210', 'Complete solar panel installation for residential property including panels, inverter, and battery backup system.', 'Delhi, India', 272285.00, 150000.00, 25000.00, 15000.00, 5000.00, 10000.00, 15.00, 18.00, 5000.00, 'INR', 'approved', '2026-01-10', 'High priority project with premium components.', NULL, 11, 10, '2025-11-14 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(8, 'COST-202512-0008', 'Commercial Solar Farm Setup', 2, 'Green Energy Solutions Pvt Ltd', 'contact@greenenergy.com', '+91 98765 43211', 'Large scale commercial solar farm installation with 500kW capacity including grid connection.', 'Rajasthan, India', 3846800.00, 2500000.00, 200000.00, 150000.00, 50000.00, 100000.00, 12.00, 18.00, 100000.00, 'INR', 'pending', '2026-01-25', 'Government tender project with strict compliance requirements.', NULL, 7, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(9, 'COST-202512-0009', 'Solar Water Heater Installation', 3, 'Priya Sharma', 'priya.sharma@email.com', '+91 98765 43212', 'Solar water heater installation for residential use with 200L capacity.', 'Mumbai, India', 84016.00, 45000.00, 8000.00, 3000.00, 2000.00, 3000.00, 20.00, 18.00, 2000.00, 'INR', 'draft', '2025-12-26', 'Standard residential installation.', NULL, 12, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(10, 'COST-202512-0010', 'Industrial Solar Power Plant', 4, 'Industrial Solutions Ltd', 'projects@industrialsolutions.com', '+91 98765 43213', '1MW industrial solar power plant with battery storage and monitoring system.', 'Gujarat, India', 6552540.00, 4500000.00, 300000.00, 200000.00, 80000.00, 150000.00, 10.00, 18.00, 200000.00, 'INR', 'approved', '2026-02-09', 'Large scale industrial project with advanced monitoring.', NULL, 12, 3, '2025-11-21 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(11, 'COST-202512-0011', 'Solar Street Light Installation', 5, 'Municipal Corporation', 'projects@municipal.gov.in', '+91 98765 43214', 'Installation of 100 solar street lights across the city with automatic controls.', 'Bangalore, India', 1266376.00, 800000.00, 120000.00, 50000.00, 30000.00, 40000.00, 8.00, 18.00, 50000.00, 'INR', 'pending', '2026-01-10', 'Government project with specific quality standards.', NULL, 4, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(12, 'COST-202512-0012', 'Solar Pump Installation', NULL, 'Farmers Cooperative Society', 'info@farmerscoop.com', '+91 98765 43215', 'Solar water pump installation for agricultural irrigation with 5HP capacity.', 'Punjab, India', 322376.00, 180000.00, 25000.00, 12000.00, 8000.00, 15000.00, 18.00, 18.00, 10000.00, 'INR', 'draft', '2025-12-31', 'Agricultural project with subsidy benefits.', NULL, 6, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(13, 'COST-202512-0013', 'Solar Panel Installation - Residential', 1, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 98765 43210', 'Complete solar panel installation for residential property including panels, inverter, and battery backup system.', 'Delhi, India', 272285.00, 150000.00, 25000.00, 15000.00, 5000.00, 10000.00, 15.00, 18.00, 5000.00, 'INR', 'approved', '2026-01-10', 'High priority project with premium components.', NULL, 1, 3, '2025-11-11 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(14, 'COST-202512-0014', 'Commercial Solar Farm Setup', 2, 'Green Energy Solutions Pvt Ltd', 'contact@greenenergy.com', '+91 98765 43211', 'Large scale commercial solar farm installation with 500kW capacity including grid connection.', 'Rajasthan, India', 3846800.00, 2500000.00, 200000.00, 150000.00, 50000.00, 100000.00, 12.00, 18.00, 100000.00, 'INR', 'pending', '2026-01-25', 'Government tender project with strict compliance requirements.', NULL, 12, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(15, 'COST-202512-0015', 'Solar Water Heater Installation', 3, 'Priya Sharma', 'priya.sharma@email.com', '+91 98765 43212', 'Solar water heater installation for residential use with 200L capacity.', 'Mumbai, India', 84016.00, 45000.00, 8000.00, 3000.00, 2000.00, 3000.00, 20.00, 18.00, 2000.00, 'INR', 'draft', '2025-12-26', 'Standard residential installation.', NULL, 10, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(16, 'COST-202512-0016', 'Industrial Solar Power Plant', 4, 'Industrial Solutions Ltd', 'projects@industrialsolutions.com', '+91 98765 43213', '1MW industrial solar power plant with battery storage and monitoring system.', 'Gujarat, India', 6552540.00, 4500000.00, 300000.00, 200000.00, 80000.00, 150000.00, 10.00, 18.00, 200000.00, 'INR', 'approved', '2026-02-09', 'Large scale industrial project with advanced monitoring.', NULL, 7, 6, '2025-11-18 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(17, 'COST-202512-0017', 'Solar Street Light Installation', 5, 'Municipal Corporation', 'projects@municipal.gov.in', '+91 98765 43214', 'Installation of 100 solar street lights across the city with automatic controls.', 'Bangalore, India', 1266376.00, 800000.00, 120000.00, 50000.00, 30000.00, 40000.00, 8.00, 18.00, 50000.00, 'INR', 'pending', '2026-01-10', 'Government project with specific quality standards.', NULL, 6, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(18, 'COST-202512-0018', 'Solar Pump Installation', NULL, 'Farmers Cooperative Society', 'info@farmerscoop.com', '+91 98765 43215', 'Solar water pump installation for agricultural irrigation with 5HP capacity.', 'Punjab, India', 322376.00, 180000.00, 25000.00, 12000.00, 8000.00, 15000.00, 18.00, 18.00, 10000.00, 'INR', 'draft', '2025-12-31', 'Agricultural project with subsidy benefits.', NULL, 13, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(19, 'COST-202512-0019', 'Solar Panel Installation - Residential', 1, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 98765 43210', 'Complete solar panel installation for residential property including panels, inverter, and battery backup system.', 'Delhi, India', 272285.00, 150000.00, 25000.00, 15000.00, 5000.00, 10000.00, 15.00, 18.00, 5000.00, 'INR', 'approved', '2026-01-10', 'High priority project with premium components.', NULL, 4, 10, '2025-11-11 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(20, 'COST-202512-0020', 'Commercial Solar Farm Setup', 2, 'Green Energy Solutions Pvt Ltd', 'contact@greenenergy.com', '+91 98765 43211', 'Large scale commercial solar farm installation with 500kW capacity including grid connection.', 'Rajasthan, India', 3846800.00, 2500000.00, 200000.00, 150000.00, 50000.00, 100000.00, 12.00, 18.00, 100000.00, 'INR', 'pending', '2026-01-25', 'Government tender project with strict compliance requirements.', NULL, 4, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(21, 'COST-202512-0021', 'Solar Water Heater Installation', 3, 'Priya Sharma', 'priya.sharma@email.com', '+91 98765 43212', 'Solar water heater installation for residential use with 200L capacity.', 'Mumbai, India', 84016.00, 45000.00, 8000.00, 3000.00, 2000.00, 3000.00, 20.00, 18.00, 2000.00, 'INR', 'draft', '2025-12-26', 'Standard residential installation.', NULL, 12, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(22, 'COST-202512-0022', 'Industrial Solar Power Plant', 4, 'Industrial Solutions Ltd', 'projects@industrialsolutions.com', '+91 98765 43213', '1MW industrial solar power plant with battery storage and monitoring system.', 'Gujarat, India', 6552540.00, 4500000.00, 300000.00, 200000.00, 80000.00, 150000.00, 10.00, 18.00, 200000.00, 'INR', 'approved', '2026-02-09', 'Large scale industrial project with advanced monitoring.', NULL, 9, 8, '2025-12-03 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(23, 'COST-202512-0023', 'Solar Street Light Installation', 5, 'Municipal Corporation', 'projects@municipal.gov.in', '+91 98765 43214', 'Installation of 100 solar street lights across the city with automatic controls.', 'Bangalore, India', 1266376.00, 800000.00, 120000.00, 50000.00, 30000.00, 40000.00, 8.00, 18.00, 50000.00, 'INR', 'pending', '2026-01-10', 'Government project with specific quality standards.', NULL, 7, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(24, 'COST-202512-0024', 'Solar Pump Installation', NULL, 'Farmers Cooperative Society', 'info@farmerscoop.com', '+91 98765 43215', 'Solar water pump installation for agricultural irrigation with 5HP capacity.', 'Punjab, India', 322376.00, 180000.00, 25000.00, 12000.00, 8000.00, 15000.00, 18.00, 18.00, 10000.00, 'INR', 'draft', '2025-12-31', 'Agricultural project with subsidy benefits.', NULL, 1, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(25, 'COST-202512-0025', 'Solar Panel Installation - Residential', 1, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 98765 43210', 'Complete solar panel installation for residential property including panels, inverter, and battery backup system.', 'Delhi, India', 272285.00, 150000.00, 25000.00, 15000.00, 5000.00, 10000.00, 15.00, 18.00, 5000.00, 'INR', 'approved', '2026-01-10', 'High priority project with premium components.', NULL, 4, 4, '2025-11-17 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(26, 'COST-202512-0026', 'Commercial Solar Farm Setup', 2, 'Green Energy Solutions Pvt Ltd', 'contact@greenenergy.com', '+91 98765 43211', 'Large scale commercial solar farm installation with 500kW capacity including grid connection.', 'Rajasthan, India', 3846800.00, 2500000.00, 200000.00, 150000.00, 50000.00, 100000.00, 12.00, 18.00, 100000.00, 'INR', 'pending', '2026-01-25', 'Government tender project with strict compliance requirements.', NULL, 6, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(27, 'COST-202512-0027', 'Solar Water Heater Installation', 3, 'Priya Sharma', 'priya.sharma@email.com', '+91 98765 43212', 'Solar water heater installation for residential use with 200L capacity.', 'Mumbai, India', 84016.00, 45000.00, 8000.00, 3000.00, 2000.00, 3000.00, 20.00, 18.00, 2000.00, 'INR', 'draft', '2025-12-26', 'Standard residential installation.', NULL, 12, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(28, 'COST-202512-0028', 'Industrial Solar Power Plant', 4, 'Industrial Solutions Ltd', 'projects@industrialsolutions.com', '+91 98765 43213', '1MW industrial solar power plant with battery storage and monitoring system.', 'Gujarat, India', 6552540.00, 4500000.00, 300000.00, 200000.00, 80000.00, 150000.00, 10.00, 18.00, 200000.00, 'INR', 'approved', '2026-02-09', 'Large scale industrial project with advanced monitoring.', NULL, 5, 9, '2025-12-07 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(29, 'COST-202512-0029', 'Solar Street Light Installation', 5, 'Municipal Corporation', 'projects@municipal.gov.in', '+91 98765 43214', 'Installation of 100 solar street lights across the city with automatic controls.', 'Bangalore, India', 1266376.00, 800000.00, 120000.00, 50000.00, 30000.00, 40000.00, 8.00, 18.00, 50000.00, 'INR', 'pending', '2026-01-10', 'Government project with specific quality standards.', NULL, 4, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(30, 'COST-202512-0030', 'Solar Pump Installation', NULL, 'Farmers Cooperative Society', 'info@farmerscoop.com', '+91 98765 43215', 'Solar water pump installation for agricultural irrigation with 5HP capacity.', 'Punjab, India', 322376.00, 180000.00, 25000.00, 12000.00, 8000.00, 15000.00, 18.00, 18.00, 10000.00, 'INR', 'draft', '2025-12-31', 'Agricultural project with subsidy benefits.', NULL, 3, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `daily_progress_reports`
--

CREATE TABLE `daily_progress_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `weather_condition` enum('sunny','cloudy','rainy','stormy','foggy') NOT NULL,
  `work_performed` text NOT NULL,
  `work_hours` decimal(5,2) NOT NULL,
  `workers_present` int(11) NOT NULL,
  `materials_used` text DEFAULT NULL,
  `equipment_used` text DEFAULT NULL,
  `challenges_faced` text DEFAULT NULL,
  `next_day_plan` text DEFAULT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_progress_reports`
--

INSERT INTO `daily_progress_reports` (`id`, `project_id`, `report_date`, `weather_condition`, `work_performed`, `work_hours`, `workers_present`, `materials_used`, `equipment_used`, `challenges_faced`, `next_day_plan`, `photos`, `status`, `submitted_by`, `approved_by`, `approved_at`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 4, '2025-12-06', 'sunny', 'Completed foundation work for Block A. Installed rebar reinforcement and poured concrete foundation. Quality check passed for all sections.', 8.50, 12, 'Concrete (50 bags), Rebar (200 kg), Cement (25 bags), Sand (2 tons)', 'Concrete mixer, Crane, Vibrator, Measuring tools', 'Heavy rain in the morning delayed start by 2 hours. Had to cover work area with tarpaulin.', 'Continue with foundation work for Block B. Start wall construction for Block A.', NULL, 'approved', 1, 1, '2025-12-07 14:22:41', 'Good progress despite weather challenges. Quality maintained.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 3, '2025-12-07', 'cloudy', 'Started electrical wiring installation. Completed main distribution panel setup and began cable laying for ground floor.', 7.00, 8, 'Electrical cables (500m), Switches (50), Outlets (30), Distribution panel (1)', 'Cable pulling machine, Multimeter, Wire strippers', 'Some cable lengths were shorter than required. Had to order additional materials.', 'Complete ground floor wiring. Start first floor electrical work.', NULL, 'pending', 3, NULL, NULL, 'Material shortage issue needs to be addressed.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 3, '2025-12-08', 'rainy', 'Plumbing installation for bathrooms completed. Installed water supply lines and drainage systems for all units.', 6.50, 6, 'PVC pipes (200m), Fittings (100), Valves (20), Water meters (10)', 'Pipe cutter, Welding machine, Pressure tester', 'Rain made outdoor work difficult. Had to focus on indoor plumbing only.', 'Test all plumbing systems. Start bathroom fixture installation.', NULL, 'approved', 1, 1, '2025-12-09 14:22:41', 'Excellent work quality. All pressure tests passed.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 3, '2025-12-09', 'sunny', 'Painting work started for completed rooms. Applied primer coat to walls and ceilings. Started color painting for bedrooms.', 8.00, 10, 'Primer paint (20 liters), Color paint (30 liters), Brushes (15), Rollers (10)', 'Paint sprayer, Ladders, Drop cloths', 'Some wall surfaces needed additional preparation before painting.', 'Complete bedroom painting. Start living room and kitchen painting.', NULL, 'pending', 1, NULL, NULL, 'Wall preparation took longer than expected.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 4, '2025-12-10', 'sunny', 'Flooring installation completed for ground floor. Installed ceramic tiles in bathrooms and marble flooring in living areas.', 9.00, 15, 'Ceramic tiles (500 sq ft), Marble tiles (300 sq ft), Adhesive (50 kg), Grout (20 kg)', 'Tile cutter, Level, Spacers, Grout float', 'Some tiles had size variations. Had to sort and match carefully.', 'Start first floor flooring. Complete tile work in remaining bathrooms.', NULL, 'approved', 2, 2, '2025-12-11 02:22:41', 'Outstanding work quality. Flooring looks excellent.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 3, '2025-12-11', 'cloudy', 'Started HVAC installation. Installed main air conditioning units and began ductwork installation for ground floor.', 7.50, 9, 'AC units (5), Ductwork (100m), Insulation (50m), Mounting brackets (20)', 'Drill machine, Duct cutter, Measuring tape', 'Some mounting locations needed structural modifications.', 'Complete ground floor ductwork. Start first floor HVAC installation.', NULL, 'pending', 2, NULL, NULL, 'Structural modifications required for proper installation.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 5, '2025-12-05', 'stormy', 'Site preparation and excavation work. Cleared site area and started foundation excavation.', 6.00, 8, 'Excavation equipment rental, Safety barriers, Marking paint', 'Excavator, Dump truck, Safety equipment', 'Heavy storm caused delays. Site became muddy and unsafe.', 'Complete excavation work. Start foundation preparation.', NULL, 'rejected', 3, 1, '2025-12-06 14:22:41', 'Work quality below standards due to weather conditions. Need to redo excavation.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 3, '2025-12-04', 'foggy', 'Roofing work completed for main building. Installed waterproofing membrane and started tile installation.', 8.50, 12, 'Roof tiles (1000 sq ft), Waterproofing membrane (500 sq ft), Adhesive (100 kg)', 'Crane, Tile cutter, Safety harnesses', 'Fog reduced visibility. Had to work more carefully.', 'Complete roof tile installation. Start gutter installation.', NULL, 'approved', 2, 2, '2025-12-05 14:22:41', 'Good progress despite visibility issues. Safety maintained.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 1, '2025-12-03', 'sunny', 'Landscaping work started. Prepared soil and began planting trees and shrubs around the building.', 6.50, 6, 'Soil (5 tons), Trees (20), Shrubs (50), Fertilizer (100 kg)', 'Shovels, Wheelbarrow, Watering system', 'Some plants were damaged during transport.', 'Complete tree planting. Start lawn preparation.', NULL, 'pending', 3, NULL, NULL, 'Need to replace damaged plants.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 2, '2025-12-02', 'cloudy', 'Final inspection and quality check completed. All systems tested and approved for handover.', 8.00, 15, 'Testing equipment, Documentation materials', 'Multimeter, Pressure tester, Inspection tools', 'Minor issues found in electrical system. Fixed immediately.', 'Prepare handover documentation. Schedule client inspection.', NULL, 'approved', 3, 2, '2025-12-03 14:22:41', 'Project completed successfully. Ready for handover.', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 1, '2025-12-06', 'sunny', 'Completed foundation work for Block A. Installed rebar reinforcement and poured concrete foundation. Quality check passed for all sections.', 8.50, 12, 'Concrete (50 bags), Rebar (200 kg), Cement (25 bags), Sand (2 tons)', 'Concrete mixer, Crane, Vibrator, Measuring tools', 'Heavy rain in the morning delayed start by 2 hours. Had to cover work area with tarpaulin.', 'Continue with foundation work for Block B. Start wall construction for Block A.', NULL, 'approved', 1, 1, '2025-12-07 14:23:22', 'Good progress despite weather challenges. Quality maintained.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(12, 5, '2025-12-07', 'cloudy', 'Started electrical wiring installation. Completed main distribution panel setup and began cable laying for ground floor.', 7.00, 8, 'Electrical cables (500m), Switches (50), Outlets (30), Distribution panel (1)', 'Cable pulling machine, Multimeter, Wire strippers', 'Some cable lengths were shorter than required. Had to order additional materials.', 'Complete ground floor wiring. Start first floor electrical work.', NULL, 'pending', 2, NULL, NULL, 'Material shortage issue needs to be addressed.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(13, 4, '2025-12-08', 'rainy', 'Plumbing installation for bathrooms completed. Installed water supply lines and drainage systems for all units.', 6.50, 6, 'PVC pipes (200m), Fittings (100), Valves (20), Water meters (10)', 'Pipe cutter, Welding machine, Pressure tester', 'Rain made outdoor work difficult. Had to focus on indoor plumbing only.', 'Test all plumbing systems. Start bathroom fixture installation.', NULL, 'approved', 3, 2, '2025-12-09 14:23:22', 'Excellent work quality. All pressure tests passed.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(14, 1, '2025-12-09', 'sunny', 'Painting work started for completed rooms. Applied primer coat to walls and ceilings. Started color painting for bedrooms.', 8.00, 10, 'Primer paint (20 liters), Color paint (30 liters), Brushes (15), Rollers (10)', 'Paint sprayer, Ladders, Drop cloths', 'Some wall surfaces needed additional preparation before painting.', 'Complete bedroom painting. Start living room and kitchen painting.', NULL, 'pending', 3, NULL, NULL, 'Wall preparation took longer than expected.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(15, 1, '2025-12-10', 'sunny', 'Flooring installation completed for ground floor. Installed ceramic tiles in bathrooms and marble flooring in living areas.', 9.00, 15, 'Ceramic tiles (500 sq ft), Marble tiles (300 sq ft), Adhesive (50 kg), Grout (20 kg)', 'Tile cutter, Level, Spacers, Grout float', 'Some tiles had size variations. Had to sort and match carefully.', 'Start first floor flooring. Complete tile work in remaining bathrooms.', NULL, 'approved', 3, 2, '2025-12-11 02:23:22', 'Outstanding work quality. Flooring looks excellent.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(16, 4, '2025-12-11', 'cloudy', 'Started HVAC installation. Installed main air conditioning units and began ductwork installation for ground floor.', 7.50, 9, 'AC units (5), Ductwork (100m), Insulation (50m), Mounting brackets (20)', 'Drill machine, Duct cutter, Measuring tape', 'Some mounting locations needed structural modifications.', 'Complete ground floor ductwork. Start first floor HVAC installation.', NULL, 'pending', 3, NULL, NULL, 'Structural modifications required for proper installation.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(17, 1, '2025-12-05', 'stormy', 'Site preparation and excavation work. Cleared site area and started foundation excavation.', 6.00, 8, 'Excavation equipment rental, Safety barriers, Marking paint', 'Excavator, Dump truck, Safety equipment', 'Heavy storm caused delays. Site became muddy and unsafe.', 'Complete excavation work. Start foundation preparation.', NULL, 'rejected', 3, 3, '2025-12-06 14:23:22', 'Work quality below standards due to weather conditions. Need to redo excavation.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(18, 5, '2025-12-04', 'foggy', 'Roofing work completed for main building. Installed waterproofing membrane and started tile installation.', 8.50, 12, 'Roof tiles (1000 sq ft), Waterproofing membrane (500 sq ft), Adhesive (100 kg)', 'Crane, Tile cutter, Safety harnesses', 'Fog reduced visibility. Had to work more carefully.', 'Complete roof tile installation. Start gutter installation.', NULL, 'approved', 2, 2, '2025-12-05 14:23:22', 'Good progress despite visibility issues. Safety maintained.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(19, 4, '2025-12-03', 'sunny', 'Landscaping work started. Prepared soil and began planting trees and shrubs around the building.', 6.50, 6, 'Soil (5 tons), Trees (20), Shrubs (50), Fertilizer (100 kg)', 'Shovels, Wheelbarrow, Watering system', 'Some plants were damaged during transport.', 'Complete tree planting. Start lawn preparation.', NULL, 'pending', 1, NULL, NULL, 'Need to replace damaged plants.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(20, 3, '2025-12-02', 'cloudy', 'Final inspection and quality check completed. All systems tested and approved for handover.', 8.00, 15, 'Testing equipment, Documentation materials', 'Multimeter, Pressure tester, Inspection tools', 'Minor issues found in electrical system. Fixed immediately.', 'Prepare handover documentation. Schedule client inspection.', NULL, 'approved', 2, 1, '2025-12-03 14:23:22', 'Project completed successfully. Ready for handover.', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(21, 4, '2025-12-06', 'sunny', 'Completed foundation work for Block A. Installed rebar reinforcement and poured concrete foundation. Quality check passed for all sections.', 8.50, 12, 'Concrete (50 bags), Rebar (200 kg), Cement (25 bags), Sand (2 tons)', 'Concrete mixer, Crane, Vibrator, Measuring tools', 'Heavy rain in the morning delayed start by 2 hours. Had to cover work area with tarpaulin.', 'Continue with foundation work for Block B. Start wall construction for Block A.', NULL, 'approved', 1, 3, '2025-12-07 14:23:30', 'Good progress despite weather challenges. Quality maintained.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(22, 2, '2025-12-07', 'cloudy', 'Started electrical wiring installation. Completed main distribution panel setup and began cable laying for ground floor.', 7.00, 8, 'Electrical cables (500m), Switches (50), Outlets (30), Distribution panel (1)', 'Cable pulling machine, Multimeter, Wire strippers', 'Some cable lengths were shorter than required. Had to order additional materials.', 'Complete ground floor wiring. Start first floor electrical work.', NULL, 'pending', 3, NULL, NULL, 'Material shortage issue needs to be addressed.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(23, 4, '2025-12-08', 'rainy', 'Plumbing installation for bathrooms completed. Installed water supply lines and drainage systems for all units.', 6.50, 6, 'PVC pipes (200m), Fittings (100), Valves (20), Water meters (10)', 'Pipe cutter, Welding machine, Pressure tester', 'Rain made outdoor work difficult. Had to focus on indoor plumbing only.', 'Test all plumbing systems. Start bathroom fixture installation.', NULL, 'approved', 1, 2, '2025-12-09 14:23:30', 'Excellent work quality. All pressure tests passed.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(24, 3, '2025-12-09', 'sunny', 'Painting work started for completed rooms. Applied primer coat to walls and ceilings. Started color painting for bedrooms.', 8.00, 10, 'Primer paint (20 liters), Color paint (30 liters), Brushes (15), Rollers (10)', 'Paint sprayer, Ladders, Drop cloths', 'Some wall surfaces needed additional preparation before painting.', 'Complete bedroom painting. Start living room and kitchen painting.', NULL, 'pending', 1, NULL, NULL, 'Wall preparation took longer than expected.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(25, 3, '2025-12-10', 'sunny', 'Flooring installation completed for ground floor. Installed ceramic tiles in bathrooms and marble flooring in living areas.', 9.00, 15, 'Ceramic tiles (500 sq ft), Marble tiles (300 sq ft), Adhesive (50 kg), Grout (20 kg)', 'Tile cutter, Level, Spacers, Grout float', 'Some tiles had size variations. Had to sort and match carefully.', 'Start first floor flooring. Complete tile work in remaining bathrooms.', NULL, 'approved', 2, 1, '2025-12-11 02:23:30', 'Outstanding work quality. Flooring looks excellent.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(26, 3, '2025-12-11', 'cloudy', 'Started HVAC installation. Installed main air conditioning units and began ductwork installation for ground floor.', 7.50, 9, 'AC units (5), Ductwork (100m), Insulation (50m), Mounting brackets (20)', 'Drill machine, Duct cutter, Measuring tape', 'Some mounting locations needed structural modifications.', 'Complete ground floor ductwork. Start first floor HVAC installation.', NULL, 'pending', 1, NULL, NULL, 'Structural modifications required for proper installation.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(27, 3, '2025-12-05', 'stormy', 'Site preparation and excavation work. Cleared site area and started foundation excavation.', 6.00, 8, 'Excavation equipment rental, Safety barriers, Marking paint', 'Excavator, Dump truck, Safety equipment', 'Heavy storm caused delays. Site became muddy and unsafe.', 'Complete excavation work. Start foundation preparation.', NULL, 'rejected', 2, 3, '2025-12-06 14:23:30', 'Work quality below standards due to weather conditions. Need to redo excavation.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(28, 5, '2025-12-04', 'foggy', 'Roofing work completed for main building. Installed waterproofing membrane and started tile installation.', 8.50, 12, 'Roof tiles (1000 sq ft), Waterproofing membrane (500 sq ft), Adhesive (100 kg)', 'Crane, Tile cutter, Safety harnesses', 'Fog reduced visibility. Had to work more carefully.', 'Complete roof tile installation. Start gutter installation.', NULL, 'approved', 3, 3, '2025-12-05 14:23:30', 'Good progress despite visibility issues. Safety maintained.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(29, 1, '2025-12-03', 'sunny', 'Landscaping work started. Prepared soil and began planting trees and shrubs around the building.', 6.50, 6, 'Soil (5 tons), Trees (20), Shrubs (50), Fertilizer (100 kg)', 'Shovels, Wheelbarrow, Watering system', 'Some plants were damaged during transport.', 'Complete tree planting. Start lawn preparation.', NULL, 'pending', 3, NULL, NULL, 'Need to replace damaged plants.', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(30, 5, '2025-12-02', 'cloudy', 'Final inspection and quality check completed. All systems tested and approved for handover.', 8.00, 15, 'Testing equipment, Documentation materials', 'Multimeter, Pressure tester, Inspection tools', 'Minor issues found in electrical system. Fixed immediately.', 'Prepare handover documentation. Schedule client inspection.', NULL, 'approved', 1, 1, '2025-12-03 14:23:30', 'Project completed successfully. Ready for handover.', '2025-12-11 14:23:30', '2025-12-11 14:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `delete_approvals`
--

CREATE TABLE `delete_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `model_name` varchar(255) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `model_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`model_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delete_approvals`
--

INSERT INTO `delete_approvals` (`id`, `model_type`, `model_id`, `requested_by`, `model_name`, `reason`, `status`, `approved_by`, `approved_at`, `rejection_reason`, `model_data`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Quotation', 2, 4, 'Quotation: QUOT-2024-002', '12345678909876', 'approved', 1, '2025-12-11 14:25:26', NULL, '{\"id\":2,\"parent_quotation_id\":null,\"revision_number\":0,\"is_revision\":false,\"is_latest\":true,\"quotation_number\":\"QUOT-2024-002\",\"quotation_type\":null,\"quotation_date\":\"2025-11-30T18:30:00.000000Z\",\"valid_until\":\"2025-12-30T18:30:00.000000Z\",\"follow_up_date\":null,\"client_id\":2,\"project_id\":2,\"subtotal\":\"750000.00\",\"tax_amount\":\"135000.00\",\"total_amount\":\"885000.00\",\"status\":\"accepted\",\"notes\":\"Commercial solar installation for office building with net metering setup.\",\"terms_conditions\":\"Payment terms: 30% advance, 40% on delivery, 30% on completion. Installation timeline: 4-6 weeks.\",\"created_by\":1,\"created_at\":\"2025-12-11T14:23:43.000000Z\",\"updated_at\":\"2025-12-11T14:23:43.000000Z\",\"last_modified_at\":null,\"channel_partner_id\":null}', '2025-12-11 14:24:58', '2025-12-11 14:25:26'),
(2, 'App\\Models\\LeaveRequest', 1, 13, 'Leave Request #1 (Sick Leave - 13 Dec, 2025)', 'f', 'pending', NULL, NULL, NULL, '{\"employee_id\":\"EMP001\",\"leave_type\":\"Sick Leave\",\"start_date\":\"2025-12-13\",\"end_date\":\"2025-12-14\",\"total_days\":2,\"reason\":\"Medical appointment\",\"status\":\"pending\"}', '2026-01-02 06:38:12', '2026-01-02 06:38:12'),
(3, 'App\\Models\\Quotation', 1, 4, 'Quotation: QUOT-2024-001', 'werfgfwfgwefgefg', 'pending', NULL, NULL, NULL, '{\"id\":1,\"parent_quotation_id\":null,\"revision_number\":0,\"is_revision\":false,\"is_latest\":true,\"quotation_number\":\"QUOT-2024-001\",\"quotation_type\":null,\"quotation_date\":\"2025-11-25T18:30:00.000000Z\",\"valid_until\":\"2025-12-25T18:30:00.000000Z\",\"follow_up_date\":null,\"client_id\":1,\"project_id\":1,\"subtotal\":\"500000.00\",\"tax_amount\":\"90000.00\",\"total_amount\":\"590000.00\",\"status\":\"approved\",\"notes\":\"Complete solar panel installation package including panels, inverters, and monitoring system.\",\"terms_conditions\":\"Payment terms: 50% advance, 50% on completion. Warranty: 5 years on panels, 2 years on inverters.\",\"created_by\":1,\"created_at\":\"2025-12-11T14:23:43.000000Z\",\"updated_at\":\"2026-01-04T09:24:53.000000Z\",\"last_modified_at\":\"2026-01-04T09:24:53.000000Z\",\"channel_partner_id\":null}', '2026-01-04 09:27:01', '2026-01-04 09:27:01');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `category` enum('proposal','contract','invoice','quotation','report','presentation','technical_spec','other') NOT NULL DEFAULT 'other',
  `status` enum('draft','active','archived','deleted') NOT NULL DEFAULT 'active',
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `expiry_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `description`, `file_name`, `file_path`, `file_type`, `file_size`, `category`, `status`, `lead_id`, `project_id`, `created_by`, `tags`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:21:29', '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(2, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:21:29', '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(3, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(4, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(5, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(6, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:21:29', '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(7, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(12, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(13, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:22:47', '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(14, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:22:47', '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(15, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(16, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(17, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(18, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:22:47', '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(19, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:22:56', '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(20, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:22:56', '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(21, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(22, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(23, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(24, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:22:56', '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(25, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(26, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(27, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(28, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(29, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(30, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(31, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(32, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(33, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(34, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(35, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(36, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(37, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(38, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(39, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(40, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(41, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(42, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(43, 'Solar Panel Installation Proposal', 'Detailed proposal for residential solar panel installation including technical specifications and pricing.', 'solar_proposal_2024.pdf', 'documents/sample_proposal.pdf', 'application/pdf', 2048576, 'proposal', 'active', 1, 1, 1, '[\"solar\",\"proposal\",\"residential\"]', '2026-01-10 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(44, 'Service Agreement Contract', 'Standard service agreement contract for solar panel maintenance and support.', 'service_agreement.docx', 'documents/service_contract.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1536000, 'contract', 'active', 2, NULL, 1, '[\"contract\",\"service\",\"maintenance\"]', '2026-12-11 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(45, 'Technical Specifications Document', 'Detailed technical specifications for commercial solar power system.', 'tech_specs_v2.pdf', 'documents/technical_specs.pdf', 'application/pdf', 5120000, 'technical_spec', 'active', NULL, 2, 2, '[\"technical\",\"specifications\",\"commercial\"]', NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(46, 'Monthly Performance Report', 'Monthly performance report showing energy generation and system efficiency.', 'performance_report_jan2024.xlsx', 'documents/performance_report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1024000, 'report', 'active', NULL, 3, 1, '[\"report\",\"performance\",\"monthly\"]', NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(47, 'Client Presentation Deck', 'PowerPoint presentation for client meeting showcasing our solar solutions.', 'client_presentation.pptx', 'documents/client_presentation.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 8192000, 'presentation', 'draft', 3, NULL, 2, '[\"presentation\",\"client\",\"solar\"]', NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(48, 'Warranty Certificate', 'Warranty certificate for installed solar panels and equipment.', 'warranty_certificate.pdf', 'documents/warranty.pdf', 'application/pdf', 512000, 'other', 'active', NULL, 4, 1, '[\"warranty\",\"certificate\",\"equipment\"]', '2030-12-10 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `joining_date` date NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `employment_type` enum('full_time','part_time','contract','intern') NOT NULL,
  `status` enum('active','inactive','terminated') NOT NULL,
  `emergency_contact` varchar(255) NOT NULL,
  `emergency_phone` varchar(255) NOT NULL,
  `skills` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `address`, `department`, `designation`, `joining_date`, `salary`, `employment_type`, `status`, `emergency_contact`, `emergency_phone`, `skills`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'Rajesh', 'Kumar', 'rajesh@company.com', '+91-9876543210', '1985-05-15', 'Mumbai, Maharashtra', 'Operations', 'Senior Technician', '2024-12-11', 45000.00, 'full_time', 'active', 'Mrs. Kumar', '+91-9876543211', 'Solar Installation, Maintenance, Troubleshooting', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', 'Priya', 'Sharma', 'priya@company.com', '+91-9876543212', '1990-08-22', 'Delhi, NCR', 'Engineering', 'Project Engineer', '2025-04-11', 55000.00, 'full_time', 'active', 'Mr. Sharma', '+91-9876543213', 'Project Management, Solar Design, AutoCAD', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', 'John', 'Doe', 'john@company.com', '+91-9876543214', '1988-12-10', 'Bangalore, Karnataka', 'Sales', 'Sales Manager', '2025-06-11', 60000.00, 'full_time', 'active', 'Mrs. Doe', '+91-9876543215', 'Sales, Customer Relations, Solar Products', '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `escalations`
--

CREATE TABLE `escalations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `escalation_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('complaint','issue','request','incident','other') NOT NULL DEFAULT 'complaint',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed','cancelled') NOT NULL DEFAULT 'open',
  `category` enum('technical','billing','service','support','general') NOT NULL DEFAULT 'general',
  `related_lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_quotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_commission_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `escalated_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `escalation_level` int(11) NOT NULL DEFAULT 1,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `requires_response` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `escalations`
--

INSERT INTO `escalations` (`id`, `escalation_number`, `title`, `description`, `type`, `priority`, `status`, `category`, `related_lead_id`, `related_project_id`, `related_invoice_id`, `related_quotation_id`, `related_commission_id`, `assigned_to`, `escalated_to`, `created_by`, `customer_name`, `customer_email`, `customer_phone`, `due_date`, `resolved_at`, `closed_at`, `resolution_notes`, `internal_notes`, `attachments`, `tags`, `escalation_level`, `is_urgent`, `requires_response`, `created_at`, `updated_at`) VALUES
(1, 'ESC2025120001', 'Solar Panel Installation Delay', 'Customer is complaining about the delay in solar panel installation. The project was supposed to be completed last week but is still ongoing.', 'complaint', 'high', 'open', 'service', 24, 1, 6, 1, NULL, 10, NULL, 1, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 9876543210', '2025-12-13 19:52:41', NULL, NULL, NULL, 'Customer is VIP client, needs immediate attention.', NULL, '[\"installation\",\"delay\",\"vip\"]', 1, 1, 1, '2025-12-04 14:22:41', '2025-12-11 14:22:41'),
(2, 'ESC2025120002', 'Billing Discrepancy', 'Customer received an invoice with incorrect amount. The billing team needs to review and correct the invoice.', 'issue', 'medium', 'open', 'billing', 42, 1, 2, 1, NULL, 12, NULL, 3, 'Priya Sharma', 'priya.sharma@email.com', '+91 9876543211', '2025-12-12 19:52:41', NULL, NULL, NULL, 'Check invoice calculation and update customer.', NULL, '[\"billing\",\"invoice\",\"calculation\"]', 1, 0, 1, '2025-12-06 14:22:41', '2025-12-11 14:22:41'),
(3, 'ESC2025120003', 'Technical Support Request', 'Customer needs technical support for their solar inverter. The inverter is not working properly after installation.', 'request', 'high', 'in_progress', 'technical', 33, 4, 3, 5, NULL, 4, NULL, 2, 'Amit Patel', 'amit.patel@email.com', '+91 9876543212', '2025-12-12 19:52:41', NULL, NULL, NULL, 'Send technical team to customer site.', NULL, '[\"technical\",\"inverter\",\"support\"]', 1, 1, 1, '2025-11-15 14:22:41', '2025-12-11 14:22:41'),
(4, 'ESC2025120004', 'Commission Payment Issue', 'Channel partner is complaining about delayed commission payment. Payment was due last month.', 'complaint', 'medium', 'in_progress', 'billing', 8, 3, 6, 3, NULL, 7, NULL, 8, 'Sunil Gupta', 'sunil.gupta@email.com', '+91 9876543213', '2025-12-14 19:52:41', NULL, NULL, NULL, 'Check commission calculation and process payment.', NULL, '[\"commission\",\"payment\",\"channel-partner\"]', 1, 0, 1, '2025-12-04 14:22:41', '2025-12-11 14:22:41'),
(5, 'ESC2025120005', 'Project Timeline Extension', 'Customer is requesting extension of project timeline due to weather conditions.', 'request', 'low', 'resolved', 'service', 50, 1, 4, 4, NULL, 9, NULL, 4, 'Neha Singh', 'neha.singh@email.com', '+91 9876543214', '2025-12-16 19:52:41', '2025-12-03 19:52:41', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Review project timeline and update customer.', NULL, '[\"timeline\",\"extension\",\"weather\"]', 1, 0, 1, '2025-11-17 14:22:41', '2025-12-11 14:22:41'),
(6, 'ESC2025120006', 'System Performance Issue', 'Solar system is not generating expected power output. Customer is concerned about ROI.', 'incident', 'critical', 'resolved', 'technical', 31, 1, 5, NULL, NULL, 12, NULL, 8, 'Vikram Mehta', 'vikram.mehta@email.com', '+91 9876543215', '2025-12-12 19:52:41', '2025-12-10 19:52:41', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Critical issue - send senior technician immediately.', NULL, '[\"performance\",\"power-output\",\"roi\"]', 1, 1, 1, '2025-12-04 14:22:41', '2025-12-11 14:22:41'),
(7, 'ESC2025120007', 'Solar Panel Installation Delay', 'Customer is complaining about the delay in solar panel installation. The project was supposed to be completed last week but is still ongoing.', 'complaint', 'high', 'open', 'service', 19, 1, 3, 4, 4, 9, NULL, 13, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 9876543210', '2025-12-13 19:53:10', NULL, NULL, NULL, 'Customer is VIP client, needs immediate attention.', NULL, '[\"installation\",\"delay\",\"vip\"]', 1, 1, 1, '2025-11-24 14:23:10', '2025-12-11 14:23:10'),
(8, 'ESC2025120008', 'Billing Discrepancy', 'Customer received an invoice with incorrect amount. The billing team needs to review and correct the invoice.', 'issue', 'medium', 'open', 'billing', 21, 1, 1, 5, 1, 12, NULL, 7, 'Priya Sharma', 'priya.sharma@email.com', '+91 9876543211', '2025-12-12 19:53:10', NULL, NULL, NULL, 'Check invoice calculation and update customer.', NULL, '[\"billing\",\"invoice\",\"calculation\"]', 1, 0, 1, '2025-11-29 14:23:10', '2025-12-11 14:23:10'),
(9, 'ESC2025120009', 'Technical Support Request', 'Customer needs technical support for their solar inverter. The inverter is not working properly after installation.', 'request', 'high', 'in_progress', 'technical', 30, 4, 1, NULL, 2, 5, NULL, 7, 'Amit Patel', 'amit.patel@email.com', '+91 9876543212', '2025-12-12 19:53:10', NULL, NULL, NULL, 'Send technical team to customer site.', NULL, '[\"technical\",\"inverter\",\"support\"]', 1, 1, 1, '2025-11-22 14:23:10', '2025-12-11 14:23:10'),
(10, 'ESC2025120010', 'Commission Payment Issue', 'Channel partner is complaining about delayed commission payment. Payment was due last month.', 'complaint', 'medium', 'in_progress', 'billing', 69, 4, 3, NULL, 5, 5, NULL, 8, 'Sunil Gupta', 'sunil.gupta@email.com', '+91 9876543213', '2025-12-14 19:53:10', NULL, NULL, NULL, 'Check commission calculation and process payment.', NULL, '[\"commission\",\"payment\",\"channel-partner\"]', 1, 0, 1, '2025-11-22 14:23:10', '2025-12-11 14:23:10'),
(11, 'ESC2025120011', 'Project Timeline Extension', 'Customer is requesting extension of project timeline due to weather conditions.', 'request', 'low', 'resolved', 'service', 25, 1, 4, 4, 6, 7, NULL, 13, 'Neha Singh', 'neha.singh@email.com', '+91 9876543214', '2025-12-16 19:53:10', '2025-12-02 19:53:10', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Review project timeline and update customer.', NULL, '[\"timeline\",\"extension\",\"weather\"]', 1, 0, 1, '2025-11-17 14:23:10', '2025-12-11 14:23:10'),
(12, 'ESC2025120012', 'System Performance Issue', 'Solar system is not generating expected power output. Customer is concerned about ROI.', 'incident', 'critical', 'resolved', 'technical', 38, 2, 4, NULL, 3, 3, NULL, 2, 'Vikram Mehta', 'vikram.mehta@email.com', '+91 9876543215', '2025-12-12 19:53:10', '2025-12-06 19:53:10', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Critical issue - send senior technician immediately.', NULL, '[\"performance\",\"power-output\",\"roi\"]', 1, 1, 1, '2025-11-21 14:23:10', '2025-12-11 14:23:10'),
(13, 'ESC2025120013', 'Solar Panel Installation Delay', 'Customer is complaining about the delay in solar panel installation. The project was supposed to be completed last week but is still ongoing.', 'complaint', 'high', 'open', 'service', 51, 4, 1, NULL, 4, 5, NULL, 4, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 9876543210', '2025-12-13 19:53:22', NULL, NULL, NULL, 'Customer is VIP client, needs immediate attention.', NULL, '[\"installation\",\"delay\",\"vip\"]', 1, 1, 1, '2025-11-22 14:23:22', '2025-12-11 14:23:22'),
(14, 'ESC2025120014', 'Billing Discrepancy', 'Customer received an invoice with incorrect amount. The billing team needs to review and correct the invoice.', 'issue', 'medium', 'open', 'billing', 31, 2, 3, NULL, 6, 6, NULL, 3, 'Priya Sharma', 'priya.sharma@email.com', '+91 9876543211', '2025-12-12 19:53:22', NULL, NULL, NULL, 'Check invoice calculation and update customer.', NULL, '[\"billing\",\"invoice\",\"calculation\"]', 1, 0, 1, '2025-11-12 14:23:22', '2025-12-11 14:23:22'),
(15, 'ESC2025120015', 'Technical Support Request', 'Customer needs technical support for their solar inverter. The inverter is not working properly after installation.', 'request', 'high', 'in_progress', 'technical', 67, 1, 6, NULL, 4, 8, NULL, 2, 'Amit Patel', 'amit.patel@email.com', '+91 9876543212', '2025-12-12 19:53:22', NULL, NULL, NULL, 'Send technical team to customer site.', NULL, '[\"technical\",\"inverter\",\"support\"]', 1, 1, 1, '2025-11-26 14:23:22', '2025-12-11 14:23:22'),
(16, 'ESC2025120016', 'Commission Payment Issue', 'Channel partner is complaining about delayed commission payment. Payment was due last month.', 'complaint', 'medium', 'in_progress', 'billing', 19, 2, 3, 5, 5, 11, NULL, 10, 'Sunil Gupta', 'sunil.gupta@email.com', '+91 9876543213', '2025-12-14 19:53:22', NULL, NULL, NULL, 'Check commission calculation and process payment.', NULL, '[\"commission\",\"payment\",\"channel-partner\"]', 1, 0, 1, '2025-11-19 14:23:22', '2025-12-11 14:23:22'),
(17, 'ESC2025120017', 'Project Timeline Extension', 'Customer is requesting extension of project timeline due to weather conditions.', 'request', 'low', 'resolved', 'service', 32, 3, 1, 5, 2, 3, NULL, 7, 'Neha Singh', 'neha.singh@email.com', '+91 9876543214', '2025-12-16 19:53:22', '2025-12-03 19:53:22', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Review project timeline and update customer.', NULL, '[\"timeline\",\"extension\",\"weather\"]', 1, 0, 1, '2025-11-14 14:23:22', '2025-12-11 14:23:22'),
(18, 'ESC2025120018', 'System Performance Issue', 'Solar system is not generating expected power output. Customer is concerned about ROI.', 'incident', 'critical', 'resolved', 'technical', 14, 1, 1, 4, 6, 12, NULL, 12, 'Vikram Mehta', 'vikram.mehta@email.com', '+91 9876543215', '2025-12-12 19:53:22', '2025-12-10 19:53:22', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Critical issue - send senior technician immediately.', NULL, '[\"performance\",\"power-output\",\"roi\"]', 1, 1, 1, '2025-11-24 14:23:22', '2025-12-11 14:23:22'),
(19, 'ESC2025120019', 'Solar Panel Installation Delay', 'Customer is complaining about the delay in solar panel installation. The project was supposed to be completed last week but is still ongoing.', 'complaint', 'high', 'open', 'service', 5, 2, 4, NULL, 4, 7, NULL, 6, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 9876543210', '2025-12-13 19:53:30', NULL, NULL, NULL, 'Customer is VIP client, needs immediate attention.', NULL, '[\"installation\",\"delay\",\"vip\"]', 1, 1, 1, '2025-11-24 14:23:30', '2025-12-11 14:23:30'),
(20, 'ESC2025120020', 'Billing Discrepancy', 'Customer received an invoice with incorrect amount. The billing team needs to review and correct the invoice.', 'issue', 'medium', 'open', 'billing', 54, 1, 3, NULL, 2, 12, NULL, 1, 'Priya Sharma', 'priya.sharma@email.com', '+91 9876543211', '2025-12-12 19:53:30', NULL, NULL, NULL, 'Check invoice calculation and update customer.', NULL, '[\"billing\",\"invoice\",\"calculation\"]', 1, 0, 1, '2025-11-16 14:23:30', '2025-12-11 14:23:30'),
(21, 'ESC2025120021', 'Technical Support Request', 'Customer needs technical support for their solar inverter. The inverter is not working properly after installation.', 'request', 'high', 'in_progress', 'technical', 62, 4, 4, 5, 6, 12, NULL, 2, 'Amit Patel', 'amit.patel@email.com', '+91 9876543212', '2025-12-12 19:53:30', NULL, NULL, NULL, 'Send technical team to customer site.', NULL, '[\"technical\",\"inverter\",\"support\"]', 1, 1, 1, '2025-11-21 14:23:30', '2025-12-11 14:23:30'),
(22, 'ESC2025120022', 'Commission Payment Issue', 'Channel partner is complaining about delayed commission payment. Payment was due last month.', 'complaint', 'medium', 'in_progress', 'billing', 24, 2, 1, 5, 5, 6, NULL, 2, 'Sunil Gupta', 'sunil.gupta@email.com', '+91 9876543213', '2025-12-14 19:53:30', NULL, NULL, NULL, 'Check commission calculation and process payment.', NULL, '[\"commission\",\"payment\",\"channel-partner\"]', 1, 0, 1, '2025-11-22 14:23:30', '2025-12-11 14:23:30'),
(23, 'ESC2025120023', 'Project Timeline Extension', 'Customer is requesting extension of project timeline due to weather conditions.', 'request', 'low', 'resolved', 'service', 11, 5, 1, NULL, 4, 9, NULL, 13, 'Neha Singh', 'neha.singh@email.com', '+91 9876543214', '2025-12-16 19:53:30', '2025-12-08 19:53:30', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Review project timeline and update customer.', NULL, '[\"timeline\",\"extension\",\"weather\"]', 1, 0, 1, '2025-12-08 14:23:30', '2025-12-11 14:23:30'),
(24, 'ESC2025120024', 'System Performance Issue', 'Solar system is not generating expected power output. Customer is concerned about ROI.', 'incident', 'critical', 'resolved', 'technical', 93, 4, 4, 4, 2, 1, NULL, 11, 'Vikram Mehta', 'vikram.mehta@email.com', '+91 9876543215', '2025-12-12 19:53:30', '2025-12-02 19:53:30', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Critical issue - send senior technician immediately.', NULL, '[\"performance\",\"power-output\",\"roi\"]', 1, 1, 1, '2025-11-25 14:23:30', '2025-12-11 14:23:30'),
(25, 'ESC2025120025', 'Solar Panel Installation Delay', 'Customer is complaining about the delay in solar panel installation. The project was supposed to be completed last week but is still ongoing.', 'complaint', 'high', 'open', 'service', 63, 3, 1, 1, 4, 1, NULL, 2, 'Rajesh Kumar', 'rajesh.kumar@email.com', '+91 9876543210', '2025-12-13 19:53:43', NULL, NULL, NULL, 'Customer is VIP client, needs immediate attention.', NULL, '[\"installation\",\"delay\",\"vip\"]', 1, 1, 1, '2025-11-11 14:23:43', '2025-12-11 14:23:43'),
(26, 'ESC2025120026', 'Billing Discrepancy', 'Customer received an invoice with incorrect amount. The billing team needs to review and correct the invoice.', 'issue', 'medium', 'open', 'billing', 10, 2, 3, 5, 1, 2, NULL, 7, 'Priya Sharma', 'priya.sharma@email.com', '+91 9876543211', '2025-12-12 19:53:43', NULL, NULL, NULL, 'Check invoice calculation and update customer.', NULL, '[\"billing\",\"invoice\",\"calculation\"]', 1, 0, 1, '2025-12-07 14:23:43', '2025-12-11 14:23:43'),
(27, 'ESC2025120027', 'Technical Support Request', 'Customer needs technical support for their solar inverter. The inverter is not working properly after installation.', 'request', 'high', 'in_progress', 'technical', 43, 1, 3, NULL, 1, 9, NULL, 12, 'Amit Patel', 'amit.patel@email.com', '+91 9876543212', '2025-12-12 19:53:43', NULL, NULL, NULL, 'Send technical team to customer site.', NULL, '[\"technical\",\"inverter\",\"support\"]', 1, 1, 1, '2025-11-21 14:23:43', '2025-12-11 14:23:43'),
(28, 'ESC2025120028', 'Commission Payment Issue', 'Channel partner is complaining about delayed commission payment. Payment was due last month.', 'complaint', 'medium', 'in_progress', 'billing', 1, 4, 2, 4, 6, 1, NULL, 1, 'Sunil Gupta', 'sunil.gupta@email.com', '+91 9876543213', '2025-12-14 19:53:43', NULL, NULL, NULL, 'Check commission calculation and process payment.', NULL, '[\"commission\",\"payment\",\"channel-partner\"]', 1, 0, 1, '2025-11-29 14:23:43', '2025-12-11 14:23:43'),
(29, 'ESC2025120029', 'Project Timeline Extension', 'Customer is requesting extension of project timeline due to weather conditions.', 'request', 'low', 'resolved', 'service', 7, 4, 1, 5, 1, 1, NULL, 11, 'Neha Singh', 'neha.singh@email.com', '+91 9876543214', '2025-12-16 19:53:43', '2025-12-09 19:53:43', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Review project timeline and update customer.', NULL, '[\"timeline\",\"extension\",\"weather\"]', 1, 0, 1, '2025-11-24 14:23:43', '2025-12-11 14:23:43'),
(30, 'ESC2025120030', 'System Performance Issue', 'Solar system is not generating expected power output. Customer is concerned about ROI.', 'incident', 'critical', 'resolved', 'technical', 1, 2, 6, 4, 2, 3, NULL, 8, 'Vikram Mehta', 'vikram.mehta@email.com', '+91 9876543215', '2025-12-12 19:53:43', '2025-12-03 19:53:43', NULL, 'Issue has been resolved successfully. Customer is satisfied with the solution provided.', 'Critical issue - send senior technician immediately.', NULL, '[\"performance\",\"power-output\",\"roi\"]', 1, 1, 1, '2025-11-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `expense_category_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `expense_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `approval_level` enum('manager','hr','admin','approved','rejected') NOT NULL DEFAULT 'manager',
  `notes` text DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `manager_approved_at` timestamp NULL DEFAULT NULL,
  `hr_approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `hr_approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `manager_rejection_reason` text DEFAULT NULL,
  `hr_rejection_reason` text DEFAULT NULL,
  `admin_rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_number`, `title`, `description`, `expense_category_id`, `project_id`, `amount`, `currency`, `expense_date`, `payment_method`, `status`, `approval_level`, `notes`, `receipt_path`, `created_by`, `approved_by`, `manager_approved_by`, `approved_at`, `manager_approved_at`, `hr_approved_by`, `hr_approved_at`, `created_at`, `updated_at`, `manager_rejection_reason`, `hr_rejection_reason`, `admin_rejection_reason`) VALUES
(1, 'EXP20250001', 'Office Supplies Purchase', 'Monthly office supplies including paper, pens, and notebooks', 4, NULL, 125.50, 'USD', '2025-12-06', 'card', 'approved', 'manager', 'Used for Q4 planning meeting materials', NULL, 7, 5, NULL, '2025-12-08 14:22:41', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(2, 'EXP20250002', 'Client Meeting Lunch', 'Business lunch with potential solar installation client', 6, NULL, 78.30, 'USD', '2025-12-08', 'card', 'paid', 'manager', 'Great discussion about rooftop solar project', NULL, 9, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(3, 'EXP20250003', 'Software Subscription', 'Annual subscription for project management software', 7, NULL, 240.00, 'USD', '2025-12-04', 'transfer', 'approved', 'manager', 'Essential tool for solar project tracking', NULL, 3, 12, NULL, '2025-12-07 14:22:41', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(4, 'EXP20250004', 'Travel to Site Visit', 'Gas and toll costs for residential solar site assessment', 7, NULL, 42.50, 'USD', '2025-12-09', 'cash', 'pending', 'manager', 'Site visit for 10kW residential installation', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(5, 'EXP20250005', 'Equipment Maintenance', 'Professional maintenance for solar panel testing equipment', 5, NULL, 325.75, 'USD', '2025-12-01', 'cheque', 'approved', 'manager', 'Annual calibration and maintenance', NULL, 3, 6, NULL, '2025-12-07 14:22:41', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(6, 'EXP20250006', 'Training Course', 'Solar installation safety certification course', 5, NULL, 450.00, 'USD', '2025-11-26', 'card', 'paid', 'manager', 'Required certification for new team member', NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(7, 'EXP20250007', 'Marketing Materials', 'Brochures and business cards for solar installation services', 4, NULL, 89.90, 'USD', '2025-12-07', 'card', 'approved', 'manager', 'Updated with new pricing and packages', NULL, 12, 10, NULL, '2025-12-09 14:22:41', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(8, 'EXP20250008', 'Internet Service', 'Monthly office internet service', 8, NULL, 65.00, 'USD', '2025-12-03', 'transfer', 'paid', 'manager', 'Business broadband connection', NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(9, 'EXP20250009', 'Legal Consultation', 'Legal advice on solar installation contracts', 4, NULL, 200.00, 'USD', '2025-11-29', 'cheque', 'approved', 'manager', 'Contract template revision', NULL, 5, 8, NULL, '2025-12-10 14:22:41', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(10, 'EXP20250010', 'Equipment Rental', 'Lift rental for solar panel installation on 2-story building', 8, NULL, 185.00, 'USD', '2025-12-05', 'card', 'rejected', 'manager', 'Should have been planned in project budget', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL, NULL, NULL),
(11, 'EXP20250011', 'Office Supplies Purchase', 'Monthly office supplies including paper, pens, and notebooks', 10, NULL, 125.50, 'USD', '2025-12-06', 'card', 'approved', 'manager', 'Used for Q4 planning meeting materials', NULL, 2, 4, NULL, '2025-12-07 14:23:10', NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(12, 'EXP20250012', 'Client Meeting Lunch', 'Business lunch with potential solar installation client', 3, NULL, 78.30, 'USD', '2025-12-08', 'card', 'paid', 'manager', 'Great discussion about rooftop solar project', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(13, 'EXP20250013', 'Software Subscription', 'Annual subscription for project management software', 13, NULL, 240.00, 'USD', '2025-12-04', 'transfer', 'approved', 'manager', 'Essential tool for solar project tracking', NULL, 3, 5, NULL, '2025-12-06 14:23:10', NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(14, 'EXP20250014', 'Travel to Site Visit', 'Gas and toll costs for residential solar site assessment', 15, NULL, 42.50, 'USD', '2025-12-09', 'cash', 'pending', 'manager', 'Site visit for 10kW residential installation', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(15, 'EXP20250015', 'Equipment Maintenance', 'Professional maintenance for solar panel testing equipment', 15, NULL, 325.75, 'USD', '2025-12-01', 'cheque', 'approved', 'manager', 'Annual calibration and maintenance', NULL, 4, 2, NULL, '2025-12-10 14:23:10', NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(16, 'EXP20250016', 'Training Course', 'Solar installation safety certification course', 2, NULL, 450.00, 'USD', '2025-11-26', 'card', 'paid', 'manager', 'Required certification for new team member', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(17, 'EXP20250017', 'Marketing Materials', 'Brochures and business cards for solar installation services', 15, NULL, 89.90, 'USD', '2025-12-07', 'card', 'approved', 'manager', 'Updated with new pricing and packages', NULL, 7, 13, NULL, '2025-12-07 14:23:10', NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(18, 'EXP20250018', 'Internet Service', 'Monthly office internet service', 10, NULL, 65.00, 'USD', '2025-12-03', 'transfer', 'paid', 'manager', 'Business broadband connection', NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(19, 'EXP20250019', 'Legal Consultation', 'Legal advice on solar installation contracts', 11, NULL, 200.00, 'USD', '2025-11-29', 'cheque', 'approved', 'manager', 'Contract template revision', NULL, 8, 7, NULL, '2025-12-07 14:23:10', NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(20, 'EXP20250020', 'Equipment Rental', 'Lift rental for solar panel installation on 2-story building', 4, NULL, 185.00, 'USD', '2025-12-05', 'card', 'rejected', 'manager', 'Should have been planned in project budget', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL, NULL, NULL),
(21, 'EXP20250021', 'Office Supplies Purchase', 'Monthly office supplies including paper, pens, and notebooks', 26, NULL, 125.50, 'USD', '2025-12-06', 'card', 'approved', 'manager', 'Used for Q4 planning meeting materials', NULL, 7, 2, NULL, '2025-12-10 14:23:22', NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(22, 'EXP20250022', 'Client Meeting Lunch', 'Business lunch with potential solar installation client', 6, NULL, 78.30, 'USD', '2025-12-08', 'card', 'paid', 'manager', 'Great discussion about rooftop solar project', NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(23, 'EXP20250023', 'Software Subscription', 'Annual subscription for project management software', 22, NULL, 240.00, 'USD', '2025-12-04', 'transfer', 'approved', 'manager', 'Essential tool for solar project tracking', NULL, 9, 8, NULL, '2025-12-06 14:23:22', NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(24, 'EXP20250024', 'Travel to Site Visit', 'Gas and toll costs for residential solar site assessment', 13, NULL, 42.50, 'USD', '2025-12-09', 'cash', 'pending', 'manager', 'Site visit for 10kW residential installation', NULL, 13, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(25, 'EXP20250025', 'Equipment Maintenance', 'Professional maintenance for solar panel testing equipment', 6, NULL, 325.75, 'USD', '2025-12-01', 'cheque', 'approved', 'manager', 'Annual calibration and maintenance', NULL, 8, 13, NULL, '2025-12-06 14:23:22', NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(26, 'EXP20250026', 'Training Course', 'Solar installation safety certification course', 18, NULL, 450.00, 'USD', '2025-11-26', 'card', 'paid', 'manager', 'Required certification for new team member', NULL, 10, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(27, 'EXP20250027', 'Marketing Materials', 'Brochures and business cards for solar installation services', 22, NULL, 89.90, 'USD', '2025-12-07', 'card', 'approved', 'manager', 'Updated with new pricing and packages', NULL, 13, 3, NULL, '2025-12-10 14:23:22', NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(28, 'EXP20250028', 'Internet Service', 'Monthly office internet service', 16, NULL, 65.00, 'USD', '2025-12-03', 'transfer', 'paid', 'manager', 'Business broadband connection', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(29, 'EXP20250029', 'Legal Consultation', 'Legal advice on solar installation contracts', 12, NULL, 200.00, 'USD', '2025-11-29', 'cheque', 'approved', 'manager', 'Contract template revision', NULL, 11, 11, NULL, '2025-12-06 14:23:22', NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(30, 'EXP20250030', 'Equipment Rental', 'Lift rental for solar panel installation on 2-story building', 3, NULL, 185.00, 'USD', '2025-12-05', 'card', 'rejected', 'manager', 'Should have been planned in project budget', NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL, NULL, NULL),
(31, 'EXP20250031', 'Office Supplies Purchase', 'Monthly office supplies including paper, pens, and notebooks', 1, NULL, 125.50, 'USD', '2025-12-06', 'card', 'approved', 'manager', 'Used for Q4 planning meeting materials', NULL, 6, 8, NULL, '2025-12-08 14:23:30', NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(32, 'EXP20250032', 'Client Meeting Lunch', 'Business lunch with potential solar installation client', 7, NULL, 78.30, 'USD', '2025-12-08', 'card', 'paid', 'manager', 'Great discussion about rooftop solar project', NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(33, 'EXP20250033', 'Software Subscription', 'Annual subscription for project management software', 35, NULL, 240.00, 'USD', '2025-12-04', 'transfer', 'approved', 'manager', 'Essential tool for solar project tracking', NULL, 4, 9, NULL, '2025-12-09 14:23:30', NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(34, 'EXP20250034', 'Travel to Site Visit', 'Gas and toll costs for residential solar site assessment', 3, NULL, 42.50, 'USD', '2025-12-09', 'cash', 'pending', 'manager', 'Site visit for 10kW residential installation', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(35, 'EXP20250035', 'Equipment Maintenance', 'Professional maintenance for solar panel testing equipment', 16, NULL, 325.75, 'USD', '2025-12-01', 'cheque', 'approved', 'manager', 'Annual calibration and maintenance', NULL, 8, 5, NULL, '2025-12-07 14:23:30', NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(36, 'EXP20250036', 'Training Course', 'Solar installation safety certification course', 30, NULL, 450.00, 'USD', '2025-11-26', 'card', 'paid', 'manager', 'Required certification for new team member', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(37, 'EXP20250037', 'Marketing Materials', 'Brochures and business cards for solar installation services', 32, NULL, 89.90, 'USD', '2025-12-07', 'card', 'approved', 'manager', 'Updated with new pricing and packages', NULL, 2, 4, NULL, '2025-12-08 14:23:30', NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(38, 'EXP20250038', 'Internet Service', 'Monthly office internet service', 3, NULL, 65.00, 'USD', '2025-12-03', 'transfer', 'paid', 'manager', 'Business broadband connection', NULL, 13, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(39, 'EXP20250039', 'Legal Consultation', 'Legal advice on solar installation contracts', 4, NULL, 200.00, 'USD', '2025-11-29', 'cheque', 'approved', 'manager', 'Contract template revision', NULL, 6, 13, NULL, '2025-12-06 14:23:30', NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(40, 'EXP20250040', 'Equipment Rental', 'Lift rental for solar panel installation on 2-story building', 10, NULL, 185.00, 'USD', '2025-12-05', 'card', 'rejected', 'manager', 'Should have been planned in project budget', NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL, NULL, NULL),
(41, 'EXP20250041', 'Office Supplies Purchase', 'Monthly office supplies including paper, pens, and notebooks', 15, NULL, 125.50, 'USD', '2025-12-06', 'card', 'approved', 'manager', 'Used for Q4 planning meeting materials', NULL, 13, 11, NULL, '2025-12-10 14:23:43', NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(42, 'EXP20250042', 'Client Meeting Lunch', 'Business lunch with potential solar installation client', 17, NULL, 78.30, 'USD', '2025-12-08', 'card', 'paid', 'manager', 'Great discussion about rooftop solar project', NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(43, 'EXP20250043', 'Software Subscription', 'Annual subscription for project management software', 12, NULL, 240.00, 'USD', '2025-12-04', 'transfer', 'approved', 'manager', 'Essential tool for solar project tracking', NULL, 9, 11, NULL, '2025-12-06 14:23:43', NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(44, 'EXP20250044', 'Travel to Site Visit', 'Gas and toll costs for residential solar site assessment', 25, NULL, 42.50, 'USD', '2025-12-09', 'cash', 'pending', 'manager', 'Site visit for 10kW residential installation', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(45, 'EXP20250045', 'Equipment Maintenance', 'Professional maintenance for solar panel testing equipment', 41, NULL, 325.75, 'USD', '2025-12-01', 'cheque', 'approved', 'manager', 'Annual calibration and maintenance', NULL, 13, 13, NULL, '2025-12-06 14:23:43', NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(46, 'EXP20250046', 'Training Course', 'Solar installation safety certification course', 30, NULL, 450.00, 'USD', '2025-11-26', 'card', 'paid', 'manager', 'Required certification for new team member', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(47, 'EXP20250047', 'Marketing Materials', 'Brochures and business cards for solar installation services', 45, NULL, 89.90, 'USD', '2025-12-07', 'card', 'approved', 'manager', 'Updated with new pricing and packages', NULL, 3, 8, NULL, '2025-12-07 14:23:43', NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(48, 'EXP20250048', 'Internet Service', 'Monthly office internet service', 29, NULL, 65.00, 'USD', '2025-12-03', 'transfer', 'paid', 'manager', 'Business broadband connection', NULL, 13, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(49, 'EXP20250049', 'Legal Consultation', 'Legal advice on solar installation contracts', 20, NULL, 200.00, 'USD', '2025-11-29', 'cheque', 'approved', 'manager', 'Contract template revision', NULL, 8, 13, NULL, '2025-12-06 14:23:43', NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(50, 'EXP20250050', 'Equipment Rental', 'Lift rental for solar panel installation on 2-story building', 3, NULL, 185.00, 'USD', '2025-12-05', 'card', 'rejected', 'manager', 'Should have been planned in project budget', NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#3B82F6',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Office Supplies', 'Stationery, printing materials, office equipment', '#3B82F6', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'Travel & Transportation', 'Business travel, fuel, vehicle maintenance', '#10B981', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'Communication', 'Phone bills, internet, software subscriptions', '#F59E0B', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'Training & Development', 'Employee training courses, certifications', '#8B5CF6', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'Marketing & Advertising', 'Digital marketing, ads, promotional materials', '#EF4444', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'Equipment & Hardware', 'Computers, solar equipment, maintenance tools', '#6B7280', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'Utilities', 'Electricity, water, rent, office maintenance', '#059669', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'Professional Services', 'Legal fees, consulting, accounting services', '#DC2626', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'Miscellaneous', 'Other business expenses not categorized above', '#7C2D12', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'Office Supplies', 'Stationery, printing materials, office equipment', '#3B82F6', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(11, 'Travel & Transportation', 'Business travel, fuel, vehicle maintenance', '#10B981', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(12, 'Communication', 'Phone bills, internet, software subscriptions', '#F59E0B', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(13, 'Training & Development', 'Employee training courses, certifications', '#8B5CF6', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(14, 'Marketing & Advertising', 'Digital marketing, ads, promotional materials', '#EF4444', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(15, 'Equipment & Hardware', 'Computers, solar equipment, maintenance tools', '#6B7280', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(16, 'Utilities', 'Electricity, water, rent, office maintenance', '#059669', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(17, 'Professional Services', 'Legal fees, consulting, accounting services', '#DC2626', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(18, 'Miscellaneous', 'Other business expenses not categorized above', '#7C2D12', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(19, 'Office Supplies', 'Stationery, printing materials, office equipment', '#3B82F6', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(20, 'Travel & Transportation', 'Business travel, fuel, vehicle maintenance', '#10B981', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(21, 'Communication', 'Phone bills, internet, software subscriptions', '#F59E0B', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(22, 'Training & Development', 'Employee training courses, certifications', '#8B5CF6', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(23, 'Marketing & Advertising', 'Digital marketing, ads, promotional materials', '#EF4444', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(24, 'Equipment & Hardware', 'Computers, solar equipment, maintenance tools', '#6B7280', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(25, 'Utilities', 'Electricity, water, rent, office maintenance', '#059669', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(26, 'Professional Services', 'Legal fees, consulting, accounting services', '#DC2626', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(27, 'Miscellaneous', 'Other business expenses not categorized above', '#7C2D12', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(28, 'Office Supplies', 'Stationery, printing materials, office equipment', '#3B82F6', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(29, 'Travel & Transportation', 'Business travel, fuel, vehicle maintenance', '#10B981', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(30, 'Communication', 'Phone bills, internet, software subscriptions', '#F59E0B', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(31, 'Training & Development', 'Employee training courses, certifications', '#8B5CF6', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(32, 'Marketing & Advertising', 'Digital marketing, ads, promotional materials', '#EF4444', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(33, 'Equipment & Hardware', 'Computers, solar equipment, maintenance tools', '#6B7280', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(34, 'Utilities', 'Electricity, water, rent, office maintenance', '#059669', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(35, 'Professional Services', 'Legal fees, consulting, accounting services', '#DC2626', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(36, 'Miscellaneous', 'Other business expenses not categorized above', '#7C2D12', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(37, 'Office Supplies', 'Stationery, printing materials, office equipment', '#3B82F6', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(38, 'Travel & Transportation', 'Business travel, fuel, vehicle maintenance', '#10B981', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(39, 'Communication', 'Phone bills, internet, software subscriptions', '#F59E0B', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(40, 'Training & Development', 'Employee training courses, certifications', '#8B5CF6', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(41, 'Marketing & Advertising', 'Digital marketing, ads, promotional materials', '#EF4444', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(42, 'Equipment & Hardware', 'Computers, solar equipment, maintenance tools', '#6B7280', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(43, 'Utilities', 'Electricity, water, rent, office maintenance', '#059669', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(44, 'Professional Services', 'Legal fees, consulting, accounting services', '#DC2626', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(45, 'Miscellaneous', 'Other business expenses not categorized above', '#7C2D12', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `expense_claims`
--

CREATE TABLE `expense_claims` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `claim_number` varchar(255) NOT NULL,
  `expense_type` varchar(255) NOT NULL,
  `expense_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `status` enum('submitted','approved','rejected','paid') NOT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_claims`
--

INSERT INTO `expense_claims` (`id`, `employee_id`, `claim_number`, `expense_type`, `expense_date`, `amount`, `description`, `receipt_path`, `status`, `approved_by`, `approved_date`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'EXP-2025-0001', 'Travel', '2025-12-06', 2500.00, 'Client meeting travel expenses', '/receipts/travel_001.pdf', 'approved', 'Manager', '2025-12-09', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', 'EXP-2025-0002', 'Meals', '2025-12-08', 1200.00, 'Team lunch meeting', '/receipts/meals_001.pdf', 'submitted', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', 'EXP-2025-0003', 'Office Supplies', '2025-12-04', 800.00, 'Stationery and office materials', '/receipts/supplies_001.pdf', 'paid', 'Admin Manager', '2025-12-07', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `g_r_n_s`
--

CREATE TABLE `g_r_n_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `grn_number` varchar(255) NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `grn_date` date NOT NULL,
  `received_date` date NOT NULL,
  `status` enum('pending','received','verified','rejected') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `delivery_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_audits`
--

CREATE TABLE `inventory_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `audit_id` varchar(255) NOT NULL,
  `warehouse_name` varchar(255) NOT NULL,
  `warehouse_location` varchar(255) NOT NULL,
  `auditor_name` varchar(255) NOT NULL,
  `auditor_designation` varchar(255) NOT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `items_audited` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `audited_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_audits`
--

INSERT INTO `inventory_audits` (`id`, `audit_id`, `warehouse_name`, `warehouse_location`, `auditor_name`, `auditor_designation`, `status`, `start_date`, `end_date`, `items_audited`, `remarks`, `created_at`, `updated_at`, `audited_by`) VALUES
(1, 'AUD-2025-0001', 'Main Warehouse', 'Mumbai', 'Rajesh Kumar', 'Senior Auditor', 'completed', '2025-12-06', '2025-12-10', 245, 'Audit completed successfully with minor discrepancies', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(2, 'AUD-2025-0002', 'Secondary Warehouse', 'Delhi', 'Priya Sharma', 'Auditor', 'in_progress', '2025-12-08', NULL, 180, 'Audit in progress, 50% completed', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(3, 'AUD-2025-0003', 'Main Warehouse', 'Mumbai', 'John Doe', 'Quality Auditor', 'pending', '2025-12-12', NULL, 0, 'Scheduled for next week', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(4, 'AUD-2025-0004', 'Secondary Warehouse', 'Delhi', 'Jane Smith', 'Senior Auditor', 'completed', '2025-12-01', '2025-12-04', 320, 'Audit completed with major discrepancy found', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `paid_date` timestamp NULL DEFAULT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `channel_partner_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `invoice_date`, `due_date`, `client_id`, `project_id`, `subtotal`, `tax_amount`, `total_amount`, `paid_amount`, `payment_details`, `paid_date`, `status`, `notes`, `terms_conditions`, `created_by`, `created_at`, `updated_at`, `channel_partner_id`) VALUES
(1, 'INV-2024-007', '2025-11-11', '2025-11-26', 1, 1, 750000.00, 135000.00, 885000.00, 885000.00, NULL, NULL, 'paid', 'Payment received in full. Thank you for choosing Solar ERP!', 'Payment due within 15 days. Late payment charges may apply.', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(2, 'INV-2024-008', '2025-11-21', '2025-12-16', 2, 2, 250000.00, 45000.00, 295000.00, 0.00, NULL, NULL, 'sent', 'Residential solar installation invoice. Please process payment at your earliest convenience.', 'Payment due within 25 days. Early payment discount available.', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(3, 'INV-2024-009', '2025-10-27', '2025-11-21', 3, 3, 2000000.00, 360000.00, 2360000.00, 1000000.00, NULL, NULL, 'sent', 'Industrial solar plant installation - Phase 1 completed. Partial payment received.', 'Payment in installments as per project milestones. Interest charges apply on overdue amounts.', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(4, 'INV-2024-010', '2025-10-12', '2025-11-06', 4, 4, 800000.00, 144000.00, 944000.00, 944000.00, NULL, NULL, 'paid', 'Hospital solar backup system installation completed successfully.', 'Payment due within 25 days. Warranty coverage as per contract.', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(5, 'INV-2024-011', '2025-12-01', '2025-12-26', 1, NULL, 50000.00, 9000.00, 59000.00, 0.00, NULL, NULL, 'draft', 'Maintenance service invoice for existing solar installation.', 'Payment due within 25 days. Service warranty valid for 6 months.', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(6, 'INV-2024-012', '2025-09-12', '2025-10-07', 2, NULL, 120000.00, 21600.00, 141600.00, 0.00, NULL, NULL, 'overdue', 'Overdue invoice for solar panel cleaning and maintenance services.', 'Payment overdue. Please contact us immediately to resolve payment.', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_number` varchar(255) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email` varchar(255) NOT NULL,
  `applicant_phone` varchar(255) NOT NULL,
  `resume_path` text NOT NULL,
  `cover_letter` text NOT NULL,
  `status` enum('applied','screening','interview','selected','rejected') NOT NULL,
  `application_date` date NOT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_notes` text DEFAULT NULL,
  `interviewer_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `application_number`, `job_title`, `applicant_name`, `applicant_email`, `applicant_phone`, `resume_path`, `cover_letter`, `status`, `application_date`, `interview_date`, `interview_notes`, `interviewer_name`, `created_at`, `updated_at`) VALUES
(1, 'APP-2025-0001', 'Solar Engineer', 'Amit Kumar', 'amit@email.com', '+91-9876543210', '/resumes/amit_kumar.pdf', 'I am interested in the Solar Engineer position and have 3 years of experience in renewable energy.', 'interview', '2025-12-06', '2025-12-13', 'Strong technical background, good communication skills', 'HR Manager', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'APP-2025-0002', 'Project Manager', 'Priya Sharma', 'priya@email.com', '+91-9876543211', '/resumes/priya_sharma.pdf', 'Experienced project manager with 5 years in construction industry.', 'selected', '2025-12-01', '2025-12-08', 'Excellent leadership skills, relevant experience', 'Project Director', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'APP-2025-0003', 'Sales Executive', 'Rajesh Singh', 'rajesh@email.com', '+91-9876543212', '/resumes/rajesh_singh.pdf', 'Passionate about solar energy sales with proven track record.', 'applied', '2025-12-09', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `consumer_number` varchar(100) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `source` enum('website','indiamart','justdial','meta_ads','referral','cold_call','other') NOT NULL DEFAULT 'website',
  `status` enum('interested','not_interested','partially_interested','not_reachable','not_answered') DEFAULT 'interested',
  `lead_stage` varchar(255) DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `notes` text DEFAULT NULL,
  `estimated_value` decimal(15,2) DEFAULT NULL,
  `expected_close_date` date DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `follow_up_notes` text DEFAULT NULL,
  `last_follow_up_at` timestamp NULL DEFAULT NULL,
  `electricity_bill_path` varchar(255) DEFAULT NULL,
  `cancelled_cheque_path` varchar(255) DEFAULT NULL,
  `aadhar_path` varchar(255) DEFAULT NULL,
  `pan_path` varchar(255) DEFAULT NULL,
  `other_document_name` varchar(255) DEFAULT NULL,
  `other_document_path` varchar(255) DEFAULT NULL,
  `passport_photo_path` varchar(255) DEFAULT NULL,
  `site_photo_pre_installation_path` varchar(255) DEFAULT NULL,
  `site_photo_post_installation_path` varchar(255) DEFAULT NULL,
  `call_count` int(11) NOT NULL DEFAULT 0,
  `assigned_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `last_updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_reassigned` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `channel_partner_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `email`, `phone`, `consumer_number`, `company`, `address`, `city`, `state`, `pincode`, `source`, `status`, `lead_stage`, `priority`, `notes`, `estimated_value`, `expected_close_date`, `follow_up_date`, `follow_up_notes`, `last_follow_up_at`, `electricity_bill_path`, `cancelled_cheque_path`, `aadhar_path`, `pan_path`, `other_document_name`, `other_document_path`, `passport_photo_path`, `site_photo_pre_installation_path`, `site_photo_post_installation_path`, `call_count`, `assigned_user_id`, `created_by`, `last_updated_by`, `is_reassigned`, `created_at`, `updated_at`, `channel_partner_id`) VALUES
(1, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:21:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:09', '2025-12-11 14:21:09', NULL),
(2, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:21:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:09', '2025-12-11 14:21:09', NULL),
(3, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:21:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:09', '2025-12-11 14:21:09', NULL),
(4, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:21:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:09', '2025-12-11 14:21:09', NULL),
(5, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:09', '2025-12-11 14:21:09', NULL),
(6, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:21:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(7, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:21:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(8, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:21:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(9, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:21:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(10, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(11, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:21:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(12, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(13, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(14, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:29', '2025-12-11 14:21:29', NULL),
(15, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:21:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(16, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:21:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(17, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:21:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(18, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:21:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(19, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(20, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:21:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(21, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(22, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(23, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:21:46', '2025-12-11 14:21:46', NULL),
(24, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:22:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(25, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:22:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(26, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:22:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(27, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:22:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(28, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(29, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:22:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(30, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(31, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(32, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:06', '2025-12-11 14:22:06', NULL),
(33, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:22:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(34, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:22:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(35, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:22:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(36, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:22:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(37, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(38, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:22:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(39, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(40, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(41, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:19', '2025-12-11 14:22:19', NULL),
(42, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:22:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(43, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:22:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(44, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:22:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(45, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:22:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(46, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(47, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:22:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(48, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(49, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(50, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(51, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:22:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(52, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:22:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(53, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:22:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(54, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:22:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(55, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(56, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:22:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(57, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(58, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(59, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:47', '2025-12-11 14:22:47', NULL),
(60, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:22:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(61, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:22:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(62, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:22:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(63, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:22:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(64, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(65, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:22:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(66, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(67, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 1, 1, 1, '2025-12-11 14:22:56', '2025-12-12 15:53:40', NULL),
(68, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:22:56', '2025-12-11 14:22:56', NULL),
(69, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:23:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(70, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:23:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 1, 1, 1, '2025-12-11 14:23:10', '2025-12-12 15:53:40', NULL),
(71, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:23:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(72, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:23:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(73, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(74, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:23:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(75, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(76, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(77, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:10', '2025-12-11 14:23:10', NULL),
(78, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:23:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(79, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:23:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(80, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:23:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(81, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:23:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(82, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(83, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:23:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(84, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(85, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(86, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:22', '2025-12-11 14:23:22', NULL),
(87, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', '1234567898432', 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2026-01-14', NULL, '2026-01-04 12:57:17', 'leads/electricity-bills/uh7W2mZwI0FtySlvtBoxorKUZglYFhEmENhnkHT1.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 4, 0, '2025-12-11 14:23:30', '2026-01-04 12:57:17', NULL),
(88, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:23:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(89, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:23:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(90, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:23:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(91, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(92, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:23:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(93, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(94, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(95, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:30', '2025-12-11 14:23:30', NULL),
(96, 'Rajesh Kumar', 'rajesh@example.com', '+91-9876543210', NULL, 'Solar Solutions Pvt Ltd', '123, MG Road, Mumbai, Maharashtra', NULL, NULL, NULL, 'website', 'interested', NULL, 'high', 'Interested in rooftop solar installation for office building.', 500000.00, '2026-01-10', '2025-12-21', NULL, '2025-12-11 14:23:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(97, 'Priya Sharma', 'priya@techcorp.com', '+91-9876543211', NULL, 'TechCorp Industries', '456, IT Park, Bangalore, Karnataka', NULL, NULL, NULL, 'indiamart', 'partially_interested', NULL, 'medium', 'Looking for commercial solar power system.', 750000.00, '2026-01-25', '2025-12-21', NULL, '2025-12-11 14:23:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(98, 'Amit Patel', 'amit@greenenergy.com', '+91-9876543212', NULL, 'Green Energy Solutions', '789, Industrial Area, Ahmedabad, Gujarat', NULL, NULL, NULL, 'referral', 'interested', NULL, 'urgent', 'High priority lead - ready to proceed with large installation.', 1200000.00, '2025-12-26', '2025-12-21', NULL, '2025-12-11 14:23:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(99, 'Sunita Singh', 'sunita@residential.com', '+91-9876543213', NULL, NULL, '321, Residential Colony, Delhi', NULL, NULL, NULL, 'meta_ads', 'partially_interested', NULL, 'medium', 'Residential solar installation for 3BHK house.', 250000.00, '2025-12-31', '2025-12-21', NULL, '2025-12-11 14:23:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(100, 'Vikram Mehta', 'vikram@manufacturing.com', '+91-9876543214', NULL, 'Mehta Manufacturing', '654, Industrial Zone, Pune, Maharashtra', NULL, NULL, NULL, 'justdial', 'not_reachable', NULL, 'high', 'Large scale industrial solar project - negotiating terms.', 2000000.00, '2025-12-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(101, 'Kavita Reddy', 'kavita@hospital.com', '+91-9876543215', NULL, 'City Hospital', '987, Medical District, Hyderabad, Telangana', NULL, NULL, NULL, 'cold_call', 'interested', NULL, 'medium', 'Successfully converted - hospital solar installation project.', 800000.00, '2025-12-06', '2025-12-21', NULL, '2025-12-11 14:23:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(102, 'Ravi Joshi', 'ravi@school.edu', '+91-9876543216', NULL, 'Delhi Public School', '147, Education Hub, Delhi', NULL, NULL, NULL, 'website', 'not_interested', NULL, 'low', 'Lost to competitor - budget constraints.', 400000.00, '2025-11-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(103, 'Anjali Verma', 'anjali@hotel.com', '+91-9876543217', NULL, 'Grand Hotel', '258, Tourist Area, Goa', NULL, NULL, NULL, 'website', 'not_answered', NULL, 'medium', 'Hotel solar installation - not responding to calls.', 600000.00, '2026-01-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(104, 'Mohammed Ali', 'mohammed@factory.com', '+91-9876543218', NULL, 'Textile Factory', '369, Industrial Estate, Surat, Gujarat', NULL, NULL, NULL, 'indiamart', 'not_interested', NULL, 'low', 'Not interested at this time - will reconsider next year.', 300000.00, '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, NULL, 0, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL),
(105, 'Demo Lead', 'demo@example.com', '9876543210', NULL, 'Demo Company', NULL, NULL, NULL, NULL, 'website', 'interested', NULL, 'medium', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 1, 4, 0, '2025-12-13 06:24:09', '2025-12-13 06:27:12', NULL),
(106, 'John Smith', 'john.smith@example.com', '9876543211', NULL, 'Tech Solutions Pvt Ltd', '123 Business Park, Sector 5', NULL, NULL, NULL, 'indiamart', 'interested', NULL, 'high', NULL, 150000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 1, 4, 0, '2025-12-13 06:29:49', '2025-12-13 06:30:13', NULL),
(107, 'Krish Bhuvela', 'bhuvelakrish@gmail.com', '06355560792', NULL, NULL, '9,aastha Homes,near janki Residency,koyali Road,undera,vadodara', NULL, NULL, NULL, 'referral', 'interested', NULL, 'low', NULL, NULL, NULL, '2025-12-23', NULL, '2025-12-13 08:42:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 4, 4, 0, '2025-12-13 08:42:04', '2025-12-13 08:42:17', NULL),
(108, 'Krish Bhuvela', 'bhuvelakrish@gmail.com', '06355560792', '1234567898432', NULL, '9,aastha Homes,near janki Residency,koyali Road,undera,vadodara', NULL, NULL, NULL, 'website', 'partially_interested', NULL, 'low', NULL, NULL, NULL, '2026-01-14', NULL, '2026-01-04 13:00:28', 'leads/electricity-bills/UXp494bIYCI9DuJlinyeuLUc4CPqu6JqhgZGCWRI.pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 4, 4, 4, 0, '2026-01-04 12:37:22', '2026-01-04 13:00:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lead_backups`
--

CREATE TABLE `lead_backups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `original_lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `source` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `priority` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `estimated_value` decimal(15,2) DEFAULT NULL,
  `expected_close_date` date DEFAULT NULL,
  `assigned_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `channel_partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `deletion_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `total_days`, `reason`, `status`, `approved_by`, `approved_date`, `comments`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'Sick Leave', '2025-12-13', '2025-12-14', 2, 'Medical appointment', 'pending', NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', 'Personal Leave', '2025-12-16', '2025-12-18', 3, 'Family function', 'approved', 'HR Manager', '2025-12-10', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', 'Annual Leave', '2025-12-21', '2025-12-26', 6, 'Vacation', 'rejected', 'HR Manager', '2025-12-09', 'Peak season, cannot approve leave', '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_code` varchar(255) NOT NULL,
  `material_request_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `specification` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `approved_quantity` int(11) NOT NULL DEFAULT 0,
  `received_quantity` int(11) NOT NULL DEFAULT 0,
  `consumed_quantity` int(11) NOT NULL DEFAULT 0,
  `remaining_quantity` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('requested','approved','ordered','received','consumed','returned') NOT NULL DEFAULT 'requested',
  `quality` enum('excellent','good','average','poor') DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `model_number` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `technical_specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technical_specs`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `material_code`, `material_request_id`, `name`, `description`, `specification`, `unit`, `quantity`, `approved_quantity`, `received_quantity`, `consumed_quantity`, `remaining_quantity`, `unit_price`, `total_price`, `status`, `quality`, `supplier`, `brand`, `model_number`, `serial_number`, `notes`, `technical_specs`, `created_at`, `updated_at`) VALUES
(1, 'MAT-5106', 1, 'Solar Panels (500W)', 'Monocrystalline, 500W rated', 'Monocrystalline, 500W rated', 'piece', 100, 100, 100, 0, 100, 2500.00, 250000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'MAT-9479', 1, 'Mounting Rail System', 'Aluminum rails with clamps', 'Aluminum rails with clamps', 'piece', 50, 50, 50, 0, 50, 1500.00, 75000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'MAT-5096', 1, 'DC Cables (4mm)', 'Twin conductor DC cable', 'Twin conductor DC cable', 'piece', 3000, 3000, 3000, 0, 3000, 45.00, 135000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'MAT-1272', 1, 'MC4 Connectors', 'Weatherproof connectors', 'Weatherproof connectors', 'piece', 200, 200, 200, 0, 200, 25.00, 5000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'MAT-2878', 1, 'Inverter (50kW)', 'String inverter, 50kW capacity', 'String inverter, 50kW capacity', 'piece', 5, 5, 5, 0, 5, 45000.00, 225000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'MAT-9541', 2, 'Safety Helmets', 'Industrial grade safety helmets', 'Industrial grade safety helmets', 'piece', 25, 25, 25, 0, 25, 800.00, 20000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'MAT-0512', 2, 'Safety Harnesses', 'Full body safety harnesses', 'Full body safety harnesses', 'piece', 15, 15, 15, 0, 15, 2500.00, 37500.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'MAT-8026', 2, 'Safety Gloves', 'Cut-resistant work gloves', 'Cut-resistant work gloves', 'piece', 50, 50, 50, 0, 50, 300.00, 15000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'MAT-7042', 2, 'Safety Shoes', 'Steel toe safety shoes', 'Steel toe safety shoes', 'piece', 30, 30, 30, 0, 30, 1500.00, 45000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'MAT-4856', 2, 'Cutting Tools Set', 'Professional cutting tool kit', 'Professional cutting tool kit', 'piece', 10, 10, 10, 0, 10, 2500.00, 25000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'MAT-9686', 3, 'Generator (75kVA)', 'Diesel generator with automatic switching', 'Diesel generator with automatic switching', 'piece', 1, 1, 1, 0, 1, 25000.00, 25000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(12, 'MAT-7477', 4, 'Steel Beams (I-Section)', 'Mild steel I-beams, 50kg/m', 'Mild steel I-beams, 50kg/m', 'piece', 500, 500, 500, 0, 500, 450.00, 225000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(13, 'MAT-6397', 4, 'Concrete Mix (Ready Mix)', 'M25 grade ready mix concrete', 'M25 grade ready mix concrete', 'piece', 100, 100, 100, 0, 100, 2800.00, 280000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(14, 'MAT-1631', 4, 'Rebar (12mm)', 'High tensile steel reinforcement bars', 'High tensile steel reinforcement bars', 'piece', 2000, 2000, 2000, 0, 2000, 60.00, 120000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(15, 'MAT-6347', 4, 'Foundation Bolts', 'Anchor bolts for mounting', 'Anchor bolts for mounting', 'piece', 400, 400, 400, 0, 400, 85.00, 34000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(16, 'MAT-3590', 5, 'Cable Trays (200mm)', 'Galvanized steel cable tray', 'Galvanized steel cable tray', 'piece', 200, 200, 200, 0, 200, 180.00, 36000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(17, 'MAT-8167', 5, 'PVC Conduit (50mm)', 'Rigid PVC conduit', 'Rigid PVC conduit', 'piece', 500, 500, 500, 0, 500, 25.00, 12500.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(18, 'MAT-8442', 5, 'Junction Boxes', 'IP65 waterproof junction boxes', 'IP65 waterproof junction boxes', 'piece', 50, 50, 50, 0, 50, 350.00, 17500.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(19, 'MAT-2539', 6, 'Office Supplies Pack', 'Complete office supplies kit', 'Complete office supplies kit', 'piece', 5, 5, 5, 0, 5, 2000.00, 10000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(20, 'MAT-6784', 6, 'Hardware Fasteners Kit', 'Mixed nuts, bolts, washers', 'Mixed nuts, bolts, washers', 'piece', 20, 20, 20, 0, 20, 850.00, 17000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(21, 'MAT-4737', 6, 'Electrical Tape', 'Insulating electrical tape', 'Insulating electrical tape', 'piece', 100, 100, 100, 0, 100, 120.00, 12000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(22, 'MAT-7835', 9, 'Digital Multimeter', 'Professional digital multimeter', 'Professional digital multimeter', 'piece', 10, 10, 10, 0, 10, 3500.00, 35000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(23, 'MAT-0528', 9, 'Insulation Tester', 'Megger insulation resistance tester', 'Megger insulation resistance tester', 'piece', 3, 3, 3, 0, 3, 8500.00, 25500.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(24, 'MAT-0067', 9, 'Current Clamp Meter', 'AC/DC current clamp meter', 'AC/DC current clamp meter', 'piece', 5, 5, 5, 0, 5, 4500.00, 22500.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(25, 'MAT-5151', 9, 'Solar Panel Tester', 'IV curve tracer for solar panels', 'IV curve tracer for solar panels', 'piece', 2, 2, 2, 0, 2, 12000.00, 24000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(26, 'MAT-2307', 1, 'Extension Cords (25ft)', 'Heavy duty extension cords for electrical connections', '25 feet, 12 AWG, rubber jacket', 'piece', 10, 0, 0, 0, 10, 850.00, 8500.00, 'requested', NULL, 'Electrical Supplies Ltd', 'PowerTech', NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(27, 'MAT-9317', 1, 'Wire Strippers Set', 'Professional wire stripping tool set', 'Multiple gauge stripping capabilities', 'set', 5, 0, 0, 0, 5, 1200.00, 6000.00, 'requested', NULL, 'Tool Master Inc', 'Professional Tools', NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(28, 'MAT-1970', 1, 'Label Printer', 'Industrial label printer for cable marking', 'Thermal transfer printer, PC connectivity', 'piece', 2, 0, 0, 0, 2, 4500.00, 9000.00, 'requested', NULL, 'Industrial Solutions', 'LabelTech', NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(29, 'MAT-1573', 1, 'Measurement Tools Kit', 'Precision measurement tools for installation', 'Lasers, levels, tape measures, calipers', 'kit', 3, 0, 0, 0, 3, 3800.00, 11400.00, 'requested', NULL, 'Precision Tools Co', 'AccurateMeasure', NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(30, 'MAT-2961', 1, 'Personal Protective Equipment', 'PPE kit for construction workers', 'Vests, gloves, masks, eye protection', 'kit', 20, 0, 0, 0, 20, 750.00, 15000.00, 'requested', NULL, 'Safety First Corp', 'SafeWork', NULL, NULL, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(31, 'MAT-9562', 13, 'Solar Panels (500W)', 'Monocrystalline, 500W rated', 'Monocrystalline, 500W rated', 'piece', 100, 100, 100, 0, 100, 2500.00, 250000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(32, 'MAT-2180', 13, 'Mounting Rail System', 'Aluminum rails with clamps', 'Aluminum rails with clamps', 'piece', 50, 50, 50, 0, 50, 1500.00, 75000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(33, 'MAT-0959', 13, 'DC Cables (4mm)', 'Twin conductor DC cable', 'Twin conductor DC cable', 'piece', 3000, 3000, 3000, 0, 3000, 45.00, 135000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(34, 'MAT-4984', 13, 'MC4 Connectors', 'Weatherproof connectors', 'Weatherproof connectors', 'piece', 200, 200, 200, 0, 200, 25.00, 5000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(35, 'MAT-3982', 13, 'Inverter (50kW)', 'String inverter, 50kW capacity', 'String inverter, 50kW capacity', 'piece', 5, 5, 5, 0, 5, 45000.00, 225000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(36, 'MAT-9765', 14, 'Safety Helmets', 'Industrial grade safety helmets', 'Industrial grade safety helmets', 'piece', 25, 25, 25, 0, 25, 800.00, 20000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(37, 'MAT-3706', 14, 'Safety Harnesses', 'Full body safety harnesses', 'Full body safety harnesses', 'piece', 15, 15, 15, 0, 15, 2500.00, 37500.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(38, 'MAT-1413', 14, 'Safety Gloves', 'Cut-resistant work gloves', 'Cut-resistant work gloves', 'piece', 50, 50, 50, 0, 50, 300.00, 15000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(39, 'MAT-4835', 14, 'Safety Shoes', 'Steel toe safety shoes', 'Steel toe safety shoes', 'piece', 30, 30, 30, 0, 30, 1500.00, 45000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(40, 'MAT-4538', 14, 'Cutting Tools Set', 'Professional cutting tool kit', 'Professional cutting tool kit', 'piece', 10, 10, 10, 0, 10, 2500.00, 25000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(41, 'MAT-7062', 15, 'Generator (75kVA)', 'Diesel generator with automatic switching', 'Diesel generator with automatic switching', 'piece', 1, 1, 1, 0, 1, 25000.00, 25000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(42, 'MAT-6555', 16, 'Steel Beams (I-Section)', 'Mild steel I-beams, 50kg/m', 'Mild steel I-beams, 50kg/m', 'piece', 500, 500, 500, 0, 500, 450.00, 225000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(43, 'MAT-2529', 16, 'Concrete Mix (Ready Mix)', 'M25 grade ready mix concrete', 'M25 grade ready mix concrete', 'piece', 100, 100, 100, 0, 100, 2800.00, 280000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(44, 'MAT-8739', 16, 'Rebar (12mm)', 'High tensile steel reinforcement bars', 'High tensile steel reinforcement bars', 'piece', 2000, 2000, 2000, 0, 2000, 60.00, 120000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(45, 'MAT-5342', 16, 'Foundation Bolts', 'Anchor bolts for mounting', 'Anchor bolts for mounting', 'piece', 400, 400, 400, 0, 400, 85.00, 34000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(46, 'MAT-2394', 17, 'Cable Trays (200mm)', 'Galvanized steel cable tray', 'Galvanized steel cable tray', 'piece', 200, 200, 200, 0, 200, 180.00, 36000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(47, 'MAT-8897', 17, 'PVC Conduit (50mm)', 'Rigid PVC conduit', 'Rigid PVC conduit', 'piece', 500, 500, 500, 0, 500, 25.00, 12500.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(48, 'MAT-6032', 17, 'Junction Boxes', 'IP65 waterproof junction boxes', 'IP65 waterproof junction boxes', 'piece', 50, 50, 50, 0, 50, 350.00, 17500.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(49, 'MAT-1083', 18, 'Office Supplies Pack', 'Complete office supplies kit', 'Complete office supplies kit', 'piece', 5, 5, 5, 0, 5, 2000.00, 10000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(50, 'MAT-7421', 18, 'Hardware Fasteners Kit', 'Mixed nuts, bolts, washers', 'Mixed nuts, bolts, washers', 'piece', 20, 20, 20, 0, 20, 850.00, 17000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(51, 'MAT-8387', 18, 'Electrical Tape', 'Insulating electrical tape', 'Insulating electrical tape', 'piece', 100, 100, 100, 0, 100, 120.00, 12000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(52, 'MAT-8162', 21, 'Digital Multimeter', 'Professional digital multimeter', 'Professional digital multimeter', 'piece', 10, 10, 10, 0, 10, 3500.00, 35000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(53, 'MAT-0827', 21, 'Insulation Tester', 'Megger insulation resistance tester', 'Megger insulation resistance tester', 'piece', 3, 3, 3, 0, 3, 8500.00, 25500.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(54, 'MAT-1278', 21, 'Current Clamp Meter', 'AC/DC current clamp meter', 'AC/DC current clamp meter', 'piece', 5, 5, 5, 0, 5, 4500.00, 22500.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(55, 'MAT-7360', 21, 'Solar Panel Tester', 'IV curve tracer for solar panels', 'IV curve tracer for solar panels', 'piece', 2, 2, 2, 0, 2, 12000.00, 24000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(56, 'MAT-4483', 1, 'Extension Cords (25ft)', 'Heavy duty extension cords for electrical connections', '25 feet, 12 AWG, rubber jacket', 'piece', 10, 0, 0, 0, 10, 850.00, 8500.00, 'requested', NULL, 'Electrical Supplies Ltd', 'PowerTech', NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(57, 'MAT-1941', 1, 'Wire Strippers Set', 'Professional wire stripping tool set', 'Multiple gauge stripping capabilities', 'set', 5, 0, 0, 0, 5, 1200.00, 6000.00, 'requested', NULL, 'Tool Master Inc', 'Professional Tools', NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(58, 'MAT-2432', 1, 'Label Printer', 'Industrial label printer for cable marking', 'Thermal transfer printer, PC connectivity', 'piece', 2, 0, 0, 0, 2, 4500.00, 9000.00, 'requested', NULL, 'Industrial Solutions', 'LabelTech', NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(59, 'MAT-5084', 1, 'Measurement Tools Kit', 'Precision measurement tools for installation', 'Lasers, levels, tape measures, calipers', 'kit', 3, 0, 0, 0, 3, 3800.00, 11400.00, 'requested', NULL, 'Precision Tools Co', 'AccurateMeasure', NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(60, 'MAT-5377', 1, 'Personal Protective Equipment', 'PPE kit for construction workers', 'Vests, gloves, masks, eye protection', 'kit', 20, 0, 0, 0, 20, 750.00, 15000.00, 'requested', NULL, 'Safety First Corp', 'SafeWork', NULL, NULL, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(61, 'MAT-4289', 25, 'Solar Panels (500W)', 'Monocrystalline, 500W rated', 'Monocrystalline, 500W rated', 'piece', 100, 100, 100, 0, 100, 2500.00, 250000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(62, 'MAT-0594', 25, 'Mounting Rail System', 'Aluminum rails with clamps', 'Aluminum rails with clamps', 'piece', 50, 50, 50, 0, 50, 1500.00, 75000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(63, 'MAT-4923', 25, 'DC Cables (4mm)', 'Twin conductor DC cable', 'Twin conductor DC cable', 'piece', 3000, 3000, 3000, 0, 3000, 45.00, 135000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(64, 'MAT-6190', 25, 'MC4 Connectors', 'Weatherproof connectors', 'Weatherproof connectors', 'piece', 200, 200, 200, 0, 200, 25.00, 5000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(65, 'MAT-0411', 25, 'Inverter (50kW)', 'String inverter, 50kW capacity', 'String inverter, 50kW capacity', 'piece', 5, 5, 5, 0, 5, 45000.00, 225000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(66, 'MAT-9099', 26, 'Safety Helmets', 'Industrial grade safety helmets', 'Industrial grade safety helmets', 'piece', 25, 25, 25, 0, 25, 800.00, 20000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(67, 'MAT-7544', 26, 'Safety Harnesses', 'Full body safety harnesses', 'Full body safety harnesses', 'piece', 15, 15, 15, 0, 15, 2500.00, 37500.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(68, 'MAT-0058', 26, 'Safety Gloves', 'Cut-resistant work gloves', 'Cut-resistant work gloves', 'piece', 50, 50, 50, 0, 50, 300.00, 15000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(69, 'MAT-8270', 26, 'Safety Shoes', 'Steel toe safety shoes', 'Steel toe safety shoes', 'piece', 30, 30, 30, 0, 30, 1500.00, 45000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(70, 'MAT-3632', 26, 'Cutting Tools Set', 'Professional cutting tool kit', 'Professional cutting tool kit', 'piece', 10, 10, 10, 0, 10, 2500.00, 25000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(71, 'MAT-4036', 27, 'Generator (75kVA)', 'Diesel generator with automatic switching', 'Diesel generator with automatic switching', 'piece', 1, 1, 1, 0, 1, 25000.00, 25000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(72, 'MAT-3131', 28, 'Steel Beams (I-Section)', 'Mild steel I-beams, 50kg/m', 'Mild steel I-beams, 50kg/m', 'piece', 500, 500, 500, 0, 500, 450.00, 225000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(73, 'MAT-4961', 28, 'Concrete Mix (Ready Mix)', 'M25 grade ready mix concrete', 'M25 grade ready mix concrete', 'piece', 100, 100, 100, 0, 100, 2800.00, 280000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(74, 'MAT-6097', 28, 'Rebar (12mm)', 'High tensile steel reinforcement bars', 'High tensile steel reinforcement bars', 'piece', 2000, 2000, 2000, 0, 2000, 60.00, 120000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(75, 'MAT-2599', 28, 'Foundation Bolts', 'Anchor bolts for mounting', 'Anchor bolts for mounting', 'piece', 400, 400, 400, 0, 400, 85.00, 34000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(76, 'MAT-9599', 29, 'Cable Trays (200mm)', 'Galvanized steel cable tray', 'Galvanized steel cable tray', 'piece', 200, 200, 200, 0, 200, 180.00, 36000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(77, 'MAT-7187', 29, 'PVC Conduit (50mm)', 'Rigid PVC conduit', 'Rigid PVC conduit', 'piece', 500, 500, 500, 0, 500, 25.00, 12500.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(78, 'MAT-5480', 29, 'Junction Boxes', 'IP65 waterproof junction boxes', 'IP65 waterproof junction boxes', 'piece', 50, 50, 50, 0, 50, 350.00, 17500.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(79, 'MAT-4014', 30, 'Office Supplies Pack', 'Complete office supplies kit', 'Complete office supplies kit', 'piece', 5, 5, 5, 0, 5, 2000.00, 10000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(80, 'MAT-2856', 30, 'Hardware Fasteners Kit', 'Mixed nuts, bolts, washers', 'Mixed nuts, bolts, washers', 'piece', 20, 20, 20, 0, 20, 850.00, 17000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(81, 'MAT-7950', 30, 'Electrical Tape', 'Insulating electrical tape', 'Insulating electrical tape', 'piece', 100, 100, 100, 0, 100, 120.00, 12000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(82, 'MAT-1161', 33, 'Digital Multimeter', 'Professional digital multimeter', 'Professional digital multimeter', 'piece', 10, 10, 10, 0, 10, 3500.00, 35000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(83, 'MAT-8119', 33, 'Insulation Tester', 'Megger insulation resistance tester', 'Megger insulation resistance tester', 'piece', 3, 3, 3, 0, 3, 8500.00, 25500.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(84, 'MAT-6063', 33, 'Current Clamp Meter', 'AC/DC current clamp meter', 'AC/DC current clamp meter', 'piece', 5, 5, 5, 0, 5, 4500.00, 22500.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(85, 'MAT-0248', 33, 'Solar Panel Tester', 'IV curve tracer for solar panels', 'IV curve tracer for solar panels', 'piece', 2, 2, 2, 0, 2, 12000.00, 24000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(86, 'MAT-7005', 1, 'Extension Cords (25ft)', 'Heavy duty extension cords for electrical connections', '25 feet, 12 AWG, rubber jacket', 'piece', 10, 0, 0, 0, 10, 850.00, 8500.00, 'requested', NULL, 'Electrical Supplies Ltd', 'PowerTech', NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(87, 'MAT-5591', 1, 'Wire Strippers Set', 'Professional wire stripping tool set', 'Multiple gauge stripping capabilities', 'set', 5, 0, 0, 0, 5, 1200.00, 6000.00, 'requested', NULL, 'Tool Master Inc', 'Professional Tools', NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(88, 'MAT-5831', 1, 'Label Printer', 'Industrial label printer for cable marking', 'Thermal transfer printer, PC connectivity', 'piece', 2, 0, 0, 0, 2, 4500.00, 9000.00, 'requested', NULL, 'Industrial Solutions', 'LabelTech', NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(89, 'MAT-7111', 1, 'Measurement Tools Kit', 'Precision measurement tools for installation', 'Lasers, levels, tape measures, calipers', 'kit', 3, 0, 0, 0, 3, 3800.00, 11400.00, 'requested', NULL, 'Precision Tools Co', 'AccurateMeasure', NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(90, 'MAT-1695', 1, 'Personal Protective Equipment', 'PPE kit for construction workers', 'Vests, gloves, masks, eye protection', 'kit', 20, 0, 0, 0, 20, 750.00, 15000.00, 'requested', NULL, 'Safety First Corp', 'SafeWork', NULL, NULL, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(91, 'MAT-6914', 37, 'Solar Panels (500W)', 'Monocrystalline, 500W rated', 'Monocrystalline, 500W rated', 'piece', 100, 100, 100, 0, 100, 2500.00, 250000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(92, 'MAT-5013', 37, 'Mounting Rail System', 'Aluminum rails with clamps', 'Aluminum rails with clamps', 'piece', 50, 50, 50, 0, 50, 1500.00, 75000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(93, 'MAT-6782', 37, 'DC Cables (4mm)', 'Twin conductor DC cable', 'Twin conductor DC cable', 'piece', 3000, 3000, 3000, 0, 3000, 45.00, 135000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(94, 'MAT-1494', 37, 'MC4 Connectors', 'Weatherproof connectors', 'Weatherproof connectors', 'piece', 200, 200, 200, 0, 200, 25.00, 5000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(95, 'MAT-4587', 37, 'Inverter (50kW)', 'String inverter, 50kW capacity', 'String inverter, 50kW capacity', 'piece', 5, 5, 5, 0, 5, 45000.00, 225000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(96, 'MAT-3376', 38, 'Safety Helmets', 'Industrial grade safety helmets', 'Industrial grade safety helmets', 'piece', 25, 25, 25, 0, 25, 800.00, 20000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(97, 'MAT-0422', 38, 'Safety Harnesses', 'Full body safety harnesses', 'Full body safety harnesses', 'piece', 15, 15, 15, 0, 15, 2500.00, 37500.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(98, 'MAT-8521', 38, 'Safety Gloves', 'Cut-resistant work gloves', 'Cut-resistant work gloves', 'piece', 50, 50, 50, 0, 50, 300.00, 15000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(99, 'MAT-1141', 38, 'Safety Shoes', 'Steel toe safety shoes', 'Steel toe safety shoes', 'piece', 30, 30, 30, 0, 30, 1500.00, 45000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(100, 'MAT-2236', 38, 'Cutting Tools Set', 'Professional cutting tool kit', 'Professional cutting tool kit', 'piece', 10, 10, 10, 0, 10, 2500.00, 25000.00, 'consumed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(101, 'MAT-9310', 39, 'Generator (75kVA)', 'Diesel generator with automatic switching', 'Diesel generator with automatic switching', 'piece', 1, 1, 1, 0, 1, 25000.00, 25000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(102, 'MAT-1740', 40, 'Steel Beams (I-Section)', 'Mild steel I-beams, 50kg/m', 'Mild steel I-beams, 50kg/m', 'piece', 500, 500, 500, 0, 500, 450.00, 225000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(103, 'MAT-0743', 40, 'Concrete Mix (Ready Mix)', 'M25 grade ready mix concrete', 'M25 grade ready mix concrete', 'piece', 100, 100, 100, 0, 100, 2800.00, 280000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(104, 'MAT-3533', 40, 'Rebar (12mm)', 'High tensile steel reinforcement bars', 'High tensile steel reinforcement bars', 'piece', 2000, 2000, 2000, 0, 2000, 60.00, 120000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(105, 'MAT-4397', 40, 'Foundation Bolts', 'Anchor bolts for mounting', 'Anchor bolts for mounting', 'piece', 400, 400, 400, 0, 400, 85.00, 34000.00, 'requested', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(106, 'MAT-5006', 41, 'Cable Trays (200mm)', 'Galvanized steel cable tray', 'Galvanized steel cable tray', 'piece', 200, 200, 200, 0, 200, 180.00, 36000.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(107, 'MAT-7568', 41, 'PVC Conduit (50mm)', 'Rigid PVC conduit', 'Rigid PVC conduit', 'piece', 500, 500, 500, 0, 500, 25.00, 12500.00, 'received', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `material_consumptions`
--

CREATE TABLE `material_consumptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `material_request_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `consumption_number` varchar(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL DEFAULT 'installation',
  `activity_description` text DEFAULT NULL,
  `work_phase` enum('preparation','foundation','structure','electrical','commissioning','maintenance','other') NOT NULL DEFAULT 'preparation',
  `work_location` varchar(255) DEFAULT NULL,
  `quantity_consumed` int(11) NOT NULL DEFAULT 0,
  `unit_of_measurement` varchar(255) DEFAULT NULL,
  `consumption_percentage` decimal(5,2) NOT NULL DEFAULT 100.00,
  `wastage_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `return_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `quality_status` enum('good','damaged','defective','expired') NOT NULL DEFAULT 'good',
  `consumption_status` enum('draft','in_progress','completed','partial','damaged','returned') NOT NULL DEFAULT 'draft',
  `waste_disposed` tinyint(1) NOT NULL DEFAULT 0,
  `return_to_stock` tinyint(1) NOT NULL DEFAULT 0,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `wastage_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost_center` varchar(255) DEFAULT NULL,
  `consumption_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `duration_hours` int(11) DEFAULT NULL,
  `documentation_type` varchar(255) DEFAULT NULL,
  `documentation_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `quality_observations` text DEFAULT NULL,
  `consumed_by` bigint(20) UNSIGNED NOT NULL,
  `supervised_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_consumptions`
--

INSERT INTO `material_consumptions` (`id`, `material_id`, `material_request_id`, `project_id`, `consumption_number`, `activity_type`, `activity_description`, `work_phase`, `work_location`, `quantity_consumed`, `unit_of_measurement`, `consumption_percentage`, `wastage_percentage`, `return_percentage`, `quality_status`, `consumption_status`, `waste_disposed`, `return_to_stock`, `unit_cost`, `total_cost`, `wastage_cost`, `cost_center`, `consumption_date`, `start_time`, `end_time`, `duration_hours`, `documentation_type`, `documentation_path`, `notes`, `quality_observations`, `consumed_by`, `supervised_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'CONS-20251030-8649', 'testing', 'Electrical load testing', 'preparation', 'Warehouse', 33, 'piece', 26.00, 5.00, 69.00, 'defective', 'completed', 0, 1, 2500.00, 82500.00, 4125.00, 'Project-1-preparation', '2025-10-30', '15:32:00', '06:42:00', 5, 'video', '/uploads/consumption/f3b74b0a-54ad-39fb-95e3-a3c6da478c54.jpg', 'Totam et reprehenderit delectus quo eaque culpa exercitationem sint officia eveniet quo assumenda autem perferendis dicta et maxime sunt sed beatae in nobis sed tempore sit qui.', 'Et rem voluptatem soluta totam magni fugiat dolor sunt ipsum ab veritatis illo velit in rerum labore.', 7, 4, 4, '2025-10-30 02:11:55', NULL, NULL),
(2, 2, 1, 1, 'CONS-20251116-6125', 'maintenance', 'Component replacement', 'other', 'Support Facility', 21, 'piece', 74.00, 1.00, 25.00, 'damaged', 'damaged', 0, 1, 1500.00, 31500.00, 315.00, 'Project-1-other', '2025-11-16', '19:31:00', '13:46:00', 3, 'report', '/uploads/consumption/4a50a0cc-7e89-3967-aaa1-99597f121d59.jpg', NULL, 'Explicabo aut ratione aut harum sint quisquam esse voluptatibus voluptas est sequi non in ad est.', 11, 13, NULL, NULL, NULL, NULL),
(3, 3, 1, 1, 'CONS-20251208-0301', 'repair', 'Equipment malfunction fixing', 'other', 'Support Facility', 89, 'piece', 73.00, 11.00, 16.00, 'expired', 'in_progress', 1, 1, 45.00, 4005.00, 440.55, 'Project-1-other', '2025-12-08', '04:03:00', '17:40:00', 6, 'photo', '/uploads/consumption/fe51f814-37f4-3370-a8d9-214dda98964c.jpg', 'Laudantium harum nihil architecto accusamus sit in officia et et est ipsum similique.', 'Ab doloremque ipsam quam saepe aut consequatur omnis laboriosam modi ut dignissimos asperiores saepe.', 11, 3, NULL, NULL, NULL, NULL),
(4, 4, 1, 1, 'CONS-20251115-9306', 'demo', 'Client presentation setup', 'other', 'Support Facility', 14, 'piece', 23.00, 5.00, 72.00, 'good', 'damaged', 0, 1, 25.00, 350.00, 17.50, 'Project-1-other', '2025-11-15', '12:01:00', '10:38:00', 1, 'video', '/uploads/consumption/94782e52-59b2-38e3-9067-5a23d5109864.jpg', 'Inventore quos ad quia qui consequatur et repudiandae animi aut quis iste autem porro nisi et consequatur vel vitae sint laborum sequi qui ex numquam eveniet dolorum.', NULL, 10, 10, NULL, NULL, NULL, NULL),
(5, 5, 1, 1, 'CONS-20251027-5374', 'maintenance', 'Panel cleaning and inspection', 'preparation', 'Warehouse', 5, 'piece', 64.00, 0.00, 36.00, 'damaged', 'completed', 0, 1, 45000.00, 225000.00, 0.00, 'Project-1-preparation', '2025-10-27', '00:20:00', '11:04:00', 8, 'report', '/uploads/consumption/4e0e8541-07de-3bff-92fc-ad3aba71cca4.jpg', 'Et tempora blanditiis impedit dicta a doloribus tempora quibusdam porro quibusdam sit non alias modi dolores laboriosam consequatur praesentium sit quod architecto.', 'Perspiciatis maiores veniam fugit nostrum harum tempora provident ut ab temporibus et incidunt rerum ut nostrum officia dicta enim.', 1, 4, 13, '2025-10-27 03:28:22', NULL, NULL),
(6, 6, 2, 2, 'CONS-20251121-4163', 'testing', 'System commissioning test', 'electrical', 'Cable Routing Zone', 15, 'piece', 34.00, 1.00, 65.00, 'good', 'partial', 0, 1, 800.00, 12000.00, 120.00, 'Project-2-electrical', '2025-11-21', '04:35:00', '20:50:00', 7, 'report', '/uploads/consumption/df46ff63-2cbb-3093-9a0d-b55c65582570.jpg', 'Dolorem eius voluptates est eos deserunt dolorem cupiditate et dolore sunt sint ducimus.', NULL, 13, 2, NULL, NULL, NULL, NULL),
(7, 7, 2, 2, 'CONS-20250917-3192', 'demo', 'System demonstration', 'maintenance', 'Maintenance Floor', 5, 'piece', 22.00, 1.00, 77.00, 'good', 'in_progress', 0, 1, 2500.00, 12500.00, 125.00, 'Project-2-maintenance', '2025-09-17', '10:16:00', '18:00:00', 7, 'report', '/uploads/consumption/ca0161c7-eca4-3255-8c57-209601683e01.jpg', 'Ratione odio ut in voluptatem est excepturi sit et non veritatis temporibus veniam tenetur non ut quo minima fugiat autem.', NULL, 11, 8, NULL, NULL, NULL, NULL),
(8, 8, 2, 2, 'CONS-20251009-5500', 'training', 'Worker training session', 'preparation', 'Storage Area', 37, 'piece', 55.00, 1.00, 44.00, 'defective', 'completed', 0, 1, 300.00, 11100.00, 111.00, 'Project-2-preparation', '2025-10-09', '12:29:00', '11:33:00', 6, 'report', '/uploads/consumption/27df5db8-731b-36b2-a9c4-d2f2ec41d078.jpg', 'Optio necessitatibus aspernatur officia ratione qui beatae vel molestiae libero omnis doloremque quo praesentium id ut sit corrupti dolore et quas numquam labore.', 'Adipisci occaecati neque nemo omnis autem rem nemo reiciendis repellat ratione illo recusandae quaerat tempora sit optio et.', 6, 2, 10, '2025-10-09 07:08:02', NULL, NULL),
(9, 9, 2, 2, 'CONS-20251116-8739', 'repair', 'Structural damage restoration', 'foundation', 'Site B - Concrete Pouring Zone', 23, 'piece', 78.00, 11.00, 11.00, 'good', 'partial', 1, 1, 1500.00, 34500.00, 3795.00, 'Project-2-foundation', '2025-11-16', '12:03:00', '15:44:00', 5, 'video', '/uploads/consumption/c29db731-ec8a-366a-9c08-e3cdfd5b378d.jpg', NULL, NULL, 1, 9, NULL, NULL, NULL, NULL),
(10, 10, 2, 2, 'CONS-20250913-9826', 'maintenance', 'Component replacement', 'electrical', 'Cable Routing Zone', 1, 'piece', 71.00, 2.00, 27.00, 'expired', 'completed', 0, 1, 2500.00, 2500.00, 50.00, 'Project-2-electrical', '2025-09-13', '20:01:00', '13:15:00', 0, 'report', '/uploads/consumption/2c346955-ee5f-3b2a-9642-657a9c1d33c9.jpg', NULL, 'Ex laboriosam eveniet cumque voluptas eos id sed dolorem possimus dolore ipsam et eius.', 12, NULL, 7, '2025-09-13 06:19:09', NULL, NULL),
(11, 11, 3, 4, 'CONS-20251031-0825', 'installation', 'Solar panel mounting and wiring', 'structure', 'Beam Installation Zone', 1, 'piece', 85.00, 4.00, 11.00, 'good', 'completed', 0, 1, 25000.00, 25000.00, 1000.00, 'Project-4-structure', '2025-10-31', '20:47:00', '18:52:00', 7, 'report', '/uploads/consumption/7a909665-4483-383e-908e-ddb16ebce135.jpg', 'Et rem optio rerum alias occaecati doloribus hic consequatur omnis beatae ea rerum consequatur nesciunt consequatur ut voluptatum recusandae iure consequatur rerum accusamus.', NULL, 1, 5, 9, '2025-10-31 12:32:23', NULL, NULL),
(12, 12, 4, 5, 'CONS-20251112-7088', 'installation', 'Solar panel mounting and wiring', 'preparation', 'Pre-production Area', 82, 'piece', 85.00, 6.00, 9.00, 'good', 'completed', 1, 1, 450.00, 36900.00, 2214.00, 'Project-5-preparation', '2025-11-12', '21:14:00', '19:37:00', 3, 'video', '/uploads/consumption/e6fe8751-e891-377e-abf4-5b0cd72cb5e9.jpg', 'Pariatur odio provident accusantium quia dolorum officiis animi sit et ad laborum rem mollitia.', NULL, 2, 12, 1, '2025-11-12 15:43:59', NULL, NULL),
(13, 13, 4, 5, 'CONS-20250925-9425', 'repair', 'Equipment malfunction fixing', 'foundation', 'Site B - Concrete Pouring Zone', 71, 'piece', 56.00, 6.00, 38.00, 'expired', 'in_progress', 1, 1, 2800.00, 198800.00, 11928.00, 'Project-5-foundation', '2025-09-25', '15:08:00', '17:58:00', 0, 'video', '/uploads/consumption/de2b8a52-0377-3526-b116-6c182bc34f7e.jpg', 'Delectus recusandae qui dolorem molestiae aut velit voluptate et quibusdam numquam eveniet aut aut expedita non pariatur magni soluta eligendi quam sit et neque et.', 'Velit modi incidunt consectetur rerum voluptas aut voluptatem dolorum amet qui adipisci dolore omnis reiciendis cumque voluptatem doloremque omnis.', 7, 13, NULL, NULL, NULL, NULL),
(14, 14, 4, 5, 'CONS-20251127-3358', 'demo', 'Exhibition display setup', 'other', 'General Work Area', 48, 'piece', 26.00, 5.00, 69.00, 'good', 'completed', 0, 1, 60.00, 2880.00, 144.00, 'Project-5-other', '2025-11-27', '23:01:00', '19:34:00', 4, 'video', '/uploads/consumption/bc46d247-9a1b-3f18-9d21-64688567a2f9.jpg', NULL, NULL, 12, 13, 4, '2025-11-27 14:42:13', NULL, NULL),
(15, 15, 4, 5, 'CONS-20251005-6010', 'training', 'Safety training program', 'foundation', 'Site A - Foundation Area', 52, 'piece', 24.00, 1.00, 75.00, 'defective', 'damaged', 0, 1, 85.00, 4420.00, 44.20, 'Project-5-foundation', '2025-10-05', '01:47:00', '23:18:00', 0, 'report', '/uploads/consumption/0bb9caae-d786-368b-a9c3-0c2e50fa0e50.jpg', NULL, 'Sunt incidunt consequatur quia animi quasi sunt non quod aut tempore vel.', 8, 13, NULL, NULL, NULL, NULL),
(16, 16, 5, 5, 'CONS-20251127-0454', 'demo', 'System demonstration', 'maintenance', 'Maintenance Floor', 62, 'piece', 54.00, 5.00, 41.00, 'defective', 'completed', 0, 1, 180.00, 11160.00, 558.00, 'Project-5-maintenance', '2025-11-27', '16:52:00', '22:42:00', 8, 'receipt', '/uploads/consumption/7725167d-3f5b-356c-9c9a-f3551adb1d03.jpg', 'Omnis est velit quia voluptatem rerum vel et officia ut cum necessitatibus assumenda fugiat autem pariatur libero expedita.', 'Voluptatem dolorum enim voluptatem facilis nemo cum est quam voluptatem est reiciendis.', 6, 8, 7, '2025-11-27 21:09:56', NULL, NULL),
(17, 17, 5, 5, 'CONS-20250912-8866', 'repair', 'Panel damage repair', 'maintenance', 'Staging Area', 69, 'piece', 45.00, 11.00, 44.00, 'defective', 'damaged', 1, 1, 25.00, 1725.00, 189.75, 'Project-5-maintenance', '2025-09-12', '08:00:00', '04:20:00', 1, 'video', '/uploads/consumption/5dc3c728-cb7e-3a4a-8966-ed2c4499d0f3.jpg', NULL, 'Dolorem et nihil recusandae et fuga ea error id cupiditate ut vel et vero modi.', 11, NULL, NULL, NULL, NULL, NULL),
(18, 18, 5, 5, 'CONS-20251028-4582', 'demo', 'Client presentation setup', 'electrical', 'Cable Routing Zone', 1, 'piece', 33.00, 1.00, 66.00, 'good', 'completed', 0, 1, 350.00, 350.00, 3.50, 'Project-5-electrical', '2025-10-28', '15:03:00', '20:08:00', 7, 'video', '/uploads/consumption/b1ca1872-9576-3629-9803-15de0f2a7802.jpg', 'Qui distinctio ducimus est atque officiis sit qui cum eaque molestiae fugit vel aspernatur earum molestiae nesciunt quo numquam voluptatem eum nemo quis ratione.', NULL, 7, NULL, 6, '2025-10-28 15:37:43', NULL, NULL),
(19, 19, 6, 3, 'CONS-20250920-5479', 'repair', 'Equipment malfunction fixing', 'foundation', 'Base Preparation Area', 5, 'piece', 66.00, 10.00, 24.00, 'expired', 'in_progress', 1, 1, 2000.00, 10000.00, 1000.00, 'Project-3-foundation', '2025-09-20', '04:29:00', '10:44:00', 3, 'photo', '/uploads/consumption/b9a08ab4-e991-3a43-8aa4-86dbe34e1330.jpg', 'Dolore dolores est in unde tempora voluptas et odit autem qui cupiditate aut pariatur.', 'Voluptatem quo sunt officiis illo ad magni laboriosam tenetur voluptatibus consequatur harum consequatur facilis minus occaecati.', 2, NULL, NULL, NULL, NULL, NULL),
(20, 20, 6, 3, 'CONS-20251007-4331', 'installation', 'Cable tray installation', 'maintenance', 'Maintenance Floor', 3, 'piece', 94.00, 5.00, 1.00, 'good', 'completed', 0, 1, 850.00, 2550.00, 127.50, 'Project-3-maintenance', '2025-10-07', '18:22:00', '19:02:00', 8, 'receipt', '/uploads/consumption/f1d4c372-e334-36fb-95b3-4ef9c9803410.jpg', 'Consequuntur odit facere veritatis animi mollitia perspiciatis ea repellat iure aut officia qui dolor.', NULL, 1, 9, 4, '2025-10-07 13:43:17', NULL, NULL),
(21, 21, 6, 3, 'CONS-20251019-0619', 'demo', 'Client presentation setup', 'structure', 'Main Structure Site', 21, 'piece', 57.00, 1.00, 42.00, 'good', 'completed', 0, 1, 120.00, 2520.00, 25.20, 'Project-3-structure', '2025-10-19', '07:07:00', '16:21:00', 4, 'video', '/uploads/consumption/7d5e21aa-b155-34f2-8c38-e2eebb95b1be.jpg', NULL, NULL, 10, 12, 4, '2025-10-19 00:44:59', NULL, NULL),
(22, 22, 9, 4, 'CONS-20251209-8492', 'testing', 'Performance verification', 'foundation', 'Site B - Concrete Pouring Zone', 5, 'piece', 52.00, 1.00, 47.00, 'good', 'completed', 0, 1, 3500.00, 17500.00, 175.00, 'Project-4-foundation', '2025-12-09', '04:11:00', '18:20:00', 6, 'receipt', '/uploads/consumption/e1de6b3d-6576-3249-b7c2-8ce4da5498d9.jpg', NULL, NULL, 8, 13, 5, '2025-12-09 13:09:01', NULL, NULL),
(23, 23, 9, 4, 'CONS-20251005-4074', 'training', 'Technical training workshop', 'commissioning', 'System Commissioning Zone', 1, 'piece', 58.00, 5.00, 37.00, 'expired', 'in_progress', 0, 1, 8500.00, 8500.00, 425.00, 'Project-4-commissioning', '2025-10-05', '00:58:00', '17:39:00', 0, 'report', '/uploads/consumption/54b1ad7a-5b7f-3048-99cc-c757a2145460.jpg', NULL, 'Asperiores consequuntur ut pariatur occaecati at id veritatis eum ipsum perferendis.', 7, 6, NULL, NULL, NULL, NULL),
(24, 24, 9, 4, 'CONS-20251126-9492', 'training', 'Safety training program', 'commissioning', 'Performance Test Site', 1, 'piece', 45.00, 2.00, 53.00, 'good', 'completed', 0, 1, 4500.00, 4500.00, 90.00, 'Project-4-commissioning', '2025-11-26', '12:24:00', '12:32:00', 1, 'photo', '/uploads/consumption/042b4a68-5bbc-3061-86bb-9f9c091b9831.jpg', 'Aut at velit et culpa maxime ut voluptas aliquam id quis architecto excepturi numquam rerum ut aspernatur perferendis est eius facilis tempora corporis.', NULL, 7, NULL, 8, '2025-11-26 02:05:10', NULL, NULL),
(25, 25, 9, 4, 'CONS-20251117-8933', 'repair', 'Structural damage restoration', 'commissioning', 'System Commissioning Zone', 1, 'piece', 63.00, 17.00, 20.00, 'defective', 'in_progress', 1, 1, 12000.00, 12000.00, 2040.00, 'Project-4-commissioning', '2025-11-17', '11:27:00', '18:30:00', 3, 'photo', '/uploads/consumption/ab244752-7a63-36f0-8726-672b59dd6408.jpg', NULL, 'Ex et ex nesciunt nulla est in quo cumque odio exercitationem ut maxime ipsa.', 5, 4, NULL, NULL, NULL, NULL),
(26, 26, 1, 1, 'CONS-20251104-0965', 'installation', 'Inverter mounting and connection', 'preparation', 'Storage Area', 1, 'piece', 98.00, 8.00, 0.00, 'good', 'completed', 1, 0, 850.00, 850.00, 68.00, 'Project-1-preparation', '2025-11-04', '06:27:00', '17:36:00', 2, 'report', '/uploads/consumption/1a9f7d2e-060f-3798-8ea2-37d1188194ae.jpg', 'Nulla tempore aut aut aliquam consequatur voluptatem quia quae veniam maiores veniam ab.', NULL, 4, 9, 9, '2025-11-04 04:59:34', NULL, NULL),
(27, 27, 1, 1, 'CONS-20251007-3121', 'training', 'Technical training workshop', 'preparation', 'Storage Area', 1, 'set', 31.00, 5.00, 64.00, 'defective', 'damaged', 0, 1, 1200.00, 1200.00, 60.00, 'Project-1-preparation', '2025-10-07', '22:32:00', '18:36:00', 5, 'photo', '/uploads/consumption/077f59ab-1b44-3db8-b2a8-1c5afe1bed67.jpg', 'Beatae et voluptate et illo minus dicta id tenetur doloremque atque iste consequatur facilis id quo sint cumque eius voluptatibus.', 'Repellat sapiente omnis consequatur vel distinctio sit ullam illum assumenda.', 5, 9, NULL, NULL, NULL, NULL),
(28, 28, 1, 1, 'CONS-20251016-3502', 'testing', 'Electrical load testing', 'other', 'Support Facility', 1, 'piece', 55.00, 5.00, 40.00, 'damaged', 'damaged', 0, 1, 4500.00, 4500.00, 225.00, 'Project-1-other', '2025-10-16', '23:59:00', '21:06:00', 2, 'report', '/uploads/consumption/5ce0a811-7a73-319e-aa10-f5ad9d2a8ad2.jpg', 'Corporis reiciendis pariatur repellendus magni magni sint dolore eaque non adipisci nihil voluptates quo autem nemo dolor dicta corrupti rerum cum tempore beatae.', 'Tempore in assumenda corporis consequatur sunt architecto officia praesentium inventore vitae.', 5, 13, NULL, NULL, NULL, NULL),
(29, 29, 1, 1, 'CONS-20251116-4592', 'training', 'Technical training workshop', 'maintenance', 'Maintenance Floor', 1, 'kit', 57.00, 4.00, 39.00, 'good', 'completed', 0, 1, 3800.00, 3800.00, 152.00, 'Project-1-maintenance', '2025-11-16', '23:29:00', '06:43:00', 7, 'receipt', '/uploads/consumption/22729d0f-d9d6-3a69-81a6-412a9457d95b.jpg', NULL, NULL, 7, NULL, 9, '2025-11-16 01:32:24', NULL, NULL),
(30, 30, 1, 1, 'CONS-20251030-2625', 'repair', 'Panel damage repair', 'other', 'Support Facility', 1, 'kit', 53.00, 10.00, 37.00, 'defective', 'in_progress', 1, 1, 750.00, 750.00, 75.00, 'Project-1-other', '2025-10-30', '02:55:00', '18:15:00', 5, 'photo', '/uploads/consumption/b20dbd22-c991-3219-8611-9aaafc0aaeb4.jpg', 'Quis dolores quos temporibus dolorem aspernatur architecto aliquid similique maiores vel vitae tempore praesentium sunt quia reiciendis ea dignissimos sint autem.', 'Rerum maxime cum quas amet et eos veritatis ut officia blanditiis quidem maxime quia animi quo.', 6, 9, NULL, NULL, NULL, NULL),
(31, 1, 1, 1, 'CONS-20251119-4256', 'installation', 'Inverter mounting and connection', 'structure', 'Main Structure Site', 25, 'piece', 98.00, 4.00, 0.00, 'defective', 'completed', 0, 0, 2500.00, 62500.00, 2500.00, 'Project-1-structure', '2025-11-19', '00:03:00', '15:24:00', 2, 'report', '/uploads/consumption/05ac9fc7-5560-374b-9cc3-d75a53b1f379.jpg', NULL, 'Voluptates non in quia voluptatibus aut consectetur at consectetur cupiditate labore deleniti id rerum et ratione suscipit et eum ut corporis.', 4, NULL, 13, '2025-11-19 03:10:38', NULL, NULL),
(32, 2, 1, 1, 'CONS-20251023-0945', 'training', 'Safety training program', 'commissioning', 'Performance Test Site', 30, 'piece', 36.00, 2.00, 62.00, 'defective', 'completed', 0, 1, 1500.00, 45000.00, 900.00, 'Project-1-commissioning', '2025-10-23', '20:45:00', '14:49:00', 7, 'video', '/uploads/consumption/f6587645-c8d3-3852-b845-4108488bab4b.jpg', 'Aliquid et mollitia soluta iure delectus eum et officia et qui quo soluta esse et natus dicta aperiam quia non enim cupiditate iusto aut doloremque aut aperiam qui.', 'Est vitae dicta qui qui et in enim quidem culpa et aperiam aut modi praesentium.', 5, 3, 12, '2025-10-23 08:05:25', NULL, NULL),
(33, 3, 1, 1, 'CONS-20251203-1496', 'installation', 'Foundation concrete pouring', 'other', 'Administrative Zone', 75, 'piece', 80.00, 4.00, 16.00, 'good', 'partial', 0, 1, 45.00, 3375.00, 135.00, 'Project-1-other', '2025-12-03', '04:20:00', '08:13:00', 3, 'photo', '/uploads/consumption/c0b18f6f-6147-3994-a139-b358d7c32bf4.jpg', 'Ipsum quasi aut non eos est sapiente mollitia exercitationem aliquid fuga qui necessitatibus dolore quam ullam qui quia.', NULL, 5, NULL, NULL, NULL, NULL, NULL),
(34, 4, 1, 1, 'CONS-20251119-2307', 'testing', 'System commissioning test', 'other', 'General Work Area', 51, 'piece', 36.00, 5.00, 59.00, 'expired', 'completed', 0, 1, 25.00, 1275.00, 63.75, 'Project-1-other', '2025-11-19', '08:11:00', '20:09:00', 3, 'report', '/uploads/consumption/399ec63a-fdf2-3794-ae60-c32476095d5a.jpg', 'Enim qui aliquid excepturi omnis officiis magnam et et ex qui error illum illo ut deleniti aut tempora earum vel quam eveniet ea totam dolore.', 'Sit sed est porro magni et accusantium molestiae ea labore quaerat blanditiis.', 5, 4, 2, '2025-11-19 18:01:26', NULL, NULL),
(35, 5, 1, 1, 'CONS-20251018-8137', 'training', 'Worker training session', 'maintenance', 'Staging Area', 1, 'piece', 42.00, 5.00, 53.00, 'defective', 'completed', 0, 1, 45000.00, 45000.00, 2250.00, 'Project-1-maintenance', '2025-10-18', '05:32:00', '18:10:00', 4, 'receipt', '/uploads/consumption/d7404c4f-ee8a-3713-b44b-43432934d6b7.jpg', NULL, 'Excepturi omnis dolores iure et culpa quaerat dicta corrupti aut voluptas veniam necessitatibus velit est nemo consequatur accusamus.', 13, 1, 1, '2025-10-18 11:50:59', NULL, NULL),
(36, 6, 2, 2, 'CONS-20250927-0434', 'testing', 'Safety system testing', 'electrical', 'Panel Connection Area', 22, 'piece', 42.00, 0.00, 58.00, 'good', 'partial', 0, 1, 800.00, 17600.00, 0.00, 'Project-2-electrical', '2025-09-27', '18:23:00', '17:47:00', 1, 'receipt', '/uploads/consumption/b5f718b2-5d5d-323e-9470-75189870c705.jpg', 'Quas debitis placeat voluptatem ipsum vero corporis veniam adipisci aut aliquid quo ipsa aliquam voluptas et facere non reprehenderit nemo ut tempora iure et recusandae nisi.', NULL, 6, 4, NULL, NULL, NULL, NULL),
(37, 7, 2, 2, 'CONS-20251005-9583', 'testing', 'Electrical load testing', 'other', 'Support Facility', 8, 'piece', 47.00, 4.00, 49.00, 'good', 'completed', 0, 1, 2500.00, 20000.00, 800.00, 'Project-2-other', '2025-10-05', '21:04:00', '14:29:00', 0, 'receipt', '/uploads/consumption/8074c68d-4d82-385c-9dd2-59bb56f9b96c.jpg', 'Autem ea vel repudiandae dignissimos vel commodi rem quia odit sed nihil quo quae nam quia.', NULL, 4, 3, 6, '2025-10-05 03:25:11', NULL, NULL),
(38, 8, 2, 2, 'CONS-20251009-1193', 'training', 'Technical training workshop', 'foundation', 'Site B - Concrete Pouring Zone', 25, 'piece', 59.00, 0.00, 41.00, 'expired', 'completed', 0, 1, 300.00, 7500.00, 0.00, 'Project-2-foundation', '2025-10-09', '20:54:00', '14:29:00', 0, 'video', '/uploads/consumption/049d4485-f4da-3f97-9eb8-fedf72f8a3ed.jpg', NULL, 'Minima aut nam sequi esse rem et impedit explicabo consequatur fugit numquam eius aut earum facilis facilis quidem fugit inventore.', 5, 8, 4, '2025-10-09 02:45:45', NULL, NULL),
(39, 9, 2, 2, 'CONS-20250927-8244', 'maintenance', 'Panel cleaning and inspection', 'foundation', 'Site B - Concrete Pouring Zone', 19, 'piece', 77.00, 1.00, 22.00, 'damaged', 'completed', 0, 1, 1500.00, 28500.00, 285.00, 'Project-2-foundation', '2025-09-27', '14:15:00', '13:15:00', 2, 'photo', '/uploads/consumption/13af8eec-c8dd-334a-b1b1-2241294a0f46.jpg', NULL, 'Atque rem eveniet aut voluptatum corrupti voluptatum aut voluptas tempora odio assumenda aut omnis qui cupiditate nihil.', 6, 11, 8, '2025-09-27 03:24:24', NULL, NULL),
(40, 10, 2, 2, 'CONS-20251111-2033', 'demo', 'System demonstration', 'commissioning', 'Final Testing Area', 4, 'piece', 56.00, 0.00, 44.00, 'good', 'in_progress', 0, 1, 2500.00, 10000.00, 0.00, 'Project-2-commissioning', '2025-11-11', '07:04:00', '15:14:00', 5, 'video', '/uploads/consumption/74271043-15da-38a5-9ec5-e7fe2b20a265.jpg', 'Ut eos non dolor deleniti nesciunt id et vel aut possimus perferendis perspiciatis dignissimos delectus sit adipisci.', NULL, 3, 9, NULL, NULL, NULL, NULL),
(41, 11, 3, 4, 'CONS-20251018-6501', 'installation', 'Electrical conduit installation', 'preparation', 'Pre-production Area', 1, 'piece', 88.00, 6.00, 6.00, 'defective', 'in_progress', 1, 1, 25000.00, 25000.00, 1500.00, 'Project-4-preparation', '2025-10-18', '00:27:00', '17:05:00', 0, 'photo', '/uploads/consumption/8811590e-4b60-36d3-8cd6-9c9630be27f8.jpg', NULL, 'Quia iste vel magnam eveniet quidem ipsum recusandae at id.', 2, 3, NULL, NULL, NULL, NULL),
(42, 12, 4, 5, 'CONS-20251211-0623', 'demo', 'Training equipment preparation', 'other', 'General Work Area', 12, 'piece', 29.00, 3.00, 68.00, 'good', 'completed', 0, 1, 450.00, 5400.00, 162.00, 'Project-5-other', '2025-12-11', '19:40:00', '10:08:00', 7, 'report', '/uploads/consumption/233f229e-2b03-32c9-9333-dd88eac1ed5b.jpg', 'Eum et quia magnam velit id possimus mollitia molestiae consectetur nemo laborum officiis placeat.', NULL, 1, 8, 1, '2025-12-11 02:22:19', NULL, NULL),
(43, 13, 4, 5, 'CONS-20251017-6239', 'maintenance', 'Preventive maintenance tasks', 'maintenance', 'Staging Area', 99, 'piece', 63.00, 0.00, 37.00, 'damaged', 'partial', 0, 1, 2800.00, 277200.00, 0.00, 'Project-5-maintenance', '2025-10-17', '20:58:00', '23:03:00', 5, 'report', '/uploads/consumption/bfd89d60-9752-3707-a189-abbb5e5a4806.jpg', NULL, 'Quis rerum molestias nihil quis deleniti impedit voluptas aut iste et tenetur libero.', 7, 8, NULL, NULL, NULL, NULL),
(44, 14, 4, 5, 'CONS-20251111-5625', 'installation', 'Electrical conduit installation', 'structure', 'Beam Installation Zone', 96, 'piece', 83.00, 4.00, 13.00, 'damaged', 'completed', 0, 1, 60.00, 5760.00, 230.40, 'Project-5-structure', '2025-11-11', '23:50:00', '16:35:00', 0, 'report', '/uploads/consumption/b4804394-a9d6-351b-8ffe-7c3002e19ad7.jpg', NULL, 'Beatae et libero numquam est quibusdam sint commodi praesentium ad necessitatibus.', 12, NULL, 9, '2025-11-11 10:26:10', NULL, NULL),
(45, 15, 4, 5, 'CONS-20251202-4604', 'training', 'Worker training session', 'electrical', 'Panel Connection Area', 72, 'piece', 38.00, 1.00, 61.00, 'good', 'in_progress', 0, 1, 85.00, 6120.00, 61.20, 'Project-5-electrical', '2025-12-02', '09:36:00', '18:29:00', 1, 'report', '/uploads/consumption/7a9a73c8-ebd9-3dbf-aa14-857a332c1941.jpg', 'Est optio corrupti et et non fugit eligendi dolores aliquam repellat delectus qui quo error omnis est et velit beatae ex vitae vel.', NULL, 4, 7, NULL, NULL, NULL, NULL),
(46, 16, 5, 5, 'CONS-20251011-6638', 'maintenance', 'Connection maintenance', 'electrical', 'Electrical Room', 87, 'piece', 82.00, 3.00, 15.00, 'damaged', 'completed', 0, 1, 180.00, 15660.00, 469.80, 'Project-5-electrical', '2025-10-11', '06:50:00', '17:51:00', 6, 'video', '/uploads/consumption/01c4b5d2-8e70-35b5-b888-3fae94cf920b.jpg', 'Esse quod dolores at quas et quo enim vel ullam repellendus et unde dolore suscipit nam.', 'Voluptatibus saepe nihil sunt deleniti iste iure veniam exercitationem itaque illum.', 13, 4, 5, '2025-10-11 11:20:28', NULL, NULL),
(47, 17, 5, 5, 'CONS-20251205-9011', 'training', 'Technical training workshop', 'structure', 'Framework Assembly Area', 44, 'piece', 50.00, 5.00, 45.00, 'good', 'partial', 0, 1, 25.00, 1100.00, 55.00, 'Project-5-structure', '2025-12-05', '01:12:00', '18:07:00', 4, 'photo', '/uploads/consumption/4675ea84-1174-3f2e-90e3-5ec588ca25d9.jpg', 'Ut autem quas quia illum quia qui sit quasi aut cupiditate ut eveniet amet dolores odit neque aliquid minus inventore officiis natus aliquam maxime repellendus vel.', NULL, 9, 13, NULL, NULL, NULL, NULL),
(48, 18, 5, 5, 'CONS-20251031-9650', 'demo', 'Client presentation setup', 'commissioning', 'Final Testing Area', 5, 'piece', 26.00, 1.00, 73.00, 'good', 'in_progress', 0, 1, 350.00, 1750.00, 17.50, 'Project-5-commissioning', '2025-10-31', '15:14:00', '18:57:00', 6, 'video', '/uploads/consumption/86f8d5c8-30df-3d7f-ab18-e72f0d81e168.jpg', NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL),
(49, 19, 6, 3, 'CONS-20251206-9307', 'testing', 'Performance verification', 'commissioning', 'Performance Test Site', 1, 'piece', 41.00, 2.00, 57.00, 'good', 'damaged', 0, 1, 2000.00, 2000.00, 40.00, 'Project-3-commissioning', '2025-12-06', '18:51:00', '11:48:00', 8, 'video', '/uploads/consumption/f456b87f-b0eb-367d-a3bd-4f6d215e3243.jpg', 'Tenetur eveniet nobis voluptate quia velit minus qui rerum ullam delectus vel ex maxime.', NULL, 1, 4, NULL, NULL, NULL, NULL),
(50, 20, 6, 3, 'CONS-20251018-9334', 'training', 'Technical training workshop', 'maintenance', 'Maintenance Floor', 11, 'piece', 42.00, 0.00, 58.00, 'expired', 'damaged', 0, 1, 850.00, 9350.00, 0.00, 'Project-3-maintenance', '2025-10-18', '18:08:00', '16:46:00', 5, 'video', '/uploads/consumption/a8937e7c-7f6f-3e0c-b3e3-e7ed1ffc2ad5.jpg', NULL, 'Libero similique ipsam neque neque voluptatum tenetur deleniti commodi maiores eius ut.', 6, 10, NULL, NULL, NULL, NULL),
(51, 21, 6, 3, 'CONS-20251203-7511', 'maintenance', 'Component replacement', 'commissioning', 'System Commissioning Zone', 34, 'piece', 66.00, 2.00, 32.00, 'expired', 'completed', 0, 1, 120.00, 4080.00, 81.60, 'Project-3-commissioning', '2025-12-03', '12:59:00', '17:36:00', 0, 'report', '/uploads/consumption/0b103a07-a76d-3af1-b1f0-6f939bd9557c.jpg', 'Qui et et id voluptatem non eos quisquam consectetur autem architecto eveniet dolores necessitatibus.', 'Mollitia quia et ut qui error aut molestias nostrum totam molestiae cupiditate.', 7, 6, 4, '2025-12-03 06:18:19', NULL, NULL),
(52, 22, 9, 4, 'CONS-20250925-7912', 'installation', 'Monitoring system setup', 'other', 'Support Facility', 3, 'piece', 87.00, 2.00, 11.00, 'good', 'in_progress', 0, 1, 3500.00, 10500.00, 210.00, 'Project-4-other', '2025-09-25', '02:55:00', '19:44:00', 5, 'receipt', '/uploads/consumption/01570430-38ab-3c77-b0c3-c24ca3e690af.jpg', 'Ducimus sit similique id est voluptatem a qui perspiciatis at repudiandae minus sit magni et delectus nemo consectetur assumenda quod illo et corrupti.', NULL, 10, 8, NULL, NULL, NULL, NULL),
(53, 23, 9, 4, 'CONS-20251111-7815', 'demo', 'Client presentation setup', 'foundation', 'Site A - Foundation Area', 2, 'piece', 23.00, 4.00, 73.00, 'defective', 'completed', 0, 1, 8500.00, 17000.00, 680.00, 'Project-4-foundation', '2025-11-11', '05:05:00', '14:53:00', 3, 'video', '/uploads/consumption/3a612a00-7b4f-30e7-a4c3-123966373d65.jpg', 'Laborum quia hic quo ab exercitationem corrupti quas perferendis odit aut molestiae nobis quas soluta ex ipsum minima vel.', 'Harum incidunt est ipsum laborum harum accusamus dolorem aliquid officia molestiae pariatur eos natus ut id.', 13, 11, 10, '2025-11-11 01:07:26', NULL, NULL),
(54, 24, 9, 4, 'CONS-20251205-1363', 'testing', 'Quality control inspection', 'foundation', 'Site A - Foundation Area', 3, 'piece', 44.00, 4.00, 52.00, 'good', 'in_progress', 0, 1, 4500.00, 13500.00, 540.00, 'Project-4-foundation', '2025-12-05', '15:47:00', '18:12:00', 4, 'video', '/uploads/consumption/906fe98e-a249-39d6-a305-59161932f86c.jpg', 'Et alias veritatis ex hic qui eligendi et accusamus aliquam id ab exercitationem ut.', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(55, 25, 9, 4, 'CONS-20251007-2591', 'maintenance', 'System check and calibration', 'foundation', 'Base Preparation Area', 2, 'piece', 62.00, 2.00, 36.00, 'good', 'completed', 0, 1, 12000.00, 24000.00, 480.00, 'Project-4-foundation', '2025-10-07', '03:09:00', '15:09:00', 6, 'receipt', '/uploads/consumption/6d52a5d1-89f6-376d-a6ce-d4c30ae90216.jpg', 'Quisquam est nobis deserunt nostrum id ut sit molestias dolor enim ut voluptatem quaerat tempore tempore rerum excepturi omnis sunt quisquam ullam in maiores ut placeat eum.', NULL, 7, NULL, 4, '2025-10-07 00:49:43', NULL, NULL),
(56, 26, 1, 1, 'CONS-20251124-5338', 'maintenance', 'System check and calibration', 'other', 'Support Facility', 1, 'piece', 63.00, 0.00, 37.00, 'good', 'partial', 0, 1, 850.00, 850.00, 0.00, 'Project-1-other', '2025-11-24', '05:14:00', '16:56:00', 4, 'report', '/uploads/consumption/e1a879ad-a428-3204-9936-55af68491561.jpg', NULL, NULL, 11, 8, NULL, NULL, NULL, NULL),
(57, 27, 1, 1, 'CONS-20251203-3663', 'maintenance', 'System check and calibration', 'other', 'General Work Area', 1, 'set', 80.00, 0.00, 20.00, 'good', 'damaged', 0, 1, 1200.00, 1200.00, 0.00, 'Project-1-other', '2025-12-03', '15:17:00', '13:14:00', 3, 'photo', '/uploads/consumption/80f097d6-b9df-3ccf-a12c-12177c2a1f03.jpg', NULL, NULL, 13, 3, NULL, NULL, NULL, NULL),
(58, 28, 1, 1, 'CONS-20251115-5324', 'installation', 'Structural beam installation', 'foundation', 'Site A - Foundation Area', 1, 'piece', 83.00, 5.00, 12.00, 'good', 'damaged', 0, 1, 4500.00, 4500.00, 225.00, 'Project-1-foundation', '2025-11-15', '06:34:00', '18:24:00', 7, 'report', '/uploads/consumption/4cde2ec0-b015-3e29-bc0f-5a0fd7730c7c.jpg', NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL),
(59, 29, 1, 1, 'CONS-20250924-4694', 'repair', 'Equipment malfunction fixing', 'maintenance', 'Equipment Service Area', 1, 'kit', 56.00, 7.00, 37.00, 'good', 'in_progress', 1, 1, 3800.00, 3800.00, 266.00, 'Project-1-maintenance', '2025-09-24', '01:40:00', '19:36:00', 3, 'receipt', '/uploads/consumption/f8083571-78d9-3041-ae90-3ed4356ad566.jpg', 'Sed harum architecto quo cumque et qui qui itaque vel facere totam tenetur ad minus ipsam laborum consequuntur itaque ea consequuntur laboriosam dolore sunt enim laudantium.', NULL, 5, NULL, NULL, NULL, NULL, NULL),
(60, 30, 1, 1, 'CONS-20251116-7971', 'installation', 'Cable tray installation', 'foundation', 'Site B - Concrete Pouring Zone', 1, 'kit', 82.00, 7.00, 11.00, 'good', 'completed', 1, 1, 750.00, 750.00, 52.50, 'Project-1-foundation', '2025-11-16', '11:32:00', '02:16:00', 0, 'report', '/uploads/consumption/6ce24461-c9f8-31cb-8fd9-3cf346f70e07.jpg', NULL, NULL, 4, 2, 1, '2025-11-15 22:20:07', NULL, NULL),
(61, 31, 13, 2, 'CONS-20250927-5971', 'training', 'Safety training program', 'maintenance', 'Maintenance Floor', 60, 'piece', 60.00, 2.00, 38.00, 'expired', 'in_progress', 0, 1, 2500.00, 150000.00, 3000.00, 'Project-2-maintenance', '2025-09-27', '22:43:00', '18:53:00', 8, 'report', '/uploads/consumption/baf03cbe-60d1-304c-9f9c-f5fec251c677.jpg', 'Corrupti atque sed dolorem magnam qui occaecati voluptatem blanditiis odio fugiat aut maiores molestiae eum magni quod nostrum voluptatem molestiae qui.', 'Explicabo et magni est non ducimus fugit dolorem et impedit occaecati non pariatur ea numquam quis consectetur facilis.', 2, NULL, NULL, NULL, NULL, NULL),
(62, 32, 13, 2, 'CONS-20250922-8927', 'training', 'Technical training workshop', 'maintenance', 'Staging Area', 14, 'piece', 32.00, 2.00, 66.00, 'good', 'completed', 0, 1, 1500.00, 21000.00, 420.00, 'Project-2-maintenance', '2025-09-22', '23:44:00', '07:36:00', 7, 'receipt', '/uploads/consumption/88f5b288-009b-34ab-8c44-3d3755f8d31f.jpg', 'Quis quod rerum sequi deleniti dicta asperiores et et odio cum saepe ratione totam doloribus ut voluptatem eum omnis ut dolor quia ut omnis est.', NULL, 6, NULL, 5, '2025-09-22 00:02:41', NULL, NULL),
(63, 33, 13, 2, 'CONS-20251028-0652', 'repair', 'Structural damage restoration', 'maintenance', 'Staging Area', 24, 'piece', 55.00, 11.00, 34.00, 'good', 'in_progress', 1, 1, 45.00, 1080.00, 118.80, 'Project-2-maintenance', '2025-10-28', '08:51:00', '17:24:00', 3, 'photo', '/uploads/consumption/9275f293-bd4a-3dc7-9520-2f83cc6a131f.jpg', NULL, NULL, 13, 1, NULL, NULL, NULL, NULL),
(64, 34, 13, 2, 'CONS-20250914-9175', 'training', 'Safety training program', 'commissioning', 'Final Testing Area', 33, 'piece', 31.00, 3.00, 66.00, 'good', 'completed', 0, 1, 25.00, 825.00, 24.75, 'Project-2-commissioning', '2025-09-14', '08:29:00', '18:13:00', 0, 'receipt', '/uploads/consumption/70ac4e92-2da6-3ac6-a16a-a4afa9b0f342.jpg', NULL, NULL, 5, NULL, 1, '2025-09-14 13:35:55', NULL, NULL),
(65, 35, 13, 2, 'CONS-20250916-8024', 'training', 'Safety training program', 'foundation', 'Base Preparation Area', 5, 'piece', 43.00, 1.00, 56.00, 'expired', 'partial', 0, 1, 45000.00, 225000.00, 2250.00, 'Project-2-foundation', '2025-09-16', '01:35:00', '16:03:00', 7, 'report', '/uploads/consumption/fc8ceb6a-07d8-346f-b5aa-7fbe2354e4c7.jpg', 'Sed soluta tenetur vero suscipit autem eos quidem ullam culpa nisi illo atque aliquid quis laboriosam possimus sunt et distinctio blanditiis corporis harum ut ad quibusdam voluptatibus.', 'Veritatis magnam unde quia voluptates nesciunt qui voluptatum quo voluptatibus qui numquam debitis corrupti rerum error.', 8, NULL, NULL, NULL, NULL, NULL),
(66, 36, 14, 5, 'CONS-20251001-2331', 'installation', 'Cable tray installation', 'commissioning', 'Performance Test Site', 11, 'piece', 97.00, 2.00, 1.00, 'expired', 'completed', 0, 1, 800.00, 8800.00, 176.00, 'Project-5-commissioning', '2025-10-01', '01:38:00', '18:14:00', 8, 'receipt', '/uploads/consumption/00aee552-2e54-316b-bcf4-b78d50399967.jpg', NULL, 'Reprehenderit consequatur occaecati consequatur reiciendis aperiam voluptatum quis autem porro qui ut eligendi architecto.', 1, NULL, 8, '2025-10-01 12:25:57', NULL, NULL),
(67, 37, 14, 5, 'CONS-20251109-7227', 'maintenance', 'Component replacement', 'commissioning', 'Final Testing Area', 14, 'piece', 68.00, 0.00, 32.00, 'expired', 'damaged', 0, 1, 2500.00, 35000.00, 0.00, 'Project-5-commissioning', '2025-11-09', '04:21:00', '06:51:00', 2, 'report', '/uploads/consumption/f1adca10-a2cc-316d-9250-7f8ad6e0bb32.jpg', NULL, 'Non iure exercitationem laboriosam rerum magnam quia iste optio est deleniti quis expedita harum ea asperiores et molestiae ut et rerum.', 13, NULL, NULL, NULL, NULL, NULL),
(68, 38, 14, 5, 'CONS-20251211-7928', 'testing', 'Electrical load testing', 'foundation', 'Site B - Concrete Pouring Zone', 21, 'piece', 31.00, 3.00, 66.00, 'damaged', 'in_progress', 0, 1, 300.00, 6300.00, 189.00, 'Project-5-foundation', '2025-12-11', '20:37:00', '13:54:00', 3, 'report', '/uploads/consumption/1b8a5cd1-69de-3520-b9a6-55764e0152df.jpg', 'Rerum laboriosam reiciendis vel enim laborum molestias autem officia libero rem quasi dolor sequi facere qui voluptatem sit voluptatem consequatur rerum dolores dolore voluptas quia quia.', 'Ratione voluptatem veniam vero a doloribus distinctio sit qui nostrum nihil et commodi omnis quisquam.', 10, 12, NULL, NULL, NULL, NULL),
(69, 39, 14, 5, 'CONS-20251127-5674', 'repair', 'Connection repair', 'foundation', 'Site B - Concrete Pouring Zone', 27, 'piece', 62.00, 17.00, 21.00, 'good', 'completed', 1, 1, 1500.00, 40500.00, 6885.00, 'Project-5-foundation', '2025-11-27', '02:17:00', '15:00:00', 6, 'video', '/uploads/consumption/006e66c0-554f-3057-94ec-80baaf614c7e.jpg', NULL, NULL, 10, NULL, 11, '2025-11-27 09:46:53', NULL, NULL),
(70, 40, 14, 5, 'CONS-20251111-7948', 'installation', 'Monitoring system setup', 'commissioning', 'Performance Test Site', 6, 'piece', 87.00, 2.00, 11.00, 'good', 'damaged', 0, 1, 2500.00, 15000.00, 300.00, 'Project-5-commissioning', '2025-11-11', '20:24:00', '15:11:00', 5, 'video', '/uploads/consumption/e52f800e-7e46-37fc-8c35-2fad43142bc7.jpg', 'Eum culpa quia alias facilis dolores porro sunt sed laborum voluptatibus explicabo eum ipsa eveniet soluta aut aut esse qui alias.', NULL, 12, NULL, NULL, NULL, NULL, NULL),
(71, 1, 1, 1, 'CONS-20251103-3306', 'repair', 'Structural damage restoration', 'structure', 'Beam Installation Zone', 81, 'piece', 68.00, 5.00, 27.00, 'damaged', 'in_progress', 0, 1, 2500.00, 202500.00, 10125.00, 'Project-1-structure', '2025-11-03', '11:25:00', '17:49:00', 7, 'video', '/uploads/consumption/d5bf58dd-350d-351d-8b84-c342c0759afb.jpg', NULL, 'Et non molestiae hic labore aliquid quibusdam ducimus mollitia ea numquam qui qui eveniet reiciendis maxime.', 8, 2, NULL, NULL, NULL, NULL),
(72, 2, 1, 1, 'CONS-20250922-3557', 'testing', 'Performance verification', 'electrical', 'Cable Routing Zone', 42, 'piece', 35.00, 4.00, 61.00, 'good', 'partial', 0, 1, 1500.00, 63000.00, 2520.00, 'Project-1-electrical', '2025-09-22', '23:37:00', '15:16:00', 4, 'receipt', '/uploads/consumption/94e9cbc4-1846-3015-8d4a-b7e90a4bce56.jpg', NULL, NULL, 3, 8, NULL, NULL, NULL, NULL),
(73, 3, 1, 1, 'CONS-20251001-8038', 'repair', 'Structural damage restoration', 'electrical', 'Panel Connection Area', 75, 'piece', 50.00, 18.00, 32.00, 'damaged', 'in_progress', 1, 1, 45.00, 3375.00, 607.50, 'Project-1-electrical', '2025-10-01', '13:56:00', '16:01:00', 4, 'report', '/uploads/consumption/e5342161-d2e7-3244-9ad1-9a3d27a36010.jpg', 'Exercitationem corrupti velit aliquam id earum dolore provident rerum voluptas aliquam voluptas debitis nemo et dolorum officiis.', 'Dicta blanditiis molestiae voluptas et quia nihil numquam consectetur officiis.', 1, 4, NULL, NULL, NULL, NULL),
(74, 4, 1, 1, 'CONS-20250928-9051', 'demo', 'Exhibition display setup', 'foundation', 'Site A - Foundation Area', 12, 'piece', 36.00, 2.00, 62.00, 'expired', 'completed', 0, 1, 25.00, 300.00, 6.00, 'Project-1-foundation', '2025-09-28', '14:29:00', '18:18:00', 8, 'receipt', '/uploads/consumption/f1dff9cc-650f-37fc-8a73-cb0c914a78ff.jpg', 'Tenetur est dolores quidem in asperiores totam necessitatibus velit architecto voluptatum aperiam ipsam omnis assumenda numquam at odit quis ut eaque.', 'At eum harum voluptas nihil cupiditate voluptatem neque consequatur quod error dolorem culpa earum officia in optio impedit aut.', 1, NULL, 2, '2025-09-28 16:01:01', NULL, NULL),
(75, 5, 1, 1, 'CONS-20251208-6182', 'demo', 'Exhibition display setup', 'structure', 'Framework Assembly Area', 2, 'piece', 60.00, 0.00, 40.00, 'good', 'damaged', 0, 1, 45000.00, 90000.00, 0.00, 'Project-1-structure', '2025-12-08', '09:04:00', '12:03:00', 7, 'receipt', '/uploads/consumption/3850d60b-a7c1-3979-a997-85c168d21447.jpg', 'Non ut molestiae esse nihil est ex rerum eius ea sed dolor aut fugit omnis laboriosam ut molestiae molestias in soluta sint hic qui fugiat modi.', NULL, 3, NULL, NULL, NULL, NULL, NULL),
(76, 6, 2, 2, 'CONS-20250923-6921', 'maintenance', 'System check and calibration', 'other', 'Support Facility', 15, 'piece', 85.00, 0.00, 15.00, 'defective', 'completed', 0, 1, 800.00, 12000.00, 0.00, 'Project-2-other', '2025-09-23', '03:14:00', '19:41:00', 1, 'report', '/uploads/consumption/9ed932f3-43e5-3fd1-95b2-c7f41451a72e.jpg', 'Libero et incidunt cumque facere sunt enim sed vel blanditiis saepe rerum illo doloribus minima doloribus aliquam voluptas animi eos saepe et id.', 'Est omnis deserunt repellendus est neque maxime quos aut rerum repellendus distinctio.', 2, 6, 10, '2025-09-23 19:32:18', NULL, NULL),
(77, 7, 2, 2, 'CONS-20251123-4996', 'maintenance', 'System check and calibration', 'other', 'General Work Area', 3, 'piece', 63.00, 2.00, 35.00, 'good', 'partial', 0, 1, 2500.00, 7500.00, 150.00, 'Project-2-other', '2025-11-23', '17:36:00', '15:02:00', 8, 'photo', '/uploads/consumption/be88d34e-ba80-3226-9070-5f441cfab1cf.jpg', NULL, NULL, 2, 12, NULL, NULL, NULL, NULL),
(78, 8, 2, 2, 'CONS-20251110-9523', 'training', 'Worker training session', 'electrical', 'Cable Routing Zone', 31, 'piece', 59.00, 1.00, 40.00, 'good', 'completed', 0, 1, 300.00, 9300.00, 93.00, 'Project-2-electrical', '2025-11-10', '00:52:00', '18:58:00', 8, 'report', '/uploads/consumption/c4bb9b55-2040-3ac3-9414-6f177860a55c.jpg', 'Blanditiis aut pariatur id fuga iusto et consequatur est sunt est id et corporis nihil doloremque porro voluptatem dolorem aut maiores eius corrupti et facilis consectetur voluptatem.', NULL, 12, NULL, 7, '2025-11-10 08:10:54', NULL, NULL),
(79, 9, 2, 2, 'CONS-20251114-9050', 'maintenance', 'Preventive maintenance tasks', 'other', 'Support Facility', 7, 'piece', 81.00, 1.00, 18.00, 'good', 'completed', 0, 1, 1500.00, 10500.00, 105.00, 'Project-2-other', '2025-11-14', '15:47:00', '17:41:00', 0, 'video', '/uploads/consumption/4e79bc8f-6822-3f61-a59f-a84519324bd3.jpg', 'Molestiae dignissimos autem et iusto unde asperiores voluptas incidunt nulla id veritatis totam velit voluptatem sapiente repellat reprehenderit molestiae.', NULL, 10, 8, 7, '2025-11-14 11:14:12', NULL, NULL),
(80, 10, 2, 2, 'CONS-20251205-5013', 'maintenance', 'Safety equipment inspection', 'preparation', 'Storage Area', 10, 'piece', 81.00, 3.00, 16.00, 'good', 'completed', 0, 1, 2500.00, 25000.00, 750.00, 'Project-2-preparation', '2025-12-05', '09:47:00', '21:12:00', 1, 'video', '/uploads/consumption/7ca82a48-d19a-31c9-aa37-2deca857ce58.jpg', NULL, NULL, 6, 7, 6, '2025-12-05 20:48:39', NULL, NULL),
(81, 11, 3, 4, 'CONS-20250915-5770', 'installation', 'Structural beam installation', 'foundation', 'Site A - Foundation Area', 1, 'piece', 98.00, 5.00, 0.00, 'good', 'completed', 0, 0, 25000.00, 25000.00, 1250.00, 'Project-4-foundation', '2025-09-15', '10:15:00', '04:33:00', 1, 'report', '/uploads/consumption/d757d22f-038c-34f4-90ce-e314a270e04c.jpg', 'Qui libero quam dolor tenetur et ad dolores voluptatem et dolores et quia rerum vitae dolorum ea aperiam voluptate asperiores molestiae dolores aut laboriosam nostrum ab vel exercitationem.', NULL, 3, 1, 4, '2025-09-15 01:03:06', NULL, NULL),
(82, 12, 4, 5, 'CONS-20251008-7039', 'demo', 'Training equipment preparation', 'structure', 'Framework Assembly Area', 31, 'piece', 30.00, 1.00, 69.00, 'defective', 'completed', 0, 1, 450.00, 13950.00, 139.50, 'Project-5-structure', '2025-10-08', '22:24:00', '15:29:00', 6, 'receipt', '/uploads/consumption/9b86cba5-de41-367b-991c-5d3279dc7971.jpg', 'Facilis vitae sed et quis soluta ipsum odio consequatur qui dignissimos officia officiis non est consectetur sed assumenda.', 'Quis illum non qui ratione nostrum maxime sequi sit adipisci molestiae voluptates voluptates eos maiores eaque et.', 3, NULL, 11, '2025-10-08 00:29:20', NULL, NULL),
(83, 13, 4, 5, 'CONS-20251107-4179', 'training', 'Technical training workshop', 'structure', 'Beam Installation Zone', 31, 'piece', 27.00, 2.00, 71.00, 'defective', 'completed', 0, 1, 2800.00, 86800.00, 1736.00, 'Project-5-structure', '2025-11-07', '19:08:00', '04:54:00', 6, 'photo', '/uploads/consumption/226d92ca-4a4c-3629-ae83-48fa7cbcea1d.jpg', NULL, 'Consequatur voluptas tempore ut et in quaerat laudantium non molestias voluptatum distinctio.', 4, NULL, 8, '2025-11-06 23:11:54', NULL, NULL),
(84, 14, 4, 5, 'CONS-20251026-1057', 'maintenance', 'Component replacement', 'foundation', 'Site A - Foundation Area', 12, 'piece', 84.00, 0.00, 16.00, 'damaged', 'completed', 0, 1, 60.00, 720.00, 0.00, 'Project-5-foundation', '2025-10-26', '15:49:00', '12:05:00', 0, 'video', '/uploads/consumption/c835d2dc-9689-3f85-8019-17f0a93ad388.jpg', 'Ipsam ut nam dolores ipsam quod qui est laborum aspernatur natus ratione iure magnam est officiis omnis et facere architecto nulla exercitationem fugiat natus quae veritatis nam nostrum.', 'Et sunt ut amet in nostrum non consequatur numquam perferendis praesentium aspernatur explicabo error repellendus expedita explicabo possimus mollitia molestiae hic.', 1, 3, 12, '2025-10-26 03:53:31', NULL, NULL),
(85, 15, 4, 5, 'CONS-20251002-8060', 'demo', 'Client presentation setup', 'maintenance', 'Maintenance Floor', 55, 'piece', 27.00, 4.00, 69.00, 'defective', 'damaged', 0, 1, 85.00, 4675.00, 187.00, 'Project-5-maintenance', '2025-10-02', '03:18:00', '16:31:00', 4, 'report', '/uploads/consumption/142cb70c-c1ce-38fd-bc1d-6d30b24fa2c0.jpg', 'Minus nesciunt quae sequi cumque quod est placeat ut porro natus ipsum est est quas possimus fugit numquam eos autem libero accusamus veritatis aut iure.', 'Consectetur asperiores culpa similique temporibus illum sint beatae inventore quia qui sapiente et et.', 11, 10, NULL, NULL, NULL, NULL),
(86, 16, 5, 5, 'CONS-20250912-5120', 'installation', 'Solar panel mounting and wiring', 'other', 'General Work Area', 39, 'piece', 95.00, 3.00, 2.00, 'damaged', 'completed', 0, 1, 180.00, 7020.00, 210.60, 'Project-5-other', '2025-09-12', '08:08:00', '10:39:00', 2, 'photo', '/uploads/consumption/5b168274-9a8b-39a9-a81f-b577478e47e1.jpg', 'Mollitia et et tenetur est qui quo beatae quia illum eligendi soluta est repellendus enim quia aliquam non facilis odio provident odio omnis.', 'Cumque nihil ad ad quod eum consequatur adipisci voluptatibus quia ratione non.', 1, 9, 1, '2025-09-12 00:45:05', NULL, NULL),
(87, 17, 5, 5, 'CONS-20250921-0106', 'testing', 'Quality control inspection', 'electrical', 'Cable Routing Zone', 83, 'piece', 54.00, 2.00, 44.00, 'good', 'completed', 0, 1, 25.00, 2075.00, 41.50, 'Project-5-electrical', '2025-09-21', '11:16:00', '18:34:00', 4, 'receipt', '/uploads/consumption/77ce52e7-3f61-3d86-bf82-9e4d55a0b5b4.jpg', 'Unde praesentium enim commodi nostrum ducimus laboriosam omnis odit officia modi magnam aspernatur esse vel repellendus ex.', NULL, 5, 10, 2, '2025-09-21 05:52:18', NULL, NULL),
(88, 18, 5, 5, 'CONS-20251018-1317', 'maintenance', 'Connection maintenance', 'preparation', 'Storage Area', 11, 'piece', 86.00, 2.00, 12.00, 'good', 'damaged', 0, 1, 350.00, 3850.00, 77.00, 'Project-5-preparation', '2025-10-18', '07:17:00', '21:44:00', 4, 'video', '/uploads/consumption/89897b7d-a91d-3f42-ae60-96ddd36c757b.jpg', 'Quo et debitis id quia quas blanditiis et officia libero laboriosam velit error ipsum officiis omnis esse quidem qui aut molestiae placeat error aliquam sed odit.', NULL, 5, 9, NULL, NULL, NULL, NULL),
(89, 19, 6, 3, 'CONS-20251008-5004', 'training', 'Worker training session', 'electrical', 'Panel Connection Area', 2, 'piece', 31.00, 3.00, 66.00, 'good', 'completed', 0, 1, 2000.00, 4000.00, 120.00, 'Project-3-electrical', '2025-10-08', '11:25:00', '18:47:00', 2, 'photo', '/uploads/consumption/223e288d-a8e4-3e4d-8409-36b50449078f.jpg', NULL, NULL, 3, NULL, 6, '2025-10-08 10:35:26', NULL, NULL),
(90, 20, 6, 3, 'CONS-20250924-3012', 'training', 'Worker training session', 'electrical', 'Panel Connection Area', 12, 'piece', 54.00, 2.00, 44.00, 'good', 'damaged', 0, 1, 850.00, 10200.00, 204.00, 'Project-3-electrical', '2025-09-24', '18:05:00', '17:09:00', 0, 'receipt', '/uploads/consumption/3e962def-9e76-3486-9db5-83650e8f635b.jpg', 'Sit et animi iusto alias ea rerum voluptatum eum quia voluptatem rerum ad soluta impedit accusamus autem illo dolores delectus culpa hic dolores incidunt explicabo.', NULL, 7, 3, NULL, NULL, NULL, NULL),
(91, 21, 6, 3, 'CONS-20251019-8344', 'repair', 'Electrical fault correction', 'preparation', 'Storage Area', 98, 'piece', 68.00, 13.00, 19.00, 'good', 'damaged', 1, 1, 120.00, 11760.00, 1528.80, 'Project-3-preparation', '2025-10-19', '15:41:00', '21:48:00', 4, 'receipt', '/uploads/consumption/451bba9e-bd4b-31a7-8535-ea64d055e016.jpg', NULL, NULL, 6, 10, NULL, NULL, NULL, NULL),
(92, 22, 9, 4, 'CONS-20251007-0942', 'repair', 'Connection repair', 'structure', 'Framework Assembly Area', 6, 'piece', 58.00, 6.00, 36.00, 'good', 'partial', 1, 1, 3500.00, 21000.00, 1260.00, 'Project-4-structure', '2025-10-07', '17:36:00', '18:05:00', 6, 'report', '/uploads/consumption/75092d1c-087a-343c-bb46-2d9a0d25fc1e.jpg', 'Aliquid voluptatem ut voluptas minus illo eveniet qui quia dolorem porro error vel soluta maiores nihil magni eum voluptatem est.', NULL, 2, 1, NULL, NULL, NULL, NULL),
(93, 23, 9, 4, 'CONS-20251018-7921', 'demo', 'Client presentation setup', 'foundation', 'Site B - Concrete Pouring Zone', 1, 'piece', 43.00, 5.00, 52.00, 'expired', 'completed', 0, 1, 8500.00, 8500.00, 425.00, 'Project-4-foundation', '2025-10-18', '18:53:00', '16:33:00', 7, 'video', '/uploads/consumption/0097981a-e9dc-343b-ae32-c262604df215.jpg', NULL, 'Voluptas non adipisci vero architecto voluptatibus itaque corrupti maxime ea ea ad sit qui error nesciunt.', 8, 8, 3, '2025-10-18 08:49:50', NULL, NULL),
(94, 24, 9, 4, 'CONS-20251017-6467', 'installation', 'Cable tray installation', 'structure', 'Framework Assembly Area', 3, 'piece', 94.00, 4.00, 2.00, 'defective', 'partial', 0, 1, 4500.00, 13500.00, 540.00, 'Project-4-structure', '2025-10-17', '19:21:00', '14:21:00', 0, 'report', '/uploads/consumption/62cebc29-3d30-3547-a0a1-aa7552eabc78.jpg', 'Dolores aut sit ut explicabo fugiat eos magnam perferendis ad quidem omnis consectetur quas voluptatum deserunt.', 'Eveniet officiis autem voluptatibus aliquam necessitatibus qui totam at aut ullam numquam commodi consectetur veritatis saepe.', 4, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `material_consumptions` (`id`, `material_id`, `material_request_id`, `project_id`, `consumption_number`, `activity_type`, `activity_description`, `work_phase`, `work_location`, `quantity_consumed`, `unit_of_measurement`, `consumption_percentage`, `wastage_percentage`, `return_percentage`, `quality_status`, `consumption_status`, `waste_disposed`, `return_to_stock`, `unit_cost`, `total_cost`, `wastage_cost`, `cost_center`, `consumption_date`, `start_time`, `end_time`, `duration_hours`, `documentation_type`, `documentation_path`, `notes`, `quality_observations`, `consumed_by`, `supervised_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(95, 25, 9, 4, 'CONS-20250914-3171', 'testing', 'System commissioning test', 'maintenance', 'Equipment Service Area', 1, 'piece', 59.00, 4.00, 37.00, 'expired', 'completed', 0, 1, 12000.00, 12000.00, 480.00, 'Project-4-maintenance', '2025-09-14', '10:54:00', '18:47:00', 0, 'video', '/uploads/consumption/f2c72723-d3a4-3858-aba8-0c51f661a510.jpg', 'Hic praesentium perferendis velit laudantium quod nam inventore est adipisci omnis in sint sed qui voluptate facere non occaecati natus cupiditate hic velit quae.', 'Inventore earum nihil voluptatem qui sunt qui molestias omnis fugit error sint dolores libero eligendi a ab labore.', 3, 3, 2, '2025-09-14 12:26:38', NULL, NULL),
(96, 26, 1, 1, 'CONS-20251204-4600', 'maintenance', 'Safety equipment inspection', 'commissioning', 'Final Testing Area', 1, 'piece', 65.00, 2.00, 33.00, 'good', 'damaged', 0, 1, 850.00, 850.00, 17.00, 'Project-1-commissioning', '2025-12-04', '06:07:00', '18:42:00', 5, 'report', '/uploads/consumption/25f03a29-2437-375e-8c88-7f4358d026af.jpg', NULL, NULL, 7, 8, NULL, NULL, NULL, NULL),
(97, 27, 1, 1, 'CONS-20251011-0795', 'repair', 'Structural damage restoration', 'commissioning', 'System Commissioning Zone', 1, 'set', 45.00, 8.00, 47.00, 'good', 'completed', 1, 1, 1200.00, 1200.00, 96.00, 'Project-1-commissioning', '2025-10-11', '01:08:00', '19:02:00', 7, 'photo', '/uploads/consumption/be3d5800-3f15-3b7c-973e-0cfe398df566.jpg', 'Esse ipsa aut pariatur necessitatibus vero molestias officiis culpa aut nemo provident sint quas consequuntur ut vitae laudantium ratione.', NULL, 7, NULL, 2, '2025-10-11 20:29:05', NULL, NULL),
(98, 28, 1, 1, 'CONS-20251119-5957', 'installation', 'Inverter mounting and connection', 'preparation', 'Storage Area', 1, 'piece', 81.00, 8.00, 11.00, 'defective', 'completed', 1, 1, 4500.00, 4500.00, 360.00, 'Project-1-preparation', '2025-11-19', '10:04:00', '16:06:00', 6, 'receipt', '/uploads/consumption/5365f3bf-0296-3e5e-87c2-04d8de4b776f.jpg', NULL, 'Eos magni eaque tempora voluptates odio ea voluptas culpa qui temporibus asperiores quae impedit et vitae minus molestias incidunt.', 10, 1, 6, '2025-11-19 01:23:35', NULL, NULL),
(99, 29, 1, 1, 'CONS-20251102-2134', 'installation', 'Safety system installation', 'foundation', 'Base Preparation Area', 1, 'kit', 81.00, 8.00, 11.00, 'expired', 'in_progress', 1, 1, 3800.00, 3800.00, 304.00, 'Project-1-foundation', '2025-11-02', '03:34:00', '12:17:00', 0, 'receipt', '/uploads/consumption/f996b3fb-feff-3e8e-a856-c6117af9fafc.jpg', NULL, 'Est omnis nesciunt ut dolore distinctio rerum quia dicta debitis ipsam.', 5, 7, NULL, NULL, NULL, NULL),
(100, 30, 1, 1, 'CONS-20251118-5193', 'maintenance', 'Panel cleaning and inspection', 'structure', 'Framework Assembly Area', 1, 'kit', 61.00, 1.00, 38.00, 'defective', 'completed', 0, 1, 750.00, 750.00, 7.50, 'Project-1-structure', '2025-11-18', '02:35:00', '18:07:00', 4, 'video', '/uploads/consumption/78400951-5ee1-3f7b-ad7b-750005693152.jpg', 'Rerum minus ut neque alias fugit ut qui quia voluptatem suscipit dolor aspernatur repellendus consequuntur.', 'Eligendi hic voluptatem exercitationem delectus quos at nobis quisquam nulla.', 12, NULL, 6, '2025-11-18 18:44:28', NULL, NULL),
(101, 31, 13, 2, 'CONS-20251013-5224', 'demo', 'System demonstration', 'other', 'Support Facility', 37, 'piece', 43.00, 4.00, 53.00, 'good', 'damaged', 0, 1, 2500.00, 92500.00, 3700.00, 'Project-2-other', '2025-10-13', '13:40:00', '16:37:00', 0, 'photo', '/uploads/consumption/226b5eae-3814-3510-8a90-cb3ca1c3bb67.jpg', 'Exercitationem dolore deleniti esse doloribus eum ut maiores sunt minus quia aut doloremque neque similique accusantium sit cum non.', NULL, 3, 11, NULL, NULL, NULL, NULL),
(102, 32, 13, 2, 'CONS-20251021-8676', 'maintenance', 'Safety equipment inspection', 'maintenance', 'Staging Area', 5, 'piece', 75.00, 2.00, 23.00, 'good', 'completed', 0, 1, 1500.00, 7500.00, 150.00, 'Project-2-maintenance', '2025-10-21', '14:46:00', '08:02:00', 3, 'video', '/uploads/consumption/7661c3ab-8995-3dd9-ae4e-8c2fd93614db.jpg', 'Ut blanditiis placeat voluptas consequuntur dignissimos velit nemo id est ab sapiente aliquam recusandae eaque sed et voluptatem libero error qui magnam.', NULL, 1, 10, 2, '2025-10-21 02:04:01', NULL, NULL),
(103, 33, 13, 2, 'CONS-20251030-2204', 'training', 'Worker training session', 'structure', 'Main Structure Site', 68, 'piece', 32.00, 0.00, 68.00, 'good', 'in_progress', 0, 1, 45.00, 3060.00, 0.00, 'Project-2-structure', '2025-10-30', '04:03:00', '18:47:00', 8, 'video', '/uploads/consumption/952995fb-b7eb-3498-921c-1b77ad5b64d9.jpg', NULL, NULL, 2, 9, NULL, NULL, NULL, NULL),
(104, 34, 13, 2, 'CONS-20251207-7320', 'training', 'Equipment operation training', 'preparation', 'Storage Area', 82, 'piece', 60.00, 4.00, 36.00, 'damaged', 'in_progress', 0, 1, 25.00, 2050.00, 82.00, 'Project-2-preparation', '2025-12-07', '01:13:00', '11:53:00', 2, 'photo', '/uploads/consumption/ed79a29e-6ff3-3a6c-9336-af0acac5cb4f.jpg', NULL, 'Rem aut est laborum itaque non odio molestiae non reprehenderit iusto vero occaecati est vitae quidem accusamus.', 4, 7, NULL, NULL, NULL, NULL),
(105, 35, 13, 2, 'CONS-20250913-5358', 'training', 'Safety training program', 'structure', 'Framework Assembly Area', 2, 'piece', 48.00, 3.00, 49.00, 'good', 'completed', 0, 1, 45000.00, 90000.00, 2700.00, 'Project-2-structure', '2025-09-13', '12:30:00', '22:43:00', 6, 'receipt', '/uploads/consumption/dad261d9-dede-39d1-88ec-dd78fd90a0d3.jpg', 'Nam tenetur enim modi eum nobis ex natus atque blanditiis quam dicta libero error iste non sed cumque quisquam a et non dolore et et.', NULL, 8, 4, 9, '2025-09-13 19:24:51', NULL, NULL),
(106, 36, 14, 5, 'CONS-20251029-2495', 'training', 'Worker training session', 'foundation', 'Site A - Foundation Area', 18, 'piece', 28.00, 1.00, 71.00, 'good', 'completed', 0, 1, 800.00, 14400.00, 144.00, 'Project-5-foundation', '2025-10-29', '19:13:00', '17:56:00', 3, 'photo', '/uploads/consumption/0e7af4e6-e428-38d7-b2f1-7d4396232330.jpg', 'Et necessitatibus quas non incidunt exercitationem ipsa voluptatem provident possimus deleniti voluptatem itaque iusto minima necessitatibus et vero omnis aut nemo itaque molestiae harum voluptatem dolorem adipisci quia.', NULL, 5, 13, 10, '2025-10-29 08:06:38', NULL, NULL),
(107, 37, 14, 5, 'CONS-20250913-3528', 'demo', 'System demonstration', 'preparation', 'Pre-production Area', 12, 'piece', 21.00, 0.00, 79.00, 'good', 'damaged', 0, 1, 2500.00, 30000.00, 0.00, 'Project-5-preparation', '2025-09-13', '21:33:00', '18:02:00', 1, 'receipt', '/uploads/consumption/d6d7b40a-e4ef-3c72-b883-1c466da3578d.jpg', 'Reprehenderit molestiae odio voluptas et dolor adipisci debitis temporibus deleniti libero ea reprehenderit consectetur quos provident repellendus molestiae totam fuga laudantium.', NULL, 6, 4, NULL, NULL, NULL, NULL),
(108, 38, 14, 5, 'CONS-20251025-8227', 'training', 'Worker training session', 'commissioning', 'Performance Test Site', 45, 'piece', 44.00, 3.00, 53.00, 'good', 'completed', 0, 1, 300.00, 13500.00, 405.00, 'Project-5-commissioning', '2025-10-25', '17:10:00', '17:46:00', 8, 'video', '/uploads/consumption/97444291-e52a-306d-ad88-bb38d3e61090.jpg', 'Natus eum eaque velit magni quo sunt rerum ipsa molestiae aut dolor repudiandae nihil ex a ut sed aut.', NULL, 6, 12, 3, '2025-10-25 11:29:38', NULL, NULL),
(109, 39, 14, 5, 'CONS-20251111-9933', 'training', 'Safety training program', 'electrical', 'Panel Connection Area', 3, 'piece', 21.00, 5.00, 74.00, 'damaged', 'completed', 0, 1, 1500.00, 4500.00, 225.00, 'Project-5-electrical', '2025-11-11', '17:45:00', '11:37:00', 4, 'report', '/uploads/consumption/e33cfced-9490-378d-ba88-bd5a3516f8af.jpg', 'Quo doloremque veniam omnis consectetur id aut doloribus ut eius aliquid aut at tempore earum laboriosam incidunt quae sint nemo alias eligendi.', 'Nisi est eum pariatur aperiam excepturi cupiditate eligendi blanditiis ipsum dolorum nam mollitia natus.', 2, 1, 4, '2025-11-10 22:27:24', NULL, NULL),
(110, 40, 14, 5, 'CONS-20251115-5288', 'training', 'Worker training session', 'electrical', 'Panel Connection Area', 2, 'piece', 50.00, 3.00, 47.00, 'damaged', 'completed', 0, 1, 2500.00, 5000.00, 150.00, 'Project-5-electrical', '2025-11-15', '09:34:00', '17:11:00', 4, 'report', '/uploads/consumption/06e5ab77-2f0b-355f-b506-5b757501e586.jpg', 'Vitae odit velit debitis necessitatibus alias rem dolore natus enim nulla aut consequatur iste non omnis sunt culpa aliquam illo maxime aperiam doloremque consectetur quibusdam accusantium quibusdam.', 'Quae sit reiciendis dolorem inventore molestiae iusto impedit dolor dolorem omnis consequatur aut tempora tempora ut hic non eos recusandae.', 5, 8, 8, '2025-11-15 03:33:36', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('draft','pending','approved','in_progress','partial','completed','rejected','cancelled') NOT NULL DEFAULT 'draft',
  `category` enum('raw_materials','tools_equipment','consumables','safety_items','electrical','mechanical','other') NOT NULL DEFAULT 'raw_materials',
  `request_type` enum('purchase','rental','transfer','emergency') NOT NULL DEFAULT 'purchase',
  `required_date` date NOT NULL,
  `approved_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `approved_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `consumed_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'INR',
  `urgency_reason` enum('normal','delay_risk','deadline_critical','equipment_failure','weather_dependent') NOT NULL DEFAULT 'normal',
  `justification` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `days_until_required` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `request_number`, `title`, `description`, `project_id`, `priority`, `status`, `category`, `request_type`, `required_date`, `approved_date`, `completion_date`, `total_amount`, `approved_amount`, `consumed_amount`, `currency`, `urgency_reason`, `justification`, `rejection_reason`, `specifications`, `attachments`, `requested_by`, `approved_by`, `assigned_to`, `notes`, `is_urgent`, `days_until_required`, `created_at`, `updated_at`) VALUES
(1, 'MR-3356', 'Solar Panel Installation Materials - Phase 1', 'Materials required for solar panel installation including panels, mounting hardware, cables, and electrical components for Phase 1 of the project.', 1, 'high', 'approved', 'electrical', 'purchase', '2024-05-15', '2024-05-10', NULL, 450000.00, 420000.00, 380000.00, 'INR', 'delay_risk', 'Critical materials needed to maintain project timeline', NULL, NULL, NULL, 1, 4, NULL, NULL, 0, -575, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'MR-2835', 'Safety Equipment and Tools', 'Safety equipment including helmets, harnesses, gloves, safety shoes, and cutting tools for site operations.', 2, 'medium', 'completed', 'safety_items', 'purchase', '2024-06-01', '2024-05-25', '2024-05-30', 85000.00, 85000.00, 85000.00, 'INR', 'normal', 'Standard safety equipment for site operations', NULL, NULL, NULL, 2, 4, NULL, NULL, 0, -558, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'MR-3335', 'Emergency Backup Generator', 'Emergency backup generator for site operations during power outages.', 4, 'urgent', 'in_progress', 'tools_equipment', 'rental', '2024-05-05', '2024-05-02', NULL, 25000.00, 25000.00, 15000.00, 'INR', 'equipment_failure', 'Critical site operations require continuous power supply', NULL, NULL, NULL, 12, 4, 13, NULL, 0, -585, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'MR-0958', 'Raw Materials for Structure', 'Steel beams, concrete, rebar, and other structural materials for foundation and mounting structure.', 5, 'high', 'pending', 'raw_materials', 'purchase', '2024-05-20', NULL, NULL, 280000.00, 0.00, 0.00, 'INR', 'deadline_critical', 'Structure foundation required before panel installation', NULL, NULL, NULL, 9, NULL, NULL, NULL, 0, -570, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'MR-9537', 'Cable Management System', 'Cable trays, conduits, junction boxes, and cable management accessories for electrical installation.', 5, 'medium', 'approved', 'electrical', 'purchase', '2024-05-25', '2024-05-20', NULL, 65000.00, 62000.00, 0.00, 'INR', 'normal', 'Required for cable routing and protection', NULL, NULL, NULL, 10, 4, NULL, NULL, 0, -565, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'MR-6799', 'Consumable Supplies', 'Office supplies, bolts, nuts, washers, tape, and other consumable items for daily operations.', 3, 'low', 'draft', 'consumables', 'purchase', '2024-06-10', NULL, NULL, 25000.00, 0.00, 0.00, 'INR', 'normal', 'Regular supplies for site maintenance', NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, -549, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'MR-4655', 'Heavy Equipment Transfer', 'Transfer of excavator and crane from site A to site B for excavation work.', 1, 'medium', 'completed', 'tools_equipment', 'transfer', '2024-05-08', '2024-05-05', '2024-05-07', 15000.00, 15000.00, 15000.00, 'INR', 'delay_risk', 'Equipment needed for site preparation', NULL, NULL, NULL, 2, 4, NULL, NULL, 0, -582, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'MR-2969', 'Weather Monitoring Equipment', 'Weather station and monitoring equipment for site safety during adverse weather conditions.', 1, 'medium', 'approved', 'tools_equipment', 'rental', '2024-05-12', '2024-05-08', NULL, 18000.00, 18000.00, 8000.00, 'INR', 'weather_dependent', 'Safety monitoring for outdoor work', NULL, NULL, NULL, 5, 4, NULL, NULL, 0, -578, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'MR-4954', 'Quality Control Instruments', 'Multimeters, insulation testers, current clamps, and other testing equipment for quality verification.', 4, 'high', 'partial', 'tools_equipment', 'purchase', '2024-05-18', '2024-05-15', NULL, 95000.00, 95000.00, 62000.00, 'INR', 'deadline_critical', 'Required for electrical testing and commissioning', NULL, NULL, NULL, 11, 4, NULL, NULL, 0, -572, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'MR-0845', 'Communication Equipment', 'Wireless communication system, walkie-talkies, and communication infrastructure for site coordination.', 3, 'medium', 'pending', 'tools_equipment', 'rental', '2024-05-30', NULL, NULL, 12000.00, 0.00, 0.00, 'INR', 'normal', 'Essential for site coordination and safety', NULL, NULL, NULL, 6, NULL, NULL, NULL, 0, -560, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'MR-9276', 'Material Transport Vehicles', 'Logistics arrangements for material transportation from warehouse to site locations.', 5, 'urgent', 'rejected', 'tools_equipment', 'rental', '2024-05-04', NULL, NULL, 22000.00, 0.00, 0.00, 'INR', 'equipment_failure', 'Urgent transportation needed for material delivery', 'Budget constraints - alternative transportation arranged', NULL, NULL, 12, NULL, NULL, NULL, 0, -586, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(12, 'MR-5844', 'Site Documentation Materials', 'Documentation supplies including folders, labels, markers, and digital storage devices.', 5, 'low', 'draft', 'consumables', 'purchase', '2024-06-05', NULL, NULL, 8000.00, 0.00, 0.00, 'INR', 'normal', 'Standard documentation supplies for project records', NULL, NULL, NULL, 3, NULL, NULL, NULL, 0, -554, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(13, 'MR-2461', 'Solar Panel Installation Materials - Phase 1', 'Materials required for solar panel installation including panels, mounting hardware, cables, and electrical components for Phase 1 of the project.', 2, 'high', 'approved', 'electrical', 'purchase', '2024-05-15', '2024-05-10', NULL, 450000.00, 420000.00, 380000.00, 'INR', 'delay_risk', 'Critical materials needed to maintain project timeline', NULL, NULL, NULL, 7, 4, NULL, NULL, 0, -575, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(14, 'MR-1293', 'Safety Equipment and Tools', 'Safety equipment including helmets, harnesses, gloves, safety shoes, and cutting tools for site operations.', 5, 'medium', 'completed', 'safety_items', 'purchase', '2024-06-01', '2024-05-25', '2024-05-30', 85000.00, 85000.00, 85000.00, 'INR', 'normal', 'Standard safety equipment for site operations', NULL, NULL, NULL, 13, 4, NULL, NULL, 0, -558, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(15, 'MR-4273', 'Emergency Backup Generator', 'Emergency backup generator for site operations during power outages.', 2, 'urgent', 'in_progress', 'tools_equipment', 'rental', '2024-05-05', '2024-05-02', NULL, 25000.00, 25000.00, 15000.00, 'INR', 'equipment_failure', 'Critical site operations require continuous power supply', NULL, NULL, NULL, 2, 4, 13, NULL, 0, -585, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(16, 'MR-5128', 'Raw Materials for Structure', 'Steel beams, concrete, rebar, and other structural materials for foundation and mounting structure.', 2, 'high', 'pending', 'raw_materials', 'purchase', '2024-05-20', NULL, NULL, 280000.00, 0.00, 0.00, 'INR', 'deadline_critical', 'Structure foundation required before panel installation', NULL, NULL, NULL, 3, NULL, NULL, NULL, 0, -570, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(17, 'MR-9159', 'Cable Management System', 'Cable trays, conduits, junction boxes, and cable management accessories for electrical installation.', 2, 'medium', 'approved', 'electrical', 'purchase', '2024-05-25', '2024-05-20', NULL, 65000.00, 62000.00, 0.00, 'INR', 'normal', 'Required for cable routing and protection', NULL, NULL, NULL, 3, 4, NULL, NULL, 0, -565, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(18, 'MR-7616', 'Consumable Supplies', 'Office supplies, bolts, nuts, washers, tape, and other consumable items for daily operations.', 3, 'low', 'draft', 'consumables', 'purchase', '2024-06-10', NULL, NULL, 25000.00, 0.00, 0.00, 'INR', 'normal', 'Regular supplies for site maintenance', NULL, NULL, NULL, 10, NULL, NULL, NULL, 0, -549, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(19, 'MR-1381', 'Heavy Equipment Transfer', 'Transfer of excavator and crane from site A to site B for excavation work.', 3, 'medium', 'completed', 'tools_equipment', 'transfer', '2024-05-08', '2024-05-05', '2024-05-07', 15000.00, 15000.00, 15000.00, 'INR', 'delay_risk', 'Equipment needed for site preparation', NULL, NULL, NULL, 6, 4, NULL, NULL, 0, -582, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(20, 'MR-5641', 'Weather Monitoring Equipment', 'Weather station and monitoring equipment for site safety during adverse weather conditions.', 3, 'medium', 'approved', 'tools_equipment', 'rental', '2024-05-12', '2024-05-08', NULL, 18000.00, 18000.00, 8000.00, 'INR', 'weather_dependent', 'Safety monitoring for outdoor work', NULL, NULL, NULL, 11, 4, NULL, NULL, 0, -578, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(21, 'MR-9511', 'Quality Control Instruments', 'Multimeters, insulation testers, current clamps, and other testing equipment for quality verification.', 5, 'high', 'partial', 'tools_equipment', 'purchase', '2024-05-18', '2024-05-15', NULL, 95000.00, 95000.00, 62000.00, 'INR', 'deadline_critical', 'Required for electrical testing and commissioning', NULL, NULL, NULL, 5, 4, NULL, NULL, 0, -572, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(22, 'MR-1321', 'Communication Equipment', 'Wireless communication system, walkie-talkies, and communication infrastructure for site coordination.', 4, 'medium', 'pending', 'tools_equipment', 'rental', '2024-05-30', NULL, NULL, 12000.00, 0.00, 0.00, 'INR', 'normal', 'Essential for site coordination and safety', NULL, NULL, NULL, 4, NULL, NULL, NULL, 0, -560, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(23, 'MR-3961', 'Material Transport Vehicles', 'Logistics arrangements for material transportation from warehouse to site locations.', 5, 'urgent', 'rejected', 'tools_equipment', 'rental', '2024-05-04', NULL, NULL, 22000.00, 0.00, 0.00, 'INR', 'equipment_failure', 'Urgent transportation needed for material delivery', 'Budget constraints - alternative transportation arranged', NULL, NULL, 11, NULL, NULL, NULL, 0, -586, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(24, 'MR-1957', 'Site Documentation Materials', 'Documentation supplies including folders, labels, markers, and digital storage devices.', 5, 'low', 'draft', 'consumables', 'purchase', '2024-06-05', NULL, NULL, 8000.00, 0.00, 0.00, 'INR', 'normal', 'Standard documentation supplies for project records', NULL, NULL, NULL, 8, NULL, NULL, NULL, 0, -554, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(25, 'MR-3173', 'Solar Panel Installation Materials - Phase 1', 'Materials required for solar panel installation including panels, mounting hardware, cables, and electrical components for Phase 1 of the project.', 3, 'high', 'approved', 'electrical', 'purchase', '2024-05-15', '2024-05-10', NULL, 450000.00, 420000.00, 380000.00, 'INR', 'delay_risk', 'Critical materials needed to maintain project timeline', NULL, NULL, NULL, 8, 4, NULL, NULL, 0, -575, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(26, 'MR-5453', 'Safety Equipment and Tools', 'Safety equipment including helmets, harnesses, gloves, safety shoes, and cutting tools for site operations.', 1, 'medium', 'completed', 'safety_items', 'purchase', '2024-06-01', '2024-05-25', '2024-05-30', 85000.00, 85000.00, 85000.00, 'INR', 'normal', 'Standard safety equipment for site operations', NULL, NULL, NULL, 10, 4, NULL, NULL, 0, -558, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(27, 'MR-5146', 'Emergency Backup Generator', 'Emergency backup generator for site operations during power outages.', 4, 'urgent', 'in_progress', 'tools_equipment', 'rental', '2024-05-05', '2024-05-02', NULL, 25000.00, 25000.00, 15000.00, 'INR', 'equipment_failure', 'Critical site operations require continuous power supply', NULL, NULL, NULL, 7, 4, 13, NULL, 0, -585, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(28, 'MR-4978', 'Raw Materials for Structure', 'Steel beams, concrete, rebar, and other structural materials for foundation and mounting structure.', 3, 'high', 'pending', 'raw_materials', 'purchase', '2024-05-20', NULL, NULL, 280000.00, 0.00, 0.00, 'INR', 'deadline_critical', 'Structure foundation required before panel installation', NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, -570, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(29, 'MR-1340', 'Cable Management System', 'Cable trays, conduits, junction boxes, and cable management accessories for electrical installation.', 4, 'medium', 'approved', 'electrical', 'purchase', '2024-05-25', '2024-05-20', NULL, 65000.00, 62000.00, 0.00, 'INR', 'normal', 'Required for cable routing and protection', NULL, NULL, NULL, 11, 4, NULL, NULL, 0, -565, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(30, 'MR-2488', 'Consumable Supplies', 'Office supplies, bolts, nuts, washers, tape, and other consumable items for daily operations.', 5, 'low', 'draft', 'consumables', 'purchase', '2024-06-10', NULL, NULL, 25000.00, 0.00, 0.00, 'INR', 'normal', 'Regular supplies for site maintenance', NULL, NULL, NULL, 6, NULL, NULL, NULL, 0, -549, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(31, 'MR-5760', 'Heavy Equipment Transfer', 'Transfer of excavator and crane from site A to site B for excavation work.', 3, 'medium', 'completed', 'tools_equipment', 'transfer', '2024-05-08', '2024-05-05', '2024-05-07', 15000.00, 15000.00, 15000.00, 'INR', 'delay_risk', 'Equipment needed for site preparation', NULL, NULL, NULL, 5, 4, NULL, NULL, 0, -582, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(32, 'MR-7200', 'Weather Monitoring Equipment', 'Weather station and monitoring equipment for site safety during adverse weather conditions.', 5, 'medium', 'approved', 'tools_equipment', 'rental', '2024-05-12', '2024-05-08', NULL, 18000.00, 18000.00, 8000.00, 'INR', 'weather_dependent', 'Safety monitoring for outdoor work', NULL, NULL, NULL, 9, 4, NULL, NULL, 0, -578, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(33, 'MR-4546', 'Quality Control Instruments', 'Multimeters, insulation testers, current clamps, and other testing equipment for quality verification.', 1, 'high', 'partial', 'tools_equipment', 'purchase', '2024-05-18', '2024-05-15', NULL, 95000.00, 95000.00, 62000.00, 'INR', 'deadline_critical', 'Required for electrical testing and commissioning', NULL, NULL, NULL, 11, 4, NULL, NULL, 0, -572, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(34, 'MR-1320', 'Communication Equipment', 'Wireless communication system, walkie-talkies, and communication infrastructure for site coordination.', 1, 'medium', 'pending', 'tools_equipment', 'rental', '2024-05-30', NULL, NULL, 12000.00, 0.00, 0.00, 'INR', 'normal', 'Essential for site coordination and safety', NULL, NULL, NULL, 2, NULL, NULL, NULL, 0, -560, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(35, 'MR-4561', 'Material Transport Vehicles', 'Logistics arrangements for material transportation from warehouse to site locations.', 4, 'urgent', 'rejected', 'tools_equipment', 'rental', '2024-05-04', NULL, NULL, 22000.00, 0.00, 0.00, 'INR', 'equipment_failure', 'Urgent transportation needed for material delivery', 'Budget constraints - alternative transportation arranged', NULL, NULL, 6, NULL, NULL, NULL, 0, -586, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(36, 'MR-6604', 'Site Documentation Materials', 'Documentation supplies including folders, labels, markers, and digital storage devices.', 4, 'low', 'draft', 'consumables', 'purchase', '2024-06-05', NULL, NULL, 8000.00, 0.00, 0.00, 'INR', 'normal', 'Standard documentation supplies for project records', NULL, NULL, NULL, 8, NULL, NULL, NULL, 0, -554, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(37, 'MR-4615', 'Solar Panel Installation Materials - Phase 1', 'Materials required for solar panel installation including panels, mounting hardware, cables, and electrical components for Phase 1 of the project.', 4, 'high', 'approved', 'electrical', 'purchase', '2024-05-15', '2024-05-10', NULL, 450000.00, 420000.00, 380000.00, 'INR', 'delay_risk', 'Critical materials needed to maintain project timeline', NULL, NULL, NULL, 2, 4, NULL, NULL, 0, -575, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(38, 'MR-6504', 'Safety Equipment and Tools', 'Safety equipment including helmets, harnesses, gloves, safety shoes, and cutting tools for site operations.', 3, 'medium', 'completed', 'safety_items', 'purchase', '2024-06-01', '2024-05-25', '2024-05-30', 85000.00, 85000.00, 85000.00, 'INR', 'normal', 'Standard safety equipment for site operations', NULL, NULL, NULL, 7, 4, NULL, NULL, 0, -558, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(39, 'MR-4340', 'Emergency Backup Generator', 'Emergency backup generator for site operations during power outages.', 5, 'urgent', 'in_progress', 'tools_equipment', 'rental', '2024-05-05', '2024-05-02', NULL, 25000.00, 25000.00, 15000.00, 'INR', 'equipment_failure', 'Critical site operations require continuous power supply', NULL, NULL, NULL, 9, 4, 13, NULL, 0, -585, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(40, 'MR-5206', 'Raw Materials for Structure', 'Steel beams, concrete, rebar, and other structural materials for foundation and mounting structure.', 5, 'high', 'pending', 'raw_materials', 'purchase', '2024-05-20', NULL, NULL, 280000.00, 0.00, 0.00, 'INR', 'deadline_critical', 'Structure foundation required before panel installation', NULL, NULL, NULL, 11, NULL, NULL, NULL, 0, -570, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(41, 'MR-5649', 'Cable Management System', 'Cable trays, conduits, junction boxes, and cable management accessories for electrical installation.', 3, 'medium', 'approved', 'electrical', 'purchase', '2024-05-25', '2024-05-20', NULL, 65000.00, 62000.00, 0.00, 'INR', 'normal', 'Required for cable routing and protection', NULL, NULL, NULL, 4, 4, NULL, NULL, 0, -565, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_12_11_183551_create_lead_calls_table', 1),
(2, '2014_10_12_000000_create_users_table', 2),
(3, '2014_10_12_100000_create_password_reset_tokens_table', 2),
(4, '2019_08_19_000000_create_failed_jobs_table', 2),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 2),
(6, '2025_10_03_050053_create_permission_tables', 2),
(7, '2025_10_03_050057_add_additional_fields_to_users_table', 2),
(8, '2025_10_03_050111_create_leads_table', 2),
(9, '2025_10_03_050117_create_projects_table', 2),
(10, '2025_10_03_050123_create_tasks_table', 2),
(11, '2025_10_03_050243_create_products_table', 2),
(12, '2025_10_03_050249_create_vendors_table', 2),
(13, '2025_10_03_050255_create_invoices_table', 2),
(14, '2025_10_03_061018_create_quotations_table', 2),
(15, '2025_10_03_063819_create_documents_table', 2),
(16, '2025_10_03_100331_create_notifications_table', 2),
(17, '2025_10_03_100945_create_costings_table', 2),
(18, '2025_10_03_104125_create_channel_partners_table', 2),
(19, '2025_10_03_104915_create_commissions_table', 2),
(20, '2025_10_03_105454_add_channel_partner_id_to_leads_table', 2),
(21, '2025_10_03_105656_add_channel_partner_id_to_projects_table', 2),
(22, '2025_10_03_105846_add_channel_partner_id_to_invoices_table', 2),
(23, '2025_10_03_105849_add_channel_partner_id_to_quotations_table', 2),
(24, '2025_10_03_123011_create_escalations_table', 2),
(25, '2025_10_03_125330_create_activities_table', 2),
(26, '2025_10_03_131710_create_resource_allocations_table', 2),
(27, '2025_10_04_042739_create_expense_categories_table', 2),
(28, '2025_10_04_042741_create_expenses_table', 2),
(29, '2025_10_04_044055_create_project_profitabilities_table', 2),
(30, '2025_10_04_050517_create_budget_categories_table', 2),
(31, '2025_10_04_050532_create_budgets_table', 2),
(32, '2025_10_04_052337_create_payment_milestones_table', 2),
(33, '2025_10_04_053508_create_contractors_table', 2),
(34, '2025_10_04_055029_create_material_requests_table', 2),
(35, '2025_10_04_055032_create_materials_table', 2),
(36, '2025_10_04_060503_create_material_consumptions_table', 2),
(37, '2025_10_06_073535_create_daily_progress_reports_table', 2),
(38, '2025_10_06_075526_create_site_warehouses_table', 2),
(39, '2025_10_06_081231_create_purchase_orders_table', 2),
(40, '2025_10_06_081303_create_purchase_order_items_table', 2),
(41, '2025_10_06_081743_create_purchase_requisitions_table', 2),
(42, '2025_10_06_081749_create_purchase_requisition_items_table', 2),
(43, '2025_10_06_090023_create_payment_requests_table', 2),
(44, '2025_10_06_090057_create_r_f_q_s_table', 2),
(45, '2025_10_06_090102_create_r_f_q_items_table', 2),
(46, '2025_10_06_090129_create_vendor_registrations_table', 2),
(47, '2025_10_06_101153_create_stock_valuations_table', 2),
(48, '2025_10_06_101202_create_stock_ledgers_table', 2),
(49, '2025_10_06_101208_create_quality_checks_table', 2),
(50, '2025_10_06_101214_create_inventory_audits_table', 2),
(51, '2025_10_06_101942_create_complaints_table', 2),
(52, '2025_10_06_101947_create_a_m_c_s_table', 2),
(53, '2025_10_06_101954_create_o_m_maintenances_table', 2),
(54, '2025_10_06_102002_create_employees_table', 2),
(55, '2025_10_06_102008_create_leave_requests_table', 2),
(56, '2025_10_06_102014_create_attendances_table', 2),
(57, '2025_10_06_102021_create_payrolls_table', 2),
(58, '2025_10_06_111442_create_performance_reviews_table', 2),
(59, '2025_10_06_111448_create_appraisals_table', 2),
(60, '2025_10_06_111511_create_job_applications_table', 2),
(61, '2025_10_06_111517_create_expense_claims_table', 2),
(62, '2025_10_06_111523_create_salary_slips_table', 2),
(63, '2025_10_06_112451_create_warehouses_table', 2),
(64, '2025_10_07_033359_add_payment_details_to_invoices_table', 2),
(65, '2025_10_07_034022_add_settings_to_users_table', 2),
(66, '2025_10_07_080449_create_g_r_n_s_table', 2),
(67, '2025_10_14_051243_add_approved_status_to_quotations_table', 2),
(68, '2025_10_14_092017_add_project_engineer_to_projects_table', 2),
(69, '2025_10_14_092418_add_checked_by_to_quality_checks_table', 2),
(70, '2025_10_14_103428_add_audited_by_to_inventory_audits_table', 2),
(71, '2025_10_14_103856_add_liaisoning_officer_to_projects_table', 2),
(72, '2025_10_15_041058_create_company_policies_table', 2),
(73, '2025_10_15_055708_add_vendor_id_to_rfqs_table', 2),
(74, '2025_11_07_093214_create_delete_approvals_table', 2),
(75, '2025_12_04_065903_add_quotation_type_and_followup_fields_to_quotations_table', 2),
(76, '2025_12_04_070351_update_existing_quotations_with_default_type', 2),
(77, '2025_12_04_124959_create_lead_backups_table', 2),
(78, '2025_12_04_142418_add_revision_fields_to_quotations_table', 2),
(79, '2025_12_04_143132_create_site_expenses_table', 2),
(80, '2025_12_04_143133_create_advances_table', 2),
(81, '2025_12_09_201227_create_duplicate_lead_approvals_table', 2),
(82, '2025_12_09_201903_add_availability_fields_to_users_table', 2),
(83, '2025_12_09_202116_add_last_updated_by_to_leads_table', 2),
(84, '2025_12_09_202907_update_leads_status_and_add_followup_fields', 2),
(85, '2025_12_09_205442_add_call_count_to_leads_table', 2),
(86, '2025_12_09_211407_add_last_updated_by_column_to_leads_table_if_not_exists', 2),
(87, '2025_12_09_211733_create_model_backups_table', 2),
(88, '2025_12_11_183550_add_contact_reveal_fields_to_leads_table', 2),
(89, '2025_12_11_184028_create_lead_contact_views_table', 2),
(90, '2025_12_11_190314_create_lead_reassignment_requests_table', 2),
(91, '2025_12_11_193739_update_lead_calls_table_structure', 2),
(92, '2025_12_11_193931_add_multi_level_approval_to_advances_table', 2),
(93, '2025_12_11_193932_add_multi_level_approval_to_expenses_table', 2),
(94, '2025_12_12_190045_create_task_reassignment_requests_table', 3),
(95, '2025_12_12_192848_update_lead_reassignment_requests_table_for_multi_level_approval', 4),
(96, '2025_12_12_195644_create_task_assignment_approvals_table', 5),
(98, '2025_12_12_203911_update_lead_reassignment_requests_table_add_method_field', 6),
(99, '2025_12_13_113841_add_is_reassigned_to_leads_table', 6),
(101, '2025_12_17_184619_add_multi_level_approval_to_site_expenses_table', 7),
(102, '2025_12_17_194139_add_multi_level_approval_to_site_expenses_table', 7),
(103, '2025_12_19_171651_create_todos_table', 8),
(104, '2025_12_20_105308_add_missing_columns_to_todos_table', 9),
(105, '2025_12_20_092543_create_todos_table', 10),
(106, '2026_01_01_200412_add_not_completed_reason_to_todos_table', 11),
(107, '2026_01_01_201348_add_not_completed_reason_to_todos_table', 12),
(108, '2026_01_02_000000_add_lead_stage_to_leads_table', 13),
(109, '2026_01_02_000001_add_consumer_number_and_address_to_leads_table', 13),
(110, '2026_01_02_000002_add_lead_attachments_to_leads_table', 14),
(111, '2026_01_02_000003_add_documents_and_photos_to_leads_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 7),
(7, 'App\\Models\\User', 8),
(8, 'App\\Models\\User', 9),
(9, 'App\\Models\\User', 10),
(10, 'App\\Models\\User', 11),
(11, 'App\\Models\\User', 12),
(12, 'App\\Models\\User', 13),
(13, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `read`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Project Status Update', 'Solar Panel Installation Project has been updated to \"In Progress\" status.', 'project_update', 0, '\"{\\\"project_id\\\":1,\\\"status\\\":\\\"in_progress\\\"}\"', NULL, '2025-12-11 12:22:41', '2025-12-11 14:22:41'),
(2, 2, 'New Task Assigned', 'You have been assigned a new task: \"Site Survey for Commercial Project\".', 'task_assigned', 0, '\"{\\\"task_id\\\":1,\\\"project_id\\\":2}\"', NULL, '2025-12-11 10:22:41', '2025-12-11 14:22:41'),
(3, 3, 'DPR Approval Required', 'Daily Progress Report for \"Residential Solar Installation\" is pending your approval.', 'dpr_approval', 0, '\"{\\\"dpr_id\\\":1,\\\"project_id\\\":1}\"', NULL, '2025-12-11 08:22:41', '2025-12-11 14:22:41'),
(4, 4, 'Milestone Achieved', 'Congratulations! Project \"Solar Farm Development\" has achieved 50% completion milestone.', 'milestone_achieved', 1, '\"{\\\"project_id\\\":3,\\\"milestone\\\":\\\"50% completion\\\"}\"', '2025-12-11 14:25:13', '2025-12-11 06:22:41', '2025-12-11 14:25:13'),
(5, 4, 'Budget Alert', 'Project \"Commercial Solar Installation\" has exceeded 80% of allocated budget.', 'budget_alert', 1, '\"{\\\"project_id\\\":2,\\\"budget_used\\\":\\\"80%\\\"}\"', '2025-12-11 14:25:13', '2025-12-11 04:22:41', '2025-12-11 14:25:13'),
(6, 1, 'Material Request', 'New material request for \"Solar Panels (50 units)\" requires your approval.', 'material_request', 1, '\"{\\\"request_id\\\":1,\\\"material\\\":\\\"Solar Panels\\\"}\"', '2025-12-11 14:25:40', '2025-12-11 02:22:41', '2025-12-11 14:25:40'),
(7, 3, 'Deadline Reminder', 'Task \"Installation Planning\" is due in 2 days. Please complete it on time.', 'deadline_reminder', 0, '\"{\\\"task_id\\\":2,\\\"deadline\\\":\\\"2025-12-13T14:22:41.662612Z\\\"}\"', NULL, '2025-12-11 00:22:41', '2025-12-11 14:22:41'),
(8, 4, 'System Update', 'New features have been added to the Solar ERP system. Check out the updated dashboard.', 'system_update', 1, '\"{\\\"version\\\":\\\"2.1.0\\\",\\\"features\\\":[\\\"Gantt Chart\\\",\\\"DPR System\\\"]}\"', '2025-12-11 14:25:13', '2025-12-10 22:22:41', '2025-12-11 14:25:13'),
(9, 3, 'Expense Approval', 'Expense claim of ₹15,000 for \"Equipment Rental\" is pending your approval.', 'expense_approval', 0, '\"{\\\"expense_id\\\":1,\\\"amount\\\":15000}\"', NULL, '2025-12-10 20:22:41', '2025-12-11 14:22:41'),
(10, 4, 'Quality Check Required', 'Quality inspection is required for completed work at \"Solar Panel Installation Site\".', 'quality_check', 1, '\"{\\\"project_id\\\":1,\\\"location\\\":\\\"Site A\\\"}\"', '2025-12-11 14:25:13', '2025-12-10 18:22:41', '2025-12-11 14:25:13'),
(11, 3, 'Team Meeting Scheduled', 'Weekly project review meeting is scheduled for tomorrow at 10:00 AM.', 'team_meeting', 0, '\"{\\\"meeting_id\\\":1,\\\"time\\\":\\\"10:00 AM\\\"}\"', '2025-12-11 14:07:41', '2025-12-10 16:22:41', '2025-12-11 14:22:41'),
(12, 5, 'Safety Alert', 'Safety inspection required: Workers not wearing helmets at construction site.', 'safety_alert', 0, '\"{\\\"site_id\\\":1,\\\"issue\\\":\\\"Safety equipment\\\"}\"', NULL, '2025-12-10 14:22:41', '2025-12-11 14:22:41'),
(13, 2, 'Project Status Update', 'Solar Panel Installation Project has been updated to \"In Progress\" status.', 'project_update', 0, '\"{\\\"project_id\\\":1,\\\"status\\\":\\\"in_progress\\\"}\"', NULL, '2025-12-11 12:23:22', '2025-12-11 14:23:22'),
(14, 3, 'New Task Assigned', 'You have been assigned a new task: \"Site Survey for Commercial Project\".', 'task_assigned', 0, '\"{\\\"task_id\\\":1,\\\"project_id\\\":2}\"', NULL, '2025-12-11 10:23:22', '2025-12-11 14:23:22'),
(15, 1, 'DPR Approval Required', 'Daily Progress Report for \"Residential Solar Installation\" is pending your approval.', 'dpr_approval', 1, '\"{\\\"dpr_id\\\":1,\\\"project_id\\\":1}\"', '2025-12-11 14:25:40', '2025-12-11 08:23:22', '2025-12-11 14:25:40'),
(16, 2, 'Milestone Achieved', 'Congratulations! Project \"Solar Farm Development\" has achieved 50% completion milestone.', 'milestone_achieved', 0, '\"{\\\"project_id\\\":3,\\\"milestone\\\":\\\"50% completion\\\"}\"', '2025-12-11 13:23:22', '2025-12-11 06:23:22', '2025-12-11 14:23:22'),
(17, 2, 'Budget Alert', 'Project \"Commercial Solar Installation\" has exceeded 80% of allocated budget.', 'budget_alert', 0, '\"{\\\"project_id\\\":2,\\\"budget_used\\\":\\\"80%\\\"}\"', NULL, '2025-12-11 04:23:22', '2025-12-11 14:23:22'),
(18, 4, 'Material Request', 'New material request for \"Solar Panels (50 units)\" requires your approval.', 'material_request', 1, '\"{\\\"request_id\\\":1,\\\"material\\\":\\\"Solar Panels\\\"}\"', '2025-12-11 14:25:13', '2025-12-11 02:23:22', '2025-12-11 14:25:13'),
(19, 1, 'Deadline Reminder', 'Task \"Installation Planning\" is due in 2 days. Please complete it on time.', 'deadline_reminder', 1, '\"{\\\"task_id\\\":2,\\\"deadline\\\":\\\"2025-12-13T14:23:22.681285Z\\\"}\"', '2025-12-11 14:25:40', '2025-12-11 00:23:22', '2025-12-11 14:25:40'),
(20, 1, 'System Update', 'New features have been added to the Solar ERP system. Check out the updated dashboard.', 'system_update', 1, '\"{\\\"version\\\":\\\"2.1.0\\\",\\\"features\\\":[\\\"Gantt Chart\\\",\\\"DPR System\\\"]}\"', '2025-12-11 14:25:40', '2025-12-10 22:23:22', '2025-12-11 14:25:40'),
(21, 5, 'Expense Approval', 'Expense claim of ₹15,000 for \"Equipment Rental\" is pending your approval.', 'expense_approval', 0, '\"{\\\"expense_id\\\":1,\\\"amount\\\":15000}\"', NULL, '2025-12-10 20:23:22', '2025-12-11 14:23:22'),
(22, 1, 'Quality Check Required', 'Quality inspection is required for completed work at \"Solar Panel Installation Site\".', 'quality_check', 1, '\"{\\\"project_id\\\":1,\\\"location\\\":\\\"Site A\\\"}\"', '2025-12-11 14:25:40', '2025-12-10 18:23:22', '2025-12-11 14:25:40'),
(23, 2, 'Team Meeting Scheduled', 'Weekly project review meeting is scheduled for tomorrow at 10:00 AM.', 'team_meeting', 0, '\"{\\\"meeting_id\\\":1,\\\"time\\\":\\\"10:00 AM\\\"}\"', '2025-12-11 14:08:22', '2025-12-10 16:23:22', '2025-12-11 14:23:22'),
(24, 1, 'Safety Alert', 'Safety inspection required: Workers not wearing helmets at construction site.', 'safety_alert', 1, '\"{\\\"site_id\\\":1,\\\"issue\\\":\\\"Safety equipment\\\"}\"', '2025-12-11 14:25:40', '2025-12-10 14:23:22', '2025-12-11 14:25:40'),
(25, 3, 'Project Status Update', 'Solar Panel Installation Project has been updated to \"In Progress\" status.', 'project_update', 0, '\"{\\\"project_id\\\":1,\\\"status\\\":\\\"in_progress\\\"}\"', NULL, '2025-12-11 12:23:30', '2025-12-11 14:23:30'),
(26, 5, 'New Task Assigned', 'You have been assigned a new task: \"Site Survey for Commercial Project\".', 'task_assigned', 0, '\"{\\\"task_id\\\":1,\\\"project_id\\\":2}\"', NULL, '2025-12-11 10:23:30', '2025-12-11 14:23:30'),
(27, 4, 'DPR Approval Required', 'Daily Progress Report for \"Residential Solar Installation\" is pending your approval.', 'dpr_approval', 1, '\"{\\\"dpr_id\\\":1,\\\"project_id\\\":1}\"', '2025-12-11 14:25:13', '2025-12-11 08:23:30', '2025-12-11 14:25:13'),
(28, 3, 'Milestone Achieved', 'Congratulations! Project \"Solar Farm Development\" has achieved 50% completion milestone.', 'milestone_achieved', 0, '\"{\\\"project_id\\\":3,\\\"milestone\\\":\\\"50% completion\\\"}\"', '2025-12-11 13:23:30', '2025-12-11 06:23:30', '2025-12-11 14:23:30'),
(29, 3, 'Budget Alert', 'Project \"Commercial Solar Installation\" has exceeded 80% of allocated budget.', 'budget_alert', 0, '\"{\\\"project_id\\\":2,\\\"budget_used\\\":\\\"80%\\\"}\"', NULL, '2025-12-11 04:23:30', '2025-12-11 14:23:30'),
(30, 1, 'Material Request', 'New material request for \"Solar Panels (50 units)\" requires your approval.', 'material_request', 1, '\"{\\\"request_id\\\":1,\\\"material\\\":\\\"Solar Panels\\\"}\"', '2025-12-11 14:25:40', '2025-12-11 02:23:30', '2025-12-11 14:25:40'),
(31, 3, 'Deadline Reminder', 'Task \"Installation Planning\" is due in 2 days. Please complete it on time.', 'deadline_reminder', 0, '\"{\\\"task_id\\\":2,\\\"deadline\\\":\\\"2025-12-13T14:23:30.866964Z\\\"}\"', NULL, '2025-12-11 00:23:30', '2025-12-11 14:23:30'),
(32, 5, 'System Update', 'New features have been added to the Solar ERP system. Check out the updated dashboard.', 'system_update', 0, '\"{\\\"version\\\":\\\"2.1.0\\\",\\\"features\\\":[\\\"Gantt Chart\\\",\\\"DPR System\\\"]}\"', '2025-12-11 13:53:30', '2025-12-10 22:23:30', '2025-12-11 14:23:30'),
(33, 4, 'Expense Approval', 'Expense claim of ₹15,000 for \"Equipment Rental\" is pending your approval.', 'expense_approval', 1, '\"{\\\"expense_id\\\":1,\\\"amount\\\":15000}\"', '2025-12-11 14:25:13', '2025-12-10 20:23:30', '2025-12-11 14:25:13'),
(34, 3, 'Quality Check Required', 'Quality inspection is required for completed work at \"Solar Panel Installation Site\".', 'quality_check', 0, '\"{\\\"project_id\\\":1,\\\"location\\\":\\\"Site A\\\"}\"', NULL, '2025-12-10 18:23:30', '2025-12-11 14:23:30'),
(35, 4, 'Team Meeting Scheduled', 'Weekly project review meeting is scheduled for tomorrow at 10:00 AM.', 'team_meeting', 1, '\"{\\\"meeting_id\\\":1,\\\"time\\\":\\\"10:00 AM\\\"}\"', '2025-12-11 14:25:13', '2025-12-10 16:23:30', '2025-12-11 14:25:13'),
(36, 1, 'Safety Alert', 'Safety inspection required: Workers not wearing helmets at construction site.', 'safety_alert', 1, '\"{\\\"site_id\\\":1,\\\"issue\\\":\\\"Safety equipment\\\"}\"', '2025-12-11 14:25:40', '2025-12-10 14:23:30', '2025-12-11 14:25:40'),
(37, 2, 'Lead Reassignment Request', 'Super Administrator has requested to reassign 58 lead(s) to Amit Singh. Reason: a', 'approval', 0, '{\"reassignment_request_id\":1,\"requested_by\":\"Super Administrator\",\"assigned_to\":\"Amit Singh\",\"leads_count\":58}', NULL, '2025-12-11 14:34:05', '2025-12-11 14:34:05'),
(38, 1, 'Lead Reassignment Request', 'Super Administrator has requested to reassign 58 lead(s) to Amit Singh. Reason: a', 'approval', 1, '{\"reassignment_request_id\":1,\"requested_by\":\"Super Administrator\",\"assigned_to\":\"Amit Singh\",\"leads_count\":58}', '2025-12-11 14:43:30', '2025-12-11 14:34:05', '2025-12-11 14:43:30'),
(39, 1, 'Reassignment Request Rejected', 'Your reassignment request has been rejected. Reason: a', 'error', 1, '{\"reassignment_request_id\":1}', '2025-12-11 14:43:30', '2025-12-11 14:34:47', '2025-12-11 14:43:30'),
(40, 2, 'Lead Reassignment Request', 'Rajesh Kumar has requested to reassign 46 lead(s) to Amit Singh. Reason: out of town', 'approval', 0, '{\"reassignment_request_id\":2,\"requested_by\":\"Rajesh Kumar\",\"assigned_to\":\"Amit Singh\",\"leads_count\":46}', NULL, '2025-12-12 13:22:03', '2025-12-12 13:22:03'),
(41, 1, 'Lead Reassignment Request', 'Rajesh Kumar has requested to reassign 46 lead(s) to Amit Singh. Reason: out of town', 'approval', 0, '{\"reassignment_request_id\":2,\"requested_by\":\"Rajesh Kumar\",\"assigned_to\":\"Amit Singh\",\"leads_count\":46}', NULL, '2025-12-12 13:22:03', '2025-12-12 13:22:03'),
(42, 2, 'Reassignment Request Rejected', 'Your reassignment request has been rejected. Reason: not available', 'error', 0, '{\"reassignment_request_id\":2}', NULL, '2025-12-12 13:22:28', '2025-12-12 13:22:28'),
(43, 2, 'Lead Reassignment Rejected', 'Your request to reassign leads has been rejected. Reason: qw', 'error', 0, '{\"reassignment_request_id\":2}', NULL, '2025-12-12 14:07:23', '2025-12-12 14:07:23'),
(44, 1, 'Lead Reassignment Rejected', 'Your request to reassign leads has been rejected. Reason: a', 'error', 0, '{\"reassignment_request_id\":1}', NULL, '2025-12-12 14:07:29', '2025-12-12 14:07:29'),
(45, 2, 'Lead Reassignment Request', 'Amit Singh has requested to reassign 7 lead(s) to Rajesh Kumar. Reason: a', 'approval', 0, '{\"reassignment_request_id\":3,\"requested_by\":\"Amit Singh\",\"assigned_to\":\"Rajesh Kumar\",\"leads_count\":7}', NULL, '2025-12-12 14:40:51', '2025-12-12 14:40:51'),
(46, 4, 'Lead Reassignment Rejected', 'Your request to reassign leads has been rejected. Reason: no', 'error', 1, '{\"reassignment_request_id\":3}', '2025-12-12 15:41:21', '2025-12-12 14:41:11', '2025-12-12 15:41:21'),
(47, 2, 'Lead Reassignment Approval Required', 'Amit Singh has requested to reassign 2 lead(s) to Rajesh Kumar. Manager approval required.', 'approval', 0, '{\"lead_reassignment_request_id\":4,\"requester_id\":4,\"assigned_to_id\":\"2\"}', NULL, '2025-12-12 15:48:24', '2025-12-12 15:48:24'),
(48, 4, 'Lead Reassignment Rejected', 'Your reassignment request has been rejected. Reason: a', 'error', 0, '{\"lead_reassignment_request_id\":4}', NULL, '2025-12-12 15:48:57', '2025-12-12 15:48:57'),
(49, 2, 'Lead Reassignment Approval Required', 'Priya Sharma has requested to reassign 2 lead(s) to Amit Singh. Manager approval required.', 'approval', 0, '{\"lead_reassignment_request_id\":5,\"requester_id\":3,\"assigned_to_id\":\"4\"}', NULL, '2025-12-12 15:53:07', '2025-12-12 15:53:07'),
(50, 1, 'Lead Reassignment Approval Required', 'Lead reassignment request from Priya Sharma has been approved by manager. Admin approval required for 2 lead(s).', 'approval', 0, '{\"lead_reassignment_request_id\":5,\"requester_id\":3,\"assigned_to_id\":4}', NULL, '2025-12-12 15:53:22', '2025-12-12 15:53:22'),
(51, 3, 'Lead Reassignment Approved', 'Your request to reassign 2 lead(s) to Amit Singh has been approved.', 'success', 0, '{\"lead_reassignment_request_id\":5}', NULL, '2025-12-12 15:53:40', '2025-12-12 15:53:40'),
(52, 4, 'Leads Assigned to You', '2 lead(s) have been reassigned to you.', 'task', 0, '{\"lead_reassignment_request_id\":5}', NULL, '2025-12-12 15:53:40', '2025-12-12 15:53:40'),
(53, 2, 'New Lead Created', 'A new lead has been created: Krish Bhuvela ()', 'lead', 0, '{\"lead_id\":107,\"lead_name\":\"Krish Bhuvela\",\"created_by\":\"Amit Singh\"}', NULL, '2025-12-13 08:42:04', '2025-12-13 08:42:04'),
(54, 1, 'Advance Final Approval Request', 'Advance ADV-2025-0001 requires final admin approval.', 'approval', 0, '{\"advance_id\":1,\"advance_number\":\"ADV-2025-0001\"}', NULL, '2025-12-18 14:49:04', '2025-12-18 14:49:04'),
(55, 4, 'Advance Approved', 'Your advance request ADV-2025-0001 has been fully approved.', 'success', 0, '{\"advance_id\":1,\"advance_number\":\"ADV-2025-0001\"}', NULL, '2025-12-18 14:50:09', '2025-12-18 14:50:09'),
(56, 2, 'Duplicate Lead Approval Required', 'A duplicate lead request for Krish Bhuvela (bhuvelakrish@gmail.com) requires your approval. An existing lead with the same email already exists.', 'approval', 0, '{\"approval_id\":1,\"approval_type\":\"duplicate_lead\",\"lead_name\":\"Krish Bhuvela\",\"existing_lead_id\":107}', NULL, '2026-01-04 12:35:46', '2026-01-04 12:35:46'),
(57, 4, 'Duplicate Lead Approved', 'Your duplicate lead request for Krish Bhuvela (bhuvelakrish@gmail.com) has been approved by Super Administrator.', 'approval', 0, '{\"approval_id\":1,\"lead_id\":108,\"lead_name\":\"Krish Bhuvela\"}', NULL, '2026-01-04 12:37:22', '2026-01-04 12:37:22');

-- --------------------------------------------------------

--
-- Table structure for table `o_m_maintenances`
--

CREATE TABLE `o_m_maintenances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_id` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `maintenance_type` varchar(255) NOT NULL,
  `scheduled_date` date NOT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') NOT NULL,
  `technician_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `work_performed` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `o_m_maintenances`
--

INSERT INTO `o_m_maintenances` (`id`, `maintenance_id`, `project_name`, `maintenance_type`, `scheduled_date`, `completed_date`, `status`, `technician_name`, `description`, `work_performed`, `notes`, `cost`, `created_at`, `updated_at`) VALUES
(1, 'MAINT-2025-0001', 'Solar Farm Project', 'Preventive', '2025-12-14', NULL, 'scheduled', 'Rajesh Kumar', 'Monthly preventive maintenance of solar panels', NULL, NULL, 15000.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'MAINT-2025-0002', 'Residential Solar', 'Corrective', '2025-12-10', '2025-12-10', 'completed', 'Priya Sharma', 'Inverter repair and replacement', 'Replaced faulty inverter module', 'Customer satisfied with repair', 25000.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'MAINT-2025-0003', 'Commercial Solar', 'Emergency', '2025-12-11', NULL, 'in_progress', 'John Doe', 'Emergency repair for power outage', NULL, NULL, 30000.00, '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_milestones`
--

CREATE TABLE `payment_milestones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `milestone_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `milestone_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'INR',
  `milestone_type` enum('advance','progress','completion','retention','final') NOT NULL,
  `milestone_percentage` int(11) NOT NULL DEFAULT 0 COMMENT 'Percentage of work completed',
  `planned_date` date NOT NULL,
  `due_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','partial','overdue','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('cash','cheque','bank_transfer','online','upi','card') DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `payment_notes` text DEFAULT NULL,
  `milestone_notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_by` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Payment receipts or documents' CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_milestones`
--

INSERT INTO `payment_milestones` (`id`, `milestone_number`, `title`, `description`, `project_id`, `quotation_id`, `milestone_amount`, `paid_amount`, `currency`, `milestone_type`, `milestone_percentage`, `planned_date`, `due_date`, `payment_date`, `status`, `payment_status`, `payment_method`, `payment_reference`, `payment_notes`, `milestone_notes`, `created_by`, `assigned_to`, `paid_by`, `paid_at`, `is_active`, `attachments`, `created_at`, `updated_at`) VALUES
(1, 'MS-7919', 'Advance Payment - Project Setup', 'Initial advance payment for project setup and mobilization', 4, NULL, 50000.00, 50000.00, 'INR', 'advance', 100, '2024-01-15', '2024-01-20', '2024-01-18', 'paid', 'paid', 'bank_transfer', 'TXN-001-ADV', 'Advance payment received as per terms', 'Project setup completed on time', 6, 5, 11, '2025-09-05 15:55:51', 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'MS-5673', 'First Progress Payment - Foundation Work', 'Payment for foundation and initial construction work', 1, 1, 150000.00, 150000.00, 'EUR', 'progress', 100, '2024-02-15', '2024-02-20', '2024-02-18', 'paid', 'paid', 'bank_transfer', 'TXN-002-PROG1', 'Progress payment after foundation completion', 'Foundation work completed ahead of schedule', 3, 11, 3, '2025-10-10 01:56:29', 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'MS-5941', 'Second Progress Payment - Structure Work', 'Payment for structural steel installation and framework', 4, 5, 200000.00, 100000.00, 'INR', 'progress', 80, '2024-03-15', '2024-03-20', '2024-03-18', 'in_progress', 'partial', 'bank_transfer', 'TXN-003-PROG2', 'Partial payment - remaining after completion', 'Structure work 80% complete', 3, 1, 9, '2025-05-22 06:47:19', 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'MS-5400', 'Third Progress Payment - Installation Phase', 'Payment for solar panel installation and electrical work', 2, 4, 180000.00, 0.00, 'EUR', 'progress', 30, '2024-05-01', '2024-05-05', NULL, 'in_progress', 'pending', NULL, NULL, NULL, 'Installation phase in progress', 4, 5, NULL, NULL, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'MS-5067', 'Completion Payment - Final Installation', 'Payment upon project completion and commissioning', 2, 4, 300000.00, 0.00, 'EUR', 'completion', 0, '2024-06-01', '2024-06-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Pending project completion', 11, 1, NULL, NULL, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'MS-3265', 'Retention Payment - Warranty Period', 'Final retention payment after warranty period', 4, 4, 75000.00, 0.00, 'INR', 'retention', 0, '2024-12-01', '2024-12-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Warranty retention - payable after 6 months', 2, 12, NULL, NULL, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'MS-4621', 'Advance Payment - Second Phase', 'Advance payment for Phase 2 expansion project', 5, 4, 80000.00, 40000.00, 'EUR', 'advance', 60, '2024-04-01', '2024-04-05', '2024-03-30', 'in_progress', 'partial', 'online', 'TXN-004-ADV2', 'Partial advance payment', 'Phase 2 project initiated', 13, 3, 10, '2025-04-29 23:19:59', 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'MS-8711', 'Equipment Installation Milestone', 'Payment for specialized equipment installation', 5, 4, 120000.00, 120000.00, 'EUR', 'final', 100, '2024-03-01', '2024-03-10', '2024-03-08', 'paid', 'paid', 'cheque', 'CHQ-789456', 'Equipment installation completed', 'All equipment installed and tested', 2, 4, 5, '2025-06-16 08:00:34', 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'MS-4481', 'Overdue Progress Payment', 'Progress payment for completion milestone - OVERDUE', 1, 5, 95000.00, 0.00, 'EUR', 'progress', 90, '2024-02-01', '2024-02-15', NULL, 'overdue', 'overdue', NULL, NULL, NULL, 'Payment overdue - follow up required', 9, 10, NULL, NULL, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'MS-4458', 'Commissioning Payment', 'Final commissioning and handover payment', 3, NULL, 55000.00, 0.00, 'USD', 'final', 0, '2024-07-15', '2024-07-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Commissioning milestone pending', 3, 13, NULL, NULL, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'MS-8478', 'Emergency Repair Milestone', 'Additional payment for emergency repairs', 3, 4, 25000.00, 0.00, 'USD', 'final', 0, '2024-04-15', '2024-04-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Emergency repairs approved', 4, 12, NULL, NULL, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(12, 'MS-4686', 'Maintenance Contract Milestone', 'Payment for annual maintenance contract', 4, 1, 40000.00, 0.00, 'INR', 'final', 0, '2024-12-15', '2024-12-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Annual maintenance contract milestone', 1, 12, NULL, NULL, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(13, 'MS-7661', 'Advance Payment - Project Setup', 'Initial advance payment for project setup and mobilization', 4, 5, 50000.00, 50000.00, 'EUR', 'advance', 100, '2024-01-15', '2024-01-20', '2024-01-18', 'paid', 'paid', 'bank_transfer', 'TXN-001-ADV', 'Advance payment received as per terms', 'Project setup completed on time', 11, 1, 12, '2025-07-13 03:26:02', 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(14, 'MS-6258', 'First Progress Payment - Foundation Work', 'Payment for foundation and initial construction work', 4, 3, 150000.00, 150000.00, 'INR', 'progress', 100, '2024-02-15', '2024-02-20', '2024-02-18', 'paid', 'paid', 'bank_transfer', 'TXN-002-PROG1', 'Progress payment after foundation completion', 'Foundation work completed ahead of schedule', 8, 1, 4, '2024-10-18 08:20:10', 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(15, 'MS-3817', 'Second Progress Payment - Structure Work', 'Payment for structural steel installation and framework', 1, 1, 200000.00, 100000.00, 'USD', 'progress', 80, '2024-03-15', '2024-03-20', '2024-03-18', 'in_progress', 'partial', 'bank_transfer', 'TXN-003-PROG2', 'Partial payment - remaining after completion', 'Structure work 80% complete', 5, 12, 3, '2024-06-03 17:35:02', 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(16, 'MS-2186', 'Third Progress Payment - Installation Phase', 'Payment for solar panel installation and electrical work', 5, 3, 180000.00, 0.00, 'USD', 'progress', 30, '2024-05-01', '2024-05-05', NULL, 'in_progress', 'pending', NULL, NULL, NULL, 'Installation phase in progress', 1, 1, NULL, NULL, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(17, 'MS-4830', 'Completion Payment - Final Installation', 'Payment upon project completion and commissioning', 3, 4, 300000.00, 0.00, 'EUR', 'completion', 0, '2024-06-01', '2024-06-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Pending project completion', 5, 13, NULL, NULL, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(18, 'MS-0135', 'Retention Payment - Warranty Period', 'Final retention payment after warranty period', 1, 3, 75000.00, 0.00, 'EUR', 'retention', 0, '2024-12-01', '2024-12-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Warranty retention - payable after 6 months', 4, 10, NULL, NULL, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(19, 'MS-7337', 'Advance Payment - Second Phase', 'Advance payment for Phase 2 expansion project', 2, 5, 80000.00, 40000.00, 'INR', 'advance', 60, '2024-04-01', '2024-04-05', '2024-03-30', 'in_progress', 'partial', 'online', 'TXN-004-ADV2', 'Partial advance payment', 'Phase 2 project initiated', 12, 9, 8, '2025-03-27 13:53:52', 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(20, 'MS-1271', 'Equipment Installation Milestone', 'Payment for specialized equipment installation', 1, NULL, 120000.00, 120000.00, 'USD', 'final', 100, '2024-03-01', '2024-03-10', '2024-03-08', 'paid', 'paid', 'cheque', 'CHQ-789456', 'Equipment installation completed', 'All equipment installed and tested', 4, 7, 13, '2025-06-04 19:00:51', 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(21, 'MS-4173', 'Overdue Progress Payment', 'Progress payment for completion milestone - OVERDUE', 1, 4, 95000.00, 0.00, 'EUR', 'progress', 90, '2024-02-01', '2024-02-15', NULL, 'overdue', 'overdue', NULL, NULL, NULL, 'Payment overdue - follow up required', 13, 6, NULL, NULL, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(22, 'MS-0128', 'Commissioning Payment', 'Final commissioning and handover payment', 4, NULL, 55000.00, 0.00, 'USD', 'final', 0, '2024-07-15', '2024-07-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Commissioning milestone pending', 1, 3, NULL, NULL, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(23, 'MS-2224', 'Emergency Repair Milestone', 'Additional payment for emergency repairs', 3, 4, 25000.00, 0.00, 'EUR', 'final', 0, '2024-04-15', '2024-04-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Emergency repairs approved', 1, 2, NULL, NULL, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(24, 'MS-5373', 'Maintenance Contract Milestone', 'Payment for annual maintenance contract', 2, 1, 40000.00, 0.00, 'USD', 'final', 0, '2024-12-15', '2024-12-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Annual maintenance contract milestone', 5, 12, NULL, NULL, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(25, 'MS-2387', 'Advance Payment - Project Setup', 'Initial advance payment for project setup and mobilization', 4, 3, 50000.00, 50000.00, 'EUR', 'advance', 100, '2024-01-15', '2024-01-20', '2024-01-18', 'paid', 'paid', 'bank_transfer', 'TXN-001-ADV', 'Advance payment received as per terms', 'Project setup completed on time', 4, 1, 8, '2025-01-18 22:19:34', 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(26, 'MS-6069', 'First Progress Payment - Foundation Work', 'Payment for foundation and initial construction work', 3, 5, 150000.00, 150000.00, 'EUR', 'progress', 100, '2024-02-15', '2024-02-20', '2024-02-18', 'paid', 'paid', 'bank_transfer', 'TXN-002-PROG1', 'Progress payment after foundation completion', 'Foundation work completed ahead of schedule', 10, 10, 10, '2025-04-24 19:18:15', 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(27, 'MS-4374', 'Second Progress Payment - Structure Work', 'Payment for structural steel installation and framework', 3, 3, 200000.00, 100000.00, 'INR', 'progress', 80, '2024-03-15', '2024-03-20', '2024-03-18', 'in_progress', 'partial', 'bank_transfer', 'TXN-003-PROG2', 'Partial payment - remaining after completion', 'Structure work 80% complete', 8, 3, 13, '2024-06-10 22:29:16', 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(28, 'MS-3466', 'Third Progress Payment - Installation Phase', 'Payment for solar panel installation and electrical work', 4, 5, 180000.00, 0.00, 'EUR', 'progress', 30, '2024-05-01', '2024-05-05', NULL, 'in_progress', 'pending', NULL, NULL, NULL, 'Installation phase in progress', 9, 3, NULL, NULL, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(29, 'MS-8669', 'Completion Payment - Final Installation', 'Payment upon project completion and commissioning', 1, 1, 300000.00, 0.00, 'USD', 'completion', 0, '2024-06-01', '2024-06-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Pending project completion', 10, 10, NULL, NULL, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(30, 'MS-0514', 'Retention Payment - Warranty Period', 'Final retention payment after warranty period', 1, NULL, 75000.00, 0.00, 'INR', 'retention', 0, '2024-12-01', '2024-12-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Warranty retention - payable after 6 months', 2, 13, NULL, NULL, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(31, 'MS-6597', 'Advance Payment - Second Phase', 'Advance payment for Phase 2 expansion project', 3, 4, 80000.00, 40000.00, 'INR', 'advance', 60, '2024-04-01', '2024-04-05', '2024-03-30', 'in_progress', 'partial', 'online', 'TXN-004-ADV2', 'Partial advance payment', 'Phase 2 project initiated', 9, 4, 7, '2024-09-15 19:18:52', 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(32, 'MS-1820', 'Equipment Installation Milestone', 'Payment for specialized equipment installation', 2, 1, 120000.00, 120000.00, 'EUR', 'final', 100, '2024-03-01', '2024-03-10', '2024-03-08', 'paid', 'paid', 'cheque', 'CHQ-789456', 'Equipment installation completed', 'All equipment installed and tested', 8, 4, 9, '2024-06-07 06:20:09', 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(33, 'MS-7223', 'Overdue Progress Payment', 'Progress payment for completion milestone - OVERDUE', 5, 1, 95000.00, 0.00, 'USD', 'progress', 90, '2024-02-01', '2024-02-15', NULL, 'overdue', 'overdue', NULL, NULL, NULL, 'Payment overdue - follow up required', 12, 11, NULL, NULL, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(34, 'MS-8472', 'Commissioning Payment', 'Final commissioning and handover payment', 2, 5, 55000.00, 0.00, 'INR', 'final', 0, '2024-07-15', '2024-07-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Commissioning milestone pending', 8, 2, NULL, NULL, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(35, 'MS-3956', 'Emergency Repair Milestone', 'Additional payment for emergency repairs', 1, 3, 25000.00, 0.00, 'USD', 'final', 0, '2024-04-15', '2024-04-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Emergency repairs approved', 10, 12, NULL, NULL, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(36, 'MS-6374', 'Maintenance Contract Milestone', 'Payment for annual maintenance contract', 5, 4, 40000.00, 0.00, 'INR', 'final', 0, '2024-12-15', '2024-12-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Annual maintenance contract milestone', 8, 12, NULL, NULL, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(37, 'MS-9483', 'Advance Payment - Project Setup', 'Initial advance payment for project setup and mobilization', 4, NULL, 50000.00, 50000.00, 'INR', 'advance', 100, '2024-01-15', '2024-01-20', '2024-01-18', 'paid', 'paid', 'bank_transfer', 'TXN-001-ADV', 'Advance payment received as per terms', 'Project setup completed on time', 1, 1, 8, '2024-06-10 01:43:48', 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(38, 'MS-5290', 'First Progress Payment - Foundation Work', 'Payment for foundation and initial construction work', 4, 4, 150000.00, 150000.00, 'USD', 'progress', 100, '2024-02-15', '2024-02-20', '2024-02-18', 'paid', 'paid', 'bank_transfer', 'TXN-002-PROG1', 'Progress payment after foundation completion', 'Foundation work completed ahead of schedule', 9, 1, 11, '2025-11-11 01:37:07', 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(39, 'MS-3698', 'Second Progress Payment - Structure Work', 'Payment for structural steel installation and framework', 4, 4, 200000.00, 100000.00, 'INR', 'progress', 80, '2024-03-15', '2024-03-20', '2024-03-18', 'in_progress', 'partial', 'bank_transfer', 'TXN-003-PROG2', 'Partial payment - remaining after completion', 'Structure work 80% complete', 10, 9, 8, '2024-06-26 17:55:07', 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(40, 'MS-0664', 'Third Progress Payment - Installation Phase', 'Payment for solar panel installation and electrical work', 5, 3, 180000.00, 0.00, 'USD', 'progress', 30, '2024-05-01', '2024-05-05', NULL, 'in_progress', 'pending', NULL, NULL, NULL, 'Installation phase in progress', 1, 4, NULL, NULL, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(41, 'MS-7826', 'Completion Payment - Final Installation', 'Payment upon project completion and commissioning', 5, NULL, 300000.00, 0.00, 'EUR', 'completion', 0, '2024-06-01', '2024-06-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Pending project completion', 4, 5, NULL, NULL, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(42, 'MS-9963', 'Retention Payment - Warranty Period', 'Final retention payment after warranty period', 5, 5, 75000.00, 0.00, 'INR', 'retention', 0, '2024-12-01', '2024-12-05', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Warranty retention - payable after 6 months', 5, 6, NULL, NULL, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(43, 'MS-6918', 'Advance Payment - Second Phase', 'Advance payment for Phase 2 expansion project', 1, 1, 80000.00, 40000.00, 'EUR', 'advance', 60, '2024-04-01', '2024-04-05', '2024-03-30', 'in_progress', 'partial', 'online', 'TXN-004-ADV2', 'Partial advance payment', 'Phase 2 project initiated', 10, 9, 9, '2025-03-22 16:22:12', 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(44, 'MS-3638', 'Equipment Installation Milestone', 'Payment for specialized equipment installation', 4, 1, 120000.00, 120000.00, 'EUR', 'final', 100, '2024-03-01', '2024-03-10', '2024-03-08', 'paid', 'paid', 'cheque', 'CHQ-789456', 'Equipment installation completed', 'All equipment installed and tested', 13, 5, 2, '2024-06-19 20:29:53', 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(45, 'MS-2659', 'Overdue Progress Payment', 'Progress payment for completion milestone - OVERDUE', 5, 4, 95000.00, 0.00, 'EUR', 'progress', 90, '2024-02-01', '2024-02-15', NULL, 'overdue', 'overdue', NULL, NULL, NULL, 'Payment overdue - follow up required', 6, 3, NULL, NULL, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(46, 'MS-1402', 'Commissioning Payment', 'Final commissioning and handover payment', 4, 1, 55000.00, 0.00, 'INR', 'final', 0, '2024-07-15', '2024-07-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Commissioning milestone pending', 10, 2, NULL, NULL, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(47, 'MS-9835', 'Emergency Repair Milestone', 'Additional payment for emergency repairs', 1, NULL, 25000.00, 0.00, 'INR', 'final', 0, '2024-04-15', '2024-04-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Emergency repairs approved', 10, 2, NULL, NULL, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(48, 'MS-8754', 'Maintenance Contract Milestone', 'Payment for annual maintenance contract', 5, NULL, 40000.00, 0.00, 'USD', 'final', 0, '2024-12-15', '2024-12-20', NULL, 'pending', 'pending', NULL, NULL, NULL, 'Annual maintenance contract milestone', 8, 11, NULL, NULL, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `payment_requests`
--

CREATE TABLE `payment_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pr_number` varchar(255) NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `request_date` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_type` enum('advance','milestone','final','retention','other') NOT NULL DEFAULT 'milestone',
  `status` enum('draft','submitted','approved','rejected','paid','cancelled') NOT NULL DEFAULT 'draft',
  `description` text NOT NULL,
  `justification` text DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_amount` decimal(15,2) DEFAULT NULL,
  `supporting_documents` text DEFAULT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `payroll_month` varchar(255) NOT NULL,
  `payroll_year` varchar(255) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','paid') NOT NULL,
  `payment_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `employee_id`, `payroll_month`, `payroll_year`, `basic_salary`, `allowances`, `deductions`, `net_salary`, `status`, `payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'September', '2025', 45000.00, 5000.00, 2000.00, 48000.00, 'paid', '2025-12-06', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', 'September', '2025', 55000.00, 6000.00, 2500.00, 58500.00, 'paid', '2025-12-06', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', 'September', '2025', 60000.00, 7000.00, 3000.00, 64000.00, 'paid', '2025-12-06', NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'EMP001', 'October', '2025', 45000.00, 5000.00, 2000.00, 48000.00, 'pending', NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'EMP002', 'October', '2025', 55000.00, 6000.00, 2500.00, 58500.00, 'pending', NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'EMP003', 'October', '2025', 60000.00, 7000.00, 3000.00, 64000.00, 'pending', NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `performance_reviews`
--

CREATE TABLE `performance_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `review_period` varchar(255) NOT NULL,
  `review_date` date NOT NULL,
  `overall_rating` int(11) NOT NULL,
  `goals_achieved` text NOT NULL,
  `areas_for_improvement` text NOT NULL,
  `manager_comments` text NOT NULL,
  `employee_comments` text NOT NULL,
  `status` enum('draft','submitted','approved','completed') NOT NULL,
  `reviewed_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `performance_reviews`
--

INSERT INTO `performance_reviews` (`id`, `employee_id`, `review_period`, `review_date`, `overall_rating`, `goals_achieved`, `areas_for_improvement`, `manager_comments`, `employee_comments`, `status`, `reviewed_by`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'Q3 2025', '2025-12-01', 4, 'Completed all assigned projects on time, improved team efficiency by 15%', 'Need to improve communication skills and leadership qualities', 'Excellent technical skills, good team player', 'Looking forward to taking on more responsibilities', 'completed', 'HR Manager', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', 'Q3 2025', '2025-12-06', 5, 'Successfully managed 3 major projects, mentored junior developers', 'Continue current performance level', 'Outstanding performance, ready for promotion', 'Thank you for the recognition', 'completed', 'Project Manager', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', 'Q3 2025', '2025-12-16', 0, '', '', '', '', 'submitted', 'Sales Manager', '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_dashboard', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(2, 'view_analytics', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(3, 'view_reports', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(4, 'manage_users', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(5, 'view_users', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(6, 'create_users', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(7, 'edit_users', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(8, 'delete_users', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(9, 'manage_leads', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(10, 'view_leads', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(11, 'create_leads', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(12, 'edit_leads', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(13, 'delete_leads', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(14, 'convert_leads', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(15, 'assign_leads', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(16, 'manage_projects', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(17, 'view_projects', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(18, 'create_projects', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(19, 'edit_projects', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(20, 'delete_projects', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(21, 'assign_projects', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(22, 'view_project_details', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(23, 'manage_project_tasks', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(24, 'manage_project_materials', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(25, 'manage_project_budget', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(26, 'manage_tasks', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(27, 'view_tasks', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(28, 'create_tasks', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(29, 'edit_tasks', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(30, 'delete_tasks', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(31, 'assign_tasks', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(32, 'update_task_status', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(33, 'manage_products', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(34, 'view_products', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(35, 'create_products', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(36, 'edit_products', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(37, 'delete_products', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(38, 'manage_vendors', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(39, 'view_vendors', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(40, 'create_vendors', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(41, 'edit_vendors', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(42, 'delete_vendors', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(43, 'manage_purchase_orders', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(44, 'view_purchase_orders', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(45, 'create_purchase_orders', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(46, 'edit_purchase_orders', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(47, 'delete_purchase_orders', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(48, 'approve_purchase_orders', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(49, 'manage_purchase_requisitions', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(50, 'view_purchase_requisitions', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(51, 'create_purchase_requisitions', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(52, 'edit_purchase_requisitions', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(53, 'approve_purchase_requisitions', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(54, 'manage_rfq', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(55, 'view_rfq', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(56, 'create_rfq', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(57, 'edit_rfq', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(58, 'manage_inventory', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(59, 'view_inventory', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(60, 'manage_stock', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(61, 'view_stock', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(62, 'manage_warehouse', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(63, 'view_warehouse', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(64, 'manage_material_requests', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(65, 'view_material_requests', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(66, 'create_material_requests', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(67, 'edit_material_requests', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(68, 'manage_material_consumption', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(69, 'view_material_consumption', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(70, 'create_material_consumption', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(71, 'edit_material_consumption', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(72, 'manage_grn', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(73, 'view_grn', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(74, 'create_grn', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(75, 'edit_grn', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(76, 'manage_stock_valuation', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(77, 'view_stock_valuation', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(78, 'manage_stock_ledger', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(79, 'view_stock_ledger', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(80, 'manage_inventory_audit', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(81, 'view_inventory_audit', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(82, 'create_inventory_audit', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(83, 'edit_inventory_audit', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(84, 'manage_invoices', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(85, 'view_invoices', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(86, 'create_invoices', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(87, 'edit_invoices', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(88, 'delete_invoices', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(89, 'approve_invoices', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(90, 'manage_quotations', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(91, 'view_quotations', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(92, 'create_quotations', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(93, 'edit_quotations', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(94, 'manage_quality_checks', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(95, 'view_quality_checks', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(96, 'create_quality_checks', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(97, 'edit_quality_checks', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(98, 'approve_quality_checks', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(99, 'manage_employees', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(100, 'view_employees', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(101, 'create_employees', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(102, 'edit_employees', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(103, 'delete_employees', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(104, 'manage_attendance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(105, 'view_attendance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(106, 'create_attendance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(107, 'edit_attendance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(108, 'manage_leave_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(109, 'view_leave_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(110, 'create_leave_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(111, 'edit_leave_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(112, 'approve_leave_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(113, 'manage_payroll', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(114, 'view_payroll', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(115, 'create_payroll', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(116, 'edit_payroll', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(117, 'manage_performance_reviews', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(118, 'view_performance_reviews', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(119, 'create_performance_reviews', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(120, 'edit_performance_reviews', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(121, 'manage_job_applications', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(122, 'view_job_applications', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(123, 'create_job_applications', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(124, 'edit_job_applications', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(125, 'manage_expense_claims', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(126, 'view_expense_claims', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(127, 'create_expense_claims', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(128, 'edit_expense_claims', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(129, 'approve_expense_claims', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(130, 'manage_accounts', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(131, 'view_accounts', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(132, 'manage_budget', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(133, 'view_budget', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(134, 'create_budget', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(135, 'edit_budget', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(136, 'manage_expenses', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(137, 'view_expenses', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(138, 'create_expenses', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(139, 'edit_expenses', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(140, 'manage_payment_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(141, 'view_payment_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(142, 'create_payment_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(143, 'edit_payment_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(144, 'approve_payment_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(145, 'manage_payment_milestones', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(146, 'view_payment_milestones', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(147, 'create_payment_milestones', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(148, 'edit_payment_milestones', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(149, 'view_financial_reports', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(150, 'manage_documents', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(151, 'view_documents', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(152, 'upload_documents', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(153, 'download_documents', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(154, 'delete_documents', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(155, 'manage_settings', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(156, 'view_settings', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(157, 'manage_system_settings', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(158, 'manage_service_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(159, 'view_service_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(160, 'create_service_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(161, 'edit_service_requests', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(162, 'manage_complaints', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(163, 'view_complaints', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(164, 'create_complaints', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(165, 'edit_complaints', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(166, 'manage_amc', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(167, 'view_amc', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(168, 'create_amc', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(169, 'edit_amc', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(170, 'manage_maintenance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(171, 'view_maintenance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(172, 'create_maintenance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(173, 'edit_maintenance', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(174, 'manage_liaisoning', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(175, 'view_liaisoning', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(176, 'create_liaisoning', 'web', '2025-12-11 14:23:40', '2025-12-11 14:23:40'),
(177, 'edit_liaisoning', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(178, 'manage_permits', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(179, 'manage_approvals', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(180, 'manage_customers', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(181, 'view_customers', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(182, 'create_customers', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(183, 'edit_customers', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(184, 'manage_commission', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(185, 'view_commission', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(186, 'manage_daily_progress', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(187, 'view_daily_progress', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(188, 'create_daily_progress', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(189, 'edit_daily_progress', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(190, 'manage_contractors', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(191, 'view_contractors', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(192, 'manage_escalations', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(193, 'view_escalations', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(194, 'create_escalations', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(195, 'manage_materials', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(196, 'view_materials', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(197, 'manage_salary_slips', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(198, 'view_salary_slips', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(199, 'create_salary_slips', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(200, 'manage_appraisals', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(201, 'view_appraisals', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(202, 'create_appraisals', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(203, 'edit_appraisals', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(204, 'manage_activities', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(205, 'view_activities', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(206, 'create_activities', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(207, 'edit_activities', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41'),
(208, 'delete_activities', 'web', '2025-12-11 14:23:41', '2025-12-11 14:23:41');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_stock_level` int(11) NOT NULL DEFAULT 0,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `hsn_code` varchar(255) DEFAULT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `description`, `category`, `unit`, `purchase_price`, `selling_price`, `min_stock_level`, `current_stock`, `hsn_code`, `gst_rate`, `is_active`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Solar Panel 540W Monocrystalline', 'SP-540W-MC-001', 'High efficiency 540W monocrystalline solar panel with 22% efficiency rating. Perfect for residential and commercial installations.', 'Solar Panels', 'pcs', 18000.00, 22000.00, 10, 25, '8541.40.12', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(2, 'Solar Inverter 5kW String', 'SI-5KW-STR-001', '5kW string inverter with MPPT technology and WiFi monitoring. Compatible with most solar panel configurations.', 'Inverters', 'pcs', 45000.00, 55000.00, 5, 8, '8504.40.95', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(3, 'Solar Mounting Structure - Rooftop', 'SMS-RT-001', 'Galvanized steel mounting structure for rooftop solar installations. Includes all necessary hardware and brackets.', 'Mounting Systems', 'set', 8000.00, 12000.00, 15, 3, '7308.90.00', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(4, 'DC Cable 4mm² Solar Grade', 'DC-CBL-4MM-001', 'UV resistant DC cable for solar panel connections. 4mm² cross section, suitable for outdoor use.', 'Cables & Wiring', 'meter', 45.00, 65.00, 1000, 0, '8544.42.90', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(5, 'MC4 Connectors Male-Female Set', 'MC4-MF-SET-001', 'Waterproof MC4 connectors for solar panel connections. Includes male and female connectors with crimping tool.', 'Connectors', 'set', 120.00, 180.00, 50, 12, '8536.69.90', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(6, 'Solar Battery 100Ah Tubular', 'SB-100AH-TUB-001', '100Ah tubular battery for solar storage applications. Deep cycle design with 5-year warranty.', 'Batteries', 'pcs', 12000.00, 15000.00, 8, 15, '8507.20.00', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(7, 'Solar Charge Controller 40A MPPT', 'SCC-40A-MPPT-001', '40A MPPT charge controller with LCD display and USB port. Maximum power point tracking for optimal efficiency.', 'Charge Controllers', 'pcs', 8500.00, 11000.00, 10, 6, '8504.40.95', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(8, 'Solar Panel Junction Box', 'SPJB-001', 'IP67 rated junction box for solar panel connections. Includes bypass diodes and terminal blocks.', 'Accessories', 'pcs', 800.00, 1200.00, 20, 35, '8536.69.90', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(9, 'Ground Mounting Structure', 'GMS-001', 'Ground mounting structure for large scale solar installations. Galvanized steel with concrete foundation.', 'Mounting Systems', 'set', 15000.00, 20000.00, 5, 2, '7308.90.00', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(10, 'Solar Monitoring System', 'SMS-001', 'Real-time solar system monitoring with mobile app. Tracks energy production, consumption, and system health.', 'Monitoring', 'pcs', 12000.00, 16000.00, 3, 0, '9032.89.00', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(11, 'AC Cable 6mm² THW', 'AC-CBL-6MM-001', 'AC cable for inverter to grid connections. 6mm² cross section with THW insulation.', 'Cables & Wiring', 'meter', 85.00, 120.00, 500, 750, '8544.42.90', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(12, 'Solar Panel Cleaning Kit', 'SPCK-001', 'Professional solar panel cleaning kit with telescopic pole, soft brush, and biodegradable cleaner.', 'Maintenance', 'kit', 2500.00, 3500.00, 5, 8, '8479.89.00', 18.00, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `project_code` varchar(255) NOT NULL,
  `status` enum('planning','active','on_hold','completed','cancelled') NOT NULL DEFAULT 'planning',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `actual_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `project_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `milestones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`milestones`)),
  `location` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `channel_partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_engineer` bigint(20) UNSIGNED DEFAULT NULL,
  `liaisoning_officer` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `project_code`, `status`, `priority`, `start_date`, `end_date`, `budget`, `actual_cost`, `project_manager_id`, `client_id`, `created_by`, `milestones`, `location`, `created_at`, `updated_at`, `channel_partner_id`, `project_engineer`, `liaisoning_officer`) VALUES
(1, 'Solar Installation - TechCorp Industries', 'Complete rooftop solar installation for TechCorp Industries office building in Bangalore. Includes 100kW solar panels, inverters, and monitoring system.', 'PROJ-2024-001', 'active', 'high', '2025-11-11', '2026-02-09', 750000.00, 250000.00, 1, 1, 1, '[{\"name\":\"Site Survey\",\"date\":\"2025-11-16T14:23:43.867012Z\",\"status\":\"completed\"},{\"name\":\"Design Approval\",\"date\":\"2025-11-21T14:23:43.867015Z\",\"status\":\"completed\"},{\"name\":\"Material Procurement\",\"date\":\"2025-12-01T14:23:43.867017Z\",\"status\":\"completed\"},{\"name\":\"Installation\",\"date\":\"2026-01-10T14:23:43.867020Z\",\"status\":\"in_progress\"},{\"name\":\"Testing & Commissioning\",\"date\":\"2026-01-30T14:23:43.867023Z\",\"status\":\"pending\"}]', 'IT Park, Bangalore, Karnataka', '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(2, 'Residential Solar System - Sunita Singh', '3kW residential solar installation for 3BHK house in Delhi. Includes rooftop mounting, grid-tie inverter, and net metering setup.', 'PROJ-2024-002', 'planning', 'medium', '2025-12-16', '2026-01-05', 250000.00, 0.00, 2, 3, 1, '[{\"name\":\"Site Survey\",\"date\":\"2025-12-13T14:23:43.867038Z\",\"status\":\"pending\"},{\"name\":\"Design & Approval\",\"date\":\"2025-12-18T14:23:43.867041Z\",\"status\":\"pending\"},{\"name\":\"Installation\",\"date\":\"2025-12-26T14:23:43.867043Z\",\"status\":\"pending\"},{\"name\":\"Commissioning\",\"date\":\"2026-01-02T14:23:43.867045Z\",\"status\":\"pending\"}]', 'Residential Colony, Delhi', '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(3, 'Industrial Solar Plant - Mehta Manufacturing', '500kW industrial solar power plant for Mehta Manufacturing facility in Pune. Large scale ground-mounted installation with advanced monitoring.', 'PROJ-2024-003', 'planning', 'urgent', '2025-12-26', '2026-04-10', 2000000.00, 0.00, 1, 6, 1, '[{\"name\":\"Feasibility Study\",\"date\":\"2025-12-16T14:23:43.867057Z\",\"status\":\"pending\"},{\"name\":\"Contract Signing\",\"date\":\"2025-12-21T14:23:43.867060Z\",\"status\":\"pending\"},{\"name\":\"Site Preparation\",\"date\":\"2025-12-31T14:23:43.867062Z\",\"status\":\"pending\"},{\"name\":\"Panel Installation\",\"date\":\"2026-02-09T14:23:43.867064Z\",\"status\":\"pending\"},{\"name\":\"Grid Connection\",\"date\":\"2026-03-21T14:23:43.867067Z\",\"status\":\"pending\"},{\"name\":\"Commissioning\",\"date\":\"2026-04-05T14:23:43.867069Z\",\"status\":\"pending\"}]', 'Industrial Zone, Pune, Maharashtra', '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(4, 'Hospital Solar Backup - City Hospital', 'Emergency solar backup system for City Hospital in Hyderabad. Includes battery storage and automatic switching for critical medical equipment.', 'PROJ-2024-004', 'completed', 'high', '2025-09-12', '2025-12-01', 800000.00, 780000.00, 2, 8, 1, '[{\"name\":\"Site Survey\",\"date\":\"2025-09-17T14:23:43.867082Z\",\"status\":\"completed\"},{\"name\":\"Design Approval\",\"date\":\"2025-09-27T14:23:43.867084Z\",\"status\":\"completed\"},{\"name\":\"Installation\",\"date\":\"2025-10-27T14:23:43.867086Z\",\"status\":\"completed\"},{\"name\":\"Testing\",\"date\":\"2025-11-21T14:23:43.867089Z\",\"status\":\"completed\"},{\"name\":\"Commissioning\",\"date\":\"2025-12-01T14:23:43.867091Z\",\"status\":\"completed\"}]', 'Medical District, Hyderabad, Telangana', '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL),
(5, 'School Solar Initiative - Delhi Public School', 'Educational solar installation for Delhi Public School. Includes solar panels, educational displays, and student learning modules.', 'PROJ-2024-005', 'on_hold', 'low', '2025-11-26', '2026-01-25', 400000.00, 50000.00, 1, NULL, 1, '[{\"name\":\"Site Survey\",\"date\":\"2025-12-01T14:23:43.867100Z\",\"status\":\"completed\"},{\"name\":\"Design\",\"date\":\"2025-12-16T14:23:43.867102Z\",\"status\":\"pending\"},{\"name\":\"Installation\",\"date\":\"2026-01-05T14:23:43.867104Z\",\"status\":\"pending\"},{\"name\":\"Commissioning\",\"date\":\"2026-01-20T14:23:43.867106Z\",\"status\":\"pending\"}]', 'Education Hub, Delhi', '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_profitabilities`
--

CREATE TABLE `project_profitabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_revenue` decimal(12,2) NOT NULL DEFAULT 0.00,
  `contract_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `progress_billing` decimal(12,2) NOT NULL DEFAULT 0.00,
  `overrun_revenue` decimal(12,2) NOT NULL DEFAULT 0.00,
  `material_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `labor_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `equipment_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `transportation_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `permits_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `overhead_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subcontractor_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_costs` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gross_profit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gross_margin_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `net_profit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_margin_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `change_order_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `retention_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `days_completed` int(11) NOT NULL DEFAULT 0,
  `total_days` int(11) NOT NULL DEFAULT 0,
  `completion_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_profitabilities`
--

INSERT INTO `project_profitabilities` (`id`, `project_id`, `period`, `start_date`, `end_date`, `total_revenue`, `contract_value`, `progress_billing`, `overrun_revenue`, `material_costs`, `labor_costs`, `equipment_costs`, `transportation_costs`, `permits_costs`, `overhead_costs`, `subcontractor_costs`, `total_costs`, `gross_profit`, `gross_margin_percentage`, `net_profit`, `net_margin_percentage`, `change_order_amount`, `retention_amount`, `days_completed`, `total_days`, `completion_percentage`, `status`, `notes`, `created_by`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'quarterly', '2025-08-14', '2025-11-12', 505540.00, 457258.00, 42045.00, 6237.00, 135014.00, 177101.00, 21490.00, 9670.00, 5471.00, 28027.00, 93249.00, 470022.00, 35518.00, 7.03, 35518.00, 7.03, 7088.00, 24094.00, 66, 129, 51.16, 'reviewed', 'Equipment rental costs were well managed.', 9, 9, '2025-12-10 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 2, 'monthly', '2025-06-21', '2025-07-21', 310671.00, 267425.00, 28703.00, 14543.00, 21847.00, 34720.00, 23814.00, 13519.00, 2910.00, 23805.00, 27849.00, 148464.00, 162207.00, 52.21, 162207.00, 52.21, 43301.00, 23372.00, 35, 37, 94.59, 'reviewed', 'Project completed within budget. Excellent cost control.', 9, 12, '2025-12-01 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 1, 'yearly', '2025-06-23', '2026-06-23', 242601.00, 210025.00, 17447.00, 15129.00, 21903.00, 123476.00, 6950.00, 18136.00, 2370.00, 26534.00, 30883.00, 230252.00, 12349.00, 5.09, 12349.00, 5.09, 9057.00, 10130.00, 263, 277, 94.95, 'reviewed', 'Strong performance this quarter.', 7, 1, '2025-11-26 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 5, 'monthly', '2025-10-20', '2025-11-19', 152843.00, 120446.00, 18565.00, 13832.00, 129925.00, 184306.00, 23518.00, 2043.00, 3740.00, 19910.00, 35522.00, 398964.00, -246121.00, -161.03, -246121.00, -161.03, 11170.00, 19726.00, 50, 100, 50.00, 'reviewed', 'Transportation costs were minimized through local sourcing.', 10, 2, '2025-11-23 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 1, 'monthly', '2025-10-15', '2025-11-14', 499313.00, 470541.00, 10800.00, 17972.00, 86006.00, 66697.00, 25855.00, 4188.00, 6256.00, 48303.00, 54141.00, 291446.00, 207867.00, 41.63, 207867.00, 41.63, 3367.00, 15513.00, 106, 158, 67.09, 'draft', 'Labor costs came in higher than expected.', 2, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 2, 'quarterly', '2025-08-16', '2025-11-14', 155467.00, 120100.00, 29647.00, 5720.00, 76420.00, 81548.00, 14760.00, 17740.00, 8625.00, 14551.00, 49567.00, 263211.00, -107744.00, -69.30, -107744.00, -69.30, 19306.00, 12647.00, 11, 206, 5.34, 'reviewed', 'Change orders increased profitability significantly.', 7, 1, '2025-11-26 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 2, 'quarterly', '2025-07-27', '2025-10-25', 394069.00, 370512.00, 20429.00, 3128.00, 121166.00, 108117.00, 40018.00, 3458.00, 3802.00, 49471.00, 88360.00, 414392.00, -20323.00, -5.16, -20323.00, -5.16, 29038.00, 1532.00, 10, 264, 3.79, 'reviewed', 'Equipment rental costs were well managed.', 6, 10, '2025-11-15 14:22:41', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 5, 'monthly', '2025-08-07', '2025-09-06', 248214.00, 218663.00, 27549.00, 2002.00, 86579.00, 133012.00, 17266.00, 16397.00, 2822.00, 25103.00, 41847.00, 323026.00, -74812.00, -30.14, -74812.00, -30.14, 15479.00, 14881.00, 132, 173, 76.30, 'draft', 'Strong performance this quarter.', 1, NULL, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 5, 'quarterly', '2025-09-23', '2025-12-22', 471941.00, 419664.00, 32679.00, 19598.00, 76888.00, 79955.00, 10042.00, 12652.00, 8475.00, 18898.00, 69746.00, 276656.00, 195285.00, 41.38, 195285.00, 41.38, 3748.00, 13489.00, 44, 344, 12.79, 'approved', 'Need to improve margin on next phase.', 10, 11, '2025-11-29 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(10, 2, 'quarterly', '2025-10-03', '2026-01-01', 222157.00, 186898.00, 34933.00, 326.00, 99063.00, 60410.00, 46558.00, 9561.00, 9578.00, 48087.00, 42364.00, 315621.00, -93464.00, -42.07, -93464.00, -42.07, 23521.00, 8158.00, 110, 229, 48.03, 'draft', 'Need to improve margin on next phase.', 7, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(11, 3, 'yearly', '2025-08-12', '2026-08-12', 311611.00, 269087.00, 40920.00, 1604.00, 118949.00, 49749.00, 48401.00, 8817.00, 6225.00, 14739.00, 10052.00, 256932.00, 54679.00, 17.55, 54679.00, 17.55, 13604.00, 12211.00, 128, 236, 54.24, 'reviewed', 'Equipment rental costs were well managed.', 13, 4, '2025-11-29 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(12, 2, 'monthly', '2025-10-17', '2025-11-16', 358929.00, 327151.00, 15167.00, 16611.00, 17897.00, 166711.00, 31090.00, 7476.00, 5084.00, 12664.00, 49327.00, 290249.00, 68680.00, 19.13, 68680.00, 19.13, 25239.00, 24178.00, 31, 176, 17.61, 'reviewed', 'Some cost overruns due to material price increases.', 7, 3, '2025-11-30 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(13, 4, 'quarterly', '2025-08-14', '2025-11-12', 198793.00, 151796.00, 42281.00, 4716.00, 109340.00, 198095.00, 22743.00, 3015.00, 5445.00, 19987.00, 67358.00, 425983.00, -227190.00, -114.28, -227190.00, -114.28, 15495.00, 22707.00, 125, 262, 47.71, 'approved', 'Equipment rental costs were well managed.', 1, 2, '2025-11-30 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(14, 1, 'yearly', '2025-08-23', '2026-08-23', 343566.00, 298611.00, 44701.00, 254.00, 46299.00, 49285.00, 9513.00, 5003.00, 5705.00, 20426.00, 50628.00, 186859.00, 156707.00, 45.61, 156707.00, 45.61, 23218.00, 19086.00, 222, 276, 80.43, 'draft', 'Strong performance this quarter.', 6, NULL, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(15, 3, 'monthly', '2025-08-07', '2025-09-06', 207381.00, 169789.00, 29152.00, 8440.00, 71096.00, 31675.00, 43632.00, 19399.00, 2687.00, 21160.00, 35330.00, 224979.00, -17598.00, -8.49, -17598.00, -8.49, 44430.00, 9508.00, 178, 212, 83.96, 'approved', 'Labor costs came in higher than expected.', 13, 5, '2025-12-02 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(16, 1, 'yearly', '2025-11-07', '2026-11-07', 126916.00, 105950.00, 16093.00, 4873.00, 18219.00, 88067.00, 32567.00, 13241.00, 7059.00, 28344.00, 69773.00, 257270.00, -130354.00, -102.71, -130354.00, -102.71, 35648.00, 3639.00, 34, 361, 9.42, 'final', 'Overhead allocation was accurate.', 8, 1, '2025-11-22 14:23:10', '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(17, 5, 'yearly', '2025-07-31', '2026-07-31', 289964.00, 275871.00, 14074.00, 19.00, 118923.00, 37728.00, 36626.00, 15472.00, 9205.00, 24927.00, 68298.00, 311179.00, -21215.00, -7.32, -21215.00, -7.32, 19944.00, 11640.00, 30, 49, 61.22, 'approved', 'Need to improve margin on next phase.', 12, 5, '2025-11-24 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(18, 2, 'quarterly', '2025-10-22', '2026-01-20', 487604.00, 454687.00, 13720.00, 19197.00, 56793.00, 117928.00, 17203.00, 7749.00, 9371.00, 36980.00, 18374.00, 264398.00, 223206.00, 45.78, 223206.00, 45.78, 27845.00, 12177.00, 63, 298, 21.14, 'reviewed', 'Change orders increased profitability significantly.', 2, 11, '2025-12-01 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(19, 3, 'monthly', '2025-06-18', '2025-07-18', 397604.00, 344620.00, 37223.00, 15761.00, 138294.00, 109643.00, 20011.00, 16010.00, 1613.00, 35124.00, 87191.00, 407886.00, -10282.00, -2.59, -10282.00, -2.59, 15668.00, 24683.00, 118, 347, 34.01, 'approved', 'Equipment rental costs were well managed.', 12, 10, '2025-12-10 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(20, 5, 'yearly', '2025-07-15', '2026-07-15', 447175.00, 406115.00, 25072.00, 15988.00, 23302.00, 132489.00, 46508.00, 16827.00, 3435.00, 14387.00, 48153.00, 285101.00, 162074.00, 36.24, 162074.00, 36.24, 6631.00, 9147.00, 180, 188, 95.74, 'approved', 'Overhead allocation was accurate.', 10, 2, '2025-11-12 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(21, 3, 'yearly', '2025-09-08', '2026-09-08', 485902.00, 455167.00, 22913.00, 7822.00, 54512.00, 149834.00, 42587.00, 14881.00, 7637.00, 40555.00, 88142.00, 398148.00, 87754.00, 18.06, 87754.00, 18.06, 3074.00, 3811.00, 34, 206, 16.50, 'reviewed', 'Overhead allocation was accurate.', 1, 4, '2025-12-05 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(22, 4, 'monthly', '2025-07-28', '2025-08-27', 225566.00, 177278.00, 40300.00, 7988.00, 102617.00, 145412.00, 15582.00, 9593.00, 8535.00, 46917.00, 69580.00, 398236.00, -172670.00, -76.55, -172670.00, -76.55, 5230.00, 8381.00, 214, 284, 75.35, 'final', 'Need to improve margin on next phase.', 2, 3, '2025-11-25 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(23, 3, 'yearly', '2025-08-30', '2026-08-30', 159015.00, 117743.00, 28127.00, 13145.00, 115205.00, 113918.00, 18713.00, 5282.00, 7144.00, 21115.00, 32440.00, 313817.00, -154802.00, -97.35, -154802.00, -97.35, 1646.00, 10149.00, 159, 225, 70.67, 'draft', 'Project completed within budget. Excellent cost control.', 13, NULL, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(24, 5, 'yearly', '2025-07-04', '2026-07-04', 348518.00, 336907.00, 8109.00, 3502.00, 146427.00, 152711.00, 45232.00, 8359.00, 5354.00, 9948.00, 70228.00, 438259.00, -89741.00, -25.75, -89741.00, -25.75, 22817.00, 3580.00, 138, 363, 38.02, 'reviewed', 'Equipment rental costs were well managed.', 2, 9, '2025-12-03 14:23:22', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(25, 1, 'yearly', '2025-10-25', '2026-10-25', 448237.00, 411575.00, 22937.00, 13725.00, 141317.00, 115385.00, 17336.00, 2622.00, 8108.00, 15206.00, 97964.00, 397938.00, 50299.00, 11.22, 50299.00, 11.22, 3743.00, 4028.00, 58, 59, 98.31, 'approved', 'Project completed within budget. Excellent cost control.', 7, 4, '2025-11-17 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(26, 1, 'monthly', '2025-07-07', '2025-08-06', 308243.00, 279611.00, 11569.00, 17063.00, 109908.00, 105402.00, 17313.00, 8046.00, 7952.00, 28621.00, 15041.00, 292283.00, 15960.00, 5.18, 15960.00, 5.18, 1001.00, 22257.00, 47, 104, 45.19, 'reviewed', 'Transportation costs were minimized through local sourcing.', 3, 11, '2025-12-07 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(27, 1, 'monthly', '2025-09-04', '2025-10-04', 490626.00, 454624.00, 28984.00, 7018.00, 44558.00, 170109.00, 25141.00, 15134.00, 8588.00, 6849.00, 89724.00, 360103.00, 130523.00, 26.60, 130523.00, 26.60, 39643.00, 1151.00, 180, 228, 78.95, 'draft', 'Strong performance this quarter.', 9, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(28, 3, 'yearly', '2025-11-07', '2026-11-07', 338077.00, 305309.00, 15773.00, 16995.00, 128663.00, 50356.00, 10134.00, 3390.00, 3804.00, 22793.00, 33669.00, 252809.00, 85268.00, 25.22, 85268.00, 25.22, 12391.00, 14943.00, 69, 218, 31.65, 'approved', 'Strong performance this quarter.', 1, 8, '2025-11-22 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(29, 1, 'monthly', '2025-11-09', '2025-12-09', 267560.00, 242591.00, 19930.00, 5039.00, 22596.00, 34065.00, 29370.00, 13984.00, 8025.00, 5414.00, 80240.00, 193694.00, 73866.00, 27.61, 73866.00, 27.61, 11119.00, 14609.00, 270, 298, 90.60, 'final', 'Need to improve margin on next phase.', 9, 12, '2025-12-01 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(30, 2, 'monthly', '2025-08-14', '2025-09-13', 527053.00, 475191.00, 44902.00, 6960.00, 117002.00, 34821.00, 11786.00, 3628.00, 6466.00, 46678.00, 59384.00, 279765.00, 247288.00, 46.92, 247288.00, 46.92, 1832.00, 21162.00, 59, 103, 57.28, 'approved', 'Need to improve margin on next phase.', 4, 3, '2025-12-09 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(31, 3, 'yearly', '2025-07-11', '2026-07-11', 102534.00, 78692.00, 23767.00, 75.00, 87880.00, 152807.00, 29097.00, 15437.00, 5582.00, 21611.00, 83450.00, 395864.00, -293330.00, -286.08, -293330.00, -286.08, 38614.00, 8758.00, 77, 149, 51.68, 'draft', 'Need to improve margin on next phase.', 3, NULL, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(32, 4, 'yearly', '2025-08-30', '2026-08-30', 130606.00, 95121.00, 23927.00, 11558.00, 72796.00, 178075.00, 10765.00, 6250.00, 9491.00, 21839.00, 18180.00, 317396.00, -186790.00, -143.02, -186790.00, -143.02, 34856.00, 14458.00, 58, 81, 71.60, 'final', 'Project completed within budget. Excellent cost control.', 7, 3, '2025-11-16 14:23:30', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(33, 4, 'monthly', '2025-07-12', '2025-08-11', 296354.00, 249756.00, 35165.00, 11433.00, 130597.00, 105780.00, 37265.00, 9383.00, 8329.00, 22266.00, 74874.00, 388494.00, -92140.00, -31.09, -92140.00, -31.09, 37302.00, 17920.00, 34, 88, 38.64, 'draft', 'Project completed within budget. Excellent cost control.', 2, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(34, 3, 'yearly', '2025-10-15', '2026-10-15', 237565.00, 210672.00, 11290.00, 15603.00, 107202.00, 25570.00, 17960.00, 3995.00, 1948.00, 31904.00, 76122.00, 264701.00, -27136.00, -11.42, -27136.00, -11.42, 38317.00, 1192.00, 33, 33, 100.00, 'reviewed', 'Change orders increased profitability significantly.', 2, 9, '2025-12-04 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(35, 2, 'quarterly', '2025-07-03', '2025-10-01', 183848.00, 142358.00, 37944.00, 3546.00, 110490.00, 166213.00, 27645.00, 10023.00, 2615.00, 36837.00, 43389.00, 397212.00, -213364.00, -116.05, -213364.00, -116.05, 954.00, 3784.00, 16, 292, 5.48, 'approved', 'Overhead allocation was accurate.', 11, 6, '2025-11-17 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(36, 1, 'yearly', '2025-07-19', '2026-07-19', 85677.00, 60912.00, 18653.00, 6112.00, 79409.00, 160533.00, 39764.00, 8101.00, 1575.00, 9453.00, 59190.00, 358025.00, -272348.00, -317.88, -272348.00, -317.88, 39140.00, 12066.00, 27, 39, 69.23, 'draft', 'Equipment rental costs were well managed.', 2, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(37, 5, 'quarterly', '2025-06-24', '2025-09-22', 84095.00, 52305.00, 18501.00, 13289.00, 145120.00, 85030.00, 30445.00, 4112.00, 2925.00, 9135.00, 81001.00, 357768.00, -273673.00, -325.43, -273673.00, -325.43, 17179.00, 17035.00, 33, 187, 17.65, 'draft', 'Change orders increased profitability significantly.', 13, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(38, 2, 'yearly', '2025-09-21', '2026-09-21', 339035.00, 312768.00, 10737.00, 15530.00, 22550.00, 115837.00, 28854.00, 15659.00, 8625.00, 13856.00, 58682.00, 264063.00, 74972.00, 22.11, 74972.00, 22.11, 18067.00, 10273.00, 42, 142, 29.58, 'draft', 'Transportation costs were minimized through local sourcing.', 7, NULL, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(39, 3, 'yearly', '2025-06-22', '2026-06-22', 325508.00, 305935.00, 6738.00, 12835.00, 136347.00, 134497.00, 6728.00, 5126.00, 7070.00, 11902.00, 34304.00, 335974.00, -10466.00, -3.22, -10466.00, -3.22, 25919.00, 9536.00, 38, 60, 63.33, 'approved', 'Strong performance this quarter.', 7, 12, '2025-12-08 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(40, 3, 'yearly', '2025-07-27', '2026-07-27', 296040.00, 264299.00, 20963.00, 10778.00, 84500.00, 25721.00, 47860.00, 12118.00, 7070.00, 29685.00, 43891.00, 250845.00, 45195.00, 15.27, 45195.00, 15.27, 25888.00, 15970.00, 80, 257, 31.13, 'final', 'Project completed within budget. Excellent cost control.', 7, 12, '2025-11-15 14:23:43', '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(255) NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `po_date` date NOT NULL,
  `expected_delivery_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL,
  `status` enum('draft','sent','acknowledged','partially_received','received','cancelled') NOT NULL DEFAULT 'draft',
  `payment_terms` enum('net_30','net_45','net_60','advance','on_delivery') NOT NULL DEFAULT 'net_30',
  `delivery_address` text NOT NULL,
  `terms_conditions` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `received_quantity` int(11) NOT NULL DEFAULT 0,
  `pending_quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pr_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `requisition_date` date NOT NULL,
  `required_date` date NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('draft','submitted','approved','rejected','converted_to_po','cancelled') NOT NULL DEFAULT 'draft',
  `purpose` text NOT NULL,
  `justification` text DEFAULT NULL,
  `estimated_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_items`
--

CREATE TABLE `purchase_requisition_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_requisition_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `estimated_unit_price` decimal(10,2) DEFAULT NULL,
  `estimated_total_price` decimal(15,2) DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quality_checks`
--

CREATE TABLE `quality_checks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qc_number` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `inspector_name` varchar(255) NOT NULL,
  `inspector_designation` varchar(255) NOT NULL,
  `status` enum('pending','passed','failed','rejected') NOT NULL,
  `qc_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `checked_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quality_checks`
--

INSERT INTO `quality_checks` (`id`, `qc_number`, `item_name`, `item_code`, `vendor_name`, `inspector_name`, `inspector_designation`, `status`, `qc_date`, `remarks`, `created_at`, `updated_at`, `checked_by`) VALUES
(1, 'QC-2025-0001', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 'SolarTech Solutions', 'John Doe', 'Quality Inspector', 'passed', '2025-12-10', 'All panels passed quality standards', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(2, 'QC-2025-0002', 'String Inverter 5KW', 'INV-5K-SI-001', 'PowerMax Services', 'Jane Smith', 'Senior Inspector', 'failed', '2025-12-09', 'Voltage fluctuation detected in 2 units', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(3, 'QC-2025-0003', 'Ground Mount Structure', 'MS-GM-001', 'MetalWorks Ltd', 'Rajesh Kumar', 'Quality Inspector', 'pending', '2025-12-08', 'Awaiting material testing results', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL),
(4, 'QC-2025-0004', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 'CableCorp Industries', 'Priya Sharma', 'Quality Inspector', 'passed', '2025-12-07', 'Cable specifications meet requirements', '2025-12-11 14:22:41', '2025-12-11 14:22:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_quotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `revision_number` int(11) NOT NULL DEFAULT 0,
  `is_revision` tinyint(1) NOT NULL DEFAULT 0,
  `is_latest` tinyint(1) NOT NULL DEFAULT 1,
  `quotation_number` varchar(255) NOT NULL,
  `quotation_type` enum('solar_chakki','solar_street_light','commercial','subsidy_quotation') DEFAULT NULL,
  `quotation_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `follow_up_date` date DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('draft','sent','accepted','approved','rejected','expired') DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_modified_at` timestamp NULL DEFAULT NULL,
  `channel_partner_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `parent_quotation_id`, `revision_number`, `is_revision`, `is_latest`, `quotation_number`, `quotation_type`, `quotation_date`, `valid_until`, `follow_up_date`, `client_id`, `project_id`, `subtotal`, `tax_amount`, `total_amount`, `status`, `notes`, `terms_conditions`, `created_by`, `created_at`, `updated_at`, `last_modified_at`, `channel_partner_id`) VALUES
(1, NULL, 0, 0, 1, 'QUOT-2024-001', NULL, '2025-11-26', '2025-12-26', NULL, 1, 1, 500000.00, 90000.00, 590000.00, 'approved', 'Complete solar panel installation package including panels, inverters, and monitoring system.', 'Payment terms: 50% advance, 50% on completion. Warranty: 5 years on panels, 2 years on inverters.', 1, '2025-12-11 14:23:43', '2026-01-04 09:24:53', '2026-01-04 09:24:53', NULL),
(3, NULL, 0, 0, 1, 'QUOT-2024-003', NULL, '2025-12-06', '2026-01-05', NULL, 3, NULL, 250000.00, 45000.00, 295000.00, 'approved', 'Residential solar system for 3BHK house with rooftop mounting.', 'Payment terms: 40% advance, 60% on completion. Free maintenance for first year.', 2, '2025-12-11 14:23:43', '2026-01-04 09:24:40', '2026-01-04 09:24:40', NULL),
(4, NULL, 0, 0, 1, 'QUOT-2024-004', NULL, '2025-11-21', '2025-12-06', NULL, 4, 3, 1200000.00, 216000.00, 1416000.00, 'approved', 'Large scale industrial solar power plant with advanced monitoring system.', 'Payment terms: 25% advance, 50% on delivery, 25% on commissioning. Project timeline: 3-4 months.', 1, '2025-12-11 14:23:43', '2026-01-04 09:27:12', '2026-01-04 09:27:12', NULL),
(5, NULL, 0, 0, 1, 'QUOT-2024-005', NULL, '2025-12-03', '2026-01-02', NULL, 5, NULL, 800000.00, 144000.00, 944000.00, 'rejected', 'Hospital solar backup system with battery storage for critical equipment.', 'Payment terms: 30% advance, 70% on completion. 24/7 support included.', 2, '2025-12-11 14:23:43', '2025-12-11 14:23:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resource_allocations`
--

CREATE TABLE `resource_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allocation_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `resource_type` enum('human','equipment','material','financial','other') NOT NULL DEFAULT 'human',
  `status` enum('planned','allocated','in_use','completed','cancelled') NOT NULL DEFAULT 'planned',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `resource_name` varchar(255) NOT NULL,
  `resource_category` varchar(255) DEFAULT NULL,
  `resource_specifications` text DEFAULT NULL,
  `allocated_to` bigint(20) UNSIGNED DEFAULT NULL,
  `allocated_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `allocation_start_date` datetime DEFAULT NULL,
  `allocation_end_date` datetime DEFAULT NULL,
  `actual_start_date` datetime DEFAULT NULL,
  `actual_end_date` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `allocated_quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `actual_quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantity_unit` varchar(255) NOT NULL DEFAULT 'units',
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_estimated_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_actual_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `budget_allocated` decimal(10,2) NOT NULL DEFAULT 0.00,
  `utilization_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `utilization_notes` text DEFAULT NULL,
  `availability_schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`availability_schedule`)),
  `constraints` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`constraints`)),
  `dependencies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dependencies`)),
  `notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_critical` tinyint(1) NOT NULL DEFAULT 0,
  `is_billable` tinyint(1) NOT NULL DEFAULT 1,
  `completion_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resource_allocations`
--

INSERT INTO `resource_allocations` (`id`, `allocation_code`, `title`, `description`, `resource_type`, `status`, `priority`, `project_id`, `activity_id`, `resource_name`, `resource_category`, `resource_specifications`, `allocated_to`, `allocated_by`, `approved_by`, `allocation_start_date`, `allocation_end_date`, `actual_start_date`, `actual_end_date`, `approved_at`, `allocated_quantity`, `actual_quantity`, `quantity_unit`, `hourly_rate`, `unit_cost`, `total_estimated_cost`, `total_actual_cost`, `budget_allocated`, `utilization_percentage`, `utilization_notes`, `availability_schedule`, `constraints`, `dependencies`, `notes`, `attachments`, `tags`, `is_critical`, `is_billable`, `completion_notes`, `created_at`, `updated_at`) VALUES
(1, 'RA2025120001', 'Senior Solar Engineer Allocation', 'Allocation of senior solar engineer for project design and supervision', 'human', 'planned', 'high', 1, 12, 'John Smith', 'Engineering', '5+ years experience in solar project design, AutoCAD certified', 3, 11, NULL, '2025-11-11 19:52:41', '2026-02-28 19:52:41', NULL, NULL, NULL, 1.00, 0.00, 'person', 1500.00, 0.00, 120000.00, 144000.00, 150000.00, 75.00, 'Sample utilization data', NULL, NULL, NULL, 'Key resource for project success', NULL, '[\"engineering\",\"senior\",\"design\"]', 1, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'RA2025120002', 'Solar Panel Installation Equipment', 'Equipment allocation for solar panel installation work', 'equipment', 'in_use', 'medium', 3, 16, 'Solar Panel Lifting Equipment', 'Installation Tools', 'Hydraulic lifting system, capacity 500kg, safety certified', NULL, 9, NULL, '2025-11-26 19:52:41', '2026-02-25 19:52:41', NULL, NULL, NULL, 2.00, 0.00, 'units', 0.00, 5000.00, 10000.00, 10100.00, 12000.00, 60.00, 'Sample utilization data', NULL, NULL, NULL, 'Essential for large panel installations', NULL, '[\"equipment\",\"installation\",\"safety\"]', 0, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'RA2025120003', 'Solar Panel Materials', 'Allocation of solar panels and mounting materials', 'material', 'allocated', 'critical', 1, 1, 'Monocrystalline Solar Panels 400W', 'Solar Components', '400W monocrystalline panels, 20.5% efficiency, 25-year warranty', NULL, 2, NULL, '2025-12-03 19:52:41', '2026-03-11 19:52:41', NULL, NULL, NULL, 100.00, 0.00, 'panels', 0.00, 12000.00, 1200000.00, 1344000.00, 1300000.00, 90.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project materials', NULL, '[\"panels\",\"materials\",\"critical\"]', 1, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'RA2025120004', 'Project Budget Allocation', 'Financial allocation for project expenses', 'financial', 'planned', 'high', 3, 11, 'Project Budget Fund', 'Finance', 'Working capital for project operations', NULL, 11, NULL, '2025-11-28 19:52:41', '2026-02-08 19:52:41', NULL, NULL, NULL, 1.00, 0.00, 'fund', 0.00, 5000000.00, 5000000.00, 5700000.00, 5500000.00, 45.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project funding', NULL, '[\"budget\",\"finance\",\"funding\"]', 1, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'RA2025120005', 'Site Supervisor Allocation', 'Site supervisor for daily operations management', 'human', 'planned', 'medium', 1, 6, 'Mike Johnson', 'Supervision', '10+ years construction experience, safety certified', 2, 9, NULL, '2025-11-18 19:52:41', '2026-02-03 19:52:41', NULL, NULL, NULL, 1.00, 0.00, 'person', 800.00, 0.00, 64000.00, 58240.00, 70000.00, 85.00, 'Sample utilization data', NULL, NULL, NULL, 'Daily site management', NULL, '[\"supervision\",\"management\",\"site\"]', 0, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'RA2025120006', 'Inverter Equipment', 'Solar inverters for power conversion', 'equipment', 'in_use', 'high', 1, 2, 'String Inverters 50kW', 'Power Electronics', '50kW string inverters, 98% efficiency, IP65 rating', NULL, 3, NULL, '2025-12-03 19:52:41', '2026-01-16 19:52:41', NULL, NULL, NULL, 4.00, 0.00, 'units', 0.00, 150000.00, 600000.00, 588000.00, 650000.00, 70.00, 'Sample utilization data', NULL, NULL, NULL, 'Critical for power conversion', NULL, '[\"inverters\",\"power\",\"electronics\"]', 1, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'RA2025120007', 'Mounting Structure Materials', 'Steel mounting structures for solar panels', 'material', 'completed', 'medium', 2, 3, 'Galvanized Steel Mounting Structure', 'Structural Components', 'Hot-dip galvanized steel, corrosion resistant, 25-year warranty', NULL, 13, NULL, '2025-12-07 19:52:41', '2026-03-06 19:52:41', NULL, NULL, NULL, 50.00, 0.00, 'sets', 0.00, 25000.00, 1250000.00, 1012500.00, 1300000.00, 55.00, 'Sample utilization data', NULL, NULL, NULL, 'Structural support for panels', NULL, '[\"mounting\",\"steel\",\"structural\"]', 0, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'RA2025120008', 'Quality Control Inspector', 'QC inspector for quality assurance', 'human', 'planned', 'medium', 4, 4, 'Sarah Wilson', 'Quality Control', 'ISO 9001 certified, 8+ years QC experience', 10, 5, NULL, '2025-11-30 19:52:41', '2026-02-02 19:52:41', NULL, NULL, NULL, 1.00, 0.00, 'person', 1000.00, 0.00, 80000.00, 80000.00, 85000.00, 65.00, 'Sample utilization data', NULL, NULL, NULL, 'Quality assurance specialist', NULL, '[\"quality\",\"inspection\",\"certified\"]', 0, 1, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'RA2025120009', 'Senior Solar Engineer Allocation', 'Allocation of senior solar engineer for project design and supervision', 'human', 'in_use', 'high', 1, 38, 'John Smith', 'Engineering', '5+ years experience in solar project design, AutoCAD certified', 8, 12, NULL, '2025-12-01 19:53:10', '2026-03-08 19:53:10', NULL, NULL, NULL, 1.00, 0.00, 'person', 1500.00, 0.00, 120000.00, 111600.00, 150000.00, 75.00, 'Sample utilization data', NULL, NULL, NULL, 'Key resource for project success', NULL, '[\"engineering\",\"senior\",\"design\"]', 1, 1, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(10, 'RA2025120010', 'Solar Panel Installation Equipment', 'Equipment allocation for solar panel installation work', 'equipment', 'completed', 'medium', 2, 29, 'Solar Panel Lifting Equipment', 'Installation Tools', 'Hydraulic lifting system, capacity 500kg, safety certified', NULL, 3, NULL, '2025-11-11 19:53:10', '2026-01-18 19:53:10', NULL, NULL, NULL, 2.00, 0.00, 'units', 0.00, 5000.00, 10000.00, 10100.00, 12000.00, 60.00, 'Sample utilization data', NULL, NULL, NULL, 'Essential for large panel installations', NULL, '[\"equipment\",\"installation\",\"safety\"]', 0, 1, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(11, 'RA2025120011', 'Solar Panel Materials', 'Allocation of solar panels and mounting materials', 'material', 'allocated', 'critical', 3, 1, 'Monocrystalline Solar Panels 400W', 'Solar Components', '400W monocrystalline panels, 20.5% efficiency, 25-year warranty', NULL, 7, NULL, '2025-11-21 19:53:10', '2026-02-02 19:53:10', NULL, NULL, NULL, 100.00, 0.00, 'panels', 0.00, 12000.00, 1200000.00, 1092000.00, 1300000.00, 90.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project materials', NULL, '[\"panels\",\"materials\",\"critical\"]', 1, 1, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(12, 'RA2025120012', 'Project Budget Allocation', 'Financial allocation for project expenses', 'financial', 'completed', 'high', 2, 6, 'Project Budget Fund', 'Finance', 'Working capital for project operations', NULL, 6, NULL, '2025-11-30 19:53:10', '2026-02-01 19:53:10', NULL, NULL, NULL, 1.00, 0.00, 'fund', 0.00, 5000000.00, 5000000.00, 5500000.00, 5500000.00, 45.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project funding', NULL, '[\"budget\",\"finance\",\"funding\"]', 1, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(13, 'RA2025120013', 'Site Supervisor Allocation', 'Site supervisor for daily operations management', 'human', 'in_use', 'medium', 3, 26, 'Mike Johnson', 'Supervision', '10+ years construction experience, safety certified', 10, 1, NULL, '2025-11-17 19:53:10', '2026-02-22 19:53:10', NULL, NULL, NULL, 1.00, 0.00, 'person', 800.00, 0.00, 64000.00, 76160.00, 70000.00, 85.00, 'Sample utilization data', NULL, NULL, NULL, 'Daily site management', NULL, '[\"supervision\",\"management\",\"site\"]', 0, 1, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(14, 'RA2025120014', 'Inverter Equipment', 'Solar inverters for power conversion', 'equipment', 'allocated', 'high', 5, 31, 'String Inverters 50kW', 'Power Electronics', '50kW string inverters, 98% efficiency, IP65 rating', NULL, 8, NULL, '2025-11-21 19:53:10', '2026-01-14 19:53:10', NULL, NULL, NULL, 4.00, 0.00, 'units', 0.00, 150000.00, 600000.00, 570000.00, 650000.00, 70.00, 'Sample utilization data', NULL, NULL, NULL, 'Critical for power conversion', NULL, '[\"inverters\",\"power\",\"electronics\"]', 1, 1, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(15, 'RA2025120015', 'Mounting Structure Materials', 'Steel mounting structures for solar panels', 'material', 'planned', 'medium', 4, 34, 'Galvanized Steel Mounting Structure', 'Structural Components', 'Hot-dip galvanized steel, corrosion resistant, 25-year warranty', NULL, 9, NULL, '2025-11-13 19:53:10', '2026-02-09 19:53:10', NULL, NULL, NULL, 50.00, 0.00, 'sets', 0.00, 25000.00, 1250000.00, 1325000.00, 1300000.00, 55.00, 'Sample utilization data', NULL, NULL, NULL, 'Structural support for panels', NULL, '[\"mounting\",\"steel\",\"structural\"]', 0, 1, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(16, 'RA2025120016', 'Quality Control Inspector', 'QC inspector for quality assurance', 'human', 'in_use', 'medium', 2, 32, 'Sarah Wilson', 'Quality Control', 'ISO 9001 certified, 8+ years QC experience', 5, 1, NULL, '2025-11-22 19:53:10', '2026-02-18 19:53:10', NULL, NULL, NULL, 1.00, 0.00, 'person', 1000.00, 0.00, 80000.00, 72800.00, 85000.00, 65.00, 'Sample utilization data', NULL, NULL, NULL, 'Quality assurance specialist', NULL, '[\"quality\",\"inspection\",\"certified\"]', 0, 1, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(17, 'RA2025120017', 'Senior Solar Engineer Allocation', 'Allocation of senior solar engineer for project design and supervision', 'human', 'planned', 'high', 1, 13, 'John Smith', 'Engineering', '5+ years experience in solar project design, AutoCAD certified', 11, 12, NULL, '2025-11-27 19:53:22', '2026-01-26 19:53:22', NULL, NULL, NULL, 1.00, 0.00, 'person', 1500.00, 0.00, 120000.00, 116400.00, 150000.00, 75.00, 'Sample utilization data', NULL, NULL, NULL, 'Key resource for project success', NULL, '[\"engineering\",\"senior\",\"design\"]', 1, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(18, 'RA2025120018', 'Solar Panel Installation Equipment', 'Equipment allocation for solar panel installation work', 'equipment', 'completed', 'medium', 4, 29, 'Solar Panel Lifting Equipment', 'Installation Tools', 'Hydraulic lifting system, capacity 500kg, safety certified', NULL, 5, NULL, '2025-12-07 19:53:22', '2026-02-21 19:53:22', NULL, NULL, NULL, 2.00, 0.00, 'units', 0.00, 5000.00, 10000.00, 9500.00, 12000.00, 60.00, 'Sample utilization data', NULL, NULL, NULL, 'Essential for large panel installations', NULL, '[\"equipment\",\"installation\",\"safety\"]', 0, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(19, 'RA2025120019', 'Solar Panel Materials', 'Allocation of solar panels and mounting materials', 'material', 'in_use', 'critical', 2, 26, 'Monocrystalline Solar Panels 400W', 'Solar Components', '400W monocrystalline panels, 20.5% efficiency, 25-year warranty', NULL, 12, NULL, '2025-11-22 19:53:22', '2026-02-21 19:53:22', NULL, NULL, NULL, 100.00, 0.00, 'panels', 0.00, 12000.00, 1200000.00, 1368000.00, 1300000.00, 90.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project materials', NULL, '[\"panels\",\"materials\",\"critical\"]', 1, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(20, 'RA2025120020', 'Project Budget Allocation', 'Financial allocation for project expenses', 'financial', 'allocated', 'high', 3, 22, 'Project Budget Fund', 'Finance', 'Working capital for project operations', NULL, 9, NULL, '2025-11-17 19:53:22', '2026-02-23 19:53:22', NULL, NULL, NULL, 1.00, 0.00, 'fund', 0.00, 5000000.00, 5000000.00, 5750000.00, 5500000.00, 45.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project funding', NULL, '[\"budget\",\"finance\",\"funding\"]', 1, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(21, 'RA2025120021', 'Site Supervisor Allocation', 'Site supervisor for daily operations management', 'human', 'allocated', 'medium', 2, 31, 'Mike Johnson', 'Supervision', '10+ years construction experience, safety certified', 9, 12, NULL, '2025-11-22 19:53:22', '2026-01-29 19:53:22', NULL, NULL, NULL, 1.00, 0.00, 'person', 800.00, 0.00, 64000.00, 75520.00, 70000.00, 85.00, 'Sample utilization data', NULL, NULL, NULL, 'Daily site management', NULL, '[\"supervision\",\"management\",\"site\"]', 0, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(22, 'RA2025120022', 'Inverter Equipment', 'Solar inverters for power conversion', 'equipment', 'in_use', 'high', 1, 24, 'String Inverters 50kW', 'Power Electronics', '50kW string inverters, 98% efficiency, IP65 rating', NULL, 12, NULL, '2025-11-30 19:53:22', '2026-02-26 19:53:22', NULL, NULL, NULL, 4.00, 0.00, 'units', 0.00, 150000.00, 600000.00, 660000.00, 650000.00, 70.00, 'Sample utilization data', NULL, NULL, NULL, 'Critical for power conversion', NULL, '[\"inverters\",\"power\",\"electronics\"]', 1, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(23, 'RA2025120023', 'Mounting Structure Materials', 'Steel mounting structures for solar panels', 'material', 'allocated', 'medium', 4, 29, 'Galvanized Steel Mounting Structure', 'Structural Components', 'Hot-dip galvanized steel, corrosion resistant, 25-year warranty', NULL, 4, NULL, '2025-12-02 19:53:22', '2026-03-05 19:53:22', NULL, NULL, NULL, 50.00, 0.00, 'sets', 0.00, 25000.00, 1250000.00, 1500000.00, 1300000.00, 55.00, 'Sample utilization data', NULL, NULL, NULL, 'Structural support for panels', NULL, '[\"mounting\",\"steel\",\"structural\"]', 0, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(24, 'RA2025120024', 'Quality Control Inspector', 'QC inspector for quality assurance', 'human', 'completed', 'medium', 2, 38, 'Sarah Wilson', 'Quality Control', 'ISO 9001 certified, 8+ years QC experience', 6, 2, NULL, '2025-11-21 19:53:22', '2026-02-06 19:53:22', NULL, NULL, NULL, 1.00, 0.00, 'person', 1000.00, 0.00, 80000.00, 95200.00, 85000.00, 65.00, 'Sample utilization data', NULL, NULL, NULL, 'Quality assurance specialist', NULL, '[\"quality\",\"inspection\",\"certified\"]', 0, 1, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(25, 'RA2025120025', 'Senior Solar Engineer Allocation', 'Allocation of senior solar engineer for project design and supervision', 'human', 'in_use', 'high', 1, 21, 'John Smith', 'Engineering', '5+ years experience in solar project design, AutoCAD certified', 1, 9, NULL, '2025-12-05 19:53:30', '2026-02-16 19:53:30', NULL, NULL, NULL, 1.00, 0.00, 'person', 1500.00, 0.00, 120000.00, 105600.00, 150000.00, 75.00, 'Sample utilization data', NULL, NULL, NULL, 'Key resource for project success', NULL, '[\"engineering\",\"senior\",\"design\"]', 1, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(26, 'RA2025120026', 'Solar Panel Installation Equipment', 'Equipment allocation for solar panel installation work', 'equipment', 'allocated', 'medium', 3, 29, 'Solar Panel Lifting Equipment', 'Installation Tools', 'Hydraulic lifting system, capacity 500kg, safety certified', NULL, 7, NULL, '2025-11-15 19:53:30', '2026-02-23 19:53:30', NULL, NULL, NULL, 2.00, 0.00, 'units', 0.00, 5000.00, 10000.00, 8700.00, 12000.00, 60.00, 'Sample utilization data', NULL, NULL, NULL, 'Essential for large panel installations', NULL, '[\"equipment\",\"installation\",\"safety\"]', 0, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(27, 'RA2025120027', 'Solar Panel Materials', 'Allocation of solar panels and mounting materials', 'material', 'allocated', 'critical', 3, 24, 'Monocrystalline Solar Panels 400W', 'Solar Components', '400W monocrystalline panels, 20.5% efficiency, 25-year warranty', NULL, 13, NULL, '2025-11-22 19:53:30', '2026-03-11 19:53:30', NULL, NULL, NULL, 100.00, 0.00, 'panels', 0.00, 12000.00, 1200000.00, 1260000.00, 1300000.00, 90.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project materials', NULL, '[\"panels\",\"materials\",\"critical\"]', 1, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(28, 'RA2025120028', 'Project Budget Allocation', 'Financial allocation for project expenses', 'financial', 'planned', 'high', 4, 51, 'Project Budget Fund', 'Finance', 'Working capital for project operations', NULL, 6, NULL, '2025-11-24 19:53:30', '2026-03-10 19:53:30', NULL, NULL, NULL, 1.00, 0.00, 'fund', 0.00, 5000000.00, 5000000.00, 4150000.00, 5500000.00, 45.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project funding', NULL, '[\"budget\",\"finance\",\"funding\"]', 1, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(29, 'RA2025120029', 'Site Supervisor Allocation', 'Site supervisor for daily operations management', 'human', 'planned', 'medium', 3, 51, 'Mike Johnson', 'Supervision', '10+ years construction experience, safety certified', 8, 5, NULL, '2025-11-30 19:53:30', '2026-03-08 19:53:30', NULL, NULL, NULL, 1.00, 0.00, 'person', 800.00, 0.00, 64000.00, 71680.00, 70000.00, 85.00, 'Sample utilization data', NULL, NULL, NULL, 'Daily site management', NULL, '[\"supervision\",\"management\",\"site\"]', 0, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(30, 'RA2025120030', 'Inverter Equipment', 'Solar inverters for power conversion', 'equipment', 'in_use', 'high', 3, 27, 'String Inverters 50kW', 'Power Electronics', '50kW string inverters, 98% efficiency, IP65 rating', NULL, 12, NULL, '2025-12-10 19:53:30', '2026-01-15 19:53:30', NULL, NULL, NULL, 4.00, 0.00, 'units', 0.00, 150000.00, 600000.00, 552000.00, 650000.00, 70.00, 'Sample utilization data', NULL, NULL, NULL, 'Critical for power conversion', NULL, '[\"inverters\",\"power\",\"electronics\"]', 1, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(31, 'RA2025120031', 'Mounting Structure Materials', 'Steel mounting structures for solar panels', 'material', 'completed', 'medium', 3, 6, 'Galvanized Steel Mounting Structure', 'Structural Components', 'Hot-dip galvanized steel, corrosion resistant, 25-year warranty', NULL, 3, NULL, '2025-11-21 19:53:30', '2026-02-11 19:53:30', NULL, NULL, NULL, 50.00, 0.00, 'sets', 0.00, 25000.00, 1250000.00, 1312500.00, 1300000.00, 55.00, 'Sample utilization data', NULL, NULL, NULL, 'Structural support for panels', NULL, '[\"mounting\",\"steel\",\"structural\"]', 0, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(32, 'RA2025120032', 'Quality Control Inspector', 'QC inspector for quality assurance', 'human', 'completed', 'medium', 4, 39, 'Sarah Wilson', 'Quality Control', 'ISO 9001 certified, 8+ years QC experience', 9, 4, NULL, '2025-12-03 19:53:30', '2026-03-03 19:53:30', NULL, NULL, NULL, 1.00, 0.00, 'person', 1000.00, 0.00, 80000.00, 85600.00, 85000.00, 65.00, 'Sample utilization data', NULL, NULL, NULL, 'Quality assurance specialist', NULL, '[\"quality\",\"inspection\",\"certified\"]', 0, 1, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(33, 'RA2025120033', 'Senior Solar Engineer Allocation', 'Allocation of senior solar engineer for project design and supervision', 'human', 'allocated', 'high', 2, 64, 'John Smith', 'Engineering', '5+ years experience in solar project design, AutoCAD certified', 12, 8, NULL, '2025-11-16 19:53:43', '2026-03-06 19:53:43', NULL, NULL, NULL, 1.00, 0.00, 'person', 1500.00, 0.00, 120000.00, 120000.00, 150000.00, 75.00, 'Sample utilization data', NULL, NULL, NULL, 'Key resource for project success', NULL, '[\"engineering\",\"senior\",\"design\"]', 1, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(34, 'RA2025120034', 'Solar Panel Installation Equipment', 'Equipment allocation for solar panel installation work', 'equipment', 'in_use', 'medium', 3, 45, 'Solar Panel Lifting Equipment', 'Installation Tools', 'Hydraulic lifting system, capacity 500kg, safety certified', NULL, 2, NULL, '2025-12-07 19:53:43', '2026-02-20 19:53:43', NULL, NULL, NULL, 2.00, 0.00, 'units', 0.00, 5000.00, 10000.00, 11000.00, 12000.00, 60.00, 'Sample utilization data', NULL, NULL, NULL, 'Essential for large panel installations', NULL, '[\"equipment\",\"installation\",\"safety\"]', 0, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(35, 'RA2025120035', 'Solar Panel Materials', 'Allocation of solar panels and mounting materials', 'material', 'completed', 'critical', 1, 48, 'Monocrystalline Solar Panels 400W', 'Solar Components', '400W monocrystalline panels, 20.5% efficiency, 25-year warranty', NULL, 13, NULL, '2025-11-29 19:53:43', '2026-02-26 19:53:43', NULL, NULL, NULL, 100.00, 0.00, 'panels', 0.00, 12000.00, 1200000.00, 1068000.00, 1300000.00, 90.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project materials', NULL, '[\"panels\",\"materials\",\"critical\"]', 1, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(36, 'RA2025120036', 'Project Budget Allocation', 'Financial allocation for project expenses', 'financial', 'allocated', 'high', 1, 6, 'Project Budget Fund', 'Finance', 'Working capital for project operations', NULL, 1, NULL, '2025-11-17 19:53:43', '2026-02-19 19:53:43', NULL, NULL, NULL, 1.00, 0.00, 'fund', 0.00, 5000000.00, 5000000.00, 5750000.00, 5500000.00, 45.00, 'Sample utilization data', NULL, NULL, NULL, 'Main project funding', NULL, '[\"budget\",\"finance\",\"funding\"]', 1, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(37, 'RA2025120037', 'Site Supervisor Allocation', 'Site supervisor for daily operations management', 'human', 'planned', 'medium', 3, 35, 'Mike Johnson', 'Supervision', '10+ years construction experience, safety certified', 3, 9, NULL, '2025-11-22 19:53:43', '2026-02-13 19:53:43', NULL, NULL, NULL, 1.00, 0.00, 'person', 800.00, 0.00, 64000.00, 72320.00, 70000.00, 85.00, 'Sample utilization data', NULL, NULL, NULL, 'Daily site management', NULL, '[\"supervision\",\"management\",\"site\"]', 0, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(38, 'RA2025120038', 'Inverter Equipment', 'Solar inverters for power conversion', 'equipment', 'in_use', 'high', 4, 26, 'String Inverters 50kW', 'Power Electronics', '50kW string inverters, 98% efficiency, IP65 rating', NULL, 9, NULL, '2025-11-23 19:53:43', '2026-02-03 19:53:43', NULL, NULL, NULL, 4.00, 0.00, 'units', 0.00, 150000.00, 600000.00, 504000.00, 650000.00, 70.00, 'Sample utilization data', NULL, NULL, NULL, 'Critical for power conversion', NULL, '[\"inverters\",\"power\",\"electronics\"]', 1, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(39, 'RA2025120039', 'Mounting Structure Materials', 'Steel mounting structures for solar panels', 'material', 'allocated', 'medium', 1, 60, 'Galvanized Steel Mounting Structure', 'Structural Components', 'Hot-dip galvanized steel, corrosion resistant, 25-year warranty', NULL, 9, NULL, '2025-11-28 19:53:43', '2026-02-16 19:53:43', NULL, NULL, NULL, 50.00, 0.00, 'sets', 0.00, 25000.00, 1250000.00, 1312500.00, 1300000.00, 55.00, 'Sample utilization data', NULL, NULL, NULL, 'Structural support for panels', NULL, '[\"mounting\",\"steel\",\"structural\"]', 0, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(40, 'RA2025120040', 'Quality Control Inspector', 'QC inspector for quality assurance', 'human', 'allocated', 'medium', 1, 58, 'Sarah Wilson', 'Quality Control', 'ISO 9001 certified, 8+ years QC experience', 8, 6, NULL, '2025-11-17 19:53:43', '2026-02-09 19:53:43', NULL, NULL, NULL, 1.00, 0.00, 'person', 1000.00, 0.00, 80000.00, 88800.00, 85000.00, 65.00, 'Sample utilization data', NULL, NULL, NULL, 'Quality assurance specialist', NULL, '[\"quality\",\"inspection\",\"certified\"]', 0, 1, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'SALES MANAGER', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(2, 'TELE SALES', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(3, 'FIELD SALES', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(4, 'PROJECT MANAGER', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(5, 'PROJECT ENGINEER', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(6, 'LIASONING EXECUTIVE', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(7, 'QUALITY ENGINEER', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(8, 'PURCHASE MANAGER/EXECUTIVE', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(9, 'ACCOUNT EXECUTIVE', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(10, 'STORE EXECUTIVE', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(11, 'SERVICE ENGINEER', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(12, 'HR MANAGER', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39'),
(13, 'SUPER ADMIN', 'web', '2025-12-11 14:23:39', '2025-12-11 14:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(2, 1),
(2, 4),
(2, 8),
(2, 9),
(2, 12),
(2, 13),
(3, 1),
(3, 4),
(3, 8),
(3, 9),
(3, 12),
(3, 13),
(4, 12),
(4, 13),
(5, 12),
(5, 13),
(6, 12),
(6, 13),
(7, 12),
(7, 13),
(8, 12),
(8, 13),
(9, 1),
(9, 13),
(10, 1),
(10, 2),
(10, 3),
(10, 13),
(11, 1),
(11, 2),
(11, 3),
(11, 13),
(12, 1),
(12, 2),
(12, 3),
(12, 13),
(13, 13),
(14, 1),
(14, 13),
(15, 1),
(15, 13),
(16, 4),
(16, 13),
(17, 4),
(17, 5),
(17, 6),
(17, 7),
(17, 11),
(17, 13),
(18, 4),
(18, 13),
(19, 4),
(19, 13),
(20, 13),
(21, 4),
(21, 13),
(22, 4),
(22, 5),
(22, 6),
(22, 7),
(22, 11),
(22, 13),
(23, 4),
(23, 13),
(24, 4),
(24, 13),
(25, 4),
(25, 13),
(26, 4),
(26, 13),
(27, 4),
(27, 5),
(27, 13),
(28, 4),
(28, 13),
(29, 4),
(29, 5),
(29, 13),
(30, 13),
(31, 4),
(31, 13),
(32, 4),
(32, 5),
(32, 13),
(33, 13),
(34, 1),
(34, 2),
(34, 3),
(34, 7),
(34, 8),
(34, 10),
(34, 11),
(34, 13),
(35, 13),
(36, 13),
(37, 13),
(38, 8),
(38, 13),
(39, 6),
(39, 8),
(39, 9),
(39, 10),
(39, 13),
(40, 6),
(40, 8),
(40, 13),
(41, 6),
(41, 8),
(41, 13),
(42, 8),
(42, 13),
(43, 8),
(43, 13),
(44, 8),
(44, 10),
(44, 13),
(45, 8),
(45, 13),
(46, 8),
(46, 13),
(47, 13),
(48, 8),
(48, 13),
(49, 8),
(49, 13),
(50, 8),
(50, 13),
(51, 8),
(51, 13),
(52, 8),
(52, 13),
(53, 8),
(53, 13),
(54, 8),
(54, 13),
(55, 8),
(55, 13),
(56, 8),
(56, 13),
(57, 8),
(57, 13),
(58, 10),
(58, 13),
(59, 7),
(59, 8),
(59, 10),
(59, 13),
(60, 10),
(60, 13),
(61, 7),
(61, 8),
(61, 10),
(61, 13),
(62, 10),
(62, 13),
(63, 10),
(63, 13),
(64, 4),
(64, 10),
(64, 13),
(65, 4),
(65, 5),
(65, 10),
(65, 13),
(66, 5),
(66, 10),
(66, 13),
(67, 10),
(67, 13),
(68, 4),
(68, 10),
(68, 13),
(69, 4),
(69, 5),
(69, 10),
(69, 13),
(70, 5),
(70, 10),
(70, 13),
(71, 10),
(71, 13),
(72, 8),
(72, 10),
(72, 13),
(73, 8),
(73, 10),
(73, 13),
(74, 8),
(74, 10),
(74, 13),
(75, 8),
(75, 10),
(75, 13),
(76, 10),
(76, 13),
(77, 10),
(77, 13),
(78, 10),
(78, 13),
(79, 10),
(79, 13),
(80, 7),
(80, 10),
(80, 13),
(81, 7),
(81, 10),
(81, 13),
(82, 7),
(82, 10),
(82, 13),
(83, 7),
(83, 10),
(83, 13),
(84, 9),
(84, 13),
(85, 9),
(85, 13),
(86, 9),
(86, 13),
(87, 9),
(87, 13),
(88, 13),
(89, 9),
(89, 13),
(90, 1),
(90, 9),
(90, 13),
(91, 1),
(91, 2),
(91, 3),
(91, 9),
(91, 13),
(92, 1),
(92, 2),
(92, 3),
(92, 9),
(92, 13),
(93, 1),
(93, 2),
(93, 3),
(93, 9),
(93, 13),
(94, 4),
(94, 7),
(94, 13),
(95, 4),
(95, 5),
(95, 7),
(95, 13),
(96, 5),
(96, 7),
(96, 13),
(97, 5),
(97, 7),
(97, 13),
(98, 7),
(98, 13),
(99, 12),
(99, 13),
(100, 12),
(100, 13),
(101, 12),
(101, 13),
(102, 12),
(102, 13),
(103, 12),
(103, 13),
(104, 12),
(104, 13),
(105, 12),
(105, 13),
(106, 12),
(106, 13),
(107, 12),
(107, 13),
(108, 12),
(108, 13),
(109, 12),
(109, 13),
(110, 12),
(110, 13),
(111, 12),
(111, 13),
(112, 12),
(112, 13),
(113, 12),
(113, 13),
(114, 12),
(114, 13),
(115, 12),
(115, 13),
(116, 12),
(116, 13),
(117, 12),
(117, 13),
(118, 12),
(118, 13),
(119, 12),
(119, 13),
(120, 12),
(120, 13),
(121, 12),
(121, 13),
(122, 12),
(122, 13),
(123, 12),
(123, 13),
(124, 12),
(124, 13),
(125, 12),
(125, 13),
(126, 12),
(126, 13),
(127, 12),
(127, 13),
(128, 12),
(128, 13),
(129, 12),
(129, 13),
(130, 9),
(130, 13),
(131, 9),
(131, 13),
(132, 9),
(132, 13),
(133, 9),
(133, 13),
(134, 9),
(134, 13),
(135, 9),
(135, 13),
(136, 9),
(136, 13),
(137, 9),
(137, 13),
(138, 9),
(138, 13),
(139, 9),
(139, 13),
(140, 9),
(140, 13),
(141, 9),
(141, 13),
(142, 9),
(142, 13),
(143, 9),
(143, 13),
(144, 9),
(144, 13),
(145, 9),
(145, 13),
(146, 9),
(146, 13),
(147, 9),
(147, 13),
(148, 9),
(148, 13),
(149, 9),
(149, 13),
(150, 2),
(150, 3),
(150, 13),
(151, 2),
(151, 3),
(151, 6),
(151, 11),
(151, 13),
(152, 2),
(152, 3),
(152, 6),
(152, 11),
(152, 13),
(153, 2),
(153, 3),
(153, 6),
(153, 11),
(153, 13),
(154, 13),
(155, 13),
(156, 13),
(157, 13),
(158, 11),
(158, 13),
(159, 11),
(159, 13),
(160, 11),
(160, 13),
(161, 11),
(161, 13),
(162, 11),
(162, 13),
(163, 11),
(163, 13),
(164, 11),
(164, 13),
(165, 11),
(165, 13),
(166, 11),
(166, 13),
(167, 11),
(167, 13),
(168, 11),
(168, 13),
(169, 11),
(169, 13),
(170, 11),
(170, 13),
(171, 11),
(171, 13),
(172, 11),
(172, 13),
(173, 11),
(173, 13),
(174, 6),
(174, 13),
(175, 6),
(175, 13),
(176, 6),
(176, 13),
(177, 6),
(177, 13),
(178, 6),
(178, 13),
(179, 6),
(179, 13),
(180, 1),
(180, 13),
(181, 1),
(181, 2),
(181, 3),
(181, 9),
(181, 11),
(181, 13),
(182, 1),
(182, 2),
(182, 3),
(182, 13),
(183, 1),
(183, 2),
(183, 3),
(183, 13),
(184, 1),
(184, 13),
(185, 1),
(185, 13),
(186, 4),
(186, 13),
(187, 4),
(187, 5),
(187, 13),
(188, 5),
(188, 13),
(189, 5),
(189, 13),
(190, 4),
(190, 13),
(191, 4),
(191, 5),
(191, 13),
(192, 4),
(192, 13),
(193, 4),
(193, 5),
(193, 13),
(194, 5),
(194, 13),
(195, 13),
(196, 7),
(196, 8),
(196, 10),
(196, 13),
(197, 13),
(198, 12),
(198, 13),
(199, 12),
(199, 13),
(200, 12),
(200, 13),
(201, 12),
(201, 13),
(202, 12),
(202, 13),
(203, 12),
(203, 13),
(204, 1),
(204, 13),
(205, 1),
(205, 13),
(206, 1),
(206, 13),
(207, 1),
(207, 13),
(208, 13);

-- --------------------------------------------------------

--
-- Table structure for table `r_f_q_items`
--

CREATE TABLE `r_f_q_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rfq_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `estimated_price` decimal(10,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `r_f_q_s`
--

CREATE TABLE `r_f_q_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rfq_number` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rfq_date` date NOT NULL,
  `quotation_due_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `status` enum('draft','sent','received','evaluated','awarded','cancelled') NOT NULL DEFAULT 'draft',
  `description` text NOT NULL,
  `terms_conditions` text DEFAULT NULL,
  `delivery_terms` text DEFAULT NULL,
  `payment_terms` text DEFAULT NULL,
  `estimated_budget` decimal(15,2) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_slips`
--

CREATE TABLE `salary_slips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `slip_number` varchar(255) NOT NULL,
  `payroll_month` varchar(255) NOT NULL,
  `payroll_year` varchar(255) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `hra` decimal(10,2) NOT NULL DEFAULT 0.00,
  `da` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pf` decimal(10,2) NOT NULL DEFAULT 0.00,
  `esi` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `generated_date` date NOT NULL,
  `status` enum('generated','sent','downloaded') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_slips`
--

INSERT INTO `salary_slips` (`id`, `employee_id`, `slip_number`, `payroll_month`, `payroll_year`, `basic_salary`, `hra`, `da`, `allowances`, `pf`, `esi`, `tax`, `other_deductions`, `net_salary`, `generated_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'SLIP-2025-0001', 'September', '2025', 45000.00, 13500.00, 9000.00, 5000.00, 5400.00, 675.00, 2000.00, 500.00, 54925.00, '2025-12-06', 'sent', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'EMP002', 'SLIP-2025-0002', 'September', '2025', 55000.00, 16500.00, 11000.00, 6000.00, 6600.00, 825.00, 3000.00, 600.00, 70475.00, '2025-12-06', 'sent', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'EMP003', 'SLIP-2025-0003', 'September', '2025', 60000.00, 18000.00, 12000.00, 7000.00, 7200.00, 900.00, 4000.00, 700.00, 75200.00, '2025-12-06', 'downloaded', '2025-12-11 14:22:41', '2025-12-11 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `site_expenses`
--

CREATE TABLE `site_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_location` varchar(255) DEFAULT NULL,
  `expense_category` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `vendor_name` varchar(255) DEFAULT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','paid') NOT NULL DEFAULT 'pending',
  `approval_level` enum('hr','admin','approved','rejected') NOT NULL DEFAULT 'hr',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `hr_approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `hr_approved_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `hr_rejection_reason` text DEFAULT NULL,
  `admin_rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_expenses`
--

INSERT INTO `site_expenses` (`id`, `expense_number`, `title`, `description`, `project_id`, `site_location`, `expense_category`, `amount`, `expense_date`, `payment_method`, `vendor_name`, `receipt_number`, `receipt_path`, `status`, `approval_level`, `notes`, `created_by`, `approved_by`, `hr_approved_by`, `hr_approved_at`, `approved_at`, `rejection_reason`, `hr_rejection_reason`, `admin_rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 'SE-2025-0001', 'abc', 'ada', 1, 'baroda', 'transport', 100000.00, '2025-12-17', 'transfer', 'ajit', '101', NULL, 'approved', 'hr', NULL, 4, 1, NULL, NULL, '2025-12-17 14:27:07', NULL, NULL, NULL, '2025-12-17 13:08:07', '2025-12-17 14:27:07');

-- --------------------------------------------------------

--
-- Table structure for table `site_warehouses`
--

CREATE TABLE `site_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `total_capacity` decimal(10,2) DEFAULT NULL,
  `used_capacity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `description` text DEFAULT NULL,
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `managed_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_ledgers`
--

CREATE TABLE `stock_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_time` time NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `transaction_type` enum('inward','outward','transfer') NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `inward_quantity` int(11) NOT NULL DEFAULT 0,
  `outward_quantity` int(11) NOT NULL DEFAULT 0,
  `balance_quantity` int(11) NOT NULL,
  `warehouse` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_ledgers`
--

INSERT INTO `stock_ledgers` (`id`, `transaction_date`, `transaction_time`, `item_name`, `item_code`, `transaction_type`, `reference_number`, `inward_quantity`, `outward_quantity`, `balance_quantity`, `warehouse`, `created_at`, `updated_at`) VALUES
(1, '2025-12-10', '10:30:00', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 'inward', 'GRN-2025-0001', 100, 0, 100, 'Main Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, '2025-12-10', '14:15:00', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 'outward', 'DC-2025-0001', 0, 25, 75, 'Main Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, '2025-12-09', '09:45:00', 'String Inverter 5KW', 'INV-5K-SI-001', 'inward', 'GRN-2025-0002', 50, 0, 50, 'Main Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, '2025-12-09', '16:20:00', 'String Inverter 5KW', 'INV-5K-SI-001', 'outward', 'DC-2025-0002', 0, 10, 40, 'Main Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, '2025-12-08', '11:00:00', 'Ground Mount Structure', 'MS-GM-001', 'inward', 'GRN-2025-0003', 200, 0, 200, 'Secondary Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, '2025-12-08', '15:30:00', 'Ground Mount Structure', 'MS-GM-001', 'transfer', 'TR-2025-0001', 0, 50, 150, 'Secondary Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, '2025-12-07', '08:15:00', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 'inward', 'GRN-2025-0004', 2000, 0, 2000, 'Main Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, '2025-12-07', '13:45:00', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 'outward', 'DC-2025-0003', 0, 500, 1500, 'Main Warehouse', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, '2025-12-10', '10:30:00', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 'inward', 'GRN-2025-0001', 100, 0, 100, 'Main Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(10, '2025-12-10', '14:15:00', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 'outward', 'DC-2025-0001', 0, 25, 75, 'Main Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(11, '2025-12-09', '09:45:00', 'String Inverter 5KW', 'INV-5K-SI-001', 'inward', 'GRN-2025-0002', 50, 0, 50, 'Main Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(12, '2025-12-09', '16:20:00', 'String Inverter 5KW', 'INV-5K-SI-001', 'outward', 'DC-2025-0002', 0, 10, 40, 'Main Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(13, '2025-12-08', '11:00:00', 'Ground Mount Structure', 'MS-GM-001', 'inward', 'GRN-2025-0003', 200, 0, 200, 'Secondary Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(14, '2025-12-08', '15:30:00', 'Ground Mount Structure', 'MS-GM-001', 'transfer', 'TR-2025-0001', 0, 50, 150, 'Secondary Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(15, '2025-12-07', '08:15:00', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 'inward', 'GRN-2025-0004', 2000, 0, 2000, 'Main Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(16, '2025-12-07', '13:45:00', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 'outward', 'DC-2025-0003', 0, 500, 1500, 'Main Warehouse', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(17, '2025-12-10', '10:30:00', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 'inward', 'GRN-2025-0001', 100, 0, 100, 'Main Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(18, '2025-12-10', '14:15:00', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 'outward', 'DC-2025-0001', 0, 25, 75, 'Main Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(19, '2025-12-09', '09:45:00', 'String Inverter 5KW', 'INV-5K-SI-001', 'inward', 'GRN-2025-0002', 50, 0, 50, 'Main Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(20, '2025-12-09', '16:20:00', 'String Inverter 5KW', 'INV-5K-SI-001', 'outward', 'DC-2025-0002', 0, 10, 40, 'Main Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(21, '2025-12-08', '11:00:00', 'Ground Mount Structure', 'MS-GM-001', 'inward', 'GRN-2025-0003', 200, 0, 200, 'Secondary Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(22, '2025-12-08', '15:30:00', 'Ground Mount Structure', 'MS-GM-001', 'transfer', 'TR-2025-0001', 0, 50, 150, 'Secondary Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(23, '2025-12-07', '08:15:00', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 'inward', 'GRN-2025-0004', 2000, 0, 2000, 'Main Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(24, '2025-12-07', '13:45:00', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 'outward', 'DC-2025-0003', 0, 500, 1500, 'Main Warehouse', '2025-12-11 14:23:30', '2025-12-11 14:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `stock_valuations`
--

CREATE TABLE `stock_valuations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `total_value` decimal(15,2) NOT NULL,
  `warehouse` varchar(255) NOT NULL,
  `last_updated` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_valuations`
--

INSERT INTO `stock_valuations` (`id`, `category`, `item_name`, `item_code`, `quantity`, `unit_cost`, `total_value`, `warehouse`, `last_updated`, `created_at`, `updated_at`) VALUES
(1, 'Solar Panels', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 2500, 8500.00, 21250000.00, 'Main Warehouse', '2025-12-09', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'Solar Panels', 'Solar Panel 400W Polycrystalline', 'SP-400-PC-001', 1800, 7200.00, 12960000.00, 'Main Warehouse', '2025-12-10', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'Inverters', 'String Inverter 5KW', 'INV-5K-SI-001', 150, 25000.00, 3750000.00, 'Main Warehouse', '2025-12-08', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'Inverters', 'Central Inverter 100KW', 'INV-100K-CI-001', 25, 150000.00, 3750000.00, 'Secondary Warehouse', '2025-12-06', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'Mounting Systems', 'Ground Mount Structure', 'MS-GM-001', 500, 12000.00, 6000000.00, 'Secondary Warehouse', '2025-12-07', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'Mounting Systems', 'Roof Mount Structure', 'MS-RM-001', 300, 8000.00, 2400000.00, 'Main Warehouse', '2025-12-05', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'Cables & Accessories', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 10000, 150.00, 1500000.00, 'Main Warehouse', '2025-12-10', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'Cables & Accessories', 'AC Cable 16mm²', 'CAB-AC-16MM-001', 5000, 300.00, 1500000.00, 'Secondary Warehouse', '2025-12-09', '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'Solar Panels', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 2500, 8500.00, 21250000.00, 'Main Warehouse', '2025-12-09', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(10, 'Solar Panels', 'Solar Panel 400W Polycrystalline', 'SP-400-PC-001', 1800, 7200.00, 12960000.00, 'Main Warehouse', '2025-12-10', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(11, 'Inverters', 'String Inverter 5KW', 'INV-5K-SI-001', 150, 25000.00, 3750000.00, 'Main Warehouse', '2025-12-08', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(12, 'Inverters', 'Central Inverter 100KW', 'INV-100K-CI-001', 25, 150000.00, 3750000.00, 'Secondary Warehouse', '2025-12-06', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(13, 'Mounting Systems', 'Ground Mount Structure', 'MS-GM-001', 500, 12000.00, 6000000.00, 'Secondary Warehouse', '2025-12-07', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(14, 'Mounting Systems', 'Roof Mount Structure', 'MS-RM-001', 300, 8000.00, 2400000.00, 'Main Warehouse', '2025-12-05', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(15, 'Cables & Accessories', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 10000, 150.00, 1500000.00, 'Main Warehouse', '2025-12-10', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(16, 'Cables & Accessories', 'AC Cable 16mm²', 'CAB-AC-16MM-001', 5000, 300.00, 1500000.00, 'Secondary Warehouse', '2025-12-09', '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(17, 'Solar Panels', 'Solar Panel 320W Monocrystalline', 'SP-320-MC-001', 2500, 8500.00, 21250000.00, 'Main Warehouse', '2025-12-09', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(18, 'Solar Panels', 'Solar Panel 400W Polycrystalline', 'SP-400-PC-001', 1800, 7200.00, 12960000.00, 'Main Warehouse', '2025-12-10', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(19, 'Inverters', 'String Inverter 5KW', 'INV-5K-SI-001', 150, 25000.00, 3750000.00, 'Main Warehouse', '2025-12-08', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(20, 'Inverters', 'Central Inverter 100KW', 'INV-100K-CI-001', 25, 150000.00, 3750000.00, 'Secondary Warehouse', '2025-12-06', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(21, 'Mounting Systems', 'Ground Mount Structure', 'MS-GM-001', 500, 12000.00, 6000000.00, 'Secondary Warehouse', '2025-12-07', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(22, 'Mounting Systems', 'Roof Mount Structure', 'MS-RM-001', 300, 8000.00, 2400000.00, 'Main Warehouse', '2025-12-05', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(23, 'Cables & Accessories', 'DC Cable 4mm²', 'CAB-DC-4MM-001', 10000, 150.00, 1500000.00, 'Main Warehouse', '2025-12-10', '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(24, 'Cables & Accessories', 'AC Cable 16mm²', 'CAB-AC-16MM-001', 5000, 300.00, 1500000.00, 'Secondary Warehouse', '2025-12-09', '2025-12-11 14:23:30', '2025-12-11 14:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `estimated_hours` int(11) DEFAULT NULL,
  `actual_hours` int(11) NOT NULL DEFAULT 0,
  `dependencies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dependencies`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `status`, `priority`, `start_date`, `due_date`, `completed_date`, `project_id`, `assigned_to`, `created_by`, `estimated_hours`, `actual_hours`, `dependencies`, `created_at`, `updated_at`) VALUES
(1, 'Site Survey and Assessment', 'Conduct detailed site survey for solar panel installation including roof condition, shading analysis, and electrical infrastructure assessment.', 'completed', 'high', '2025-12-01', '2025-12-06', '2025-12-07', 3, 2, 1, 8, 6, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(2, 'Design Solar Panel Layout', 'Create detailed solar panel layout design including panel placement, wiring diagram, and inverter positioning.', 'in_progress', 'high', '2025-12-08', '2025-12-13', NULL, 3, 6, 1, 12, 4, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(3, 'Procure Solar Panels', 'Source and procure high-quality solar panels from approved vendors including quality checks and delivery coordination.', 'pending', 'urgent', '2025-12-12', '2025-12-18', NULL, 4, 4, 1, 16, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(4, 'Install Mounting Structure', 'Install roof mounting structure for solar panels ensuring proper waterproofing and structural integrity.', 'pending', 'medium', '2025-12-16', '2025-12-21', NULL, 4, 7, 1, 20, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(5, 'Install Solar Panels', 'Install solar panels on the mounting structure with proper alignment and secure fastening.', 'pending', 'high', '2025-12-19', '2025-12-23', NULL, 1, 11, 1, 24, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(6, 'Electrical Wiring and Connections', 'Complete electrical wiring from panels to inverter and grid connection point with proper safety measures.', 'pending', 'high', '2025-12-21', '2025-12-25', NULL, 2, 11, 1, 16, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'Install Inverter and Monitoring System', 'Install solar inverter and monitoring system for performance tracking and remote monitoring.', 'pending', 'medium', '2025-12-23', '2025-12-27', NULL, 1, 1, 1, 8, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'System Testing and Commissioning', 'Conduct comprehensive system testing including performance verification, safety checks, and commissioning.', 'pending', 'urgent', '2025-12-26', '2025-12-29', NULL, 4, 12, 1, 12, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'Customer Training and Handover', 'Provide customer training on system operation, maintenance procedures, and monitoring system usage.', 'pending', 'medium', '2025-12-29', '2025-12-31', NULL, 1, 5, 1, 4, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'Documentation and Warranty Registration', 'Complete all project documentation, warranty registration, and submit final reports to customer.', 'pending', 'low', '2025-12-31', '2026-01-02', NULL, 3, 6, 1, 6, 0, NULL, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'Site Survey and Assessment', 'Conduct detailed site survey for solar panel installation including roof condition, shading analysis, and electrical infrastructure assessment.', 'completed', 'high', '2025-12-01', '2025-12-06', '2025-12-07', 5, 10, 1, 8, 6, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(12, 'Design Solar Panel Layout', 'Create detailed solar panel layout design including panel placement, wiring diagram, and inverter positioning.', 'in_progress', 'high', '2025-12-08', '2025-12-13', NULL, 4, 12, 1, 12, 4, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(13, 'Procure Solar Panels', 'Source and procure high-quality solar panels from approved vendors including quality checks and delivery coordination.', 'pending', 'urgent', '2025-12-12', '2025-12-18', NULL, 4, 13, 1, 16, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(14, 'Install Mounting Structure', 'Install roof mounting structure for solar panels ensuring proper waterproofing and structural integrity.', 'pending', 'medium', '2025-12-16', '2025-12-21', NULL, 5, 10, 1, 20, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(15, 'Install Solar Panels', 'Install solar panels on the mounting structure with proper alignment and secure fastening.', 'pending', 'high', '2025-12-19', '2025-12-23', NULL, 5, 6, 1, 24, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(16, 'Electrical Wiring and Connections', 'Complete electrical wiring from panels to inverter and grid connection point with proper safety measures.', 'pending', 'high', '2025-12-21', '2025-12-25', NULL, 4, 7, 1, 16, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(17, 'Install Inverter and Monitoring System', 'Install solar inverter and monitoring system for performance tracking and remote monitoring.', 'pending', 'medium', '2025-12-23', '2025-12-27', NULL, 5, 8, 1, 8, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(18, 'System Testing and Commissioning', 'Conduct comprehensive system testing including performance verification, safety checks, and commissioning.', 'pending', 'urgent', '2025-12-26', '2025-12-29', NULL, 5, 11, 1, 12, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(19, 'Customer Training and Handover', 'Provide customer training on system operation, maintenance procedures, and monitoring system usage.', 'pending', 'medium', '2025-12-29', '2025-12-31', NULL, 1, 5, 1, 4, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(20, 'Documentation and Warranty Registration', 'Complete all project documentation, warranty registration, and submit final reports to customer.', 'pending', 'low', '2025-12-31', '2026-01-02', NULL, 5, 11, 1, 6, 0, NULL, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(21, 'Site Survey and Assessment', 'Conduct detailed site survey for solar panel installation including roof condition, shading analysis, and electrical infrastructure assessment.', 'completed', 'high', '2025-12-01', '2025-12-06', '2025-12-07', 2, 5, 1, 8, 6, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(22, 'Design Solar Panel Layout', 'Create detailed solar panel layout design including panel placement, wiring diagram, and inverter positioning.', 'in_progress', 'high', '2025-12-08', '2025-12-13', NULL, 3, 8, 1, 12, 4, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(23, 'Procure Solar Panels', 'Source and procure high-quality solar panels from approved vendors including quality checks and delivery coordination.', 'pending', 'urgent', '2025-12-12', '2025-12-18', NULL, 1, 11, 1, 16, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(24, 'Install Mounting Structure', 'Install roof mounting structure for solar panels ensuring proper waterproofing and structural integrity.', 'pending', 'medium', '2025-12-16', '2025-12-21', NULL, 2, 10, 1, 20, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(25, 'Install Solar Panels', 'Install solar panels on the mounting structure with proper alignment and secure fastening.', 'pending', 'high', '2025-12-19', '2025-12-23', NULL, 3, 1, 1, 24, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(26, 'Electrical Wiring and Connections', 'Complete electrical wiring from panels to inverter and grid connection point with proper safety measures.', 'pending', 'high', '2025-12-21', '2025-12-25', NULL, 1, 6, 1, 16, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(27, 'Install Inverter and Monitoring System', 'Install solar inverter and monitoring system for performance tracking and remote monitoring.', 'pending', 'medium', '2025-12-23', '2025-12-27', NULL, 3, 6, 1, 8, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(28, 'System Testing and Commissioning', 'Conduct comprehensive system testing including performance verification, safety checks, and commissioning.', 'pending', 'urgent', '2025-12-26', '2025-12-29', NULL, 5, 6, 1, 12, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(29, 'Customer Training and Handover', 'Provide customer training on system operation, maintenance procedures, and monitoring system usage.', 'pending', 'medium', '2025-12-29', '2025-12-31', NULL, 2, 11, 1, 4, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(30, 'Documentation and Warranty Registration', 'Complete all project documentation, warranty registration, and submit final reports to customer.', 'pending', 'low', '2025-12-31', '2026-01-02', NULL, 1, 6, 1, 6, 0, NULL, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(31, 'Site Survey and Assessment', 'Conduct detailed site survey for solar panel installation including roof condition, shading analysis, and electrical infrastructure assessment.', 'completed', 'high', '2025-12-01', '2025-12-06', '2025-12-07', 4, 13, 1, 8, 6, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(32, 'Design Solar Panel Layout', 'Create detailed solar panel layout design including panel placement, wiring diagram, and inverter positioning.', 'in_progress', 'high', '2025-12-08', '2025-12-13', NULL, 5, 8, 1, 12, 4, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(33, 'Procure Solar Panels', 'Source and procure high-quality solar panels from approved vendors including quality checks and delivery coordination.', 'pending', 'urgent', '2025-12-12', '2025-12-18', NULL, 3, 4, 1, 16, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(34, 'Install Mounting Structure', 'Install roof mounting structure for solar panels ensuring proper waterproofing and structural integrity.', 'pending', 'medium', '2025-12-16', '2025-12-21', NULL, 1, 6, 1, 20, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(35, 'Install Solar Panels', 'Install solar panels on the mounting structure with proper alignment and secure fastening.', 'pending', 'high', '2025-12-19', '2025-12-23', NULL, 3, 1, 1, 24, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(36, 'Electrical Wiring and Connections', 'Complete electrical wiring from panels to inverter and grid connection point with proper safety measures.', 'pending', 'high', '2025-12-21', '2025-12-25', NULL, 1, 4, 1, 16, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(37, 'Install Inverter and Monitoring System', 'Install solar inverter and monitoring system for performance tracking and remote monitoring.', 'pending', 'medium', '2025-12-23', '2025-12-27', NULL, 2, 8, 1, 8, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(38, 'System Testing and Commissioning', 'Conduct comprehensive system testing including performance verification, safety checks, and commissioning.', 'pending', 'urgent', '2025-12-26', '2025-12-29', NULL, 4, 4, 1, 12, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(39, 'Customer Training and Handover', 'Provide customer training on system operation, maintenance procedures, and monitoring system usage.', 'pending', 'medium', '2025-12-29', '2025-12-31', NULL, 1, 9, 1, 4, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(40, 'Documentation and Warranty Registration', 'Complete all project documentation, warranty registration, and submit final reports to customer.', 'pending', 'low', '2025-12-31', '2026-01-02', NULL, 2, 5, 1, 6, 0, NULL, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(41, 'Site Survey and Assessment', 'Conduct detailed site survey for solar panel installation including roof condition, shading analysis, and electrical infrastructure assessment.', 'completed', 'high', '2025-12-01', '2025-12-06', '2025-12-07', 1, 13, 1, 8, 6, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(42, 'Design Solar Panel Layout', 'Create detailed solar panel layout design including panel placement, wiring diagram, and inverter positioning.', 'in_progress', 'high', '2025-12-08', '2025-12-13', NULL, 3, 11, 1, 12, 4, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(43, 'Procure Solar Panels', 'Source and procure high-quality solar panels from approved vendors including quality checks and delivery coordination.', 'pending', 'urgent', '2025-12-12', '2025-12-18', NULL, 2, 2, 1, 16, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(44, 'Install Mounting Structure', 'Install roof mounting structure for solar panels ensuring proper waterproofing and structural integrity.', 'pending', 'medium', '2025-12-16', '2025-12-21', NULL, 1, 1, 1, 20, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(45, 'Install Solar Panels', 'Install solar panels on the mounting structure with proper alignment and secure fastening.', 'pending', 'high', '2025-12-19', '2025-12-23', NULL, 5, 10, 1, 24, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(46, 'Electrical Wiring and Connections', 'Complete electrical wiring from panels to inverter and grid connection point with proper safety measures.', 'pending', 'high', '2025-12-21', '2025-12-25', NULL, 3, 7, 1, 16, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(47, 'Install Inverter and Monitoring System', 'Install solar inverter and monitoring system for performance tracking and remote monitoring.', 'pending', 'medium', '2025-12-23', '2025-12-27', NULL, 2, 3, 1, 8, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(48, 'System Testing and Commissioning', 'Conduct comprehensive system testing including performance verification, safety checks, and commissioning.', 'pending', 'urgent', '2025-12-26', '2025-12-29', NULL, 3, 2, 1, 12, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(49, 'Customer Training and Handover', 'Provide customer training on system operation, maintenance procedures, and monitoring system usage.', 'pending', 'medium', '2025-12-29', '2025-12-31', NULL, 2, 4, 1, 4, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(50, 'Documentation and Warranty Registration', 'Complete all project documentation, warranty registration, and submit final reports to customer.', 'pending', 'low', '2025-12-31', '2026-01-02', NULL, 2, 12, 1, 6, 0, NULL, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_available_for_followup` tinyint(1) NOT NULL DEFAULT 1,
  `unavailability_reason` text DEFAULT NULL,
  `unavailable_until` timestamp NULL DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `last_login_at` timestamp NULL DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `avatar`, `department`, `designation`, `is_active`, `is_available_for_followup`, `unavailability_reason`, `unavailable_until`, `settings`, `last_login_at`, `employee_id`, `joining_date`, `salary`, `address`, `emergency_contact`, `emergency_phone`) VALUES
(1, 'Super Administrator', 'superadmin@solarerp.com', NULL, '$2y$12$yoMIbIw5ReeB6Ov813RWR.7NcNJaj2DCqnYavq6MHZ9yeqyECnja2', NULL, '2025-12-11 14:16:52', '2026-01-04 12:37:14', '+91-9876543210', NULL, 'IT', 'Super Administrator', 1, 1, NULL, NULL, NULL, '2026-01-04 12:37:14', 'EMP001', '2024-12-11', 150000.00, 'Corporate Office, Mumbai', 'Emergency Contact', '+91-9876543211'),
(2, 'Rajesh Kumar', 'sales.manager@solarerp.com', NULL, '$2y$12$Ky36OQZUlFxDK3YAYUvHVem2t44N7P9nw7J1gsbErvMZDD.zeZ6mS', NULL, '2025-12-11 14:17:02', '2026-01-04 12:40:42', '+91-9876543212', NULL, 'Sales', 'Sales Manager', 1, 1, NULL, NULL, NULL, '2026-01-04 12:40:42', 'EMP002', '2025-04-11', 120000.00, 'Sales Office, Delhi', 'Emergency Contact', '+91-9876543213'),
(3, 'Priya Sharma', 'tele.sales@solarerp.com', NULL, '$2y$12$1eXbqQ1x3/4/WFbuiyEOLuFCKrC/KYqMGdgj/yROkSkgDlfVwaNya', NULL, '2025-12-11 14:17:02', '2025-12-13 09:09:38', '+91-9876543214', NULL, 'Sales', 'Tele Sales Executive', 1, 1, NULL, NULL, NULL, '2025-12-13 09:09:38', 'EMP003', '2025-06-11', 45000.00, 'Call Center, Mumbai', 'Emergency Contact', '+91-9876543215'),
(4, 'Amit Singh', 'field.sales@solarerp.com', NULL, '$2y$12$6QnTdQV18852deBpm7ewg.STkhLSHXS8aochnVKOk6RHql9ABJIZG', NULL, '2025-12-11 14:17:02', '2026-01-04 12:51:11', '+91-9876543216', NULL, 'Sales', 'Field Sales Executive', 1, 1, NULL, NULL, NULL, '2026-01-04 12:51:11', 'EMP004', '2025-07-11', 55000.00, 'Field Office, Bangalore', 'Emergency Contact', '+91-9876543217'),
(5, 'Vikram Patel', 'project.manager@solarerp.com', NULL, '$2y$12$jS/LgCTyLrM/ewcF6gw7h.908OZFr88S0xfqh0X4uHx46tJbchjCW', NULL, '2025-12-11 14:17:02', '2025-12-11 14:23:43', '+91-9876543218', NULL, 'Projects', 'Project Manager', 1, 1, NULL, NULL, NULL, NULL, 'EMP005', '2025-02-11', 100000.00, 'Project Office, Pune', 'Emergency Contact', '+91-9876543219'),
(6, 'Suresh Reddy', 'project.engineer@solarerp.com', NULL, '$2y$12$WpFv2wvvcsggPzYPpbK6VOYakReGLOI8XSjynOgsITYjPrZkTgl2a', NULL, '2025-12-11 14:17:02', '2025-12-11 14:23:43', '+91-9876543220', NULL, 'Projects', 'Project Engineer', 1, 1, NULL, NULL, NULL, NULL, 'EMP006', '2025-05-11', 70000.00, 'Site Office, Hyderabad', 'Emergency Contact', '+91-9876543221'),
(7, 'Meera Joshi', 'liaisoning@solarerp.com', NULL, '$2y$12$E2cLV47geEsNf3AUwYYbr.lXL7CYCIGVcH8XPAza/T5Hq4ooK2YoW', NULL, '2025-12-11 14:17:02', '2025-12-20 06:28:26', '+91-9876543222', NULL, 'Liaisoning', 'Liaisoning Executive', 1, 1, NULL, NULL, NULL, '2025-12-20 06:28:26', 'EMP007', '2025-08-11', 60000.00, 'Regulatory Office, Delhi', 'Emergency Contact', '+91-9876543223'),
(8, 'Ravi Kumar', 'quality.engineer@solarerp.com', NULL, '$2y$12$uSktKfxlo80pA07UOyIx0eM.oLg9gTvUWHpm7mHOiKWnENmLviz4i', NULL, '2025-12-11 14:17:02', '2025-12-11 14:23:43', '+91-9876543224', NULL, 'Quality', 'Quality Engineer', 1, 1, NULL, NULL, NULL, NULL, 'EMP008', '2025-06-11', 65000.00, 'Quality Lab, Chennai', 'Emergency Contact', '+91-9876543225'),
(9, 'Deepak Gupta', 'purchase.manager@solarerp.com', NULL, '$2y$12$KM7WpMBts.9eYRxJihiyQOtS529llTslb0xxRrk8us3Yp6Nv2C8Ly', NULL, '2025-12-11 14:17:02', '2026-01-01 15:03:00', '+91-9876543226', NULL, 'Purchase', 'Purchase Manager', 1, 1, NULL, NULL, NULL, '2026-01-01 15:03:00', 'EMP009', '2025-03-11', 90000.00, 'Purchase Office, Mumbai', 'Emergency Contact', '+91-9876543227'),
(10, 'Sunita Agarwal', 'account.executive@solarerp.com', NULL, '$2y$12$O4nfY37N07wr1BWNJ/3n/uloIQnD4yis6RDZEIePXNGgtc34OpLGK', NULL, '2025-12-11 14:17:02', '2025-12-17 13:08:53', '+91-9876543228', NULL, 'Accounts', 'Account Executive', 1, 1, NULL, NULL, NULL, '2025-12-17 13:08:53', 'EMP010', '2025-04-11', 75000.00, 'Accounts Office, Kolkata', 'Emergency Contact', '+91-9876543229'),
(11, 'Manoj Verma', 'store.executive@solarerp.com', NULL, '$2y$12$iiRZQR/rXVwl9F2yYVkXruy7P.yc1uPxAD2Loifl6TTDawY7BzvJS', NULL, '2025-12-11 14:17:02', '2025-12-19 13:03:54', '+91-9876543230', NULL, 'Store', 'Store Executive', 1, 1, NULL, NULL, NULL, '2025-12-19 13:03:54', 'EMP011', '2025-07-11', 50000.00, 'Warehouse, Ahmedabad', 'Emergency Contact', '+91-9876543231'),
(12, 'Kiran Nair', 'service.engineer@solarerp.com', NULL, '$2y$12$30tMXuesW2elPnopayinZuCNBkSqGy5T/icbwEqFtS6HRbJxN6q9.', NULL, '2025-12-11 14:17:02', '2026-01-01 15:36:33', '+91-9876543232', NULL, 'Service', 'Service Engineer', 1, 1, NULL, NULL, NULL, '2026-01-01 15:36:33', 'EMP012', '2025-06-11', 60000.00, 'Service Center, Kochi', 'Emergency Contact', '+91-9876543233'),
(13, 'Anita Desai', 'hr.manager@solarerp.com', NULL, '$2y$12$5PlvrTTKE7vreskIVAjYz.VJsJUkBRzpEIOfN7yaq2fMVYKRJS4om', NULL, '2025-12-11 14:17:02', '2026-01-02 06:42:41', '+91-9876543234', NULL, 'HR', 'HR Manager', 1, 1, NULL, NULL, NULL, '2026-01-02 06:42:41', 'EMP013', '2024-12-11', 110000.00, 'HR Office, Mumbai', 'Emergency Contact', '+91-9876543235');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','blacklisted') NOT NULL DEFAULT 'active',
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_terms` int(11) NOT NULL DEFAULT 30,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `email`, `phone`, `company`, `address`, `gst_number`, `pan_number`, `contact_person`, `status`, `credit_limit`, `payment_terms`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(2, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(3, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(4, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(5, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:21:29', '2025-12-11 14:21:29'),
(6, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(7, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(8, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(9, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(10, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:22:41', '2025-12-11 14:22:41'),
(11, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(12, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(13, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(14, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(15, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:22:47', '2025-12-11 14:22:47'),
(16, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(17, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(18, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(19, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(20, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:22:56', '2025-12-11 14:22:56'),
(21, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(22, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(23, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(24, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(25, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:23:10', '2025-12-11 14:23:10'),
(26, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(27, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(28, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(29, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(30, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:23:22', '2025-12-11 14:23:22'),
(31, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(32, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(33, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(34, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(35, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:23:30', '2025-12-11 14:23:30'),
(36, 'SolarTech Solutions', 'rajesh@solartech.com', '011-23456789', 'SolarTech Solutions Pvt Ltd', '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001', '07AABCU9603R1ZX', 'AABCU9603R', 'Rajesh Kumar', 'active', 500000.00, 30, 'Reliable supplier for solar panels and inverters', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(37, 'Green Energy Contractors', 'priya@greenenergy.com', '022-34567890', 'Green Energy Contractors', '456 Business Park, Andheri East, Mumbai, Maharashtra 400069', '27AABCE1234F1Z5', 'AABCE1234F', 'Priya Sharma', 'active', 750000.00, 45, 'Expert installation team for commercial projects', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(38, 'PowerMax Services', 'amit@powermax.com', '080-45678901', 'PowerMax Services Ltd', '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100', '29AABCF5678G2H6', 'AABCF5678G', 'Amit Singh', 'active', 300000.00, 15, 'Maintenance and service provider', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(39, 'EcoSolar Supplies', 'sunita@ecosolar.com', '079-56789012', 'EcoSolar Supplies', '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015', '24AABCG9012H3I7', 'AABCG9012H', 'Sunita Patel', 'active', 400000.00, 30, 'Specialized in residential solar components', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43'),
(40, 'SunRise Installations', 'vikram@sunrise.com', '040-67890123', 'SunRise Installations Pvt Ltd', '654 Innovation District, HITEC City, Hyderabad, Telangana 500081', '36AABCH3456I4J8', 'AABCH3456I', 'Vikram Reddy', 'inactive', 600000.00, 30, 'Currently under review for quality standards', 1, '2025-12-11 14:23:43', '2025-12-11 14:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_registrations`
--

CREATE TABLE `vendor_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_number` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'India',
  `gst_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `registration_type` varchar(255) NOT NULL,
  `registration_date` date NOT NULL,
  `business_description` text NOT NULL,
  `categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`categories`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`documents`)),
  `status` enum('pending','under_review','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_code` varchar(255) NOT NULL,
  `warehouse_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `manager_name` varchar(255) NOT NULL,
  `manager_email` varchar(255) NOT NULL,
  `manager_phone` varchar(255) NOT NULL,
  `status` enum('active','inactive','maintenance') NOT NULL,
  `capacity_percentage` int(11) NOT NULL,
  `total_items` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activities_activity_code_unique` (`activity_code`),
  ADD KEY `activities_created_by_foreign` (`created_by`),
  ADD KEY `activities_approved_by_foreign` (`approved_by`),
  ADD KEY `activities_project_id_status_index` (`project_id`,`status`),
  ADD KEY `activities_assigned_to_status_index` (`assigned_to`,`status`),
  ADD KEY `activities_planned_start_date_planned_end_date_index` (`planned_start_date`,`planned_end_date`),
  ADD KEY `activities_status_priority_index` (`status`,`priority`),
  ADD KEY `activities_is_milestone_status_index` (`is_milestone`,`status`);

--
-- Indexes for table `advances`
--
ALTER TABLE `advances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `advances_advance_number_unique` (`advance_number`),
  ADD KEY `advances_created_by_foreign` (`created_by`),
  ADD KEY `advances_approved_by_foreign` (`approved_by`),
  ADD KEY `advances_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `advances_vendor_id_status_index` (`vendor_id`,`status`),
  ADD KEY `advances_project_id_status_index` (`project_id`,`status`),
  ADD KEY `advances_status_index` (`status`),
  ADD KEY `advances_manager_approved_by_foreign` (`manager_approved_by`),
  ADD KEY `advances_hr_approved_by_foreign` (`hr_approved_by`);

--
-- Indexes for table `appraisals`
--
ALTER TABLE `appraisals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `a_m_c_s`
--
ALTER TABLE `a_m_c_s`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `a_m_c_s_amc_number_unique` (`amc_number`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `budgets_budget_number_unique` (`budget_number`),
  ADD KEY `budgets_budget_category_id_foreign` (`budget_category_id`),
  ADD KEY `budgets_project_id_foreign` (`project_id`),
  ADD KEY `budgets_approved_by_foreign` (`approved_by`),
  ADD KEY `budgets_created_by_foreign` (`created_by`);

--
-- Indexes for table `budget_categories`
--
ALTER TABLE `budget_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `budget_categories_name_unique` (`name`);

--
-- Indexes for table `channel_partners`
--
ALTER TABLE `channel_partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `channel_partners_partner_code_unique` (`partner_code`),
  ADD UNIQUE KEY `channel_partners_email_unique` (`email`),
  ADD KEY `channel_partners_assigned_to_foreign` (`assigned_to`),
  ADD KEY `channel_partners_created_by_foreign` (`created_by`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commissions_commission_number_unique` (`commission_number`),
  ADD KEY `commissions_channel_partner_id_foreign` (`channel_partner_id`),
  ADD KEY `commissions_project_id_foreign` (`project_id`),
  ADD KEY `commissions_invoice_id_foreign` (`invoice_id`),
  ADD KEY `commissions_quotation_id_foreign` (`quotation_id`),
  ADD KEY `commissions_approved_by_foreign` (`approved_by`),
  ADD KEY `commissions_created_by_foreign` (`created_by`);

--
-- Indexes for table `company_policies`
--
ALTER TABLE `company_policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_policies_policy_code_unique` (`policy_code`),
  ADD KEY `company_policies_created_by_foreign` (`created_by`),
  ADD KEY `company_policies_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `complaints_complaint_number_unique` (`complaint_number`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contractors_contractor_code_unique` (`contractor_code`),
  ADD KEY `contractors_created_by_foreign` (`created_by`),
  ADD KEY `contractors_assigned_to_foreign` (`assigned_to`),
  ADD KEY `contractors_verified_by_foreign` (`verified_by`),
  ADD KEY `contractors_status_availability_index` (`status`,`availability`),
  ADD KEY `contractors_contractor_type_status_index` (`contractor_type`,`status`),
  ADD KEY `contractors_city_state_index` (`city`,`state`),
  ADD KEY `contractors_specialization_index` (`specialization`);

--
-- Indexes for table `costings`
--
ALTER TABLE `costings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `costings_costing_number_unique` (`costing_number`),
  ADD KEY `costings_created_by_foreign` (`created_by`),
  ADD KEY `costings_approved_by_foreign` (`approved_by`),
  ADD KEY `costings_status_created_at_index` (`status`,`created_at`),
  ADD KEY `costings_project_id_index` (`project_id`);

--
-- Indexes for table `daily_progress_reports`
--
ALTER TABLE `daily_progress_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_progress_reports_submitted_by_foreign` (`submitted_by`),
  ADD KEY `daily_progress_reports_approved_by_foreign` (`approved_by`),
  ADD KEY `daily_progress_reports_project_id_report_date_index` (`project_id`,`report_date`),
  ADD KEY `daily_progress_reports_status_report_date_index` (`status`,`report_date`);

--
-- Indexes for table `delete_approvals`
--
ALTER TABLE `delete_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delete_approvals_requested_by_foreign` (`requested_by`),
  ADD KEY `delete_approvals_approved_by_foreign` (`approved_by`),
  ADD KEY `delete_approvals_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `delete_approvals_status_index` (`status`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_created_by_foreign` (`created_by`),
  ADD KEY `documents_category_status_index` (`category`,`status`),
  ADD KEY `documents_lead_id_created_at_index` (`lead_id`,`created_at`),
  ADD KEY `documents_project_id_created_at_index` (`project_id`,`created_at`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Indexes for table `escalations`
--
ALTER TABLE `escalations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `escalations_escalation_number_unique` (`escalation_number`),
  ADD KEY `escalations_related_lead_id_foreign` (`related_lead_id`),
  ADD KEY `escalations_related_project_id_foreign` (`related_project_id`),
  ADD KEY `escalations_related_invoice_id_foreign` (`related_invoice_id`),
  ADD KEY `escalations_related_quotation_id_foreign` (`related_quotation_id`),
  ADD KEY `escalations_related_commission_id_foreign` (`related_commission_id`),
  ADD KEY `escalations_escalated_to_foreign` (`escalated_to`),
  ADD KEY `escalations_created_by_foreign` (`created_by`),
  ADD KEY `escalations_status_priority_index` (`status`,`priority`),
  ADD KEY `escalations_assigned_to_status_index` (`assigned_to`,`status`),
  ADD KEY `escalations_created_at_status_index` (`created_at`,`status`),
  ADD KEY `escalations_due_date_status_index` (`due_date`,`status`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expenses_expense_number_unique` (`expense_number`),
  ADD KEY `expenses_expense_category_id_foreign` (`expense_category_id`),
  ADD KEY `expenses_project_id_foreign` (`project_id`),
  ADD KEY `expenses_created_by_foreign` (`created_by`),
  ADD KEY `expenses_approved_by_foreign` (`approved_by`),
  ADD KEY `expenses_manager_approved_by_foreign` (`manager_approved_by`),
  ADD KEY `expenses_hr_approved_by_foreign` (`hr_approved_by`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_claims`
--
ALTER TABLE `expense_claims`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_claims_claim_number_unique` (`claim_number`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `g_r_n_s`
--
ALTER TABLE `g_r_n_s`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `g_r_n_s_grn_number_unique` (`grn_number`),
  ADD KEY `g_r_n_s_received_by_foreign` (`received_by`),
  ADD KEY `g_r_n_s_verified_by_foreign` (`verified_by`),
  ADD KEY `g_r_n_s_vendor_id_status_index` (`vendor_id`,`status`),
  ADD KEY `g_r_n_s_purchase_order_id_status_index` (`purchase_order_id`,`status`),
  ADD KEY `g_r_n_s_project_id_status_index` (`project_id`,`status`),
  ADD KEY `g_r_n_s_grn_date_status_index` (`grn_date`,`status`);

--
-- Indexes for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_audits_audit_id_unique` (`audit_id`),
  ADD KEY `inventory_audits_audited_by_foreign` (`audited_by`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_project_id_foreign` (`project_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`),
  ADD KEY `invoices_status_invoice_date_index` (`status`,`invoice_date`),
  ADD KEY `invoices_client_id_created_at_index` (`client_id`,`created_at`),
  ADD KEY `invoices_channel_partner_id_foreign` (`channel_partner_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_applications_application_number_unique` (`application_number`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_assigned_user_id_foreign` (`assigned_user_id`),
  ADD KEY `leads_created_by_foreign` (`created_by`),
  ADD KEY `leads_status_assigned_user_id_index` (`status`,`assigned_user_id`),
  ADD KEY `leads_source_created_at_index` (`source`,`created_at`),
  ADD KEY `leads_channel_partner_id_foreign` (`channel_partner_id`),
  ADD KEY `leads_last_updated_by_foreign` (`last_updated_by`);

--
-- Indexes for table `lead_backups`
--
ALTER TABLE `lead_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_backups_expires_at_index` (`expires_at`),
  ADD KEY `lead_backups_original_lead_id_index` (`original_lead_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materials_material_code_unique` (`material_code`),
  ADD KEY `materials_material_request_id_index` (`material_request_id`),
  ADD KEY `materials_status_index` (`status`),
  ADD KEY `materials_supplier_index` (`supplier`);

--
-- Indexes for table `material_consumptions`
--
ALTER TABLE `material_consumptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_consumptions_consumption_number_unique` (`consumption_number`),
  ADD KEY `material_consumptions_material_request_id_foreign` (`material_request_id`),
  ADD KEY `material_consumptions_supervised_by_foreign` (`supervised_by`),
  ADD KEY `material_consumptions_approved_by_foreign` (`approved_by`),
  ADD KEY `material_consumptions_consumption_date_consumption_status_index` (`consumption_date`,`consumption_status`),
  ADD KEY `material_consumptions_material_id_consumption_date_index` (`material_id`,`consumption_date`),
  ADD KEY `material_consumptions_project_id_work_phase_index` (`project_id`,`work_phase`),
  ADD KEY `material_consumptions_consumed_by_consumption_date_index` (`consumed_by`,`consumption_date`),
  ADD KEY `material_consumptions_quality_status_index` (`quality_status`),
  ADD KEY `material_consumptions_work_phase_index` (`work_phase`),
  ADD KEY `material_consumptions_cost_center_index` (`cost_center`);

--
-- Indexes for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_requests_request_number_unique` (`request_number`),
  ADD KEY `material_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `material_requests_assigned_to_foreign` (`assigned_to`),
  ADD KEY `material_requests_status_priority_index` (`status`,`priority`),
  ADD KEY `material_requests_project_id_status_index` (`project_id`,`status`),
  ADD KEY `material_requests_requested_by_status_index` (`requested_by`,`status`),
  ADD KEY `material_requests_required_date_status_index` (`required_date`,`status`),
  ADD KEY `material_requests_category_index` (`category`),
  ADD KEY `material_requests_request_type_index` (`request_type`),
  ADD KEY `material_requests_is_urgent_required_date_index` (`is_urgent`,`required_date`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_index` (`user_id`,`read`),
  ADD KEY `notifications_user_id_type_index` (`user_id`,`type`);

--
-- Indexes for table `o_m_maintenances`
--
ALTER TABLE `o_m_maintenances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `o_m_maintenances_maintenance_id_unique` (`maintenance_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_milestones`
--
ALTER TABLE `payment_milestones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_milestones_milestone_number_unique` (`milestone_number`),
  ADD KEY `payment_milestones_quotation_id_foreign` (`quotation_id`),
  ADD KEY `payment_milestones_created_by_foreign` (`created_by`),
  ADD KEY `payment_milestones_assigned_to_foreign` (`assigned_to`),
  ADD KEY `payment_milestones_paid_by_foreign` (`paid_by`),
  ADD KEY `payment_milestones_project_id_status_index` (`project_id`,`status`),
  ADD KEY `payment_milestones_milestone_type_status_index` (`milestone_type`,`status`),
  ADD KEY `payment_milestones_due_date_payment_status_index` (`due_date`,`payment_status`);

--
-- Indexes for table `payment_requests`
--
ALTER TABLE `payment_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_requests_pr_number_unique` (`pr_number`),
  ADD KEY `payment_requests_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `payment_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `payment_requests_vendor_id_status_index` (`vendor_id`,`status`),
  ADD KEY `payment_requests_project_id_status_index` (`project_id`,`status`),
  ADD KEY `payment_requests_requested_by_status_index` (`requested_by`,`status`),
  ADD KEY `payment_requests_request_date_status_index` (`request_date`,`status`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_is_active_index` (`category`,`is_active`),
  ADD KEY `products_sku_index` (`sku`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_project_code_unique` (`project_code`),
  ADD KEY `projects_project_manager_id_foreign` (`project_manager_id`),
  ADD KEY `projects_client_id_foreign` (`client_id`),
  ADD KEY `projects_created_by_foreign` (`created_by`),
  ADD KEY `projects_status_project_manager_id_index` (`status`,`project_manager_id`),
  ADD KEY `projects_start_date_end_date_index` (`start_date`,`end_date`),
  ADD KEY `projects_channel_partner_id_foreign` (`channel_partner_id`),
  ADD KEY `projects_project_engineer_foreign` (`project_engineer`),
  ADD KEY `projects_liaisoning_officer_foreign` (`liaisoning_officer`);

--
-- Indexes for table `project_profitabilities`
--
ALTER TABLE `project_profitabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_profitabilities_created_by_foreign` (`created_by`),
  ADD KEY `project_profitabilities_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `project_profitabilities_project_id_period_start_date_index` (`project_id`,`period`,`start_date`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_created_by_foreign` (`created_by`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`),
  ADD KEY `purchase_orders_vendor_id_status_index` (`vendor_id`,`status`),
  ADD KEY `purchase_orders_project_id_status_index` (`project_id`,`status`),
  ADD KEY `purchase_orders_po_date_status_index` (`po_date`,`status`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_product_id_foreign` (`product_id`),
  ADD KEY `purchase_order_items_purchase_order_id_product_id_index` (`purchase_order_id`,`product_id`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_requisitions_pr_number_unique` (`pr_number`),
  ADD KEY `purchase_requisitions_approved_by_foreign` (`approved_by`),
  ADD KEY `purchase_requisitions_project_id_status_index` (`project_id`,`status`),
  ADD KEY `purchase_requisitions_requested_by_status_index` (`requested_by`,`status`),
  ADD KEY `purchase_requisitions_requisition_date_status_index` (`requisition_date`,`status`);

--
-- Indexes for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_requisition_items_product_id_foreign` (`product_id`),
  ADD KEY `pr_items_pr_product_idx` (`purchase_requisition_id`,`product_id`);

--
-- Indexes for table `quality_checks`
--
ALTER TABLE `quality_checks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quality_checks_qc_number_unique` (`qc_number`),
  ADD KEY `quality_checks_checked_by_foreign` (`checked_by`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_quotation_number_unique` (`quotation_number`),
  ADD KEY `quotations_project_id_foreign` (`project_id`),
  ADD KEY `quotations_created_by_foreign` (`created_by`),
  ADD KEY `quotations_status_quotation_date_index` (`status`,`quotation_date`),
  ADD KEY `quotations_client_id_created_at_index` (`client_id`,`created_at`),
  ADD KEY `quotations_channel_partner_id_foreign` (`channel_partner_id`),
  ADD KEY `quotations_parent_quotation_id_revision_number_index` (`parent_quotation_id`,`revision_number`),
  ADD KEY `quotations_is_latest_index` (`is_latest`);

--
-- Indexes for table `resource_allocations`
--
ALTER TABLE `resource_allocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource_allocations_allocation_code_unique` (`allocation_code`),
  ADD KEY `resource_allocations_activity_id_foreign` (`activity_id`),
  ADD KEY `resource_allocations_allocated_by_foreign` (`allocated_by`),
  ADD KEY `resource_allocations_approved_by_foreign` (`approved_by`),
  ADD KEY `ra_project_status_idx` (`project_id`,`status`),
  ADD KEY `ra_resource_type_status_idx` (`resource_type`,`status`),
  ADD KEY `ra_allocated_to_status_idx` (`allocated_to`,`status`),
  ADD KEY `ra_dates_idx` (`allocation_start_date`,`allocation_end_date`),
  ADD KEY `ra_status_priority_idx` (`status`,`priority`),
  ADD KEY `ra_critical_status_idx` (`is_critical`,`status`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `r_f_q_items`
--
ALTER TABLE `r_f_q_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `r_f_q_items_product_id_foreign` (`product_id`),
  ADD KEY `rfq_items_rfq_product_idx` (`rfq_id`,`product_id`);

--
-- Indexes for table `r_f_q_s`
--
ALTER TABLE `r_f_q_s`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `r_f_q_s_rfq_number_unique` (`rfq_number`),
  ADD KEY `r_f_q_s_approved_by_foreign` (`approved_by`),
  ADD KEY `r_f_q_s_project_id_status_index` (`project_id`,`status`),
  ADD KEY `r_f_q_s_created_by_status_index` (`created_by`,`status`),
  ADD KEY `r_f_q_s_rfq_date_status_index` (`rfq_date`,`status`),
  ADD KEY `r_f_q_s_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `salary_slips`
--
ALTER TABLE `salary_slips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salary_slips_slip_number_unique` (`slip_number`);

--
-- Indexes for table `site_expenses`
--
ALTER TABLE `site_expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_expenses_expense_number_unique` (`expense_number`),
  ADD KEY `site_expenses_created_by_foreign` (`created_by`),
  ADD KEY `site_expenses_approved_by_foreign` (`approved_by`),
  ADD KEY `site_expenses_project_id_expense_date_index` (`project_id`,`expense_date`),
  ADD KEY `site_expenses_status_index` (`status`),
  ADD KEY `site_expenses_hr_approved_by_foreign` (`hr_approved_by`);

--
-- Indexes for table `site_warehouses`
--
ALTER TABLE `site_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_warehouses_managed_by_foreign` (`managed_by`),
  ADD KEY `site_warehouses_project_id_status_index` (`project_id`,`status`),
  ADD KEY `site_warehouses_status_managed_by_index` (`status`,`managed_by`);

--
-- Indexes for table `stock_ledgers`
--
ALTER TABLE `stock_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_valuations`
--
ALTER TABLE `stock_valuations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_created_by_foreign` (`created_by`),
  ADD KEY `tasks_status_assigned_to_index` (`status`,`assigned_to`),
  ADD KEY `tasks_project_id_due_date_index` (`project_id`,`due_date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendors_created_by_foreign` (`created_by`),
  ADD KEY `vendors_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `vendor_registrations`
--
ALTER TABLE `vendor_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_registrations_registration_number_unique` (`registration_number`),
  ADD KEY `vendor_registrations_status_registration_date_index` (`status`,`registration_date`),
  ADD KEY `vendor_registrations_reviewed_by_status_index` (`reviewed_by`,`status`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_warehouse_code_unique` (`warehouse_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `advances`
--
ALTER TABLE `advances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appraisals`
--
ALTER TABLE `appraisals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `a_m_c_s`
--
ALTER TABLE `a_m_c_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `budget_categories`
--
ALTER TABLE `budget_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `channel_partners`
--
ALTER TABLE `channel_partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `company_policies`
--
ALTER TABLE `company_policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `costings`
--
ALTER TABLE `costings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `daily_progress_reports`
--
ALTER TABLE `daily_progress_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `delete_approvals`
--
ALTER TABLE `delete_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `escalations`
--
ALTER TABLE `escalations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `expense_claims`
--
ALTER TABLE `expense_claims`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `g_r_n_s`
--
ALTER TABLE `g_r_n_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `lead_backups`
--
ALTER TABLE `lead_backups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `material_consumptions`
--
ALTER TABLE `material_consumptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `o_m_maintenances`
--
ALTER TABLE `o_m_maintenances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_milestones`
--
ALTER TABLE `payment_milestones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `payment_requests`
--
ALTER TABLE `payment_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_profitabilities`
--
ALTER TABLE `project_profitabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quality_checks`
--
ALTER TABLE `quality_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `resource_allocations`
--
ALTER TABLE `resource_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `r_f_q_items`
--
ALTER TABLE `r_f_q_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_f_q_s`
--
ALTER TABLE `r_f_q_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_slips`
--
ALTER TABLE `salary_slips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `site_expenses`
--
ALTER TABLE `site_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_warehouses`
--
ALTER TABLE `site_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_ledgers`
--
ALTER TABLE `stock_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `stock_valuations`
--
ALTER TABLE `stock_valuations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `vendor_registrations`
--
ALTER TABLE `vendor_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `advances`
--
ALTER TABLE `advances`
  ADD CONSTRAINT `advances_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `advances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `advances_hr_approved_by_foreign` FOREIGN KEY (`hr_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `advances_manager_approved_by_foreign` FOREIGN KEY (`manager_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `advances_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `advances_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `budgets_budget_category_id_foreign` FOREIGN KEY (`budget_category_id`) REFERENCES `budget_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `budgets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `channel_partners`
--
ALTER TABLE `channel_partners`
  ADD CONSTRAINT `channel_partners_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `channel_partners_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commissions_channel_partner_id_foreign` FOREIGN KEY (`channel_partner_id`) REFERENCES `channel_partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commissions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commissions_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `company_policies`
--
ALTER TABLE `company_policies`
  ADD CONSTRAINT `company_policies_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `company_policies_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contractors`
--
ALTER TABLE `contractors`
  ADD CONSTRAINT `contractors_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contractors_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contractors_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `costings`
--
ALTER TABLE `costings`
  ADD CONSTRAINT `costings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `costings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `costings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `daily_progress_reports`
--
ALTER TABLE `daily_progress_reports`
  ADD CONSTRAINT `daily_progress_reports_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `daily_progress_reports_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_progress_reports_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delete_approvals`
--
ALTER TABLE `delete_approvals`
  ADD CONSTRAINT `delete_approvals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delete_approvals_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `documents_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `escalations`
--
ALTER TABLE `escalations`
  ADD CONSTRAINT `escalations_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `escalations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `escalations_escalated_to_foreign` FOREIGN KEY (`escalated_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `escalations_related_commission_id_foreign` FOREIGN KEY (`related_commission_id`) REFERENCES `commissions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `escalations_related_invoice_id_foreign` FOREIGN KEY (`related_invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `escalations_related_lead_id_foreign` FOREIGN KEY (`related_lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `escalations_related_project_id_foreign` FOREIGN KEY (`related_project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `escalations_related_quotation_id_foreign` FOREIGN KEY (`related_quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`),
  ADD CONSTRAINT `expenses_hr_approved_by_foreign` FOREIGN KEY (`hr_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_manager_approved_by_foreign` FOREIGN KEY (`manager_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `g_r_n_s`
--
ALTER TABLE `g_r_n_s`
  ADD CONSTRAINT `g_r_n_s_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `g_r_n_s_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `g_r_n_s_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `g_r_n_s_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `g_r_n_s_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  ADD CONSTRAINT `inventory_audits_audited_by_foreign` FOREIGN KEY (`audited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_channel_partner_id_foreign` FOREIGN KEY (`channel_partner_id`) REFERENCES `channel_partners` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `leads` (`id`),
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_assigned_user_id_foreign` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_channel_partner_id_foreign` FOREIGN KEY (`channel_partner_id`) REFERENCES `channel_partners` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `leads_last_updated_by_foreign` FOREIGN KEY (`last_updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_material_request_id_foreign` FOREIGN KEY (`material_request_id`) REFERENCES `material_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_consumptions`
--
ALTER TABLE `material_consumptions`
  ADD CONSTRAINT `material_consumptions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_consumptions_consumed_by_foreign` FOREIGN KEY (`consumed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `material_consumptions_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `material_consumptions_material_request_id_foreign` FOREIGN KEY (`material_request_id`) REFERENCES `material_requests` (`id`),
  ADD CONSTRAINT `material_consumptions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_consumptions_supervised_by_foreign` FOREIGN KEY (`supervised_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD CONSTRAINT `material_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_requests_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `material_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_milestones`
--
ALTER TABLE `payment_milestones`
  ADD CONSTRAINT `payment_milestones_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_milestones_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payment_milestones_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_milestones_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_requests`
--
ALTER TABLE `payment_requests`
  ADD CONSTRAINT `payment_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_requests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_requests_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_requests_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_channel_partner_id_foreign` FOREIGN KEY (`channel_partner_id`) REFERENCES `channel_partners` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `projects_liaisoning_officer_foreign` FOREIGN KEY (`liaisoning_officer`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_project_engineer_foreign` FOREIGN KEY (`project_engineer`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `project_profitabilities`
--
ALTER TABLE `project_profitabilities`
  ADD CONSTRAINT `project_profitabilities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `project_profitabilities_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_profitabilities_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD CONSTRAINT `purchase_requisitions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_requisitions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_requisitions_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD CONSTRAINT `purchase_requisition_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_requisition_items_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quality_checks`
--
ALTER TABLE `quality_checks`
  ADD CONSTRAINT `quality_checks_checked_by_foreign` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_channel_partner_id_foreign` FOREIGN KEY (`channel_partner_id`) REFERENCES `channel_partners` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `leads` (`id`),
  ADD CONSTRAINT `quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quotations_parent_quotation_id_foreign` FOREIGN KEY (`parent_quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `resource_allocations`
--
ALTER TABLE `resource_allocations`
  ADD CONSTRAINT `resource_allocations_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resource_allocations_allocated_by_foreign` FOREIGN KEY (`allocated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resource_allocations_allocated_to_foreign` FOREIGN KEY (`allocated_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resource_allocations_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resource_allocations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `r_f_q_items`
--
ALTER TABLE `r_f_q_items`
  ADD CONSTRAINT `r_f_q_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `r_f_q_items_rfq_id_foreign` FOREIGN KEY (`rfq_id`) REFERENCES `r_f_q_s` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `r_f_q_s`
--
ALTER TABLE `r_f_q_s`
  ADD CONSTRAINT `r_f_q_s_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `r_f_q_s_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `r_f_q_s_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `r_f_q_s_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `site_expenses`
--
ALTER TABLE `site_expenses`
  ADD CONSTRAINT `site_expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `site_expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_expenses_hr_approved_by_foreign` FOREIGN KEY (`hr_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `site_expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `site_warehouses`
--
ALTER TABLE `site_warehouses`
  ADD CONSTRAINT `site_warehouses_managed_by_foreign` FOREIGN KEY (`managed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_warehouses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `vendor_registrations`
--
ALTER TABLE `vendor_registrations`
  ADD CONSTRAINT `vendor_registrations_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
