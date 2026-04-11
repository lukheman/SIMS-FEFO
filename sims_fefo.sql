/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: sims_fefo
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `cache` VALUES
('da4b9237bacccdf19c0760cab7aec4a8359010b0','i:1;',1775539721),
('da4b9237bacccdf19c0760cab7aec4a8359010b0:timer','i:1775539721;',1775539721);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `keranjang_belanja`
--

DROP TABLE IF EXISTS `keranjang_belanja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `keranjang_belanja` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_reseller` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keranjang_belanja_id_reseller_foreign` (`id_reseller`),
  CONSTRAINT `keranjang_belanja_id_reseller_foreign` FOREIGN KEY (`id_reseller`) REFERENCES `reseller` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keranjang_belanja`
--

LOCK TABLES `keranjang_belanja` WRITE;
/*!40000 ALTER TABLE `keranjang_belanja` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `keranjang_belanja` VALUES
(1,1,'2026-03-07 05:52:42','2026-03-07 05:52:42'),
(2,2,'2026-03-07 05:52:42','2026-03-07 05:52:42');
/*!40000 ALTER TABLE `keranjang_belanja` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000001_create_cache_table',1),
(2,'0001_01_01_000002_create_jobs_table',1),
(3,'2025_02_16_041446_create_users_table',1),
(4,'2025_02_16_050305_create_sessions_table',1),
(5,'2025_02_16_092346_create_produk_table',1),
(6,'2025_03_03_065359_create_mutasi_table',1),
(7,'2025_04_30_124735_create_restock_table',1),
(8,'2025_05_07_155322_create_persediaan_table',1),
(9,'2025_06_24_060545_create_reseller_table',1),
(10,'2025_06_25_085349_create_keranjang_table',1),
(11,'2025_06_25_321818_create_transaksi_table',1),
(12,'2025_06_25_443406_create_pesanan_table',1),
(13,'2025_07_01_000001_add_exp_columns_to_persediaan_table',2),
(14,'2025_07_01_000002_add_batch_id_to_mutasi_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `mutasi`
--

DROP TABLE IF EXISTS `mutasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mutasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produk` bigint(20) unsigned NOT NULL,
  `id_persediaan` bigint(20) unsigned DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL DEFAULT '2025-10-07',
  `jenis` enum('masuk','keluar') NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `satuan` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_id_produk_foreign` (`id_produk`),
  KEY `mutasi_id_persediaan_foreign` (`id_persediaan`),
  CONSTRAINT `mutasi_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mutasi_id_persediaan_foreign` FOREIGN KEY (`id_persediaan`) REFERENCES `persediaan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mutasi`
--

LOCK TABLES `mutasi` WRITE;
/*!40000 ALTER TABLE `mutasi` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `mutasi` VALUES
(1,1,NULL,1,'2026-03-06','masuk',NULL,0,'2026-03-15 07:01:00','2026-03-15 07:01:00'),
(2,5,1,1,'2025-10-07','masuk',NULL,0,'2026-04-06 21:29:46','2026-04-06 21:29:46');
/*!40000 ALTER TABLE `mutasi` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `persediaan`
--

DROP TABLE IF EXISTS `persediaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `persediaan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produk` bigint(20) unsigned NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `tanggal_exp` date DEFAULT NULL,
  `tanggal_masuk` date NOT NULL DEFAULT '2026-03-30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `persediaan_id_produk_foreign` (`id_produk`),
  CONSTRAINT `persediaan_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persediaan`
--

LOCK TABLES `persediaan` WRITE;
/*!40000 ALTER TABLE `persediaan` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `persediaan` VALUES
(1,5,1,'2026-04-08','2026-04-07','2026-04-06 21:29:46','2026-04-06 21:29:46');
/*!40000 ALTER TABLE `persediaan` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produk` bigint(20) unsigned NOT NULL,
  `id_reseller` bigint(20) unsigned DEFAULT NULL,
  `id_transaksi` bigint(20) unsigned DEFAULT NULL,
  `id_keranjang_belanja` bigint(20) unsigned DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `satuan` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_id_produk_foreign` (`id_produk`),
  KEY `pesanan_id_reseller_foreign` (`id_reseller`),
  KEY `pesanan_id_transaksi_foreign` (`id_transaksi`),
  KEY `pesanan_id_keranjang_belanja_foreign` (`id_keranjang_belanja`),
  CONSTRAINT `pesanan_id_keranjang_belanja_foreign` FOREIGN KEY (`id_keranjang_belanja`) REFERENCES `keranjang_belanja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_id_reseller_foreign` FOREIGN KEY (`id_reseller`) REFERENCES `reseller` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `pesanan` VALUES
(1,1,1,NULL,1,1,1,'2026-03-29 06:47:50','2026-03-29 06:47:50');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(100) NOT NULL,
  `kode_produk` varchar(20) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `lead_time` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `exp` date DEFAULT NULL,
  `harga_jual_unit_kecil` decimal(10,2) NOT NULL,
  `tingkat_konversi` int(11) NOT NULL DEFAULT 0,
  `unit_kecil` enum('pcs','botol','kilogram') NOT NULL,
  `unit_besar` enum('dos','bal','pack','renteng') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `produk_kode_produk_unique` (`kode_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `produk` VALUES
(1,'Minyak Goreng  ','8998866501033',228000.00,25000.00,20,'Minyak Goreng Sawit Sedaap','C:\\Users\\LENOVO\\AppData\\Local\\Temp\\php7F9B.tmp','2030-12-20',23000.00,6,'pcs','dos','2026-03-15 06:55:20','2026-03-15 07:00:33'),
(2,'NoMos Biru','1922422300089',4000.00,5000.00,5,'','C:\\Users\\LENOVO\\AppData\\Local\\Temp\\php59A1.tmp','2027-01-01',5000.00,60,'pcs','dos','2026-03-29 06:32:03','2026-03-29 06:32:59'),
(3,'MILKU','5518158172548',35000.00,5000.00,0,'MILKU RASA COKLAT ','C:\\Users\\LENOVO\\AppData\\Local\\Temp\\phpBC94.tmp','2026-08-25',5000.00,12,'botol','bal','2026-03-29 06:36:28','2026-03-29 06:36:28'),
(4,'Golda','18998866201848',35000.00,5000.00,0,'','C:\\Users\\LENOVO\\AppData\\Local\\Temp\\phpC554.tmp',NULL,5000.00,12,'botol','bal','2026-03-29 06:42:30','2026-03-29 06:42:30'),
(5,'test','5285000390596',10000.00,10000.00,5,'fads','/tmp/php96j55ijkstie4yTaaNp',NULL,10000.00,1,'pcs','bal','2026-04-06 21:27:58','2026-04-06 21:27:58');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `reseller`
--

DROP TABLE IF EXISTS `reseller`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reseller` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('reseller') NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reseller_email_unique` (`email`),
  UNIQUE KEY `reseller_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reseller`
--

LOCK TABLES `reseller` WRITE;
/*!40000 ALTER TABLE `reseller` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `reseller` VALUES
(1,'reseller1@gmail.com','Reseller 1','$2y$12$/To4Iwqe2DC70ZCJf3WiSekUG9CNvy.3KSS8pJ8u9lhDh0aVyc97S','reseller',NULL,NULL,NULL,'2026-03-07 05:52:42','2026-03-07 05:52:42'),
(2,'reseller2@gmail.com','Reseller 2','$2y$12$QqmsWR1SdcWs7yEXIZoj2uNI45RHc7t.Ab/1ixx6m.cbJUgCEBi3S','reseller',NULL,NULL,NULL,'2026-03-07 05:52:42','2026-03-07 05:52:42');
/*!40000 ALTER TABLE `reseller` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `restock`
--

DROP TABLE IF EXISTS `restock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `restock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produk` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restock_id_produk_foreign` (`id_produk`),
  CONSTRAINT `restock_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restock`
--

LOCK TABLES `restock` WRITE;
/*!40000 ALTER TABLE `restock` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `restock` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions` VALUES
('UfDPE03h2Wj1y26wqZvjpJgjCKx0P18FMkfhaOJU',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHdnejdONXc2RXlob290aFdqRWFCZGozS2FRNHFuOUhSRjFVRzZuRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbmd1ZGFuZy9iYXJhbmctbWFzdWsiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1775539786);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_reseller` bigint(20) unsigned DEFAULT NULL,
  `id_kurir` bigint(20) unsigned DEFAULT NULL,
  `status` enum('pending','diproses','dikirim','ditolak','diterima','selesai','batal') NOT NULL DEFAULT 'pending',
  `status_pembayaran` enum('belum_bayar','lunas') NOT NULL DEFAULT 'belum_bayar',
  `metode_pembayaran` enum('cod','transfer','tunai') NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_id_reseller_foreign` (`id_reseller`),
  KEY `transaksi_id_kurir_foreign` (`id_kurir`),
  CONSTRAINT `transaksi_id_kurir_foreign` FOREIGN KEY (`id_kurir`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_id_reseller_foreign` FOREIGN KEY (`id_reseller`) REFERENCES `reseller` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `transaksi` VALUES
(1,1,4,'dikirim','lunas','transfer',NULL,'2026-03-28 23:48:13','2026-03-29 06:48:13','2026-03-29 06:50:33');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('kasir','admingudang','pimpinan','reseller','kurir') NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'kasir@gmail.com','Kasir','$2y$12$BQetf9vJ4ZzOLNYGPiIDZelAPfo1BvtMEwRxYYaDG3UEfX.JC4q7i','kasir','082020543045',NULL,NULL,NULL,NULL),
(2,'admingudang@gmail.com','admin gudang','$2y$12$uGi5Ga/Ks8ZKvc.jMfT6tOEWod4QSINoROBwCWn6QE/vfOD86l1Si','admingudang','082062918286',NULL,NULL,NULL,NULL),
(3,'pimpinan@gmail.com','pimpinan','$2y$12$pc/fmOsgQamGl9JhfBW6xujFwqbBwLbvBk4TRfd22E1lWpOMSMWy2','pimpinan','082033212573',NULL,NULL,NULL,NULL),
(4,'kurir1@gmail.com','Kurir 1','$2y$12$zNgQxi4OItSq9mbwwPfBC.a7ikR6HqZ5nepRazNSNRycQl7VhrYta','kurir','082083470021',NULL,NULL,NULL,NULL),
(5,'kurir2@gmail.com','Kurir 2','$2y$12$UKI7gVZAuf6R.lEocEqtSuNzz9z6KlgLiWglXmTPHyl3ZW6Xe4D8u','kurir','082082150810',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-04-07 13:46:50
