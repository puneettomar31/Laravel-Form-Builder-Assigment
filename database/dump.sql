SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `ai_tasks`;
CREATE TABLE `ai_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_id` bigint unsigned DEFAULT NULL,
  `action` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prompt` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `output_schema` json DEFAULT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tokens` int unsigned DEFAULT NULL,
  `latency_ms` int unsigned DEFAULT NULL,
  `error` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_tasks_form_id_index` (`form_id`),
  KEY `ai_tasks_status_index` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `form_submissions`;
CREATE TABLE `form_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_id` bigint unsigned NOT NULL,
  `submission_data` json NOT NULL,
  `search_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `stored_files` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_submissions_form_id_index` (`form_id`),
  KEY `form_submissions_search_text_index` (`search_text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `forms`;
CREATE TABLE `forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `public_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schema` json NOT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `forms_slug_unique` (`slug`),
  UNIQUE KEY `forms_public_uuid_unique` (`public_uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `forms` (`id`, `0`, `title`, `1`, `slug`, `2`, `description`, `3`, `public_uuid`, `4`, `schema`, `5`, `status`, `6`, `created_at`, `7`, `updated_at`, `8`) VALUES ('1', '1', 'Sample Contact Form', 'Sample Contact Form', 'sample-contact-form', 'sample-contact-form', 'A sample form for contact and feedback.', 'A sample form for contact and feedback.', '6f06c352-8043-40d4-bafb-36c037e53a7f', '6f06c352-8043-40d4-bafb-36c037e53a7f', '{\"fields\": [{\"key\": \"name\", \"type\": \"text\", \"label\": \"Name\", \"options\": [], \"required\": true, \"validation\": {\"min_length\": 2}, \"placeholder\": \"Enter your name\"}, {\"key\": \"email\", \"type\": \"email\", \"label\": \"Email\", \"options\": [], \"required\": true, \"validation\": [], \"placeholder\": \"Enter your email\"}, {\"key\": \"message\", \"type\": \"textarea\", \"label\": \"Message\", \"options\": [], \"required\": false, \"validation\": {\"max_length\": 500}, \"placeholder\": \"Write your message\"}]}', '{\"fields\": [{\"key\": \"name\", \"type\": \"text\", \"label\": \"Name\", \"options\": [], \"required\": true, \"validation\": {\"min_length\": 2}, \"placeholder\": \"Enter your name\"}, {\"key\": \"email\", \"type\": \"email\", \"label\": \"Email\", \"options\": [], \"required\": true, \"validation\": [], \"placeholder\": \"Enter your email\"}, {\"key\": \"message\", \"type\": \"textarea\", \"label\": \"Message\", \"options\": [], \"required\": false, \"validation\": {\"max_length\": 500}, \"placeholder\": \"Write your message\"}]}', 'published', 'published', '2026-08-04 06:37:36', '2026-08-04 06:37:36', '2026-08-04 06:37:36', '2026-08-04 06:37:36');

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `0`, `migration`, `1`, `batch`, `2`) VALUES ('1', '1', '0001_01_01_000000_create_users_table', '0001_01_01_000000_create_users_table', '1', '1');
INSERT INTO `migrations` (`id`, `0`, `migration`, `1`, `batch`, `2`) VALUES ('2', '2', '0001_01_01_000001_create_cache_table', '0001_01_01_000001_create_cache_table', '1', '1');
INSERT INTO `migrations` (`id`, `0`, `migration`, `1`, `batch`, `2`) VALUES ('3', '3', '0001_01_01_000002_create_jobs_table', '0001_01_01_000002_create_jobs_table', '1', '1');
INSERT INTO `migrations` (`id`, `0`, `migration`, `1`, `batch`, `2`) VALUES ('4', '4', '2026_08_04_053754_create_forms_table', '2026_08_04_053754_create_forms_table', '1', '1');
INSERT INTO `migrations` (`id`, `0`, `migration`, `1`, `batch`, `2`) VALUES ('5', '5', '2026_08_04_053755_create_form_submissions_table', '2026_08_04_053755_create_form_submissions_table', '1', '1');
INSERT INTO `migrations` (`id`, `0`, `migration`, `1`, `batch`, `2`) VALUES ('6', '6', '2026_08_04_053756_create_ai_tasks_table', '2026_08_04_053756_create_ai_tasks_table', '1', '1');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `0`, `user_id`, `1`, `ip_address`, `2`, `user_agent`, `3`, `payload`, `4`, `last_activity`, `5`) VALUES ('nwsb4yHuxfvFRlwOBh68pKUCf3iiijkGFisD9ww6', 'nwsb4yHuxfvFRlwOBh68pKUCf3iiijkGFisD9ww6', NULL, NULL, '127.0.0.1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.131.0 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.131.0 Chrome/148.0.7778.280 Electron/42.7.0 Safari/537.36', 'eyJfdG9rZW4iOiJPdnFyR2RNNUR1Q2VCbW1Gc1RrTFhUNmtoZ0d0bXYwODBPaVhUd1FFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJmb3Jtcy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 'eyJfdG9rZW4iOiJPdnFyR2RNNUR1Q2VCbW1Gc1RrTFhUNmtoZ0d0bXYwODBPaVhUd1FFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJmb3Jtcy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', '1785825583', '1785825583');
INSERT INTO `sessions` (`id`, `0`, `user_id`, `1`, `ip_address`, `2`, `user_agent`, `3`, `payload`, `4`, `last_activity`, `5`) VALUES ('cXYjM5fA1qtHsQBtB1aThbrrTZVPsDJSYOtAErMz', 'cXYjM5fA1qtHsQBtB1aThbrrTZVPsDJSYOtAErMz', NULL, NULL, '127.0.0.1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIxQW5DRG00S2tvZk1RbFZBdm15RVRWNmc5czZFTmZwVWVKYVZ0cFROIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJmb3Jtcy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 'eyJfdG9rZW4iOiIxQW5DRG00S2tvZk1RbFZBdm15RVRWNmc5czZFTmZwVWVKYVZ0cFROIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJmb3Jtcy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', '1785825593', '1785825593');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `0`, `name`, `1`, `email`, `2`, `email_verified_at`, `3`, `password`, `4`, `remember_token`, `5`, `created_at`, `6`, `updated_at`, `7`) VALUES ('1', '1', 'Test User', 'Test User', 'test@example.com', 'test@example.com', '2026-08-04 06:37:36', '2026-08-04 06:37:36', '$2y$12$l3eadEOYhm0LCv81nQ7EK.b8szHO36cglIl2EFovNkNBnc5ny1eJy', '$2y$12$l3eadEOYhm0LCv81nQ7EK.b8szHO36cglIl2EFovNkNBnc5ny1eJy', 'BeOz6hZD3F', 'BeOz6hZD3F', '2026-08-04 06:37:36', '2026-08-04 06:37:36', '2026-08-04 06:37:36', '2026-08-04 06:37:36');

SET FOREIGN_KEY_CHECKS = 1;
