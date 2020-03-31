SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_guid_unique` (`guid`),
  KEY `companies_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `companies` (`id`, `guid`, `status`, `name`, `email`, `phone`, `additional_contact`, `additional_contact_number`, `description`, `address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'4ec55d3b-6401-4b02-8b1b-50e7596e14c9',	1,	'Steuber-Miller',	'rgreenfelder@kshlerin.biz',	'68-9400429',	'Janette Blackburn',	'(740) 650-6003',	'Synchronised Zerodefect Openarchitecture',	'8872 Mccormick Place\nGlenford, OH 43739',	'2020-03-27 03:10:56',	'2020-03-27 03:13:43',	NULL),
(2,	'a473f365-228d-4abb-a7e9-ebbf5f49dbf2',	1,	'Murray Ltd',	'ankunding.carmine@kerluke.com',	'04-7897263',	'Angie Long',	'(304) 225-7041',	'Networked Executive Capacity',	'2806 West End Court Meadowbrook, WV 26404',	'2020-03-27 03:15:43',	'2020-03-27 03:15:43',	NULL),
(3,	'1b7ed1dc-8076-4f4b-ad1b-6c259efbe037',	1,	'Carroll Ltd',	'raymond.koss@gerlach.com',	'76-9644733',	'Aurora Dorsey',	'(603) 344-3705',	'Operative Transitional Parallelism',	'9961 Metron Place Newton Junction, NH 03859',	'2020-03-27 03:17:04',	'2020-03-27 03:17:04',	NULL),
(4,	'3611fa84-edb4-4d4b-aca1-312f841d9853',	1,	'Champlin LLC',	'cmorissette@cruickshank.biz',	'20-5706795',	'Olga Glass',	'(334) 612-3276',	'Inverse Heuristic Knowledgebase',	'9931 Reta Drive\nMontgomery, AL 36140',	'2020-03-27 03:18:09',	'2020-03-27 03:18:09',	NULL),
(5,	'137bc99d-efde-441f-8b1f-4f9f7ef26357',	0,	'Parker-Bogisich',	'wintheiser.alta@bernier.com',	'88-1903544',	'Joel Abbott',	'(478) 755-7798',	'User-Centric Scalable Instructionset',	'9207 Keystone Avenue Knoxville, GA 31050',	'2020-03-27 03:18:58',	'2020-03-27 03:18:58',	NULL);

DROP TABLE IF EXISTS `document_types`;
CREATE TABLE `document_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_types_guid_unique` (`guid`),
  KEY `document_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `document_types` (`id`, `guid`, `name`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'01ae6bca-515d-41ac-b9be-30953efd95b9',	'CV/Resume',	NULL,	'2020-03-27 02:05:17',	'2020-03-27 02:05:40',	NULL);

DROP TABLE IF EXISTS `experience_levels`;
CREATE TABLE `experience_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `experience_levels_guid_unique` (`guid`),
  KEY `experience_levels_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `experience_levels` (`id`, `guid`, `title`, `created_at`, `updated_at`) VALUES
(1,	'c1023696-9eca-4711-a55e-5df79eda8c5f',	'Basic Knowledge',	'2020-03-27 02:07:38',	'2020-03-27 02:07:38'),
(2,	'3cd72852-1b2c-4f7a-9fcb-2c262db029c6',	'Limited Experience',	'2020-03-27 02:07:59',	'2020-03-27 02:09:24'),
(3,	'70ebe4e5-6628-4072-9b82-5995f2c72381',	'Intermediate',	'2020-03-27 02:08:12',	'2020-03-27 02:08:12'),
(4,	'47937760-f93c-4118-bcdc-79ff8130b7e0',	'Advanced',	'2020-03-27 02:08:20',	'2020-03-27 02:08:20'),
(5,	'0bd97c5a-e266-4e22-b2ec-4bab21b13cc3',	'Expert',	'2020-03-27 02:08:29',	'2020-03-27 02:08:29');

DROP TABLE IF EXISTS `interviews`;
CREATE TABLE `interviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_application_id` bigint(20) unsigned DEFAULT NULL,
  `candidate_id` bigint(20) unsigned NOT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `interview_stage_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT '1',
  `interviewer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interview_date` datetime DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `hired_at` datetime DEFAULT NULL,
  `joining_date` datetime DEFAULT NULL,
  `salary` double(8,2) DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `interviews_guid_unique` (`guid`),
  KEY `interviews_id_index` (`id`),
  KEY `interviews_job_application_id_index` (`job_application_id`),
  KEY `interviews_candidate_id_index` (`candidate_id`),
  KEY `interviews_company_id_index` (`company_id`),
  KEY `interviews_interview_stage_id_index` (`interview_stage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interviews` (`id`, `guid`, `job_application_id`, `candidate_id`, `company_id`, `interview_stage_id`, `status`, `interviewer_name`, `interview_date`, `rejected_at`, `hired_at`, `joining_date`, `salary`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'7f4838ae-04e2-43c5-8024-0dd10256a7d1',	3,	10,	2,	5,	2,	NULL,	'2020-04-10 00:00:00',	NULL,	NULL,	NULL,	NULL,	'May date change.',	'2020-03-31 04:32:05',	'2020-03-31 04:51:37',	NULL),
(2,	'c3023a3b-86ea-4a7e-b860-97d6d462be7f',	NULL,	1,	1,	5,	2,	'John Doe',	'2020-04-15 00:00:00',	NULL,	NULL,	NULL,	NULL,	NULL,	'2020-03-31 04:52:19',	'2020-03-31 04:53:07',	NULL);

DROP TABLE IF EXISTS `interview_stages`;
CREATE TABLE `interview_stages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `i_order` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `interview_stages_guid_unique` (`guid`),
  KEY `interview_stages_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interview_stages` (`id`, `guid`, `i_order`, `title`, `created_at`, `updated_at`) VALUES
(1,	'c2468b17-c529-4e3a-a2c0-927af18ff177',	1,	'Informational Interview',	'2020-03-27 02:16:00',	'2020-03-27 02:16:00'),
(2,	'53064104-b79a-4899-9568-58a309ade80c',	2,	'Screening or Telephone Interview',	'2020-03-27 02:16:10',	'2020-03-27 02:16:10'),
(3,	'fe45d7cb-f737-48ee-8d72-9da365805fb1',	3,	'Individual Interview',	'2020-03-27 02:16:18',	'2020-03-27 02:16:18'),
(4,	'bebbd9b8-db91-404f-9bdc-8ad7a64acf57',	4,	'Small Group or Committee Interview',	'2020-03-27 02:16:27',	'2020-03-27 02:16:27'),
(5,	'9c6dd9e5-05b3-458a-988d-dbf8eaabf5c9',	5,	'Task Oriented or Testing Interview',	'2020-03-27 02:16:37',	'2020-03-27 02:16:37');

DROP TABLE IF EXISTS `interview_stage_histories`;
CREATE TABLE `interview_stage_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `interview_id` bigint(20) unsigned NOT NULL,
  `interview_stage_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interview_stage_histories_id_index` (`id`),
  KEY `interview_stage_histories_interview_id_index` (`interview_id`),
  KEY `interview_stage_histories_interview_stage_id_index` (`interview_stage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interview_stage_histories` (`id`, `interview_id`, `interview_stage_id`, `created_at`, `updated_at`) VALUES
(1,	1,	1,	'2020-03-31 04:32:05',	'2020-03-31 04:32:05'),
(4,	1,	3,	'2020-04-03 18:30:00',	'2020-03-31 04:51:08'),
(5,	1,	5,	'2020-04-09 18:30:00',	'2020-03-31 04:51:37'),
(6,	2,	1,	'2020-04-07 18:30:00',	'2020-03-31 04:52:43'),
(7,	2,	5,	'2020-04-14 18:30:00',	'2020-03-31 04:53:07');

DROP TABLE IF EXISTS `interview_status_histories`;
CREATE TABLE `interview_status_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` bigint(20) unsigned NOT NULL,
  `interview_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'pending | interview_scheduled | on_hold | rejected | hired',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interview_status_histories_id_index` (`id`),
  KEY `interview_status_histories_creator_id_index` (`creator_id`),
  KEY `interview_status_histories_interview_id_index` (`interview_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interview_status_histories` (`id`, `creator_id`, `interview_id`, `status`, `created_at`, `updated_at`) VALUES
(1,	2,	1,	2,	'2020-03-31 04:32:05',	'2020-03-31 04:32:05'),
(2,	2,	2,	1,	'2020-03-31 04:52:19',	'2020-03-31 04:52:19'),
(3,	2,	2,	2,	'2020-03-31 04:52:42',	'2020-03-31 04:52:42');

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `job_department_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '1.Open 2.Closed',
  `open_vacancies` int(11) DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `job_description` text COLLATE utf8mb4_unicode_ci,
  `company_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jobs_guid_unique` (`guid`),
  KEY `jobs_id_index` (`id`),
  KEY `jobs_company_id_index` (`company_id`),
  KEY `jobs_job_department_id_index` (`job_department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `jobs` (`id`, `guid`, `company_id`, `job_department_id`, `status`, `open_vacancies`, `title`, `from_date`, `to_date`, `job_description`, `company_description`, `created_at`, `updated_at`, `deleted_at`, `archived_at`) VALUES
(1,	'4942d52c-540f-4072-a7ba-da58f232d13b',	4,	8,	1,	2,	'Assemblers and Fabricators',	'2020-03-01',	'2020-03-31',	'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',	'Lorem Ipsum is simply dummy text. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum',	'2020-03-31 04:10:41',	'2020-03-31 04:10:49',	NULL,	NULL),
(2,	'ca4ffc2f-5970-4a4d-8473-11d6c5bec0d6',	4,	8,	0,	2,	'Jewelers and Precious Stone and Metal Workers',	'2020-03-01',	'2029-03-31',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis libero eu sem ultrices finibus eu nec arcu. Aliquam erat volutpat. Nunc egestas quam metus, vitae accumsan urna iaculis vel. Vivamus eleifend, augue ac lacinia posuere, urna ipsum imperdiet purus, sit amet tempor ligula leo non turpis. Ut sit amet sodales dui. Mauris erat quam, condimentum at volutpat malesuada, porta a eros. Nam ac pulvinar eros. Sed hendrerit ac nisi non commodo. Vestibulum finibus arcu id purus eleifend tempor.',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis libero eu sem ultrices finibus eu nec arcu. Aliquam erat volutpat. Nunc egestas quam metus, vitae accumsan urna iaculis vel. Vivamus eleifend, augue ac lacinia posuere, urna ipsum imperdiet purus, sit amet tempor ligula leo non turpis. Ut sit amet sodales dui. Mauris erat quam, condimentum at volutpat malesuada, porta a eros. Nam ac pulvinar eros. Sed hendrerit ac nisi non commodo. Vestibulum finibus arcu id purus eleifend tempor.',	'2020-03-31 04:11:54',	'2020-03-31 04:11:54',	NULL,	NULL),
(3,	'e45cc526-58e1-4351-b23f-68d0989acd0e',	3,	8,	1,	5,	'Medical Appliance Technicians',	'2020-03-01',	'2029-03-31',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis libero eu sem ultrices finibus eu nec arcu. Aliquam erat volutpat. Nunc egestas quam metus, vitae accumsan urna iaculis vel.',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis libero eu sem ultrices finibus eu nec arcu. Aliquam erat volutpat. Nunc egestas quam metus, vitae accumsan urna iaculis vel.',	'2020-03-31 04:12:51',	'2020-03-31 04:13:38',	NULL,	NULL),
(4,	'7d26d365-867c-4468-ab1e-c5e396cdd5d4',	2,	8,	1,	2,	'Miscellaneous Manufacturing',	'2020-03-01',	'2025-03-31',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis libero eu sem ultrices finibus eu nec arcu. Aliquam erat volutpat. Nunc egestas quam metus, vitae accumsan urna iaculis vel.',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis quis libero eu sem ultrices finibus eu nec arcu. Aliquam erat volutpat. Nunc egestas quam metus, vitae accumsan urna iaculis vel.',	'2020-03-31 04:14:42',	'2020-03-31 04:14:42',	NULL,	NULL);

DROP TABLE IF EXISTS `job_applications`;
CREATE TABLE `job_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_application_number` char(36) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `increment_number` bigint(20) unsigned DEFAULT NULL,
  `job_id` bigint(20) unsigned NOT NULL,
  `candidate_id` bigint(20) unsigned NOT NULL,
  `user_document_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'pending | interview_scheduled | on_hold | rejected | hired',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_applications_guid_unique` (`guid`),
  KEY `job_applications_id_index` (`id`),
  KEY `job_applications_job_id_index` (`job_id`),
  KEY `job_applications_candidate_id_index` (`candidate_id`),
  KEY `user_document_id` (`user_document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_applications` (`id`, `guid`, `job_application_number`, `increment_number`, `job_id`, `candidate_id`, `user_document_id`, `status`, `created_at`, `updated_at`) VALUES
(3,	'26a01d73-b489-4e3f-befd-dbbf3d645700',	'AT-1',	1,	4,	10,	7,	2,	'2020-03-31 04:30:05',	'2020-03-31 04:32:05');

DROP TABLE IF EXISTS `job_departments`;
CREATE TABLE `job_departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_departments_guid_unique` (`guid`),
  KEY `job_departments_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_departments` (`id`, `guid`, `name`, `created_at`, `updated_at`) VALUES
(1,	'ffc537ff-9386-4fce-b0d7-d950dba6f60e',	'Agriculture, Food and Natural Resources',	'2020-03-27 02:59:34',	'2020-03-27 02:59:34'),
(2,	'ce34abb7-0b94-4587-9d5b-f2ca4e02fd98',	'Architecture and Construction',	'2020-03-27 02:59:41',	'2020-03-27 02:59:41'),
(3,	'a0dffdbc-3f80-4204-8d9c-bbb61305ca08',	'Information Technology',	'2020-03-27 03:00:00',	'2020-03-27 03:00:00'),
(4,	'a0688b1c-51d4-48bf-a58f-b4e1136e4dc0',	'Education and Training',	'2020-03-27 03:00:07',	'2020-03-27 03:00:07'),
(5,	'6b4b7b9b-701c-4d52-bb33-713d5f312460',	'Finance',	'2020-03-27 03:00:14',	'2020-03-27 03:00:18'),
(6,	'17a67753-e5b9-41be-9f53-92679d57f7b0',	'Health Science',	'2020-03-27 03:00:26',	'2020-03-27 03:00:26'),
(7,	'ab18192a-80fb-4877-9026-8d6ea9b4026d',	'Law, Public Safety, Corrections and Security',	'2020-03-27 03:00:34',	'2020-03-27 03:00:34'),
(8,	'46cde094-9556-4e39-ad89-6b55bb29cd44',	'Manufacturing',	'2020-03-27 03:00:41',	'2020-03-27 03:00:41');

DROP TABLE IF EXISTS `job_experience_levels`;
CREATE TABLE `job_experience_levels` (
  `job_id` bigint(20) unsigned DEFAULT NULL,
  `experience_level_id` bigint(20) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_experience_levels` (`job_id`, `experience_level_id`) VALUES
(1,	2),
(2,	2),
(2,	3),
(3,	3),
(1,	1),
(2,	4),
(2,	5),
(3,	5),
(4,	1),
(4,	2);

DROP TABLE IF EXISTS `job_qualifications`;
CREATE TABLE `job_qualifications` (
  `job_id` bigint(20) unsigned DEFAULT NULL,
  `qualification_id` bigint(20) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_qualifications` (`job_id`, `qualification_id`) VALUES
(2,	3),
(3,	3),
(1,	1),
(2,	1),
(4,	1);

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(15,	'2014_10_12_000000_create_users_table',	1),
(17,	'2016_06_01_000001_create_oauth_auth_codes_table',	1),
(18,	'2016_06_01_000002_create_oauth_access_tokens_table',	1),
(19,	'2016_06_01_000003_create_oauth_refresh_tokens_table',	1),
(20,	'2016_06_01_000004_create_oauth_clients_table',	1),
(21,	'2016_06_01_000005_create_oauth_personal_access_clients_table',	1),
(22,	'2014_10_12_100000_create_password_resets_table',	2),
(23,	'2020_03_02_135115_create_verify_users_table',	2),
(25,	'2020_03_05_113736_create_table_experience_levels_05_03_01',	3),
(26,	'2020_03_05_131244_create_table_qualifications_05_03_01',	4),
(27,	'2020_03_05_132943_create_table_job_departments_05_03_01',	5),
(28,	'2020_03_05_135638_create_table_interview_stages_05_03_01',	6),
(29,	'2020_03_07_072238_create_table_companies_07_03_01',	7),
(31,	'2020_03_07_100210_create_table_jobs_07_03_01',	8),
(41,	'2020_03_13_073723_create_table_job_applications_13_03_01',	9),
(42,	'2020_03_16_094237_create_table_interview_16_03_01',	9),
(43,	'2020_03_17_094237_create_table_document_type_17_03_01',	10),
(45,	'2020_03_17_103412_create_table_user_documents_17_03_01',	11);

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('05df2bff7619243d49aef2d59663491b09f142b39fff1e4025681d81a4d8f7ebc07bf2a447a475ed',	10,	1,	'rileyfarrellUDz@teleosaurs.xyz',	'[]',	0,	'2020-03-31 04:06:32',	'2020-03-31 04:06:32',	'2020-04-02 09:36:32'),
('2b18c59a3264f7e32c5531f10c7c053a81d9380d7dd95df9345facec60cf9a3178f6c3db67aca822',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-28 00:01:08',	'2020-03-28 00:01:08',	'2020-03-30 05:31:07'),
('3dc405c5889193c785c69ad6fbd01264e9c274a5cd7831fc057e2132be34c46e09008663a7705c41',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-27 01:51:19',	'2020-03-27 01:51:19',	'2020-03-29 07:21:19'),
('50e20f74d6bef07f37eb1a2fb065b33cfb8da2f805cdfc038a2800cbe5be49748c9f9b00d7999845',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-29 08:01:19',	'2020-03-29 08:01:19',	'2020-03-31 13:31:19'),
('527be37cde51ff6bde21198d6a0272568c0eb0460a70993e2623e77cbe31fa5310ee333335fc5735',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-29 08:19:59',	'2020-03-29 08:19:59',	'2020-03-31 13:49:59'),
('867dba17971958e6161cf737f9782f5b48c758950de8e42452b7bb36ed41570e432711281932a34d',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-29 08:20:00',	'2020-03-29 08:20:00',	'2020-03-31 13:49:59'),
('b5a8dfc0f15e143e99a0f7bc02a5f161ecc528764e2e97698bc001e4c9d1c213b568da3e16f5d528',	1,	1,	'avamccarthyK5Q@teleosaurs.xyz',	'[]',	0,	'2020-03-27 01:46:36',	'2020-03-27 01:46:36',	'2020-03-29 07:16:36'),
('baf3d97a19da75b438beecc0aed5c666950fadf29d801a756db80ec85bfa7b2a14fceaa9e1e6a6f8',	10,	1,	'rileyfarrellUDz@teleosaurs.xyz',	'[]',	0,	'2020-03-31 04:03:22',	'2020-03-31 04:03:22',	'2020-04-02 09:33:21'),
('d99222adecac0c3b48180ef638cebde3c45bd045bd62be40d155db8f91b643ba732254b78049ee67',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-28 04:25:49',	'2020-03-28 04:25:49',	'2020-03-30 09:55:49'),
('ef0f9aa497db340528677150c8eb34fc8b675ac737ae38d7bfc82895c4205aa9ddb277ea54f686b5',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-31 04:08:26',	'2020-03-31 04:08:26',	'2020-04-02 09:38:26'),
('f11b0a276a8a6c45049a0640a49ad8382df49ec5f1bfb4d4f90de15e71dba692dd0b28c68347990d',	1,	1,	'avamccarthyK5Q@teleosaurs.xyz',	'[]',	0,	'2020-03-27 01:52:56',	'2020-03-27 01:52:56',	'2020-03-29 07:22:56'),
('fd26c9862f0367277a839d4e852268f7122811d3e6ccdd123b75d12e093ec616e18fc75848c7cb28',	2,	1,	'admin@admin.com',	'[]',	0,	'2020-03-27 01:51:39',	'2020-03-27 01:51:39',	'2020-03-29 07:21:39');

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1,	NULL,	'Laravel Personal Access Client',	'8IA8pU2r56OQBoA8xhrWM8vhgafRkNx2jtUuXPu6',	'http://localhost',	1,	0,	0,	'2020-03-27 01:46:33',	'2020-03-27 01:46:33'),
(2,	NULL,	'Laravel Password Grant Client',	'SV9y1OzGsZwJs5eEvyma2YgefwIwoczLXrWuxR2X',	'http://localhost',	0,	1,	0,	'2020-03-27 01:46:33',	'2020-03-27 01:46:33');

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1,	1,	'2020-03-27 01:46:33',	'2020-03-27 01:46:33');

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`, `updated_at`) VALUES
(1,	'deanacobbWtV@teleosaurs.xyz',	'zI0h1o7XxPhh31CZNgjwuUtD2P0dL6YEwRdhEoBWH1wHOJmk6KzN25jrIY60',	'2020-03-31 03:30:42',	'2020-03-31 03:30:42');

DROP TABLE IF EXISTS `qualifications`;
CREATE TABLE `qualifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qualifications_guid_unique` (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `qualifications` (`id`, `guid`, `title`, `created_at`, `updated_at`) VALUES
(1,	'e1467b19-9799-4fdd-a41e-7d1e7acffba3',	"Bachelor's Degree",	'2020-03-27 02:12:19',	'2020-03-27 02:12:19'),
(2,	'9d758a22-edd2-4151-b1e0-6db65305db27',	'Honours Degree',	'2020-03-27 02:12:25',	'2020-03-27 02:12:25'),
(3,	'280c1234-37a9-41ec-baba-b6d9f3cfd630',	"Master's Degree",	'2020-03-27 02:12:35',	'2020-03-27 02:12:35'),
(4,	'58c70059-f76b-403c-8cbc-acb3d486045e',	'Doctoral Degree',	'2020-03-27 02:12:42',	'2020-03-27 02:12:42');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1.Admin 2.Candidate',
  `gender` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1.Male 2.Female',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_guid_unique` (`guid`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `guid`, `type`, `gender`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `birthday`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1,	'7bfd0182-c4e2-481c-ae9b-43c93bc0578f',	2,	1,	'Ava',	'Mccarthy',	'avamccarthyK5Q@teleosaurs.xyz',	'2020-03-27 01:35:43',	'$2y$10$yj.B5Mzzy.hO6.YDE.bYbOR.tClL9DfpczowTLqBQgi8KaiiTYIeC',	NULL,	'1994-11-11',	'2020-03-27 01:52:56',	'2020-03-27 01:28:40',	'2020-03-27 01:52:56'),
(2,	'7bfd0182-c4e2-481c-ae9b-43c93bc05796',	1,	1,	'Admin',	'Test',	'admin@admin.com',	'2020-03-27 01:35:43',	'$2y$10$yj.B5Mzzy.hO6.YDE.bYbOR.tClL9DfpczowTLqBQgi8KaiiTYIeC',	NULL,	'1994-11-11',	'2020-03-31 04:08:26',	'2020-03-27 01:28:40',	'2020-03-31 04:08:26'),
(10,	'16971f79-025f-45c7-8399-a2fd2b036835',	2,	1,	'Riley',	'Farrell',	'rileyfarrellUDz@teleosaurs.xyz',	'2020-03-31 04:03:09',	'$2y$10$oSS2R9dW2TvQHFWPphGtleeVjG4pmh3j0v/ck06flPIhwH3aU/59C',	NULL,	'2020-03-01',	'2020-03-31 04:06:32',	'2020-03-31 04:00:54',	'2020-03-31 04:06:32');

DROP TABLE IF EXISTS `user_documents`;
CREATE TABLE `user_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` bigint(20) unsigned DEFAULT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `object_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mimetype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_documents_guid_unique` (`guid`),
  KEY `user_documents_object_type_object_id_index` (`object_type`,`object_id`),
  KEY `user_documents_id_index` (`id`),
  KEY `user_documents_document_type_id_index` (`document_type_id`),
  KEY `user_documents_creator_id_index` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_documents` (`id`, `guid`, `document_type_id`, `creator_id`, `object_type`, `object_id`, `name`, `disk`, `path`, `filename`, `mimetype`, `size`, `header`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7,	'5bd7298b-90b4-40ce-bf0d-9bdadb61d719',	1,	10,	'user',	10,	'1585648489-ZjgCW8ITDz.jpg',	'public',	'1585648489-ZjgCW8ITDz.jpg',	'cv_format.jpg',	'image/jpeg',	'229827',	'image/jpeg',	'2020-03-31 04:24:49',	'2020-03-31 04:24:49',	NULL);

DROP TABLE IF EXISTS `verify_users`;
CREATE TABLE `verify_users` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `verify_users` (`email`, `token`, `created_at`, `updated_at`) VALUES
('rileyfarrellUDz@teleosaurs.xyz',	'QLdIAdrRq6pfYRG5IcTTV71EIjgQ2QZq2yFvmnCSzcAfZFlaW6LY4zosEwwAEy1MVrB0yX',	'2020-03-31 04:00:54',	'2020-03-31 04:00:54');

-- 2020-03-31 10:35:14
