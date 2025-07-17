/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - sultanwedding
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sultanwedding` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `sultanwedding`;

/*Table structure for table `barang` */

DROP TABLE IF EXISTS `barang`;

CREATE TABLE `barang` (
  `kdbarang` int unsigned NOT NULL AUTO_INCREMENT,
  `namabarang` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `hargasewa` decimal(15,2) NOT NULL DEFAULT '0.00',
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdbarang`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `barang` */

insert  into `barang`(`kdbarang`,`namabarang`,`satuan`,`jumlah`,`hargasewa`,`foto`,`created_at`,`updated_at`) values 
(10,'Kursi Plastik + Cover','Lusin',100,50000.00,'1752571978_2b603f20e189c38564e3.webp','2025-07-15 09:22:26','2025-07-15 09:32:58'),
(11,'Meja Bundar','Unit',100,75000.00,'1752579501_545d9c229ed1f98dd34d.webp','2025-07-15 09:22:26','2025-07-15 13:09:06'),
(12,'Karpet Merah','Meter',50,25000.00,'1752579562_ff3951ad87c3e5900e99.jpg','2025-07-15 09:22:26','2025-07-17 12:59:36'),
(13,'Kotak Tamu','Unit',10,100000.00,'1752579619_0d3bb4291f522dcdce2b.jpg','2025-07-15 09:22:26','2025-07-15 13:09:06'),
(15,'Lighting','Set',8,1500000.00,'1752579830_89dc7ee8238c19ad31cd.avif','2025-07-15 09:22:26','2025-07-15 11:43:50'),
(16,'Sound System','Set',3,2000000.00,'1752579856_96e5dcb552ae96e459a5.jpg','2025-07-15 09:22:26','2025-07-15 11:44:16'),
(18,'Kamera DSLR + Videografer','Set',2,3000000.00,'1752579920_6e6a0d4eca66885a8fa4.jpg','2025-07-15 09:22:26','2025-07-17 12:59:36');

/*Table structure for table `detail_paket` */

DROP TABLE IF EXISTS `detail_paket`;

CREATE TABLE `detail_paket` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `kdpaket` int unsigned NOT NULL,
  `kdbarang` int unsigned NOT NULL,
  `jumlah` int unsigned NOT NULL DEFAULT '1',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_paket_kdpaket_foreign` (`kdpaket`),
  KEY `detail_paket_kdbarang_foreign` (`kdbarang`),
  CONSTRAINT `detail_paket_kdbarang_foreign` FOREIGN KEY (`kdbarang`) REFERENCES `barang` (`kdbarang`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_paket_kdpaket_foreign` FOREIGN KEY (`kdpaket`) REFERENCES `paket` (`kdpaket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detail_paket` */

/*Table structure for table `detailpemesananbarang` */

DROP TABLE IF EXISTS `detailpemesananbarang`;

CREATE TABLE `detailpemesananbarang` (
  `kddetailpemesananbarang` int unsigned NOT NULL AUTO_INCREMENT,
  `kdpemesananbarang` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `kdbarang` int unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kddetailpemesananbarang`),
  KEY `detailpemesananbarang_kdbarang_foreign` (`kdbarang`),
  KEY `detailpemesananbarang_kdpemesananbarang_foreign` (`kdpemesananbarang`),
  CONSTRAINT `detailpemesananbarang_kdbarang_foreign` FOREIGN KEY (`kdbarang`) REFERENCES `barang` (`kdbarang`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detailpemesananbarang_kdpemesananbarang_foreign` FOREIGN KEY (`kdpemesananbarang`) REFERENCES `pemesananbarang` (`kdpemesananbarang`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detailpemesananbarang` */

insert  into `detailpemesananbarang`(`kddetailpemesananbarang`,`kdpemesananbarang`,`kdbarang`,`jumlah`,`harga`,`subtotal`,`created_at`,`updated_at`) values 
(37,'BR-20250717-0001',12,1,25000.00,25000.00,'2025-07-17 12:27:04','2025-07-17 12:27:04'),
(38,'BR-20250717-0001',18,1,3000000.00,3000000.00,'2025-07-17 12:27:05','2025-07-17 12:27:05');

/*Table structure for table `kategori` */

DROP TABLE IF EXISTS `kategori`;

CREATE TABLE `kategori` (
  `kdkategori` int unsigned NOT NULL AUTO_INCREMENT,
  `namakategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdkategori`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `kategori` */

insert  into `kategori`(`kdkategori`,`namakategori`,`created_at`,`updated_at`) values 
(6,'Wedding','2025-07-15 08:58:34','2025-07-15 08:58:34'),
(7,'Ulang Tahun','2025-07-15 08:58:51','2025-07-15 08:58:51'),
(8,'Lamaran','2025-07-15 08:59:07','2025-07-15 08:59:07');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(39,'2023-08-01-000001','App\\Database\\Migrations\\CreateUsersTable','default','App',1752305712,1),
(40,'2024-07-01-000001','App\\Database\\Migrations\\CreateKategoriTable','default','App',1752305712,1),
(41,'2024-07-01-000002','App\\Database\\Migrations\\CreateBarangTable','default','App',1752305712,1),
(42,'2024-07-01-000003','App\\Database\\Migrations\\CreatePaketTable','default','App',1752305712,1),
(43,'2024-07-01-000004','App\\Database\\Migrations\\CreatePelangganTable','default','App',1752305712,1),
(44,'2024-07-01-000005','App\\Database\\Migrations\\CreatePemesananbarangTable','default','App',1752305712,1),
(45,'2024-07-01-000006','App\\Database\\Migrations\\CreateDetailpemesananbarangTable','default','App',1752305712,1),
(46,'2024-07-01-000007','App\\Database\\Migrations\\CreatePemesananpaketTable','default','App',1752305712,1),
(47,'2024-07-01-000008','App\\Database\\Migrations\\CreatePembayaranTable','default','App',1752305712,1),
(48,'2024-07-21-000001','App\\Database\\Migrations\\AddVerificationColumns','default','App',1752305712,1),
(49,'2024-07-21-000003','App\\Database\\Migrations\\CreateDetailPaketTable','default','App',1752305712,1),
(50,'2025-07-11-045616','App\\Database\\Migrations\\AddFotoToBarang','default','App',1752305712,1),
(51,'2025-07-12-020853','App\\Database\\Migrations\\AddFieldsToPembayaranTable','default','App',1752305712,1),
(52,'2024-07-15-000002','App\\Database\\Migrations\\AddConfirmationFieldsToPembayaran','default','App',1752479698,2),
(53,'2025-07-14-075220','App\\Database\\Migrations\\UpdatePemesananbarangTable','default','App',1752483831,3),
(54,'2025-07-14-075412','App\\Database\\Migrations\\FixPemesananBarangTable','default','App',1752483831,3),
(55,'2025-07-14-090330','App\\Database\\Migrations\\AddPengembalianFieldsToPemesananBarang','default','App',1752483831,3);

/*Table structure for table `paket` */

DROP TABLE IF EXISTS `paket`;

CREATE TABLE `paket` (
  `kdpaket` int unsigned NOT NULL AUTO_INCREMENT,
  `namapaket` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `detailpaket` text COLLATE utf8mb4_general_ci,
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kdkategori` int unsigned NOT NULL,
  `kdbarang` int unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdpaket`),
  KEY `paket_kdkategori_foreign` (`kdkategori`),
  KEY `paket_kdbarang_foreign` (`kdbarang`),
  CONSTRAINT `paket_kdbarang_foreign` FOREIGN KEY (`kdbarang`) REFERENCES `barang` (`kdbarang`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `paket_kdkategori_foreign` FOREIGN KEY (`kdkategori`) REFERENCES `kategori` (`kdkategori`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `paket` */

insert  into `paket`(`kdpaket`,`namapaket`,`detailpaket`,`harga`,`foto`,`kdkategori`,`kdbarang`,`created_at`,`updated_at`) values 
(7,'Intimate wedding','Paket Intimate Wedding kami mencakup layanan utama dan perencanaan komprehensif mulai dari konsultasi eksklusif untuk memahami visi Anda, penyusunan jadwal acara yang detail, manajemen anggaran transparan, hingga koordinasi dengan vendor-vendor terpercaya di Padang. Tim koordinasi Hari-H profesional kami akan memastikan semua berjalan lancar, dari persiapan hingga akhir acara, sehingga Anda bisa sepenuhnya menikmati hari istimewa Anda.\r\n\r\nUntuk lokasi dan dekorasi, kami akan merekomendasikan dan membantu booking tempat yang ideal, seperti villa pribadi dengan pemandangan alam, taman outdoor yang asri, atau restoran dengan area private yang nyaman di Padang. Dekorasi akan sepenuhnya dipersonalisasi untuk menciptakan latar belakang yang indah pada area akad/pemberkatan, penataan meja resepsi yang elegan dengan centerpiece menawan, buket pengantin dan boutonniere dari bunga segar, serta pencahayaan ambiens yang romantis. Detail sentuhan pribadi seperti signage selamat datang dan guest book yang unik juga akan ditambahkan.\r\n\r\nDalam aspek kuliner dan minuman, Anda akan dimanjakan dengan pilihan katering premium, termasuk menu hidangan utama yang dapat disesuaikan selera (baik buffet, plated set menu, atau family style dengan pilihan masakan Padang modern, fusion, atau western), makanan pembuka dan penutup yang lezat, serta pilihan minuman segar seperti jus, infused water, dan mocktail spesial. Sebuah kue pengantin dengan desain elegan dan rasa pilihan Anda juga akan menjadi bagian dari perayaan.\r\n\r\nMomen berharga Anda akan terabadikan dengan sempurna melalui dokumentasi profesional kami, meliputi fotografi full day oleh fotografer berpengalaman yang menangkap setiap emosi, serta videografi sinematik full day yang akan menghasilkan wedding film berdurasi standar dan video highlight pendek yang siap dibagikan.\r\n\r\nUntuk hiburan dan pendukung acara, kami menyediakan MC profesional yang akan memandu jalannya resepsi dengan hangat, serta sistem suara dan musik latar berkualitas untuk menciptakan suasana yang sempurna. Peralatan sound system dan mic standar yang memadai juga akan disiapkan.\r\n\r\nSebagai benefit tambahan, tergantung paket dan ketersediaan, kami juga dapat menyediakan layanan makeup artist & hairdo untuk pengantin wanita, gaun pengantin dan setelan jas pengantin pria, kartu undangan (digital atau cetak), dan souvenir personal untuk tamu Anda.',10000000.00,'1752570253_038c3232bb355b34c5e1.jpg',6,NULL,'2025-07-15 09:04:13','2025-07-15 09:05:21'),
(8,'Reguler Wedding','Paket Reguler kami menyediakan layanan utama dan perencanaan menyeluruh, dimulai dengan konsultasi mendalam untuk memahami visi dan preferensi Anda. Kami akan membantu menyusun jadwal acara yang terperinci, mengelola anggaran secara transparan, serta berkoordinasi dengan jaringan vendor-vendor terkemuka di Padang, memastikan setiap elemen acara berjalan harmonis. Tim koordinasi Hari-H kami yang berpengalaman akan hadir penuh untuk memastikan kelancaran acara, mulai dari persiapan hingga penutupan resepsi, memungkinkan Anda dan keluarga menikmati setiap momen tanpa beban.\r\n\r\nDalam hal lokasi dan dekorasi, kami akan merekomendasikan dan membantu booking venue yang tepat untuk kapasitas tamu Anda, baik itu ballroom hotel, convention center, atau venue pernikahan populer lainnya di Padang. Dekorasi akan disesuaikan dengan tema pilihan Anda, mencakup dekorasi panggung pelaminan yang megah, mini garden yang asri, dekorasi lorong ballroom yang elegan, photobooth menarik, serta penataan area catering dan meja penerima tamu yang serasi. Kami juga menyediakan penataan bunga segar untuk buket pengantin, boutonniere, dan centerpiece yang mempercantik setiap sudut ruangan.\r\n\r\nUntuk kuliner dan minuman, Anda akan disajikan dengan paket katering prasmanan premium yang dapat disesuaikan, menawarkan beragam hidangan lezat dan favorit, termasuk hidangan pembuka, hidangan utama, dan aneka dessert yang menggugah selera. Pilihan menu bisa disesuaikan dengan selera Anda, mulai dari masakan Padang autentik, hidangan fusion, hingga western. Minuman segar seperti air mineral, jus, dan teh akan tersedia melimpah, dan sebuah kue pengantin elegan dengan desain dan rasa pilihan Anda akan menjadi puncak manis perayaan.\r\n\r\nMomen tak terlupakan Anda akan diabadikan dengan sempurna melalui dokumentasi profesional kami. Ini mencakup fotografi full day oleh tim fotografer yang ahli menangkap setiap emosi dan detail, serta videografi sinematik full day yang akan menghasilkan wedding film berdurasi lengkap dan video highlight yang siap dibagikan.\r\n\r\nUntuk mendukung jalannya acara, kami menyediakan hiburan dan pendukung acara seperti MC profesional yang akan memandu resepsi dengan ramah dan lancar, sistem suara yang berkualitas tinggi, serta musik latar yang menciptakan suasana meriah dan harmonis. Sebuah band akustik atau pilihan hiburan lainnya juga dapat disiapkan sesuai permintaan.\r\n\r\nSebagai benefit tambahan dalam Paket Reguler, kami juga menawarkan layanan makeup artist & hairdo untuk pengantin wanita, penyediaan gaun pengantin dan setelan jas pengantin pria, kartu undangan fisik atau digital, serta souvenir personal yang berkesan untuk tamu Anda.\r\n\r\nPaket Reguler ini adalah solusi lengkap untuk pernikahan impian Anda di Padang, memastikan setiap detail ditangani dengan profesionalisme dan sentuhan personal. Mari berdiskusi lebih lanjut untuk menciptakan perayaan yang tak akan terlupakan!',10000000.00,'1752570540_91a4fd8ea3bf438c7b8c.jpg',6,NULL,'2025-07-15 09:09:00','2025-07-15 09:09:00'),
(9,'Luxuri Wedding','Paket Pernikahan Mewah kami dimulai dengan layanan konsultasi dan perencanaan VIP yang sangat personal, di mana kami akan menyelami setiap keinginan dan imajinasi Anda untuk merancang blueprint pernikahan yang unik. Kami akan menyusun jadwal acara yang sangat detail, melakukan manajemen anggaran yang cermat, dan secara eksklusif berkoordinasi dengan jaringan vendor-vendor kelas atas, baik lokal maupun internasional, memastikan setiap elemen acara terintegrasi sempurna. Seorang Wedding Planner & Coordinator pribadi akan mendampingi Anda di setiap langkah, dan tim Wedding Day Director kami akan memastikan kelancaran acara Anda dari awal hingga akhir, memungkinkan Anda fokus sepenuhnya pada momen bahagia.\r\n\r\nDalam hal lokasi dan dekorasi, kami akan mengamankan dan mengubah venue paling prestisius di Padang menjadi latar belakang impian Anda. Ini bisa berupa ballroom hotel bintang lima dengan tata cahaya canggih dan sound system premium, atau private resort eksklusif yang menawarkan pemandangan menakjubkan. Dekorasi akan dieksekusi dengan kemewahan maksimal, menggunakan material premium, instalasi bunga segar berlimpah dari desainer bunga terkemuka, penataan panggung pelaminan yang grand, mini garden artistik, hingga chandelier kristal dan pencahayaan khusus yang menciptakan suasana magis. Setiap sudut akan dirancang dengan indah, termasuk photobooth interaktif yang mewah, area lounge yang nyaman, serta penataan meja dan kursi dengan tableware dan linen kelas atas.\r\n\r\nUntuk kuliner dan minuman, Anda akan menikmati gourmet catering eksklusif dengan pilihan menu yang disesuaikan secara personal oleh koki bintang lima. Ini bisa berupa plated set menu yang disajikan elegan, buffet premium dengan pilihan hidangan internasional dan live cooking stations, atau food stalls tematik dengan presentasi artistik. Kami juga menyediakan wine dan champagne selection premium, signature mocktails, serta baristas profesional untuk kopi dan teh. Sebuah kue pengantin couture dengan desain kustom dan rasa istimewa akan menjadi masterpiece penutup.\r\n\r\nMomen puncak Anda akan diabadikan dengan dokumentasi sinematik dan fotografi tingkat tinggi. Ini mencakup fotografi dan videografi multi-crew full day oleh tim seniman visual yang diakui, menggunakan peralatan canggih untuk menangkap setiap detail dan emosi. Hasilnya berupa album foto designer edition, cinematic wedding film berdurasi panjang, video highlight yang artistik, serta drone videography untuk menangkap keindahan venue dari ketinggian.\r\n\r\nDari sisi hiburan dan pendukung acara, kami akan menghadirkan MC profesional dan selebriti untuk memandu jalannya acara. Hiburan live premium akan disediakan, seperti orkestra penuh, band terkenal, penampil internasional, atau DJ ternama, menciptakan atmosfer yang tak terlupakan. Kami juga akan menyediakan sistem suara dan pencahayaan panggung canggih, serta efek spesial seperti kembang api, dry ice, atau proyektor mapping untuk momen-momen puncak.\r\n\r\nSebagai benefit tambahan eksklusif, paket ini mencakup makeup artist dan hairdo dari penata rias ternama untuk pengantin wanita, penyewaan gaun pengantin dan setelan jas pengantin pria dari desainer couture pilihan, undangan pernikahan custom-made berbahan premium, souvenir eksklusif untuk tamu, serta layanan valet dan pengamanan di lokasi.\r\n\r\nDengan Paket Pernikahan Mewah kami, impian pernikahan Anda di Padang akan terwujud menjadi sebuah perayaan spektakuler yang tak hanya memukau mata, tetapi juga menyentuh hati. Mari konsultasikan visi Anda dengan kami untuk merancang masterpiece pernikahan Anda.',25000000.00,'1752570663_7c54fe01b27cd78c5ee3.jpg',6,NULL,'2025-07-15 09:11:03','2025-07-15 09:11:42'),
(10,'Premium Wedding','Paket Pernikahan Premium kami dimulai dengan layanan konsultasi dan perencanaan yang mendalam, di mana kami akan berkolaborasi erat dengan Anda untuk menyusun konsep dan flow acara yang sempurna. Kami menyediakan manajemen jadwal dan anggaran yang detail, serta melakukan koordinasi menyeluruh dengan vendor-vendor premium terpilih di Padang, memastikan setiap aspek pernikahan Anda terintegrasi dengan mulus. Seorang Wedding Coordinator pribadi akan mendampingi Anda, dan tim On-Site Director kami akan memastikan kelancaran acara di Hari-H, sehingga Anda bisa fokus menikmati setiap detik tanpa kekhawatiran.\r\n\r\nUntuk lokasi dan dekorasi, kami akan membantu Anda mengamankan venue pilihan yang mencerminkan gaya Anda, dilengkapi dengan dekorasi yang lebih mewah dan detail. Ini termasuk panggung pelaminan yang lebih megah dengan backdrop desain khusus, mini garden yang lebih luas dan detail, instalasi bunga segar dari desainer bunga pilihan, lorong ballroom yang dihias elegan, serta area photobooth dan lounge yang dirancang secara artistik. Kami juga menyediakan pencahayaan ambiens dan tata suara yang lebih canggih untuk menciptakan atmosfer yang dramatis dan romantis. Penataan meja tamu akan menggunakan linen dan tableware kualitas superior, dengan centerpiece yang lebih grand dan berkelas.\r\n\r\nDalam hal kuliner dan minuman, Anda akan menikmati paket catering eksklusif dengan pilihan menu yang lebih beragam dan presentasi yang lebih menawan. Ini bisa berupa kombinasi plated set menu dan buffet premium dengan stasiun makanan interaktif (live cooking stations), menyajikan hidangan internasional favorit serta hidangan khas Padang yang diolah secara modern. Kami juga menyediakan pilihan minuman premium, termasuk signature mocktails, infused water dengan rasa unik, dan baristas untuk kopi atau teh. Sebuah kue pengantin kustom dengan desain elegan dan rasa istimewa juga menjadi bagian dari paket ini.\r\n\r\nMomen berharga Anda akan diabadikan dengan sempurna melalui dokumentasi profesional tingkat tinggi. Ini mencakup fotografi multi-crew full day oleh fotografer berpengalaman yang ahli menangkap setiap detail artistik, serta videografi sinematik full day yang akan menghasilkan wedding film berdurasi lebih panjang dengan kualitas sinematik, video highlight yang kreatif, dan opsi drone videography untuk menangkap keindahan venue.\r\n\r\nDari sisi hiburan dan pendukung acara, kami akan menghadirkan MC profesional dan berkarakter yang mampu menciptakan suasana hangat dan berkesan. Hiburan live akan disediakan, seperti live band dengan pilihan genre musik yang lebih luas, penyanyi solo profesional, atau pertunjukan khusus yang dapat disesuaikan dengan tema Anda. Kami juga menyediakan sistem suara dan pencahayaan panggung yang lebih canggih untuk mendukung penampilan hiburan, serta efek khusus sederhana (misalnya, dry ice atau sparkler) untuk momen-momen puncak.\r\n\r\nSebagai benefit tambahan eksklusif, paket ini mencakup makeup artist & hairdo dari stylist berpengalaman untuk pengantin wanita, penyewaan gaun pengantin dan setelan jas pengantin pria dari koleksi premium, desain dan cetak undangan pernikahan custom-made berbahan berkualitas, serta souvenir eksklusif yang berkesan untuk tamu Anda.\r\n\r\nDengan Paket Pernikahan Premium kami, Anda tidak hanya mendapatkan sebuah acara, melainkan sebuah perayaan cinta yang dirancang dengan detail, kualitas, dan sentuhan personal yang akan dikenang sepanjang masa di Padang. Mari berdiskusi lebih lanjut untuk mewujudkan pernikahan impian Anda.',20000000.00,'1752570769_6530e2c434439f7df17f.jpg',6,NULL,'2025-07-15 09:12:49','2025-07-15 09:12:49'),
(11,'Birthday','Paket Ulang Tahun kami menyediakan layanan utama dan perencanaan menyeluruh, dimulai dengan sesi konsultasi untuk memahami visi Anda, jumlah tamu, dan budget. Kami akan membantu Anda memilih tema, menyusun flow acara yang seru, serta merekomendasikan dan berkoordinasi dengan vendor-vendor terbaik di Padang. Tim koordinasi Hari-H kami akan memastikan semua berjalan lancar, mulai dari setting tempat hingga akhir acara, sehingga Anda bisa sepenuhnya menikmati perayaan.\r\n\r\nUntuk lokasi dan dekorasi, kami menyediakan berbagai pilihan venue yang dapat disesuaikan dengan skala pesta Anda. Dekorasi akan disesuaikan dengan tema pilihan Anda, termasuk backdrop menarik, balloon arch yang semarak, penataan meja dengan centerpiece lucu atau elegan, dan area photo booth yang instagrammable. Kami juga akan menyediakan peralatan pesta seperti meja, kursi, dan tableware yang serasi dengan tema.\r\n\r\nDalam hal kuliner dan minuman, Anda akan disajikan dengan paket katering yang lezat dan beragam, disesuaikan dengan preferensi Anda. Pilihan menu bisa berupa hidangan utama (prasmanan atau box), aneka camilan favorit (seperti snack corner atau food stall mini), serta minuman segar. Sebuah kue ulang tahun kustom dengan desain dan rasa pilihan Anda akan menjadi bintang utama perayaan.\r\n\r\nMomen bahagia ini akan diabadikan dengan sempurna melalui dokumentasi profesional. Kami menyediakan fotografer yang akan menangkap setiap tawa, senyum, dan detail pesta, menghasilkan foto-foto yang cerah dan penuh kenangan.\r\n\r\nDari sisi hiburan dan pendukung acara, kami akan memastikan pesta Anda tetap hidup. Tersedia MC yang akan memandu acara dengan interaktif dan meriah, sistem suara yang memadai untuk musik latar atau karaoke, serta musik yang disesuaikan dengan playlist pilihan Anda. Untuk pesta anak-anak, kami bisa menyediakan magician, badut, atau games facilitator yang akan menambah keseruan.\r\n\r\nSebagai benefit tambahan, tergantung paket yang Anda pilih, kami juga dapat menyediakan undangan digital atau cetak, goodie bag atau souvenir untuk tamu, serta face painting atau balloon twisting untuk pesta anak-anak.\r\n\r\nMari wujudkan pesta ulang tahun impian Anda di Padang! Hubungi kami untuk berdiskusi lebih lanjut dan mendapatkan penawaran terbaik.',8000000.00,'1752570953_da5ace7bbca1c367baa4.jpg',7,NULL,'2025-07-15 09:15:53','2025-07-15 09:15:53'),
(12,'Lamaran','Paket Lamaran kami menyediakan layanan utama dan perencanaan menyeluruh, dimulai dengan sesi konsultasi pribadi untuk memahami visi Anda, preferensi, dan detail penting lainnya. Kami akan membantu Anda dalam memilih tema, menyusun flow acara yang lancar, dan merekomendasikan serta berkoordinasi dengan vendor-vendor terbaik di Padang. Tim koordinasi di Hari-H kami akan berada di lokasi untuk memastikan setiap persiapan dan jalannya acara berlangsung sempurna, sehingga Anda bisa fokus sepenuhnya pada momen sakral tersebut.\r\n\r\nUntuk lokasi dan dekorasi, kami akan membantu Anda memilih dan menata venue yang ideal untuk momen lamaran Anda. Dekorasi akan disesuaikan secara personal dengan tema pilihan Anda, mencakup backdrop yang cantik dan elegan (misalnya dengan flower wall atau inisial nama), penataan meja utama dengan centerpiece bunga segar yang menawan, lighting yang menciptakan suasana romantis, serta area photo booth yang estetik untuk mengabadikan kebahagiaan.\r\n\r\nDalam hal kuliner dan minuman, Anda akan disajikan dengan paket hidangan yang lezat dan disesuaikan. Pilihan menu bisa berupa set menu privat yang disajikan dengan elegan, canape dan finger food pilihan, atau sajian prasmanan untuk keluarga yang lebih santai. Kami juga menyediakan pilihan minuman segar seperti infused water, jus, dan mocktail spesial. Sebuah kue lamaran mini dengan desain cantik juga akan melengkapi manisnya momen Anda.\r\n\r\nMomen berharga ini akan diabadikan dengan indah melalui dokumentasi profesional. Kami menyediakan fotografer yang berpengalaman untuk menangkap setiap ekspresi, tawa, dan keharuan dari awal hingga akhir acara, menghasilkan foto-foto berkualitas tinggi yang akan menjadi kenang-kenangan abadi.\r\n\r\nSebagai benefit tambahan, tergantung paket yang Anda pilih, kami juga dapat menyediakan hand bouquet untuk calon pengantin wanita, ring box yang cantik, signage tulisan khusus (misalnya \"Will You Marry Me?\"), sound system sederhana untuk musik latar romantis, atau bahkan bantuan dalam mempersiapkan speech lamaran Anda.\r\n\r\nMari wujudkan momen lamaran impian Anda di Padang. Hubungi kami untuk berdiskusi lebih lanjut dan menciptakan awal yang tak terlupakan bagi kisah cinta Anda!',7000000.00,'1752571099_ac7c1df4da51f80f161d.jpg',8,NULL,'2025-07-15 09:18:19','2025-07-15 09:18:19');

/*Table structure for table `pelanggan` */

DROP TABLE IF EXISTS `pelanggan`;

CREATE TABLE `pelanggan` (
  `kdpelanggan` int unsigned NOT NULL AUTO_INCREMENT,
  `namapelanggan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `nohp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `iduser` int unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdpelanggan`),
  KEY `pelanggan_iduser_foreign` (`iduser`),
  CONSTRAINT `pelanggan_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pelanggan` */

insert  into `pelanggan`(`kdpelanggan`,`namapelanggan`,`alamat`,`nohp`,`iduser`,`created_at`,`updated_at`) values 
(1,'Muklis','Padang','083182423488',2,'2025-07-12 07:37:01','2025-07-12 07:37:01'),
(2,'mimi wulandari','Padang','083182423488',3,'2025-07-15 05:42:06','2025-07-15 05:42:06'),
(3,'putri','Padang','083182423488',4,'2025-07-15 11:46:49','2025-07-15 11:46:49');

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `kdpembayaran` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `metodepembayaran` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'transfer, cash, dll',
  `tipepembayaran` varchar(20) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'dp, dp2, lunas',
  `jumlahbayar` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'jumlah yang dibayarkan pada transaksi ini',
  `sisa` decimal(15,2) NOT NULL DEFAULT '0.00',
  `totalpembayaran` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'total yang sudah dibayarkan',
  `buktipembayaran` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'bukti pembayaran (foto/file)',
  `status` enum('pending','partial','success','failed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT 'status pembayaran',
  `dp_confirmed` tinyint(1) DEFAULT '0' COMMENT '1 = DP dikonfirmasi, 0 = belum dikonfirmasi',
  `dp_confirmed_at` datetime DEFAULT NULL,
  `dp_confirmed_by` int DEFAULT NULL,
  `h1_paid` tinyint(1) DEFAULT '0' COMMENT '1 = H-1 dibayar, 0 = belum dibayar',
  `h1_confirmed` tinyint(1) DEFAULT '0' COMMENT '1 = H-1 dikonfirmasi, 0 = belum dikonfirmasi',
  `h1_confirmed_at` datetime DEFAULT NULL,
  `h1_confirmed_by` int DEFAULT NULL,
  `full_confirmed` tinyint(1) DEFAULT '0' COMMENT '1 = pelunasan dikonfirmasi, 0 = belum dikonfirmasi',
  `full_confirmed_at` datetime DEFAULT NULL,
  `full_confirmed_by` int DEFAULT NULL,
  `full_paid` char(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rejected_reason` text COLLATE utf8mb4_general_ci,
  `rejected_at` datetime DEFAULT NULL,
  `rejected_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdpembayaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pembayaran` */

insert  into `pembayaran`(`kdpembayaran`,`tgl`,`metodepembayaran`,`tipepembayaran`,`jumlahbayar`,`sisa`,`totalpembayaran`,`buktipembayaran`,`status`,`dp_confirmed`,`dp_confirmed_at`,`dp_confirmed_by`,`h1_paid`,`h1_confirmed`,`h1_confirmed_at`,`h1_confirmed_by`,`full_confirmed`,`full_confirmed_at`,`full_confirmed_by`,`full_paid`,`rejected_reason`,`rejected_at`,`rejected_by`,`created_at`,`updated_at`) values 
('INV-20250717-0001','2025-07-17','transfer','lunas',2400000.00,0.00,8000000.00,'INV-20250717-0001_h1_1752735956_240e09e05132d8de3484.png','success',1,'2025-07-17 07:05:30',NULL,1,1,'2025-07-17 07:06:35',NULL,1,'2025-07-17 07:06:41',NULL,'1',NULL,NULL,NULL,'2025-07-17 07:04:39','2025-07-17 07:06:41'),
('INV-20250717-0002','2025-07-17','transfer','dp',6000000.00,14000000.00,6000000.00,NULL,'pending',0,NULL,NULL,0,0,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-17 07:08:22','2025-07-17 07:08:22'),
('PAY-20250717-0001','2025-07-17','tunai','lunas',3025000.00,0.00,3025000.00,NULL,'',1,'2025-07-17 12:27:05',1,0,0,NULL,NULL,1,'2025-07-17 12:27:05',1,'1',NULL,NULL,NULL,'2025-07-17 12:27:05','2025-07-17 12:27:05');

/*Table structure for table `pemesananbarang` */

DROP TABLE IF EXISTS `pemesananbarang`;

CREATE TABLE `pemesananbarang` (
  `kdpemesananbarang` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` datetime NOT NULL,
  `kdpelanggan` int unsigned DEFAULT NULL,
  `alamatpesanan` text COLLATE utf8mb4_general_ci,
  `lamapemesanan` int NOT NULL,
  `grandtotal` decimal(10,2) NOT NULL,
  `kdpembayaran` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `tgl_kembali` date DEFAULT NULL,
  `status_pengembalian` enum('baik','rusak','hilang') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan_pengembalian` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdpemesananbarang`),
  KEY `pemesananbarang_kdpelanggan_foreign` (`kdpelanggan`),
  KEY `pemesananbarang_ibfk_1` (`kdpembayaran`),
  CONSTRAINT `pemesananbarang_ibfk_1` FOREIGN KEY (`kdpembayaran`) REFERENCES `pembayaran` (`kdpembayaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pemesananbarang_kdpelanggan_foreign` FOREIGN KEY (`kdpelanggan`) REFERENCES `pelanggan` (`kdpelanggan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pemesananbarang` */

insert  into `pemesananbarang`(`kdpemesananbarang`,`tgl`,`kdpelanggan`,`alamatpesanan`,`lamapemesanan`,`grandtotal`,`kdpembayaran`,`status`,`tgl_kembali`,`status_pengembalian`,`catatan_pengembalian`,`created_at`,`updated_at`) values 
('BR-20250717-0001','2025-07-17 00:00:00',2,'Padang',1,3025000.00,'PAY-20250717-0001','completed','2025-07-17','baik','tes','2025-07-17 12:27:04','2025-07-17 12:59:36');

/*Table structure for table `pemesananpaket` */

DROP TABLE IF EXISTS `pemesananpaket`;

CREATE TABLE `pemesananpaket` (
  `kdpemesananpaket` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `kdpelanggan` int unsigned NOT NULL,
  `kdpaket` int unsigned NOT NULL,
  `hargapaket` decimal(15,2) NOT NULL DEFAULT '0.00',
  `alamatpesanan` text COLLATE utf8mb4_general_ci,
  `jumlahhari` int NOT NULL DEFAULT '1' COMMENT 'dalam hari',
  `luaslokasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `grandtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','process','completed','cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `kdpembayaran` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `metodepembayaran` enum('transfer','cash') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`kdpemesananpaket`),
  KEY `pemesananpaket_kdpelanggan_foreign` (`kdpelanggan`),
  KEY `pemesananpaket_kdpaket_foreign` (`kdpaket`),
  KEY `pemesananpaket_kdpembayaran_foreign` (`kdpembayaran`),
  CONSTRAINT `pemesananpaket_kdpaket_foreign` FOREIGN KEY (`kdpaket`) REFERENCES `paket` (`kdpaket`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pemesananpaket_kdpelanggan_foreign` FOREIGN KEY (`kdpelanggan`) REFERENCES `pelanggan` (`kdpelanggan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pemesananpaket_kdpembayaran_foreign` FOREIGN KEY (`kdpembayaran`) REFERENCES `pembayaran` (`kdpembayaran`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pemesananpaket` */

insert  into `pemesananpaket`(`kdpemesananpaket`,`tgl`,`kdpelanggan`,`kdpaket`,`hargapaket`,`alamatpesanan`,`jumlahhari`,`luaslokasi`,`grandtotal`,`status`,`kdpembayaran`,`metodepembayaran`,`created_at`,`updated_at`) values 
('BK-20250717-0001','2025-07-22',3,11,8000000.00,'tes',3,'10x15',8000000.00,'completed','INV-20250717-0001','transfer','2025-07-17 07:04:39','2025-07-17 07:06:41'),
('BK-20250717-0002','2025-08-28',3,10,20000000.00,'tes',1,'10x15',20000000.00,'cancelled','INV-20250717-0002','transfer','2025-07-17 07:08:23','2025-07-17 07:13:45');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'admin, user, dll',
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active' COMMENT 'active, inactive',
  `is_verified` tinyint(1) DEFAULT '0' COMMENT '0=unverified, 1=verified',
  `verification_code` varchar(6) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verification_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`name`,`role`,`status`,`is_verified`,`verification_code`,`verification_expiry`,`last_login`,`remember_token`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'admin','admin@example.com','$2y$10$HOZpKEqsSvhxymi4HoRCSOwLbPNwyt/Bp2kJQuqAGMYz4O9sBYF8u','Administrator','admin','active',1,'','2025-07-14 05:09:01','2025-07-17 11:45:09',NULL,'2025-07-12 07:35:12','2025-07-17 11:45:09',NULL),
(2,'muklis','muklis@pingaja.site','$2y$10$ECYvxqFhmdrNL3C6SNz6M.u2170.8lWJWS2f3FjV4.24ttzEMXmje','Muklis','pelanggan','active',1,NULL,NULL,'2025-07-15 05:38:03',NULL,'2025-07-12 07:37:01','2025-07-15 05:38:03',NULL),
(3,'mimi','mimi@pingaja.site','$2y$10$bZ6/4.qr6uRI.NL5jWWvquhd/gwPSl4PBDzbB5J5xn0rSsh6UUGjK','mimi wulandari','pelanggan','active',1,NULL,NULL,'2025-07-15 05:42:32',NULL,'2025-07-15 05:42:06','2025-07-15 05:42:32',NULL),
(4,'putri','putri@pingaja.site','$2y$10$idV44DdKkVhGsvgqUthNV.Rytfr/GxWXcQIYZYV8XIFqA0xjeV7Gi','putri','pelanggan','active',1,'546680','2025-07-16 11:46:49','2025-07-17 07:03:14',NULL,'2025-07-15 11:46:49','2025-07-17 07:03:14',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
