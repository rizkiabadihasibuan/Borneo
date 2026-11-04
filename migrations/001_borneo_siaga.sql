-- ============================================================
-- Borneo Siaga — Database Schema & Seed Data
-- Production-ready SQL untuk impor ke MySQL hosting
--
-- Audited: 2026-11-04
-- Perubahan dari dump phpMyAdmin asli:
--   1. Hapus INSERT data sementara dari tabel `cache`
--      dan `sessions` (struktur tabel tetap dipertahankan)
--   2. Tidak ada INSERT untuk `cache_locks` (memang kosong)
--   3. Koreksi sintaks MariaDB → MySQL standard:
--      - Hapus CHECK (json_valid(...)) yang tidak didukung
--        MySQL < 8.0.16 pada beberapa hosting shared
--      - Ganti SET SQL_MODE legacy dengan versi lebih portabel
--   4. Tambah SET FOREIGN_KEY_CHECKS = 0/1 untuk keamanan impor
--   5. Urutan pembuatan tabel dipastikan:
--      users → report_recipients → reports
--      → report_dispatches → report_dispatch_histories
--      → report_status_histories (semua FK terpenuhi)
--   6. Semua data utama dipertahankan:
--      users (1 admin), reports (4 laporan),
--      report_recipients (5 penerima),
--      report_status_histories (6 riwayat)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================================
-- URUTAN PEMBUATAN TABEL (foreign key safe):
--   1. users
--   2. report_recipients
--   3. reports
--   4. report_dispatches        (FK → users, report_recipients, reports)
--   5. report_dispatch_histories (FK → users, report_dispatches)
--   6. report_status_histories  (FK → users, reports)
--   7. Tabel independen: cache, cache_locks, failed_jobs, jobs,
--      job_batches, migrations, password_reset_tokens, sessions,
--      whatsapp_webhook_logs
-- ============================================================

-- --------------------------------------------------------
-- Tabel: `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id`                bigint(20) UNSIGNED NOT NULL,
  `name`              varchar(255) NOT NULL,
  `email`             varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password`          varchar(255) NOT NULL,
  `is_admin`          tinyint(1) NOT NULL DEFAULT 0,
  `remember_token`    varchar(100) DEFAULT NULL,
  `created_at`        timestamp NULL DEFAULT NULL,
  `updated_at`        timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Posko', 'admin@borneosiaga.id', NULL, '$2y$12$7WzvLqowi2Y6BeXtaJY/2Oeb7KM9p7XjBuxTgmvG04.QSl/2f8kKu', 1, 'Tf2o1DTpax3zB033VnBpR4AJbhIeDnCQleT4l2F4NfheBa3u2eQbo6L1fyUS', '2026-10-27 06:48:21', '2026-10-27 06:48:21');

-- --------------------------------------------------------
-- Tabel: `report_recipients`
-- --------------------------------------------------------

CREATE TABLE `report_recipients` (
  `id`                bigint(20) UNSIGNED NOT NULL,
  `name`              varchar(255) NOT NULL,
  `category`          enum('pemerintah','komunitas_mpa') NOT NULL,
  `agency_type`       varchar(255) NOT NULL,
  `province`          varchar(255) NOT NULL,
  `regency`           varchar(255) DEFAULT NULL,
  `contact_person`    varchar(255) DEFAULT NULL,
  `phone`             varchar(255) NOT NULL,
  `whatsapp_number`   varchar(50) DEFAULT NULL,
  `email`             varchar(255) DEFAULT NULL,
  `address`           text DEFAULT NULL,
  `admin_notes`       text DEFAULT NULL,
  `preferred_channel` varchar(32) NOT NULL DEFAULT 'email',
  `verified_channels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `is_active`         tinyint(1) NOT NULL DEFAULT 1,
  `created_at`        timestamp NULL DEFAULT NULL,
  `updated_at`        timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `report_recipients` (`id`, `name`, `category`, `agency_type`, `province`, `regency`, `contact_person`, `phone`, `whatsapp_number`, `email`, `address`, `admin_notes`, `preferred_channel`, `verified_channels`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BPBD Provinsi Kalimantan Barat',     'pemerintah',   'BPBD', 'Kalimantan Barat',   'Kota Pontianak',     'Pusdalops BPBD Kalbar',         '0561734567',    '081234567801', 'pusdalops.kalbar@bpbd.go.id',   'Jl. Adi Sucipto No. 1, Pontianak',           NULL, 'both', '["whatsapp","email"]', 1, '2026-11-02 08:31:30', '2026-11-02 08:31:30'),
(2, 'BPBD Provinsi Kalimantan Tengah',    'pemerintah',   'BPBD', 'Kalimantan Tengah',  'Kota Palangka Raya', 'Posko Karhutla Kalteng',        '05363221234',   '081234567802', 'posko.kalteng@bpbd.go.id',      'Jl. Tjilik Riwut Km. 1, Palangka Raya',      NULL, 'both', '["whatsapp","email"]', 1, '2026-11-02 08:31:30', '2026-11-02 08:31:30'),
(3, 'BPBD Provinsi Kalimantan Selatan',   'pemerintah',   'BPBD', 'Kalimantan Selatan', 'Kota Banjarbaru',    'Pusdalops BPBD Kalsel',         '05114772345',   '081234567803', 'bpbd@kalselprov.go.id',         'Jl. Dharma Praja No. 2, Banjarbaru',         NULL, 'both', '["whatsapp","email"]', 1, '2026-11-02 08:31:30', '2026-11-02 08:31:30'),
(4, 'BPBD Provinsi Kalimantan Timur',     'pemerintah',   'BPBD', 'Kalimantan Timur',   'Kota Samarinda',     'Tim Siaga Bencana Kaltim',      '0541743210',    '081234567804', 'bpbd@kaltimprov.go.id',         'Jl. Jenderal Sudirman No. 1, Samarinda',     NULL, 'both', '["whatsapp","email"]', 1, '2026-11-02 08:31:30', '2026-11-02 08:31:30'),
(5, 'Masyarakat Peduli Api (MPA) Sebangau', 'komunitas_mpa', 'MPA', 'Kalimantan Tengah', 'Kota Palangka Raya', 'Koordinator Relawan Sebangau', '085249001122', '085249001122', 'relawan.sebangau@borneosiaga.id', 'Desa Kereng Bangkirai, Sebangau',            NULL, 'whatsapp', '["whatsapp"]', 1, '2026-11-02 08:31:30', '2026-11-02 08:31:30');

-- --------------------------------------------------------
-- Tabel: `reports`
-- --------------------------------------------------------

CREATE TABLE `reports` (
  `id`                bigint(20) UNSIGNED NOT NULL,
  `ticket_number`     varchar(32) NOT NULL,
  `access_code_hash`  varchar(255) NOT NULL,
  `reporter_name`     varchar(100) NOT NULL,
  `reporter_phone`    varchar(50) NOT NULL,
  `province_code`     varchar(10) DEFAULT NULL,
  `province_name`     varchar(100) DEFAULT NULL,
  `regency_code`      varchar(10) DEFAULT NULL,
  `regency_name`      varchar(100) DEFAULT NULL,
  `district_code`     varchar(10) DEFAULT NULL,
  `district_name`     varchar(100) DEFAULT NULL,
  `village_code`      varchar(10) DEFAULT NULL,
  `village_name`      varchar(100) DEFAULT NULL,
  `location_detail`   varchar(255) DEFAULT NULL,
  `province`          varchar(100) NOT NULL,
  `regency`           varchar(100) NOT NULL,
  `district`          varchar(100) NOT NULL,
  `village`           varchar(100) NOT NULL,
  `incident_location` varchar(255) NOT NULL,
  `latitude`          decimal(10,7) DEFAULT NULL,
  `longitude`         decimal(10,7) DEFAULT NULL,
  `incident_time`     datetime NOT NULL,
  `incident_type`     varchar(100) NOT NULL,
  `description`       text NOT NULL,
  `photo_path`        varchar(255) DEFAULT NULL,
  `status`            varchar(32) NOT NULL DEFAULT 'Menunggu Verifikasi',
  `created_at`        timestamp NULL DEFAULT NULL,
  `updated_at`        timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reports` (`id`, `ticket_number`, `access_code_hash`, `reporter_name`, `reporter_phone`, `province_code`, `province_name`, `regency_code`, `regency_name`, `district_code`, `district_name`, `village_code`, `village_name`, `location_detail`, `province`, `regency`, `district`, `village`, `incident_location`, `latitude`, `longitude`, `incident_time`, `incident_type`, `description`, `photo_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BS-20261027-0001', '$2y$12$hovmX3/kmX9r0LHtxTXydO6RU/9uQUkixaicA/2Dpal/U1YEwDnVq', 'iky',                  '08227730776',  NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kalimantan Barat',   'kab dua',              'kec tiga',           'desa gubuk',      'desa kakaa kajamdam ksjian',   5.2284090, 97.0167740, '2026-10-27 12:21:00', 'Kebakaran Dekat Permukiman',          'nkneiwijdwidnlnskhsssmsjshs mscksjosjwgsvan cckdckabc x c,masjsah', NULL,                                                  'Selesai Ditangani',     '2026-10-27 05:22:57', '2026-10-27 08:51:45'),
(2, 'BS-20261027-0002', '$2y$12$toQLCYRML8witZJwjYQ34.OQKUYSXxiEMAufdbM5XZL6AP/v/gRri', 'Budi',                 '082145621236', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kalimantan Timur',   'Kabupaten Gunung',     'Kecamatan Dewantara','Desa Simangambat', 'Jalan medan Banda Aceh',       5.2283790, 97.0168380, '2026-10-27 16:23:00', 'Kebakaran Semak Belukar / Alang-alang','tadi ada api besar di dekat permukiman',                             'reports/e9ecdbf1-7bf1-4023-8c9f-bd0a7ec11dcc.png', 'Menunggu Verifikasi',   '2026-10-27 09:26:15', '2026-10-27 09:26:15'),
(3, 'BS-20261029-0001', '$2y$12$4UW16pjK8WO4rJ4/0OZ0CuEGCUs6QhsxYk7AKPJBdSZKejjEeSJ2y', 'rizki',               '0812345678',   NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kalimantan Selatan', 'Kabupaten Gunung',     'Kecamatan Dewantara','desa satu',       'jalan medan banda aceh',       5.2217680, 97.0492730, '2026-10-29 12:49:00', 'Kebakaran Semak Belukar / Alang-alang','kebakaran terjadi di dekt pemukiman warga',                          NULL,                                                  'Selesai Ditangani',     '2026-10-29 05:51:26', '2026-10-29 05:52:08'),
(4, 'BS-20261104-0001', '$2y$12$OTFGoj8MNB4yr/plpJfrxuPqNwDpBnbThAC6jXRwwKM021Q84UDIi', 'RIZKI ABADI HASIBUAN', '082276730776', '62', 'Kalimantan Tengah', '6271', 'Kota Palangka Raya', '6271020', 'Bukit Batu', '6271020007', 'Habaring Hurung', 'jalan kota palangka raya', 'Kalimantan Tengah', 'Kota Palangka Raya', 'Bukit Batu', 'Habaring Hurung', 'jalan kota palangka raya', 5.2284710, 97.0168680, '2026-11-04 10:31:00', 'Kebakaran Semak Belukar / Alang-alang', 'kondisinya mulai semakin parah', NULL, 'Menunggu Verifikasi', '2026-11-04 03:32:35', '2026-11-04 03:32:35');

-- --------------------------------------------------------
-- Tabel: `report_dispatches`
-- (Tidak ada data — kosong saat dump)
-- --------------------------------------------------------

CREATE TABLE `report_dispatches` (
  `id`                       bigint(20) UNSIGNED NOT NULL,
  `report_id`                bigint(20) UNSIGNED NOT NULL,
  `recipient_id`             bigint(20) UNSIGNED NOT NULL,
  `admin_id`                 bigint(20) UNSIGNED DEFAULT NULL,
  `channel`                  varchar(32) NOT NULL DEFAULT 'email',
  `dispatch_notes`           text NOT NULL,
  `include_photo`            tinyint(1) NOT NULL DEFAULT 0,
  `include_reporter_contact` tinyint(1) NOT NULL DEFAULT 0,
  `status`                   varchar(32) NOT NULL DEFAULT 'Menunggu Pengiriman',
  `retry_count`              int(10) UNSIGNED NOT NULL DEFAULT 0,
  `recipient_status`         varchar(32) NOT NULL DEFAULT 'Menunggu Konfirmasi Penerima',
  `reference_id`             varchar(255) DEFAULT NULL,
  `provider_message_id`      varchar(150) DEFAULT NULL,
  `delivery_error`           text DEFAULT NULL,
  `recipient_feedback`       text DEFAULT NULL,
  `sent_at`                  timestamp NULL DEFAULT NULL,
  `delivered_at`             timestamp NULL DEFAULT NULL,
  `read_at`                  timestamp NULL DEFAULT NULL,
  `confirmed_at`             timestamp NULL DEFAULT NULL,
  `completed_at`             timestamp NULL DEFAULT NULL,
  `created_at`               timestamp NULL DEFAULT NULL,
  `updated_at`               timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabel: `report_dispatch_histories`
-- (Tidak ada data — kosong saat dump)
-- --------------------------------------------------------

CREATE TABLE `report_dispatch_histories` (
  `id`                   bigint(20) UNSIGNED NOT NULL,
  `report_dispatch_id`   bigint(20) UNSIGNED NOT NULL,
  `admin_id`             bigint(20) UNSIGNED DEFAULT NULL,
  `previous_status`      varchar(32) DEFAULT NULL,
  `new_status`           varchar(32) NOT NULL,
  `notes`                text DEFAULT NULL,
  `created_at`           timestamp NULL DEFAULT NULL,
  `updated_at`           timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabel: `report_status_histories`
-- --------------------------------------------------------

CREATE TABLE `report_status_histories` (
  `id`               bigint(20) UNSIGNED NOT NULL,
  `report_id`        bigint(20) UNSIGNED NOT NULL,
  `previous_status`  varchar(32) DEFAULT NULL,
  `new_status`       varchar(32) NOT NULL,
  `notes`            text DEFAULT NULL,
  `admin_id`         bigint(20) UNSIGNED DEFAULT NULL,
  `created_at`       timestamp NULL DEFAULT NULL,
  `updated_at`       timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `report_status_histories` (`id`, `report_id`, `previous_status`, `new_status`, `notes`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL,                   'Menunggu Verifikasi',  'Laporan pengaduan masyarakat berhasil diterima sistem dan sedang menunggu verifikasi tim posko siaga.', NULL, '2026-10-27 05:22:57', '2026-10-27 05:22:57'),
(2, 1, 'Menunggu Verifikasi',  'Selesai Ditangani',    'selesai dilaksanakan',                                                                                  1,    '2026-10-27 06:49:47', '2026-10-27 06:49:47'),
(3, 2, NULL,                   'Menunggu Verifikasi',  'Laporan pengaduan masyarakat berhasil diterima sistem dan sedang menunggu verifikasi tim posko siaga.', NULL, '2026-10-27 09:26:15', '2026-10-27 09:26:15'),
(4, 3, NULL,                   'Menunggu Verifikasi',  'Laporan pengaduan masyarakat berhasil diterima sistem dan sedang menunggu verifikasi tim posko siaga.', NULL, '2026-10-29 05:51:26', '2026-10-29 05:51:26'),
(5, 3, 'Menunggu Verifikasi',  'Selesai Ditangani',    'selesai petugas ke lapangan',                                                                           1,    '2026-10-29 05:52:08', '2026-10-29 05:52:08'),
(6, 4, NULL,                   'Menunggu Verifikasi',  'Laporan pengaduan masyarakat berhasil diterima sistem dan sedang menunggu verifikasi tim posko siaga.', NULL, '2026-11-04 03:32:35', '2026-11-04 03:32:35');

-- --------------------------------------------------------
-- Tabel: `whatsapp_webhook_logs`
-- (Tidak ada data — kosong saat dump)
-- --------------------------------------------------------

CREATE TABLE `whatsapp_webhook_logs` (
  `id`               bigint(20) UNSIGNED NOT NULL,
  `message_id`       varchar(150) DEFAULT NULL,
  `recipient_id`     varchar(50) DEFAULT NULL,
  `event_status`     varchar(50) NOT NULL,
  `idempotency_key`  varchar(200) NOT NULL,
  `raw_payload`      longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `is_processed`     tinyint(1) NOT NULL DEFAULT 0,
  `created_at`       timestamp NULL DEFAULT NULL,
  `updated_at`       timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabel-tabel sistem Laravel (independen, tanpa FK)
-- --------------------------------------------------------

CREATE TABLE `cache` (
  `key`        varchar(255) NOT NULL,
  `value`      mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Catatan: Data cache sementara dihapus (akan diisi ulang saat runtime)

CREATE TABLE `cache_locks` (
  `key`        varchar(255) NOT NULL,
  `owner`      varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id`         bigint(20) UNSIGNED NOT NULL,
  `uuid`       varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue`      text NOT NULL,
  `payload`    longtext NOT NULL,
  `exception`  longtext NOT NULL,
  `failed_at`  timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id`           bigint(20) UNSIGNED NOT NULL,
  `queue`        varchar(255) NOT NULL,
  `payload`      longtext NOT NULL,
  `attempts`     tinyint(3) UNSIGNED NOT NULL,
  `reserved_at`  int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at`   int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id`             varchar(255) NOT NULL,
  `name`           varchar(255) NOT NULL,
  `total_jobs`     int(11) NOT NULL,
  `pending_jobs`   int(11) NOT NULL,
  `failed_jobs`    int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options`        mediumtext DEFAULT NULL,
  `cancelled_at`   int(11) DEFAULT NULL,
  `created_at`     int(11) NOT NULL,
  `finished_at`    int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id`        int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch`     int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,  '0001_01_01_000000_create_users_table',                                                        1),
(2,  '0001_01_01_000001_create_cache_table',                                                        1),
(3,  '0001_01_01_000002_create_jobs_table',                                                         1),
(4,  '2026_10_27_000001_create_reports_table',                                                      1),
(5,  '2026_10_27_000002_create_report_status_histories_table',                                      1),
(6,  '2026_10_27_000003_add_reporter_and_detailed_location_to_reports_table',                       2),
(7,  '2026_10_27_000004_add_is_admin_to_users_table',                                               3),
(8,  '2026_10_27_000005_create_report_recipients_and_dispatches_tables',                            4),
(9,  '2026_10_27_000006_enhance_recipients_and_dispatches_for_whatsapp_and_channels',              5),
(10, '2026_10_27_000007_change_preferred_channel_in_report_recipients',                             6),
(11, '2026_11_03_000001_add_administrative_location_codes_to_reports_table',                        7);

CREATE TABLE `password_reset_tokens` (
  `email`      varchar(255) NOT NULL,
  `token`      varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id`            varchar(255) NOT NULL,
  `user_id`       bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address`    varchar(45) DEFAULT NULL,
  `user_agent`    text DEFAULT NULL,
  `payload`       longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Catatan: Data sesi lokal dihapus (akan dibuat ulang saat login production)

-- ============================================================
-- PRIMARY KEYS, INDEKS, DAN AUTO_INCREMENT
-- ============================================================

ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reports_ticket_number_unique` (`ticket_number`),
  ADD KEY `reports_created_at_status_index` (`created_at`, `status`),
  ADD KEY `reports_status_index` (`status`);

ALTER TABLE `report_dispatches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_dispatches_recipient_id_foreign` (`recipient_id`),
  ADD KEY `report_dispatches_admin_id_foreign` (`admin_id`),
  ADD KEY `report_dispatches_report_id_recipient_id_status_index` (`report_id`, `recipient_id`, `status`),
  ADD KEY `report_dispatches_provider_message_id_index` (`provider_message_id`);

ALTER TABLE `report_dispatch_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_dispatch_histories_admin_id_foreign` (`admin_id`),
  ADD KEY `report_dispatch_histories_report_dispatch_id_created_at_index` (`report_dispatch_id`, `created_at`);

ALTER TABLE `report_recipients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_recipients_category_province_is_active_index` (`category`, `province`, `is_active`);

ALTER TABLE `report_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_status_histories_admin_id_foreign` (`admin_id`),
  ADD KEY `report_status_histories_report_id_created_at_index` (`report_id`, `created_at`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

ALTER TABLE `whatsapp_webhook_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `whatsapp_webhook_logs_idempotency_key_unique` (`idempotency_key`),
  ADD KEY `whatsapp_webhook_logs_message_id_index` (`message_id`);

-- ============================================================
-- AUTO_INCREMENT
-- ============================================================

ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `report_dispatches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `report_dispatch_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `report_recipients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `report_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `whatsapp_webhook_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- ============================================================
-- FOREIGN KEY CONSTRAINTS
-- (Dibuat setelah semua tabel dan PRIMARY KEY terdefinisi)
-- ============================================================

ALTER TABLE `report_dispatches`
  ADD CONSTRAINT `report_dispatches_admin_id_foreign`
    FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `report_dispatches_recipient_id_foreign`
    FOREIGN KEY (`recipient_id`) REFERENCES `report_recipients` (`id`),
  ADD CONSTRAINT `report_dispatches_report_id_foreign`
    FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

ALTER TABLE `report_dispatch_histories`
  ADD CONSTRAINT `report_dispatch_histories_admin_id_foreign`
    FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `report_dispatch_histories_report_dispatch_id_foreign`
    FOREIGN KEY (`report_dispatch_id`) REFERENCES `report_dispatches` (`id`) ON DELETE CASCADE;

ALTER TABLE `report_status_histories`
  ADD CONSTRAINT `report_status_histories_admin_id_foreign`
    FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `report_status_histories_report_id_foreign`
    FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
