-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 27, 2025 at 02:39 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myirt_adaptive_learning_min`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `admin_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `email`, `user_id`) VALUES
(1, 'Admin 1', 'admin1@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int NOT NULL,
  `class_name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `class_name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E');

-- --------------------------------------------------------

--
-- Table structure for table `class_attendance`
--

CREATE TABLE `class_attendance` (
  `id` int NOT NULL,
  `class_id` int NOT NULL,
  `teacher_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_attendance`
--

INSERT INTO `class_attendance` (`id`, `class_id`, `teacher_id`) VALUES
(10, 2, 54),
(11, 3, 54),
(12, 2, 55),
(13, 2, 56),
(14, 2, 57),
(15, 2, 58),
(16, 2, 59),
(17, 1, 60);

-- --------------------------------------------------------

--
-- Table structure for table `gagal_post_test`
--

CREATE TABLE `gagal_post_test` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `level` int NOT NULL,
  `total` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gagal_post_test`
--

INSERT INTO `gagal_post_test` (`id`, `student_id`, `level`, `total`) VALUES
(4, 411, 2, 1),
(5, 421, 2, 2),
(6, 424, 2, 2),
(7, 425, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `level_student`
--

CREATE TABLE `level_student` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `level` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level_student`
--

INSERT INTO `level_student` (`id`, `student_id`, `level`) VALUES
(34, 11, 2),
(35, 16, 2),
(36, 17, 2),
(37, 19, 2),
(38, 23, 2),
(39, 24, 2),
(40, 25, 2),
(41, 34, 2),
(42, 38, 2),
(43, 48, 2),
(44, 49, 2),
(45, 50, 2),
(46, 51, 2),
(47, 57, 2),
(48, 59, 2),
(49, 75, 2),
(50, 88, 2),
(51, 91, 2),
(52, 94, 1),
(53, 95, 1),
(54, 97, 2),
(55, 98, 3),
(56, 99, 1),
(57, 100, 2),
(58, 101, 2),
(59, 102, 1),
(60, 103, 2),
(61, 104, 2),
(62, 105, 2),
(63, 107, 3),
(64, 108, 1),
(65, 109, 3),
(66, 110, 3),
(67, 111, 1),
(68, 112, 2),
(69, 113, 2),
(70, 114, 1),
(71, 116, 1),
(72, 118, 1),
(73, 119, 2),
(74, 120, 2),
(75, 121, 1),
(76, 123, 2),
(77, 122, 1),
(78, 124, 1),
(79, 125, 2),
(80, 126, 1),
(81, 127, 1),
(82, 128, 1),
(83, 129, 1),
(84, 130, 1),
(85, 131, 1),
(86, 132, 2),
(87, 133, 1),
(89, 140, 2),
(90, 142, 2),
(91, 411, 1),
(92, 411, 1),
(93, 412, 2),
(94, 418, 2),
(95, 420, 0),
(96, 420, 2),
(97, 421, 1),
(98, 148, 1),
(99, 423, 0),
(100, 424, 1),
(101, 425, 1);

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id` int NOT NULL,
  `materi_desc` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id`, `materi_desc`, `module_id`) VALUES
(1, '<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Sel pertama kali ditemukan oleh Robert Hooke seorang ilmuwan berkebangsaan Inggris. Berdasarkan penemuan Robert Hooke, berkembanglah teori-teori mengenai sel. Beberapa saintis turut memberikan kontribusi terhadap konsep sel. Apa yang dimaksud dengan sel? Bagaimana komponen kimiawi penyusun sel?</p>\n\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Sel sangat mendasar bagi ilmu Biologi sebagaimana atom bagi ilmu Kimia. Seluruh organisme terdiri atas sel. Dalam hierarki organisasi biologis, sel merupakan kumpulan materi paling sederhana yang dapat hidup (Campbell, Reece, &amp; Mitchel: 2002). Jadi, sel merupakan unit struktural dan fungsional terkecil makhluk hidup. Sel sebagai unit struktural terkecil berarti bahwa sel merupakan penyusun yang mendasar bagi tubuh makhluk hidup. Sel tidak dapat dibagi-bagi lagi menjadi bagian yang lebih kecil dan dapat berdiri sendiri. Adapun sel dikatakan sebagai unit fungsional terkecil berarti bahwa sel dapat melakukan berbagai proses kehidupan, misalnya respirasi, transportasi,reproduksi, ekskresi, sekresi, dan sintesis. Selain itu, sel juga merupakan unit hereditas makhluk hidup yang menurunkan sifat genetis dari satu generasi kepada generasi berikutnya.&quot;</p>\n', 1),
(3, '<p>&nbsp; &nbsp; &nbsp; &nbsp; Seluruh kegiatan kehidupan sel merupakan akibat dari reaksi kimia yang berlangsung di dalam sel. Senyawa kimia penyusun sel disebut protoplasma yangmerupakan substansi kompleks. Secara garis besarnya, komponen kimia sebuah sel terdiri atas unsur makro, unsur mikro, senyawa organik, dan senyawa anorganik.</p>\n\n<ol>\n	<li>Unsur Makro<br />\n	Unsur makro merupakan unsur terbesar yang menyusun sebuah sel. Unsur makro terdiri atas lima unsur utama, yaitu oksigen (O) sebanyak 62%, karbon (C) sebanyak 20%, hidrogen (H) sebanyak 10%, nitrogen (N) sebanyak 10%, dan kalium (K) sebanyak 25%. Selain itu, juga terdapat sulfur (S), fosfor (P), kalsium<br />\n	(Ca), magnesium (Mg), dan natrium (Na). Dari berbagai unsur tersebut, unsur karbon, hidrogen, dan oksigen merupakan unsur paling utama serta dapat bersenyawa membentuk molekul karbohidrat, lemak, asam nukleat, dan protein.<br />\n	&nbsp;</li>\n	<li>Unsur Mikro<br />\n	Unsur mikro merupakan unsur yang terdapat dalam jumlah sangat sedikit. Beberapa jenis unsur mikro dalam sel, antara lain besi (Fe), tembaga (Cu), kobalt (Co), mangan (Mn), seng (Zn), molibdenum (Mo), boron (Bo), dan silikon (Si).<br />\n	&nbsp;</li>\n	<li>Senyawa Organik<br />\n	Senyawa organik terdiri atas mikromolekul dan makromolekul. Mikromolekul terdiri atas asam amino, nukleotida, asam lemak, dan glukosa. Makromolekul terdiri atas karbohidrat, protein, lemak, dan asam nukleat.<br />\n	a. Karbohidrat<br />\n	Karbohidrat tersusun dari unsur karbon (C), hidrogen (H), dan oksigen (O). Karbohidrat terdiri atas rangkaian molekul-molekul gula yang disebut monosakarida. Polisakarida merupakan untaian monosakarida yang sangat panjang. Untaian ini dapat lurus maupun bercabang-cabang. Polisakarida digolongkan menjadi polisakarida struktural dan polisakarida nutrien. Beberapa contoh polisakarida struktural yaitu selulosa pembentuk dinding sel tumbuhan, asam hialuronat sebagai salah satu komponen substansi antara sel pada jaringan ikat, serta glikolipida dan glikoprotein yang merupakan struktur penting dari membran sel. Beberapa contoh polisakarida nutrien yaitu amilum, yang terdapat di dalam sel tumbuhan dan bakteri, glikogen di dalam sel hewan, serta paramilum di dalam beberapa jenis sel Protozoa.<br />\n	b. Protein<br />\n	Protein merupakan senyawa organik terbesar yang menyusun sel dan merupakan polimer dari asam amino yang saling berikatan dengan ikatan peptida. Protein tersusun dari karbon, hidrogen, oksigen, dan nitrogen, serta kadang-kadang terdapat sulfur dan fosfor sebagai unsur tambahan. Fungsi protein di dalam sel di antaranya membentuk membran plasma Bersama lemak dan karbohidrat, mengatur permeabilitas membran sel, mengatur keseimbangan kadar asam basa dalam sel, protein yang berupa enzim bertindak sebagai katalisator berbagai reaksi kimia, serta berperan dalam Gerakan dalam sel.<br />\n	c. Lipid (Lemak)<br />\n	Lipid tersusun dari unsur karbon, hidrogen, dan oksigen. Lipid merupakan senyawa yang bersifat &nbsp;hidrofobik dan larut dalam pelarut organik. Di dalam sel terdapat bermacam-macam lipid di antaranya fosfolipid, glikolipid, dan steroid.<br />\n	d. Nukleotida dan Asam Nukleat<br />\n	Asam nukleat adalah makromolekul yang sangat penting untuk kelangsungan hidup sel. Asam nukleat terdiri atas dua macam, yaitu asam deoksiribosa nukleat (DNA) dan asam ribosa nukleat (RNA). DNA merupakan penyimpan informasi genetis dalam sel dan bersama-sama dengan protein histon membentuk kromosom. Satu asam nukleat terdiri atas nukleotida-nukleotida yang saling berikatan dengan ikatan fosfodiester.</li>\n	<li>Senyawa Anorganik<br />\n	Senyawa anorganik yang menjadi komponen kimiawi sel di antaranya air, garam-garam mineral, dan gas.<br />\n	a. Air<br />\n	Air merupakan senyawa penyusun sel terbesar (50&ndash;60% berat sel). Air berperan sangat penting pada kehidupan sel maupun kehidupan semua organisme. Air merupakan pelarut dan pengangkut senyawa-senyawa yang diperlukan sel maupun limbah yang harus dibuang. Air juga berperan sebagai media berlangsungnya reaksi-reaksi kimia di dalam sel.<br />\n	b. Garam-Garam Mineral<br />\n	Garam-garam mineral di dalam sel terdapat dalam bentuk ion positif (kation) dan ion negatif (anion). Beberapa contoh garam mineral dalam sel antara lain NaCl, MgCl, CaSO4, dan NaHCO3. Garam-garam mineral tersebut berfungsi untuk mempertahankan tekanan osmotik dan keseimbangan asam basa dalam sel.<br />\n	c. Gas<br />\n	Beberapa jenis gas yang terlibat dalam aktivitas sel antara lain karbon dioksida (CO2), oksigen (O2), dan amonia (NH3).</li>\n</ol>\n', 2),
(4, '<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:20,&quot;w&quot;:1050,&quot;h&quot;:62,&quot;abs_x&quot;:199,&quot;abs_y&quot;:149}\">&nbsp; &nbsp; &nbsp; &nbsp; Berdasarkan jumlah sel penyusunnya, organisme dibedakan menjadi organisme uniseluler dan multiseluler. Organisme uniseluler adalah organisme yang hanya terdiri atas satu sel. Adapun organisme multiseluler adalah organisme yang tubuhnya tersusun dari banyak sel. Sel tumbuhan dan sel hewan termasuk sel eukariotik, sedangkan sel bakteri termasuk sel prokariotik. Apa yang dimaksud dengan sel eukariotik dan prokariotik?</p>\n\n<h2 bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:98,&quot;w&quot;:1050,&quot;h&quot;:46,&quot;abs_x&quot;:199,&quot;abs_y&quot;:227}\"><br bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:98,&quot;w&quot;:0,&quot;h&quot;:22,&quot;abs_x&quot;:199,&quot;abs_y&quot;:227}\" />\n1. Struktur Sel Prokariotik dan Eukariotik</h2>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\"><br bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:163,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:199,&quot;abs_y&quot;:292}\" />\n&nbsp; &nbsp; &nbsp; &nbsp; Berdasarkan strukturnya, sel dapat dibedakan menjadi dua jenis yaitu sel prokariotik dan sel eukariotik.<br bis_size=\"{&quot;x&quot;:642,&quot;y&quot;:184,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:821,&quot;abs_y&quot;:313}\" />\na. Sel Prokariotik<br bis_size=\"{&quot;x&quot;:118,&quot;y&quot;:205,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:297,&quot;abs_y&quot;:334}\" />\n&nbsp; &nbsp; &nbsp; &nbsp; Sel prokariotik yaitu sel yang tidak memiliki membran inti sehingga inti sel berbatasan langsung dengan sitoplasma. Makhluk hidup yang termasuk prokariotik adalah bakteri dan ganggang biru (Cyanobacteria). Struktur umum sel prokariotik sebagai berikut.<br bis_size=\"{&quot;x&quot;:470,&quot;y&quot;:246,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:649,&quot;abs_y&quot;:375}\" />\n1) Dinding selnya tersusun dari peptidoglikan, lipid, dan protein. Dinding sel berfungsi sebagai pelindung dan pemberi bentuk tubuh.<br bis_size=\"{&quot;x&quot;:778,&quot;y&quot;:267,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:957,&quot;abs_y&quot;:396}\" />\n2) Membran plasma tersusun dari karbohidrat, lemak, dan protein. Membran plasma berfungsi sebagai pelindung molekuler sel terhadap lingkungan di sekitarnya.<br bis_size=\"{&quot;x&quot;:952,&quot;y&quot;:288,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:1131,&quot;abs_y&quot;:417}\" />\n3) Sitoplasma tersusun dari air, protein, lipid, mineral, dan enzim-enzim yang berfungsi untuk mencerna<img alt=\"\" src=\"../assets/images/materi/sel prokariotik.png\" style=\"float:right; height:188px; width:301px\" /><br bis_size=\"{&quot;x&quot;:614,&quot;y&quot;:309,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:793,&quot;abs_y&quot;:438}\" />\nmakanan secara intraseluler dan untuk melakukan proses metabolisme sel. Pada sitoplasma terdapat DNA dan RNA, ribosom, serta mesosom. Mesosom berfungsi sebagai penghasil energi.</p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\">&nbsp;</p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\">&nbsp;</p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\">&nbsp;</p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\">&nbsp;</p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\"><br bis_size=\"{&quot;x&quot;:119,&quot;y&quot;:350,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:298,&quot;abs_y&quot;:479}\" />\nb. Sel Eukariotik<br bis_size=\"{&quot;x&quot;:113,&quot;y&quot;:371,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:292,&quot;abs_y&quot;:500}\" />\nSel eukariotik merupakan sel yang sudah memiliki membran inti (nukleus dibungkus membran nukleus) dan sistem endomembran. Sel eukariotik terdapat pada sel tumbuhan dan sel hewan.</p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<img alt=\"\" src=\"../assets/images/materi/sel hewan dan tumbuhan (eukariotik).png\" style=\"height:404px; width:800px\" /></p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\"><br bis_size=\"{&quot;x&quot;:61,&quot;y&quot;:413,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:240,&quot;abs_y&quot;:542}\" />\nStruktur sel eukariotik terdiri atas tiga komponen utama yaitu membran plasma, sitoplasma, dan organel-organel sel.<br bis_size=\"{&quot;x&quot;:689,&quot;y&quot;:433,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:868,&quot;abs_y&quot;:562}\" />\n1) Membran Plasma<br bis_size=\"{&quot;x&quot;:137,&quot;y&quot;:454,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:316,&quot;abs_y&quot;:583}\" />\nMembran plasma bersifat selektif permeabel (semipermeabel) yang artinya membran plasma dapat dilalui oleh molekul atau ion tertentu.</p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\"><img alt=\"\" src=\"../assets/images/materi/membran plasma.png\" style=\"height:398px; width:800px\" /></p>\n\n<p bis_size=\"{&quot;x&quot;:20,&quot;y&quot;:161,&quot;w&quot;:1050,&quot;h&quot;:1164,&quot;abs_x&quot;:199,&quot;abs_y&quot;:290}\"><br bis_size=\"{&quot;x&quot;:806,&quot;y&quot;:475,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:985,&quot;abs_y&quot;:604}\" />\n2) Sitoplasma<br bis_size=\"{&quot;x&quot;:99,&quot;y&quot;:496,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:278,&quot;abs_y&quot;:625}\" />\nSitoplasma adalah cairan sel yang berada di luar membran inti. Komponen utama penyusun sitoplasma yaitu sitosol, substansi genetik, sitoskeleton, dan organel-organel sel.<br bis_size=\"{&quot;x&quot;:1017,&quot;y&quot;:517,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:1196,&quot;abs_y&quot;:646}\" />\nSitoplasma berfungsi sebagai sumber bahan kimia penting bagi sel dan tempat<br bis_size=\"{&quot;x&quot;:474,&quot;y&quot;:537,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:653,&quot;abs_y&quot;:666}\" />\nterjadinya reaksi metabolisme.<br bis_size=\"{&quot;x&quot;:195,&quot;y&quot;:558,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:374,&quot;abs_y&quot;:687}\" />\n3) Organel-Organel Sel<br bis_size=\"{&quot;x&quot;:154,&quot;y&quot;:579,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:333,&quot;abs_y&quot;:708}\" />\nOrganel-organel sel terdapat dalam sitoplasma. Macam-macam organel penyusun sel sebagai berikut.<br bis_size=\"{&quot;x&quot;:609,&quot;y&quot;:600,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:788,&quot;abs_y&quot;:729}\" />\na) Inti Sel (Nukleus)<br bis_size=\"{&quot;x&quot;:134,&quot;y&quot;:621,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:313,&quot;abs_y&quot;:750}\" />\nNukleus merupakan organel terbesar yang berada dalam sel dengan diameter sekitar 10 mm. Nukleus berfungsi sebagai pengatur pembelahan sel, pengendali seluruh kegiatan sel, dan pembawa informasi genetik.<br bis_size=\"{&quot;x&quot;:207,&quot;y&quot;:662,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:386,&quot;abs_y&quot;:791}\" />\nb) Retikulum Endoplasma (RE)<br bis_size=\"{&quot;x&quot;:199,&quot;y&quot;:683,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:378,&quot;abs_y&quot;:812}\" />\nRetikulum endoplasma tersusun oleh membran yang berbentuk seperti jala. RE memiliki beberapa fungsi berikut.<br bis_size=\"{&quot;x&quot;:671,&quot;y&quot;:704,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:850,&quot;abs_y&quot;:833}\" />\n(1) Menyintesis lemak dan kolesterol (RE kasar dan RE halus).<br bis_size=\"{&quot;x&quot;:381,&quot;y&quot;:725,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:560,&quot;abs_y&quot;:854}\" />\n(2) Menampung protein yang disintesis oleh ribosom (RE kasar).<br bis_size=\"{&quot;x&quot;:391,&quot;y&quot;:745,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:570,&quot;abs_y&quot;:874}\" />\n(3) Transportasi molekul-molekul (RE kasar dan RE halus).<br bis_size=\"{&quot;x&quot;:359,&quot;y&quot;:766,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:538,&quot;abs_y&quot;:895}\" />\n(4) Menetralkan racun (detoksifikasi).<br bis_size=\"{&quot;x&quot;:233,&quot;y&quot;:787,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:412,&quot;abs_y&quot;:916}\" />\nc) Ribosom<br bis_size=\"{&quot;x&quot;:85,&quot;y&quot;:808,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:264,&quot;abs_y&quot;:937}\" />\nRibosom merupakan struktur unit gabungan protein dengan RNA ribosom (disingkat RNA-r). Ribosom terdiri atas dua subunit, yaitu subunit kecil dan subunit besar. Ribosom berperan dalam sintesis protein.<br bis_size=\"{&quot;x&quot;:205,&quot;y&quot;:849,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:384,&quot;abs_y&quot;:978}\" />\n4) Kompleks Golgi<br bis_size=\"{&quot;x&quot;:126,&quot;y&quot;:870,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:305,&quot;abs_y&quot;:999}\" />\nKompleks Golgi mempunyai hubungan yang erat dengan RE dalam sintesis protein. Kompleks Golgi mempunyai beberapa fungsi berikut.<br bis_size=\"{&quot;x&quot;:811,&quot;y&quot;:891,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:990,&quot;abs_y&quot;:1020}\" />\na) Tempat sintesis polisakarida seperti mukus, selulosa, dan hemiselulosa.<br bis_size=\"{&quot;x&quot;:448,&quot;y&quot;:912,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:627,&quot;abs_y&quot;:1041}\" />\nb) Membentuk membran plasma.<br bis_size=\"{&quot;x&quot;:210,&quot;y&quot;:933,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:389,&quot;abs_y&quot;:1062}\" />\nc) Membentuk kantong sekresi untuk membungkus zat yang akan dikeluarkan sel.<br bis_size=\"{&quot;x&quot;:492,&quot;y&quot;:953,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:671,&quot;abs_y&quot;:1082}\" />\nd) Membentuk akrosom pada sperma, kuning telur pada sel telur, dan lisosom.<br bis_size=\"{&quot;x&quot;:471,&quot;y&quot;:974,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:650,&quot;abs_y&quot;:1103}\" />\n5) Lisosom<br bis_size=\"{&quot;x&quot;:83,&quot;y&quot;:995,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:262,&quot;abs_y&quot;:1124}\" />\nLisosom merupakan kantong membran yang berisi enzim-enzim hidrolitik (lisozim) seperti enzim protease, lipase, nuklease, fosfatase, dan enzim pencerna yang lain. Beberapa fungsi lisosom yaitu melakukan pencernaan intrasel, autofagi, eksositosis, dan autolisis.<br bis_size=\"{&quot;x&quot;:525,&quot;y&quot;:1036,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:704,&quot;abs_y&quot;:1165}\" />\n6) Badan Mikro<br bis_size=\"{&quot;x&quot;:108,&quot;y&quot;:1057,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:287,&quot;abs_y&quot;:1186}\" />\nBadan mikro terdiri atas dua tipe, yaitu peroksisom dan glioksisom. Peroksisom terdapat pada sel hewan, Fungi, dan tumbuhan. Peroksisom berperan dalam oksidasi substrat menghasilkan H2O2 yang selanjutnya dipecah menjadi H2O dan O2. Selain itu, peroksisom juga berperan dalam pengubahan lemak menjadi karbohidrat dan penguraian purin dalam sel. Adapun glioksisom berperan dalam metabolisme asam lemak dan sebagai tempat terjadinya siklus glioksilat.<br bis_size=\"{&quot;x&quot;:668,&quot;y&quot;:1120,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:847,&quot;abs_y&quot;:1249}\" />\n7) Mitokondria<br bis_size=\"{&quot;x&quot;:102,&quot;y&quot;:1140,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:281,&quot;abs_y&quot;:1269}\" />\nMitokondria memiliki dua jenis membran yaitu membran luar dan membran dalam. Membran dalam membentuk tonjolan-tonjolan yang disebut krista. Tonjolan-tonjolan tersebut berfungsi untuk memperluas permukaan agar penyerapan oksigen lebih efektif.<img alt=\"\" src=\"../assets/images/materi/mitokondria.png\" style=\"float:right; height:260px; width:400px\" /><br bis_size=\"{&quot;x&quot;:476,&quot;y&quot;:1182,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:655,&quot;abs_y&quot;:1311}\" />\nRuangan dalam mitokondria berisi cairan yang disebut matriks mitokondria. Di dalam matriks mitokondria terdapat enzim pernapasan, DNA, RNA, dan protein. Mitokondria berfungsi sebagai<br bis_size=\"{&quot;x&quot;:65,&quot;y&quot;:1224,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:244,&quot;abs_y&quot;:1353}\" />\ntempat terjadinya respirasi seluler. Dalam respirasi seluler terjadi oksidasi makanan yang menghasilkan energi. Secara sederhana, reaksi oksidasi makanan dapat ditulis sebagai berikut.<br bis_size=\"{&quot;x&quot;:62,&quot;y&quot;:1265,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:241,&quot;abs_y&quot;:1394}\" />\nC6H12O6 + 6O2 oksidasi makanan&rarr; 6CO2 + 6H2O + energi<br bis_size=\"{&quot;x&quot;:375,&quot;y&quot;:1286,&quot;w&quot;:0,&quot;h&quot;:15,&quot;abs_x&quot;:554,&quot;abs_y&quot;:1415}\" />\nBerkaitan dengan fungsi tersebut, mitokondria sering disebut the power house of cell.</p>\n', 3),
(5, '<p>2. Perbedaan Sel Hewan dengan Sel Tumbuhan<br />\nSel hewan dan sel tumbuhan termasuk sel eukariotik. Meskipun sama-sama sel eukariotik, kedua sel tersebut memiliki beberapa perbedaan pada organel-organel selnya secara spesifik.&nbsp;<br />\nBerdasarkan hasil pengamatan sel menggunakan mikroskop elektron dapat diketahui perbedaan antara sel hewan dengan sel tumbuhan. Sel hewan memiliki sentriol yang tidak dimiliki oleh sel tumbuhan. Adapun sel tumbuhan memiliki dinding sel, plastida, dan vakuola yang tidak dimiliki oleh sel hewan.<br />\na. Struktur Sel Tumbuhan<br />\nBagian-bagian sel yang hanya dimiliki oleh sel tumbuhan yaitu dinding sel, vakuola, dan plastida.<br />\n1) Dinding Sel<br />\nDinding sel merupakan lapisan terluar yang tersusun dari selulosa, hemiselulosa, dan pektin. Dinding sel berfungsi sebagai penyokong dan pelindung selaput plasma serta memelihara keseimbangan sel dari tekanan. Adanya dinding sel mengakibatkan bentuk sel tumbuhan relatif tetap.<br />\n2) Vakuola<br />\nVakuola atau rongga sel adalah organel sitoplasmik yang berisi cairan yang dibatasi membran tonoplas.<br />\nVakuola mempunyai beberapa fungsi sebagai berikut.<br />\na) Tempat menyimpan zat makanan seperti amilum dan gula.<br />\nb) Memasukkan air melalui tonoplast untuk membangun turgiditas sel bersama dinding sel.<br />\nc) Menyimpan pigmen.<br />\nd) Menyimpan minyak asiri.<br />\ne) Tempat penimbunan sisa metabolisme dan metabolit sekunder seperti Ca-oksalat, tanin, getah karet, dan alkaloid.<br />\n3) Plastida<br />\nBerdasarkan kandungan pigmen di dalamnya, plastida dibedakan menjadi tiga tipe yaitu kloroplas, kromoplas, dan leukoplas.<br />\na) Kloroplas</p>\n\n<p><img alt=\"\" src=\"../assets/images/materi/kloroplas.png\" style=\"height:354px; width:559px\" /><br />\nKloroplas yaitu plastida yang mengandung klorofil dan pigmen fotosintetik lainnya. Kloroplas tersusun dari membran luar dan membran dalam. Membran luar berfungsi mengatur keluar masuknya zat. Membran dalam membungkus cairan kloroplas yang disebut stroma. Membran dalam melipat ke arah dalam dan membentuk lembaran-lembaran yang disebut tilakoid. Pada tempat-tempat tertentu, tilakoid bertumpuk-tumpuk membentuk grana. Kloroplas berfungsi sebagai tempat berlangsungnya fotosintesis.<br />\nb) Kromoplas<br />\nKromoplas yaitu plastida yang mengandung pigmen nonfotosintetik. Beberapa pigmen yang terdapat dalam kromoplas yaitu xantofil dan karoten.<br />\nc) Leukoplas<br />\nLeukoplas adalah plastida yang tidak berwarna. Biasanya terdapat pada organ penyimpan makanan<br />\ncadangan seperti biji dan umbi. Ada tiga macam leukoplas yaitu amiloplas untuk menyimpan amilum, elaioplas (lipidoplas) untuk membentuk dan menyimpan lemak, serta proteoplas untuk menyimpan protein.<br />\nb. Struktur Sel Hewan<br />\nSel hewan memiliki dua sentriol di dalam sentrosom. Sentriol berperan dalam proses pembelahan sel. Saat pembelahan sel, tiap-tiap sentriol memisahkan diri menuju kutub yang berlawanan dan memancarkan benang-benang gelendong pembelahan yang akan menjerat kromosom.</p>\n', 4),
(6, '<p>Sel disebut sebagai unit fungsional terkecil dalam kehidupan karena di dalam sel berlangsung proses-proses kehidupan (bioproses).<br />\n1. Mekanisme Transpor Melalui Membran<br />\nPerpindahan molekul atau ion yang melewati membran ada dua macam yaitu transpor pasif dan transpor aktif.<br />\na. Transpor Pasif<br />\nTranspor pasif merupakan perpindahan molekul atau ion tanpa menggunakan energi sel. Perpindahan molekul tersebut terjadi secara spontan mengikuti gradien konsentrasi. Contoh transpor pasif yaitu difusi dan osmosis.<br />\nDifusi adalah perpindahan molekul-molekul zat dari konsentrasi tinggi ke konsentrasi rendah baik melalui membran plasma ataupun tidak. Difusi dibedakan menjadi dua yaitu difusi sederhana dan difusi terbantu. Difusi sederhana terjadi secara spontan, molekul zat akan berdifusi menyebar ke seluruh<br />\nruangan sampai dicapai kesetimbangan. Faktor-faktor yang memengaruhi proses difusi yaitu wujud materi, ukuran molekul, konsentrasi zat, dan suhu.<br />\nDifusi terbantu merupakan proses difusi dengan perantara protein pembawa dari konsentrasi tinggi ke konsentrasi rendah. Contoh mekanisme difusi terbantu yaitu proses molekul glukosa melewati membran.<br />\nOsmosis adalah perpindahan molekul-molekul pelarut (misal air) dari larutan berkonsentrasi rendah (hipotonik) ke larutan berkonsentrasi tinggi (hipertonik) melalui selaput (membran) semipermeabel. Jika pelarut yang digunakan berupa air, osmosis dapat berarti perpindahan molekul air melalui membran semipermeabel dari larutan yang kadar airnya tinggi ke larutan yang kadar airnya rendah.<br />\nAir akan masuk ke dalam sel jika konsentrasi larutan dalam sel tinggi sehingga terjadi endosmosis. Endosmosis pada sel hewan mengakibatkan kehancuran sel karena robeknya membran plasma (lisis). Endosmosis pada sel tumbuhan mengakibatkan sel dalam keadaan turgid. Sementara itu, air di dalam sel akan keluar jika konsentrasi larutan di luar sel tinggi dan terjadi eksosmosis. Eksosmosis pada hewan akan mengakibatkan pengerutan sel (krenasi). Eksosmosis pada tumbuhan akan mengakibatkan terlepasnya membran dari dinding sel yang disebut plasmolisis.<br />\nb. Transpor Aktif<br />\nTranspor aktif adalah transpor yang memerlukan energi untuk melawan gradien konsentrasi. Pada transpor aktif terjadi pemompaan molekul melewati membran dan melawan gradien konsentrasi. Contoh transpor aktif antara lain pompa natrium-kalium, endositosis, dan eksositosis.</p>\n', 5),
(7, '<p>2. Sintesis Protein dalam Sel<br />\nSelain mekanisme transpor melalui membran, di dalam sel juga terjadi sintesis protein. Sintesis protein berlangsung di ribosom. Bagaimana proses berlangsungnya sintesis protein dalam sel? Lakukan terlebih dahulu kegiatan berikut untuk mengetahui mekanisme sintesis protein dalam sel.<br />\nSintesis protein adalah proses penerjemahan gen menjadi urutan asam amino yang akan disintesis menjadi polipeptida (protein). Sintesis protein secara garis besar dibagi menjadi dua tahapan utama, yaitu proses pembuatan molekul mRNA pada inti sel (transkripsi) dan proses penerjemahan mRNA oleh tRNA serta perangkaian asam amino di ribosom (translasi).<br />\na. Transkripsi<br />\nProses transkripsi diawali dari sintesis RNA dari salah satu rantai DNA sense atau rantai cetakan. Adapun rantai DNA komplemennya disebut rantai antisense. Rentangan DNA yang ditranskripsi menjadi molekul RNA disebut unit transkripsi. Transkripsi terdiri atas tiga tahap yaitu inisiasi, elongasi, dan terminasi.<br />\n1) Inisiasi (Permulaan)<br />\nProses inisiasi dimulai dari promoter, yakni daerah DNA yang merupakan tempat melekatnya RNA polimerase. Promoter mencakup titik awal (start point) transkripsi yaitu nukleotida yang menunjukkan dimulainya sintesis protein (kodon start). Fungsi promoter untuk menentukan tempat dimulainya transkripsi dan menentukan satu rantai DNA yang akan digunakan sebagai cetakan.<br />\n2) Elongasi (Pemanjangan)<br />\nElongasi terjadi saat RNA bergerak di sepanjang pilinan ganda DNA terbuka secara berurutan. Enzim RNA polimerase menambahkan nukleotida dari molekul RNA yang sedang tumbuh di sepanjang rantai DNA. Setelah proses sintesis RNA selesai, rantai DNA terbentuk kembali dan molekul RNA baru terlepas dari cetakannya.<br />\n3) Terminasi (Pengakhiran)<br />\nProses transkripsi akan berhenti setelah sampai pada terminator, yakni urutan DNA yang berfungsi menghentikan transkripsi (kodon terminasi).<br />\nb. Translasi<br />\nTranslasi adalah proses pelekatan antara tRNA dengan asam amino dengan bantuan enzim aminoasil-tRNA sintetase. Ribosom memudahkan pelekatan yang spesifik antara antikodon tRNA dengan kodon mRNA selama sintesis protein.<br />\nTahap translasi terdiri atas inisiasi, elongasi, dan terminasi.<br />\n1) Inisiasi<br />\nRibosom kecil mengikatkan diri pada mRNA dan tRNA inisiator. Ribosom melekat pada salah satu ujung mRNA. Di dekat pelekatan tersebut terdapat kodon start AUG (yang membawa kode untuk membentuk asam amino metionin). Kodon ini memberikan sinyal dimulainya proses translasi.<br />\n2) Elongasi<br />\nTahap ini dimulai dengan terbentuknya asam-asam amino yang berikatan dengan metionin. Molekul rRNA dari ribosom mengatalis pembentukan ikatan peptida antara asam amino yang baru dengan ujung rantai polipeptida yang sebelumnya terbentuk dari asam amino yang dibawa tRNA. Setelah itu, tRNA<br />\nkeluar dari ribosom. Peristiwa ini berlangsung sampai terbentuk polipeptida.<br />\n3) Terminasi<br />\nElongasi akan berhenti setelah ribosom mencapai kodon stop yaitu UAA, UAG, atau UGA. Kodon stop berfungsi sebagai sinyal untuk menghentikan translasi. Selanjutnya, polipeptida yang terbentuk akan lepas dari ribosom menuju ke sitoplasma.<br />\nProses terminasi diakhiri dengan terbentuknya rantai asam amino yang sangat panjang, atau lebih sering dinamakan dengan rantai polipeptida. Rantai polipeptida inilah yang kita sebut dengan protein.<br />\nProtein atau rantai polipeptida dari hasil sintesis protein merupakan rantai protein primer. Protein ini harus mengalami modifikasi agar bisa digunakan dalam tubuh. Proses modifikasi dilakukan di badan Golgi. Hasil modifikasi ini dapat dibedakan menjadi dua yaitu protein struktural dan protein dinamis (fungsional).<br />\na. Protein Struktural<br />\nProtein struktural merupakan protein yang berperan dalam pembentukan struktur sel. Sebagai contoh, protein integral dan protein perifer yang berada pada membran sel. Sementara itu, protein struktural di dalam sel berperan untuk membentuk kerangka sel yang disebut sitoskeleton. Sitoskeleton berupa jaringan protein filamen yang memantapkan membran plasma sehingga menyokong stabilitas bentuk sel. Protein filamen ini terdiri atas mikrofilamen, filamen tengah (filamen intermediet), dan mikrotubulus. Mikrotubulus dibangun dari protein globuler yang disebut tubulin. Filamen intermediat disusun dari keluarga protein yang beragam disebut keratin. Mikrofilamen disusun dari protein globuler yang disebut aktin.</p>\n\n<p><img alt=\"\" src=\"../assets/images/materi/protein struktural.png\" style=\"height:387px; width:672px\" /><br />\nb. Protein Fungsional<br />\nProtein fungsional merupakan protein yang berperan dalam pengaturan aktivitas sel, misalnya enzim dan hormon.<br />\n1) Enzim<br />\nEnzim adalah satu atau beberapa gugus polipeptida (protein) yang berfungsi sebagai katalis dalam suatu reaksi kimia organik. Sebagian besar enzim bekerja di dalam sel (enzim intraseluler), tetapi ada juga enzim yang dibuat di dalam sel kemudian dikeluarkan dari dalam sel untuk menjalankan fungsinya<br />\n(enzim ekstraseluler). Contoh enzim intraseluler adalah enzim katalase. Enzim ini banyak terdapat di organel peroksisom yang berfungsi memecah senyawa H2O2 (hidrogen peroksida) yang bersifat toksik menjadi H2O dan O2. Adapun contoh enzim ekstraseluler adalah enzim-enzim pencernaan, misalnya enzim pepsin yang berfungsi memecah protein menjadi pepton.<br />\n2) Hormon<br />\nHormon terdiri atas tiga jenis berdasarkan struktur kimiawinya yaitu hormon yang terbuat dari protein atau peptida (hormon peptida), hormon yang terbuat dari kolesterol (hormon steroid), dan hormon yang terbuat dari asam amino (hormon tiroid). Jadi, protein merupakan salah satu bahan baku untuk membuat hormon.<br />\nHormon berperan mengatur homeostasis, metabolisme, reproduksi, pertumbuhan, dan perkembangan. Homeostasis adalah pengaturan secara otomatis dalam tubuh agar kelangsungan hidup dapat dipertahankan. Sebagai contoh pengendalian tekanan darah, kerja jantung, dan kadar gula darah.</p>\n', 6),
(8, '<p>3. Reproduksi Sel<br />\nReproduksi sel merupakan proses penggandaan materi genetik (DNA) yang terdapat di dalam nukleus sehingga menghasilkan sel-sel anakan yang memiliki materi genetik yang identik. Reproduksi sel dapat terjadi karena peristiwa pembelahan sel secara mitosis.<br />\nPembelahan mitosis adalah peristiwa pembelahan sel yang terjadi pada sel-sel somatis serta menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya. Pembelahan mitosis bertujuan untuk pertumbuhan dan regenerasi sel.</p>\n\n<p><img alt=\"\" src=\"../assets/images/materi/pembelahan mitosis.png\" style=\"height:241px; width:717px\" /></p>\n\n<p>Sel melangsungkan pembelahan mitosis sehingga dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.<br />\nTanaman mengalami<br />\npertumbuhan karena adanya penambahan jumlah sel sebagai hasil pembelahan mitosis. Pertumbuhan<br />\nadalah suatu proses pertambahan ukuran, baik volume, bobot, dan jumlah sel yang bersifat<br />\nirreversible. Pertumbuhan pada tumbuhan umumnya terjadi pada daerah meristem (titik tumbuh)<br />\ndi antaranya terdapat pada ujung akar dan ujung batang. Adapun tahap-tahap pembelahan mitosis<br />\nmeliputi profase, prometafase, metafase, anafase, dan telofase.</p>\n', 7);

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int NOT NULL,
  `module_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_topic_id` int NOT NULL,
  `number` int NOT NULL,
  `module_level` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `module_desc`, `sub_topic_id`, `number`, `module_level`) VALUES
(1, 'Konsep Sel', 1, 1, '1'),
(2, 'Komponen Kimiawi Penyusun Sel', 1, 2, '2'),
(3, 'Struktur Sel Prokariotik dan Eukariotik', 2, 3, '2'),
(4, 'Perbedaan Sel Hewan dengan Sel Tumbuhan', 2, 4, '1'),
(5, 'Mekanisme Transpor Melalui Membran', 3, 5, '2'),
(6, 'Sintesis Protein', 3, 6, '3'),
(7, 'Reproduksi Sel', 3, 7, '2'),
(9, 'Tes', 4, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `module_learned`
--

CREATE TABLE `module_learned` (
  `id` int NOT NULL,
  `module_id` int NOT NULL,
  `student_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module_learned`
--

INSERT INTO `module_learned` (`id`, `module_id`, `student_id`) VALUES
(10, 2, 91),
(11, 2, 57),
(12, 1, 57),
(13, 2, 24),
(14, 3, 57),
(15, 3, 24),
(16, 4, 57),
(17, 5, 24),
(18, 7, 24),
(19, 6, 24),
(20, 5, 57),
(21, 1, 24),
(22, 4, 24),
(23, 7, 57),
(24, 6, 57),
(25, 1, 94),
(26, 4, 94),
(27, 2, 94),
(28, 3, 94),
(29, 2, 97),
(30, 1, 97),
(31, 3, 97),
(32, 4, 97),
(33, 5, 97),
(34, 7, 97),
(35, 6, 97),
(36, 1, 99),
(37, 2, 101),
(38, 2, 19),
(39, 3, 19),
(40, 5, 19),
(41, 7, 19),
(42, 6, 19),
(43, 1, 19),
(44, 4, 19),
(45, 2, 23),
(46, 3, 23),
(47, 5, 23),
(48, 7, 23),
(49, 6, 23),
(50, 2, 113),
(51, 3, 113),
(52, 5, 113),
(53, 1, 114),
(54, 4, 114),
(55, 2, 114),
(56, 3, 114),
(57, 5, 114),
(58, 1, 111),
(59, 4, 111),
(60, 2, 11),
(61, 3, 11),
(62, 5, 11),
(63, 7, 11),
(64, 6, 11),
(65, 2, 34),
(66, 3, 34),
(67, 5, 34),
(68, 7, 34),
(69, 6, 34),
(70, 2, 16),
(71, 3, 16),
(72, 5, 16),
(73, 7, 16),
(74, 6, 16),
(75, 1, 16),
(76, 4, 16),
(77, 6, 109),
(78, 1, 109),
(79, 4, 109),
(80, 2, 109),
(81, 3, 109),
(82, 5, 109),
(83, 7, 109),
(84, 6, 110),
(85, 1, 110),
(86, 4, 110),
(87, 2, 110),
(88, 3, 110),
(89, 5, 110),
(90, 7, 110),
(91, 2, 17),
(92, 3, 17),
(93, 5, 17),
(94, 7, 17),
(95, 6, 17),
(96, 1, 17),
(97, 4, 17),
(98, 2, 112),
(99, 3, 112),
(100, 5, 112),
(101, 2, 50),
(102, 3, 50),
(103, 5, 50),
(104, 7, 50),
(105, 1, 50),
(106, 2, 51),
(107, 3, 51),
(108, 5, 51),
(109, 7, 51),
(110, 6, 51),
(111, 1, 51),
(112, 4, 51),
(113, 2, 25),
(114, 3, 25),
(115, 5, 25),
(116, 1, 25),
(117, 4, 25),
(118, 1, 118),
(119, 4, 118),
(120, 2, 118),
(121, 3, 118),
(122, 5, 118),
(123, 7, 118),
(124, 1, 116),
(125, 4, 116),
(126, 2, 116),
(127, 3, 116),
(128, 5, 116),
(129, 7, 116),
(130, 6, 116),
(131, 2, 123);

-- --------------------------------------------------------

--
-- Table structure for table `module_question`
--

CREATE TABLE `module_question` (
  `id` int NOT NULL,
  `module_id` int NOT NULL,
  `question` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module_question`
--

INSERT INTO `module_question` (`id`, `module_id`, `question`, `answer`) VALUES
(1, 1, 'Menurut Campbell, Reece, & Mitchel sel merupakan . . .', 3),
(2, 1, 'Unit struktural dan fungsional terkecil makhluk hidup adalah pengertian dari ...', 5),
(3, 1, 'Sel disebut sebagai unit fungsional terkecil dalam kehidupan karena . . .', 12),
(4, 1, 'Sel dapat melakukan berbagai proses kehidupan misalnya . . .\n<br/>1.	respirasi\n<br/>2.	transportasi\n<br/>3.	reproduksi\n<br/>4.	ekskresi\n<br/>5.	sekresi\n<br/>6.	sintesis\n<br/>Jawaban yang benar dari pilihan diatas adalah . . .\n', 16),
(5, 1, 'Istilah sel pertama kali dinyatakan oleh . . .', 18),
(6, 1, 'Sel juga merupakan unit . . . makhluk hidup yang menurunkan sifat genetis dari satu generasi kepada generasi berikutnya.', 22),
(7, 1, 'Sel merupakan ruangan-ruangan kecil yang dibatasi oleh dinding adalah pengertian sel menurut . . .', 25),
(8, 2, 'Senyawa kimia penyusun sel disebut . . .', 29),
(9, 2, 'komponen kimia sebuah sel terdiri atas . . .', 35),
(10, 2, 'unsur makro adalah . . .', 38),
(11, 2, 'Senyawa organik terdiri atas . . .', 44),
(12, 2, 'Air merupakan senyawa penyusun sel terbesar. Berapa banyak kontribusi air terhadap sel?', 47),
(13, 2, 'Gas yang terlibat dalam aktivitas sel adalah . . ', 49),
(14, 2, 'penyimpan informasi genetis dalam sel dan bersama-sama dengan protein histon membentuk kromosom adalah . . .', 54),
(15, 3, 'berdasarkan strukturnya, sel dapat dibedakan menjadi . . . jenis.', 57),
(16, 3, 'Makhluk hidup yang termasuk prokariotik adalah . . .', 63),
(17, 3, 'Dinding sel berfungsi sebagai . . .', 68),
(18, 3, 'Membran plasma tersusun dari . . .', 69),
(19, 3, 'Struktur sel eukariotik terdiri atas tiga komponen utama yaitu . . .', 73),
(20, 3, 'Macam-macam organel penyusun sel adalah . . .', 80),
(21, 3, '1) Tempat sintesis polisakarida seperti mukus, selulosa, dan hemiselulosa. \n<br/>2) Menetralkan racun (detoksifikasi)\n<br/>3) Menyintesis lemak dan kolesterol\n<br/>4) Membentuk akrosom pada sperma, kuning telur pada sel telur, dan lisosom.\n<br/>Fungsi dari kompleks golgi adalah . . .\n', 82),
(22, 4, 'Bagian sel yang hanya dimiliki oleh sel tumbuhan yaitu . . .', 85),
(23, 4, 'Sel hewan dan sel tumbuhan termasuk . . .', 90),
(24, 4, 'Sel hewan memiliki . . . yang tidak dimiliki oleh sel tumbuhan.', 96),
(25, 4, 'lapisan terluar yang tersusun dari selulosa, hemiselulosa, dan pektin adalah . . .', 97),
(26, 4, 'Vakuola mempunyai beberapa fungsi sebagai berikut, kecuali . . .', 102),
(27, 4, 'Kromoplas yaitu . . .', 106),
(28, 4, 'Sel hewan memiliki . . . sentriol di dalam sentrosom', 109),
(29, 5, 'Perpindahan molekul atau ion yang melewati membran ada dua macam yaitu . . .', 115),
(30, 5, 'Difusi adalah . . .', 117),
(31, 5, 'Contoh transpor pasif yaitu . . .', 124),
(32, 5, 'Transpor pasif merupakan . . .', 128),
(33, 5, 'Contoh transpor aktif antara lain :\n<br/>1. pompa natrium-kalium, \n<br/>2.endositosis\n<br/>3.eksositosis\n<br/>Contoh transpor aktif yang benar pada soal diatas adalah . . .', 134),
(34, 5, 'Air akan masuk ke dalam sel jika konsentrasi larutan dalam sel tinggi sehingga terjadi . . .', 134),
(35, 5, 'Eksosmosis pada tumbuhan akan mengakibatkan . . .', 139),
(36, 6, 'Sintesis protein berlangsung di  . . .', 143),
(37, 6, 'Sintesis protein adalah  . . .', 145),
(38, 6, 'Translasi adalah . . .', 150),
(39, 6, 'Proses transkripsi akan berhenti setelah sampai pada terminator, yakni urutan DNA yang berfungsi menghentikan transkripsi. Proses ini terjadi pada tahapan . . .', 156),
(40, 6, 'Translasi terdiri atas berapa tahapan?', 158),
(41, 6, 'kodon start AUG bertugas untuk  . . .', 163),
(42, 6, 'Yang berperan mengatur homeostasis, metabolisme, reproduksi, pertumbuhan, dan perkembangan adalah . . .', 165),
(43, 7, 'Reproduksi sel merupakan proses penggandaan materi genetik (DNA) yang terdapat di dalam nukleus sehingga . . .', 169),
(44, 7, 'peristiwa pembelahan sel yang terjadi pada sel-sel somatis serta menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya. Pengertian dari . . .', 174),
(45, 7, 'Pembelahan mitosis bertujuan . . .', 180),
(46, 7, 'Adapun tahap-tahap pembelahan mitosis meliputi :\n<br/>1.profase \n<br/>2.prometafase, \n<br/>3.metafase, \n<br/>4. anafase \n<br/>5.telofase\n<br/>tahap-tahap pembelahan yang benar adalah . . .\n', 184),
(47, 7, 'Tanaman mengalami pertumbuhan karena . . .', 185),
(48, 7, 'Sel melangsungkan pembelahan mitosis sehingga . . .', 191),
(49, 7, 'Pertumbuhan adalah . . .', 196),
(50, 7, 'Halo Ini tes', 198);

-- --------------------------------------------------------

--
-- Table structure for table `module_question_choice`
--

CREATE TABLE `module_question_choice` (
  `id` int NOT NULL,
  `answer_desc` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module_question_choice`
--

INSERT INTO `module_question_choice` (`id`, `answer_desc`, `question_id`) VALUES
(1, 'bagian yang terdiri dari nukelus (inti sel) dan nukleolus (anak inti)', 1),
(2, 'Sel adalah unit dasar kehidupan makhluk hidup', 1),
(3, 'kumpulan materi paling sederhana yang dapat hidup', 1),
(4, 'mata rantai terakhir dalam rantai besar yang membentuk jaringan organ, sistem, dan individu.', 1),
(5, 'sel', 2),
(6, 'sel prokariotik', 2),
(7, 'sel eukariotik', 2),
(8, 'reproduksi sel', 2),
(9, 'merupakan penyusun yang mendasar bagi tubuh makhluk hidup ', 3),
(10, 'di dalam sel terjadi berbagai reaksi-reaksi kimia kehidupan ', 3),
(11, 'dapat menurunkan sifat genetis dari satu generasi ke generasi berikutnya ', 3),
(12, 'sel tidak dapat dibagi-bagi lagi menjadi lebih kecil', 3),
(13, '1,2,3,6', 4),
(14, '2,4,5,6', 4),
(15, '1,3,5,6', 4),
(16, 'semua benar', 4),
(17, 'Johannes Purkinje', 5),
(18, 'Robert Hooke', 5),
(19, 'Theodor Schwann', 5),
(20, 'Rudolf Virchow', 5),
(21, 'protoplasma', 6),
(22, 'hereditas', 6),
(23, 'ekskresi', 6),
(24, 'sekresi', 6),
(25, 'Robert Hooke', 7),
(26, 'Rene Dutrochet', 7),
(27, 'Alexander Braun', 7),
(28, 'Rudolph Vircow', 7),
(29, 'protoplasma', 8),
(30, 'hereditas', 8),
(31, 'ekskresi', 8),
(32, 'sekresi', 8),
(33, 'unsur makro, unsur mikro, senyawa organik', 9),
(34, 'senyawa organik, dan senyawa anorganik.', 9),
(35, 'unsur makro, unsur mikro, senyawa organik, dan senyawa anorganik.', 9),
(36, 'unsur mikro, senyawa organik, dan senyawa anorganik.', 9),
(37, 'unsur yang terdapat dalam jumlah sangat sedikit', 10),
(38, 'unsur terbesar yang menyusun sebuah sel.', 10),
(39, 'makromolekul yang sangat penting untuk kelangsungan hidup sel.', 10),
(40, 'penyimpan informasi genetis dalam sel dan bersama-sama dengan protein histon membentuk kromosom.', 10),
(41, 'asam nukleat', 11),
(42, 'asam amino dan nukleotida', 11),
(43, 'karbohidrat dan protein,', 11),
(44, 'mikromolekul dan makromolekul.', 11),
(45, '40-45%', 12),
(46, '45-50%', 12),
(47, '50-60%', 12),
(48, '60-70%', 12),
(49, 'NH3', 13),
(50, 'NaCl', 13),
(51, 'CaSO4', 13),
(52, 'NaHCO3', 13),
(53, 'RNA', 14),
(54, 'DNA', 14),
(55, 'Lipid', 14),
(56, 'Enzim', 14),
(57, '2', 15),
(58, '3', 15),
(59, '4', 15),
(60, '5', 15),
(61, 'bakteri', 16),
(62, 'ganggang biru', 16),
(63, 'a dan b benar', 16),
(64, 'a dan b salah', 16),
(65, 'pelindung molekuler sel terhadap lingkungan di sekitarnya.', 17),
(66, 'untuk mencerna makanan secara intraseluler dan untuk melakukan proses metabolisme sel.', 17),
(67, 'penghasil energi.', 17),
(68, 'pelindung dan pemberi bentuk tubuh', 17),
(69, 'karbohidrat, lemak, dan protein.', 18),
(70, 'peptidoglikan, lipid, dan protein', 18),
(71, 'air, protein, lipid, mineral, dan enzim-enzim ', 18),
(72, 'peptidoglikan, lipid, dan lemak', 18),
(73, 'membran plasma, sitoplasma, dan Kompleks Golgi', 19),
(74, 'membran plasma, sitoplasma, dan organel-organel sel.', 19),
(75, 'sitoplasma, Lisosom, dan organel-organel sel.', 19),
(76, 'kompleks Golgi, Lisosom, dan organel-organel sel', 19),
(77, 'Inti Sel (Nukleus)', 20),
(78, 'Retikulum Endoplasma (RE)', 20),
(79, 'Ribosom', 20),
(80, 'Semua jawaban benar', 20),
(81, '1 dan 2', 21),
(82, '1 dan 4', 21),
(83, '2 dan 3', 21),
(84, '2 dan 4', 21),
(85, 'plastida', 22),
(86, 'sentrosom', 22),
(87, 'mikrotubulus', 22),
(88, 'mitokondria', 22),
(89, 'sel prokariotik', 23),
(90, 'sel eukariotik', 23),
(91, 'sitoplasma', 23),
(92, 'membran plasma', 23),
(93, 'dinding sel', 24),
(94, 'vakuola', 24),
(95, 'plastida', 24),
(96, 'sentriol', 24),
(97, 'dinding sel', 25),
(98, 'vakuola', 25),
(99, 'plastida', 25),
(100, 'sentriol', 25),
(101, 'Tempat menyimpan zat makanan seperti amilum dan gula.', 26),
(102, 'mengatur keluar masuknya zat ', 26),
(103, 'Menyimpan pigmen.', 26),
(104, 'Menyimpan minyak asiri.', 26),
(105, 'plastida yang mengandung klorofil dan pigmen fotosintetik lainnya', 27),
(106, 'plastida yang mengandung pigmen nonfotosintetik', 27),
(107, 'plastida yang tidak berwarna', 27),
(108, 'plastida yang berwarna', 27),
(109, 'dua', 28),
(110, 'tiga', 28),
(111, 'empat', 28),
(112, 'lima', 28),
(113, 'transpor pasif dan transpor tidak aktif', 29),
(114, 'transpor aktif dan transpor aktif', 29),
(115, 'transpor pasif dan transpor aktif', 29),
(116, 'semua jawaban salah', 29),
(117, 'perpindahan molekul-molekul zat dari konsentrasi tinggi ke konsentrasi rendah baik melalui membran plasma ataupun tidak.', 30),
(118, 'perpindahan molekul-molekul pelarut (misal air) dari larutan berkonsentrasi rendah (hipotonik) ke larutan berkonsentrasi tinggi (hipertonik) melalui selaput (membran) semipermeabel.', 30),
(119, 'perpindahan molekul air melalui membran semipermeabel dari larutan yang kadar airnya tinggi ke larutan yang kadar airnya rendah', 30),
(120, 'perpindahan molekul atau ion tanpa menggunakan energi sel.', 30),
(121, 'difusi dan eksositosis', 31),
(122, 'endositosis dan eksositosis.', 31),
(123, 'pompa natrium-kalium', 31),
(124, 'difusi dan osmosis.', 31),
(125, 'perpindahan molekul-molekul zat dari konsentrasi tinggi ke konsentrasi rendah baik melalui membran plasma ataupun tidak.', 32),
(126, 'perpindahan molekul-molekul pelarut (misal air) dari larutan berkonsentrasi rendah (hipotonik) ke larutan berkonsentrasi tinggi (hipertonik) melalui selaput (membran) semipermeabel.', 32),
(127, 'perpindahan molekul air melalui membran semipermeabel dari larutan yang kadar airnya tinggi ke larutan yang kadar airnya rendah', 32),
(128, 'perpindahan molekul atau ion tanpa menggunakan energi sel.', 32),
(129, '1 dan 2', 33),
(130, '2 dan 3', 33),
(131, '1 dan 3', 33),
(132, 'Semua jawaban benar', 33),
(133, 'eksosmosis', 34),
(134, 'endosmosis', 34),
(135, 'plasmolisis', 34),
(136, 'eksosmosis dan endosmosis', 34),
(137, 'kehancuran sel karena robeknya membran plasma (lisis).', 35),
(138, 'kerusakan sel.', 35),
(139, 'terlepasnya membran dari dinding sel', 35),
(140, 'pengerutan sel (krenasi).', 35),
(141, 'membran', 36),
(142, 'badan golgi', 36),
(143, 'ribosom', 36),
(144, 'RNA', 36),
(145, 'proses penerjemahan gen menjadi urutan asam amino yang akan disintesis menjadi polipeptida (protein)', 37),
(146, 'proses pelekatan antara tRNA dengan asam amino dengan bantuan enzim aminoasil-tRNA sintetase.', 37),
(147, 'Proses transkripsi diawali dari sintesis RNA dari salah satu rantai DNA sense atau rantai cetakan. Adapun rantai DNA komplemennya disebut rantai antisense', 37),
(148, 'menentukan tempat dimulainya transkripsi dan menentukan satu rantai DNA yang akan digunakan sebagai cetakan', 37),
(149, 'proses penerjemahan gen menjadi urutan asam amino yang akan disintesis menjadi polipeptida (protein)', 38),
(150, 'proses pelekatan antara tRNA dengan asam amino dengan bantuan enzim aminoasil-tRNA sintetase.', 38),
(151, 'Proses transkripsi diawali dari sintesis RNA dari salah satu rantai DNA sense atau rantai cetakan. Adapun rantai DNA komplemennya disebut rantai antisense', 38),
(152, 'menentukan tempat dimulainya transkripsi dan menentukan satu rantai DNA yang akan digunakan sebagai cetakan', 38),
(153, 'Inisiasi', 39),
(154, 'Elongasi', 39),
(155, 'Translasi', 39),
(156, 'Terminasi', 39),
(157, '2', 40),
(158, '3', 40),
(159, '4', 40),
(160, '5', 40),
(161, 'mengatalis pembentukan ikatan peptida antara asam amino yang baru', 41),
(162, 'memberi sinyal untuk menghentikan translasi', 41),
(163, 'membawa kode untuk membentuk asam amino metionin', 41),
(164, 'melepas dari ribosom menuju ke sitoplasma', 41),
(165, 'Hormon', 42),
(166, 'Enzim', 42),
(167, 'Protein', 42),
(168, 'DNA', 42),
(169, 'menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 43),
(170, 'menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 43),
(171, 'dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 43),
(172, 'semua jawaban salah.', 43),
(173, 'Reproduksi sel', 44),
(174, 'Pembelahan mitosis', 44),
(175, 'pembelahan amitosis', 44),
(176, 'pembelahan meiosis', 44),
(177, 'dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 45),
(178, 'untuk menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 45),
(179, 'untuk menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 45),
(180, 'untuk pertumbuhan dan regenerasi sel.', 45),
(181, '1,2,5', 46),
(182, '3,2,4', 46),
(183, '5,4,1', 46),
(184, '1,2,3,4,5', 46),
(185, 'adanya penambahan jumlah sel sebagai hasil pembelahan mitosis. ', 47),
(186, 'Pertumbuhan umumnya terjadi pada daerah meristem (titik tumbuh)', 47),
(187, 'terdapat pada ujung akar dan ujung batang.', 47),
(188, 'peristiwa pembelahan sel secara mitosis', 47),
(189, 'menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 48),
(190, 'menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 48),
(191, 'dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 48),
(192, 'semua jawaban salah.', 48),
(193, 'proses penggandaan materi genetik (DNA) yang terdapat di dalam nukleus sehingga menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 49),
(194, 'pembelahan mitosis sehingga dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 49),
(195, 'peristiwa pembelahan sel yang terjadi pada sel-sel somatis serta menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 49),
(196, 'suatu proses pertambahan ukuran, baik volume, bobot, dan jumlah sel yang bersifat irreversible.', 49),
(197, 'Ini saya lagi tes', 50),
(198, 'ini saya lagi tes', 50),
(199, 'ini saya lagi tes', 50);

-- --------------------------------------------------------

--
-- Table structure for table `pelajaran`
--

CREATE TABLE `pelajaran` (
  `id_mapel` int NOT NULL,
  `mapel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelajaran`
--

INSERT INTO `pelajaran` (`id_mapel`, `mapel`) VALUES
(1, 'Biologi'),
(2, 'Matematika');

-- --------------------------------------------------------

--
-- Table structure for table `pre_test_answer`
--

CREATE TABLE `pre_test_answer` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `modul_1` int NOT NULL,
  `modul_2` int NOT NULL,
  `modul_3` int NOT NULL,
  `modul_4` int NOT NULL,
  `modul_5` int NOT NULL,
  `modul_6` int NOT NULL,
  `modul_7` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_test_answer`
--

INSERT INTO `pre_test_answer` (`id`, `student_id`, `modul_1`, `modul_2`, `modul_3`, `modul_4`, `modul_5`, `modul_6`, `modul_7`) VALUES
(185, 23, 0, 0, 0, 0, 0, 0, 0),
(186, 25, 0, 0, 0, 0, 0, 1, 0),
(187, 34, 0, 0, 0, 0, 0, 1, 0),
(188, 24, 0, 1, 0, 0, 0, 0, 0),
(189, 38, 0, 0, 0, 0, 0, 0, 0),
(190, 17, 0, 1, 1, 0, 1, 0, 0),
(191, 22, 0, 0, 1, 0, 1, 1, 0),
(192, 11, 1, 1, 0, 1, 0, 1, 1),
(193, 16, 0, 0, 1, 1, 0, 0, 0),
(194, 19, 1, 1, 0, 0, 0, 0, 1),
(195, 48, 0, 1, 0, 0, 0, 0, 0),
(196, 43, 1, 0, 1, 1, 0, 0, 0),
(197, 51, 1, 1, 0, 1, 0, 1, 0),
(198, 59, 0, 0, 1, 0, 0, 0, 1),
(199, 72, 1, 1, 1, 1, 0, 1, 1),
(200, 75, 1, 1, 1, 1, 1, 1, 0),
(201, 57, 0, 0, 0, 1, 0, 1, 0),
(202, 88, 1, 1, 0, 1, 0, 1, 0),
(203, 91, 1, 1, 0, 1, 1, 1, 0),
(204, 49, 1, 1, 1, 1, 1, 1, 1),
(205, 50, 1, 1, 1, 1, 0, 1, 0),
(206, 94, 0, 0, 0, 0, 0, 0, 0),
(207, 95, 0, 0, 0, 0, 0, 0, 0),
(208, 97, 1, 1, 0, 1, 0, 1, 0),
(209, 98, 1, 1, 1, 1, 1, 1, 1),
(210, 99, 1, 0, 0, 1, 0, 0, 1),
(211, 100, 1, 0, 0, 0, 0, 0, 0),
(212, 101, 1, 1, 1, 1, 1, 1, 1),
(213, 102, 0, 0, 0, 0, 0, 0, 0),
(214, 103, 1, 1, 1, 1, 1, 1, 1),
(215, 104, 1, 1, 1, 1, 1, 1, 1),
(216, 105, 0, 1, 1, 1, 1, 1, 1),
(217, 107, 1, 1, 1, 1, 1, 1, 1),
(218, 108, 0, 0, 0, 0, 0, 0, 0),
(219, 109, 1, 1, 1, 1, 1, 1, 1),
(220, 110, 1, 1, 1, 1, 1, 1, 1),
(221, 111, 0, 1, 0, 0, 0, 0, 0),
(222, 112, 0, 0, 1, 0, 0, 0, 0),
(223, 113, 0, 1, 0, 0, 0, 0, 1),
(224, 114, 1, 0, 0, 0, 0, 0, 0),
(225, 116, 0, 0, 0, 0, 1, 0, 1),
(226, 118, 1, 1, 0, 0, 0, 0, 0),
(227, 119, 0, 0, 0, 1, 0, 1, 0),
(228, 120, 1, 0, 0, 1, 0, 0, 0),
(229, 121, 0, 0, 0, 0, 0, 0, 0),
(230, 122, 0, 0, 0, 0, 0, 0, 0),
(231, 123, 0, 0, 0, 0, 0, 0, 0),
(232, 124, 0, 0, 0, 0, 0, 0, 0),
(233, 125, 0, 0, 0, 0, 0, 0, 0),
(234, 126, 0, 0, 0, 0, 0, 0, 0),
(235, 127, 0, 0, 0, 0, 0, 0, 1),
(236, 128, 0, 0, 0, 0, 0, 0, 0),
(237, 129, 1, 1, 1, 1, 1, 1, 1),
(238, 130, 1, 1, 1, 1, 1, 1, 1),
(239, 131, 1, 1, 1, 1, 1, 1, 1),
(240, 132, 1, 1, 1, 1, 1, 1, 1),
(241, 133, 1, 0, 0, 0, 1, 0, 0),
(242, 134, 0, 0, 0, 0, 0, 0, 1),
(243, 140, 0, 0, 0, 0, 0, 0, 0),
(244, 142, 0, 0, 0, 0, 0, 0, 0),
(245, 411, 0, 0, 0, 0, 0, 1, 0),
(247, 411, 0, 0, 1, 0, 0, 0, 0),
(248, 411, 0, 1, 0, 0, 0, 0, 0),
(249, 411, 0, 0, 0, 0, 0, 0, 0),
(250, 411, 0, 0, 0, 0, 0, 0, 1),
(251, 412, 0, 0, 0, 0, 0, 0, 1),
(252, 418, 0, 0, 0, 1, 0, 0, 0),
(253, 420, 0, 1, 1, 0, 0, 0, 0),
(254, 421, 1, 1, 0, 0, 0, 1, 0),
(255, 148, 0, 0, 0, 0, 0, 1, 0),
(256, 422, 0, 0, 0, 0, 0, 0, 0),
(257, 423, 0, 0, 0, 0, 0, 0, 0),
(258, 424, 1, 1, 1, 1, 1, 1, 1),
(259, 425, 1, 1, 1, 1, 1, 1, 1),
(260, 425, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pre_test_result`
--

CREATE TABLE `pre_test_result` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `level` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_test_result`
--

INSERT INTO `pre_test_result` (`id`, `student_id`, `level`) VALUES
(192, 91, 11),
(193, 92, 12),
(194, 93, 13),
(195, 411, 2),
(196, 91, 11),
(197, 92, 12),
(198, 93, 13),
(199, 91, 11),
(200, 92, 12),
(201, 93, 13),
(202, 91, 11),
(203, 92, 12),
(204, 93, 13),
(205, 412, 2),
(206, 91, 11),
(207, 92, 12),
(208, 93, 13),
(209, 91, 11),
(210, 92, 12),
(211, 93, 13),
(212, 93, 13),
(213, 418, 2),
(214, 91, 11),
(215, 92, 12),
(216, 93, 13),
(217, 93, 13),
(218, 91, 11),
(219, 92, 12),
(220, 93, 13),
(221, 93, 13),
(222, 93, 13),
(223, 420, 2),
(224, 93, 13),
(225, 421, 2),
(226, 91, 11),
(227, 92, 12),
(228, 93, 13),
(229, 93, 13),
(230, 93, 13),
(231, 93, 13),
(232, 91, 11),
(233, 92, 12),
(234, 93, 13),
(235, 91, 11),
(236, 92, 12),
(237, 93, 13),
(238, 93, 13),
(239, 93, 13),
(240, 93, 13),
(241, 91, 11),
(242, 92, 12),
(243, 93, 13),
(244, 424, 2),
(245, 91, 11),
(246, 92, 12),
(247, 93, 13),
(248, 425, 3);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_result`
--

CREATE TABLE `quiz_result` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `nilai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz_result`
--

INSERT INTO `quiz_result` (`id`, `student_id`, `nilai`) VALUES
(10, 24, 210),
(11, 57, 195),
(12, 94, 186),
(13, 97, 133),
(14, 99, 80),
(15, 19, 88),
(16, 23, 84),
(17, 113, 76),
(18, 114, 61),
(19, 111, 47),
(20, 11, 69),
(21, 34, 69),
(22, 16, 84),
(23, 109, 90),
(24, 110, 84),
(25, 17, 88),
(26, 112, 73),
(27, 50, 76),
(28, 51, 80),
(29, 25, 71),
(30, 118, 73),
(31, 116, 80);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_result_e_learning`
--

CREATE TABLE `quiz_result_e_learning` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `nilai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz_result_e_learning`
--

INSERT INTO `quiz_result_e_learning` (`id`, `student_id`, `nilai`) VALUES
(3, 26, 33),
(4, 12, 37),
(5, 13, 39),
(6, 32, 43),
(7, 27, 45),
(8, 18, 41),
(9, 15, 37),
(10, 21, 41),
(11, 44, 45),
(12, 30, 59),
(13, 31, 51),
(14, 53, 10),
(15, 52, 53),
(16, 58, 57),
(17, 59, 49),
(18, 67, 43),
(19, 76, 69),
(20, 77, 49),
(21, 78, 86),
(22, 79, 49),
(23, 80, 73),
(24, 81, 57),
(25, 82, 57),
(26, 83, 61),
(27, 84, 69),
(28, 85, 73),
(29, 86, 71),
(30, 87, 82),
(31, 33, 47),
(32, 140, 22),
(33, 411, 31);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `nis` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`, `nis`, `student_name`, `student_address`, `phone_number`, `class_id`) VALUES
(11, 262, '9988', 'Izlah Dwi Putri Aprilia ', 'Kalianget Timur', '081331339828', 3),
(12, 264, '9961', 'Syifa Aulya Fahrun Nisa\'', 'Brambang', '0881-0258-41801', 3),
(13, 265, '10094', 'Nurisa Hidayanti', 'Marengan laok', '0877-8189-7702', 3),
(15, 267, '10201', 'Syarifatul Arifa ', 'Dsn jubbluk barat', '0819-1627-1141', 3),
(16, 268, '10116', 'Heni Oktavia', 'Marengan laok', '087701787556', 3),
(17, 269, '9985', 'Ika Yuli Astiana ', 'Kalimo\'ok', '081938073316', 3),
(18, 270, '0058396450', 'Sofi aprilianti ', 'Kalianget timur', '085216590582', 3),
(19, 271, '10005', 'Adelia Mila Safitri', 'Karanganyar', '087864004334', 3),
(21, 273, '10193', 'Rani Sahara', 'Perum Trunojoyo regency', '082335977664', 3),
(26, 278, '9999', 'Ramadhani Nur Indah Putri', 'Perumahan Kalimook ', '081908079203', 3),
(27, 279, '9993', 'MILA ABRILIANTI', 'DUSUN KEBUN KELAPA', '087806487249', 3),
(30, 284, '10066', 'Rina kurniawati', 'kalianget', '085230794865', 3),
(31, 285, '9951', 'Nadlinatul Maghfiroh aulia', 'Jln pelabuhan kertasada', '895324220259', 3),
(32, 287, '9954', 'Nurul Latifa', 'Dusun Kebun Kelapa', '087838034197', 3),
(33, 288, '9997', 'Mohammad Baldy lawaahidz', 'Dusun padurekso ', '082336967778', 3),
(34, 289, '10020', 'Junaedi Arista ', 'Kalianget barat', '083135601355', 3),
(50, 313, '10111', 'Aulia Wulan Septi Ramadhani', 'Jl Lojikantang', '087889023052', 3),
(51, 314, '9977', 'Dia Ulfa Marissa', 'Kalbar', '087856185413', 3),
(52, 315, '10022', 'Moh Karim', 'Kalianget', '083135601322', 3),
(53, 316, '9950', 'Nadia Duta Salsabila', 'Marengan', '085941257729', 3),
(58, 321, '9949', 'Moh Hafidz Abdillah', 'Talango', '081808415951', 3),
(59, 322, '10164', 'Rindiani', 'Pinggir Papas', '081919144992', 3),
(76, 342, '10030', 'Selly Agustini Putri', 'Kalianget', '087765970700', 3),
(87, 353, '10053', 'Mery Puspita Sari', 'Marengan', '087854978609', 3),
(109, 376, '9984', 'HANIYASIH', 'KALIMO\'OK', '087857735961', 3),
(110, 377, '9991', 'Lufiana Meisya Warningsih', 'Kalianget Barat', '082260086739', 3),
(111, 378, '10152', 'medi efa nurrahman', 'gapurana', '085733178663', 3),
(112, 379, '9972', 'Alif nurrahman ', 'Marengan Laok', '085856286400', 3),
(113, 380, '10011', 'Aria Hasanudin ', 'Marengan Laok', '081334422763', 3),
(114, 381, '10012', 'Arifatus Sa\'adah ', 'Kalianget timur', '085204710086', 3),
(116, 383, '10106', 'Akbar firmansyah', 'Perumahan kalimo\'ok', '082356417948', 3),
(118, 385, '10123', 'Maretha Salsa', 'Pabian ', '085798213850', 3),
(131, 399, '11111', 'Nur Faizah', 'Tuban', '085695507327', 1),
(132, 400, '22222', 'Ana M', 'Sumenep', '085232316207', 1),
(133, 401, '324', 'firaz', 'bangkalan', '12345567', 1),
(135, 403, '123', 'faiz', 'tuban', '085678999000', 4),
(136, 402, '12345', '12345', 'tuban', '085666777888', 4),
(137, 405, '1234567890', 'Alful Laila S', 'Pamekasan', '0000', 4),
(138, 406, '123456', 'afra', 'bangkalan', '085232828220', 1),
(139, 407, '220', 'tes12', 'tes12', '007', 1),
(140, 408, '11', '11', '11', '11', 1),
(151, 425, '115', 'Sigma ', '12321', '14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_topic`
--

CREATE TABLE `sub_topic` (
  `id` int NOT NULL,
  `sub_topic_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic_id` int NOT NULL,
  `number` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_topic`
--

INSERT INTO `sub_topic` (`id`, `sub_topic_desc`, `topic_id`, `number`) VALUES
(1, 'Konsep Sel dan Komponen Kimiawi Penyusun Sel', 1, 1),
(2, 'Struktur dan Fungsi Bagian-Bagian Sel', 1, 2),
(3, 'Bioproses dalam Sel', 1, 3),
(4, 'Struktur dan Fungsi Jaringan Tumbuhan', 2, 1),
(5, 'Jaringan Penyusun Organ Tumbuhan', 2, 2),
(6, 'Kultur Jaringan Tumbuhan', 2, 3),
(7, 'Struktur, Letak, dan Fungsi Jaringan Hewan Vertebrata', 3, 1),
(8, 'Teknologi yang Berkaitan dengan Jaringan Hewan', 3, 2),
(9, 'Alat Gerak Pasif', 4, 1),
(10, 'Kelainan dan Teknologi yang Berhubungan dengan Sistem Gerak', 4, 2),
(11, 'Darah', 5, 1),
(12, 'Alat-Alat Peredaran Darah dan Mekanisme Peredaran Darah', 5, 2),
(13, 'Kelainan-Kelainan dan Teknologi yang Berkaitan dengan Sistem Peredaran Darah', 5, 3),
(14, 'Zat-Zat Makanan yang Diperlukan Tubuh', 6, 1),
(15, 'Struktur dan Fungsi Sistem Pencernaan Manusia dan Ruminansia', 6, 2),
(16, 'Sistem Pernapasan pada Manusia', 7, 1),
(17, 'Sistem Pernapasan pada Hewan', 7, 2),
(18, 'Kelainan-Kelainan pada Sistem Pernapasan Manusia', 7, 3),
(19, 'Proses Ekskresi pada Manusia', 8, 1),
(20, 'Gangguan dan penyakit pada Sistem Ekskresi', 8, 2),
(21, 'Sistem Saraf Manusia', 9, 1),
(22, 'Sistem Endokrin (Hormon) Manusia', 9, 2),
(23, 'Sistem Indra Manusia', 9, 3),
(24, 'Gangguan dan Pengaruh Psikotropika pada Sistem Koordinasi Manusia', 9, 4),
(25, 'Struktur dan Fungsi Alat Reproduksi Manusia', 10, 1),
(26, 'Proses-Proses yang Berlangsung dalam Organ Reproduksi', 10, 2),
(27, 'Keterkaitan Antara Kesehatan Reproduksi dengan Program KB dan Kependudukan', 10, 3),
(28, 'Mekanisme Pertahanan Tubuh', 11, 1),
(29, 'Jenis-Jenis Kekebalan dan Gangguan pada Sistem Kekebalan Tubuh', 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `survey_question`
--

CREATE TABLE `survey_question` (
  `id` int NOT NULL,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_mapel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_question`
--

INSERT INTO `survey_question` (`id`, `question`, `category`, `id_mapel`) VALUES
(1, 'Saya merasa senang mengikuti pelajaran biologi selama pembelajaran daring', '1', 1),
(2, 'Saya tidak pernah mengeluh jika ada tugas biologi', '1', 1),
(3, 'Saya sering hadir pada saat pelajaran biologi', '1', 1),
(4, 'Apabila mengalami kesulitan dalam memahami materi, saya bertanya.', '1', 1),
(5, 'Tugas yang diberikan guru membuat saya semakin tertatik dengan biologi.', '1', 1),
(6, 'Ketika di rumah saya memilih belajar daripada bermain biologi.', '2', 1),
(7, 'Tanpa ada yang menyuruh, saya belajar biologi sendiri di rumah. ', '2', 1),
(8, 'Saya tidak merasa kesulitan dalam memahami materi-materi biologi', '2', 1),
(9, 'Saat ulangan saya sering mendapat nilai > 75', '2', 1),
(10, 'Saya pernah mengikuti olimpiade biologi', '2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `survey_result`
--

CREATE TABLE `survey_result` (
  `id` int NOT NULL,
  `level_result` int NOT NULL,
  `student_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_result`
--

INSERT INTO `survey_result` (`id`, `level_result`, `student_id`) VALUES
(170, 3, 23),
(171, 2, 25),
(172, 3, 34),
(173, 2, 24),
(174, 3, 38),
(175, 3, 22),
(176, 2, 16),
(177, 2, 17),
(178, 3, 11),
(179, 2, 19),
(180, 2, 43),
(181, 3, 48),
(182, 3, 51),
(183, 3, 59),
(184, 3, 72),
(185, 3, 75),
(186, 3, 57),
(187, 3, 91),
(188, 3, 88),
(189, 2, 49),
(190, 3, 50),
(191, 1, 94),
(192, 1, 95),
(193, 3, 97),
(194, 3, 98),
(195, 1, 99),
(196, 3, 100),
(197, 3, 101),
(198, 1, 102),
(199, 3, 103),
(200, 3, 104),
(201, 3, 105),
(202, 3, 107),
(203, 1, 108),
(204, 3, 109),
(205, 3, 110),
(206, 1, 111),
(207, 2, 112),
(208, 2, 113),
(209, 1, 114),
(210, 1, 116),
(211, 1, 118),
(212, 2, 119),
(213, 2, 120),
(214, 1, 121),
(215, 1, 122),
(216, 2, 123),
(217, 1, 124),
(218, 2, 125),
(219, 1, 126),
(220, 1, 127),
(221, 1, 128),
(222, 3, 129),
(223, 3, 130),
(224, 3, 131),
(225, 3, 132),
(226, 1, 133),
(227, 3, 140),
(228, 2, 142),
(229, 2, 411),
(230, 2, 411),
(231, 2, 412),
(232, 3, 418),
(233, 3, 411),
(234, 3, 420),
(235, 3, 421),
(236, 3, 148),
(237, 2, 422),
(238, 2, 423),
(239, 2, 424),
(240, 2, 425);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `teacher_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_type` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `teacher_name`, `teacher_address`, `nip`, `phone_number`, `email`, `teacher_type`) VALUES
(54, 391, 'Safwan, S.Pd., M.Si.', 'Kalianget', '197012221998021004', '08179374443', NULL, '1'),
(55, 413, 'sdfsdf', 'sdfsdf', NULL, '123', 'yantooo@gmail.com', '2'),
(56, 414, 'Muhammad Kurniawan Dwi Hariyadi', 'Jl. Pemuda Gg. Yakup No 8 RT 26 RW 03', NULL, '0895623464379', 'yantooo123123@gmail.com', '2'),
(57, 415, 'Muhammad Kurniawan Dwi Hariyadi', 'Jl. Pemuda Gg. Yakup No 8 RT 26 RW 03', '123123', '0895623464379', NULL, '1'),
(58, 416, 'azrel', 'asdasdas', NULL, '123123123', 'azrel@gmail.com', '2'),
(59, 417, 'azrel2', 'Jl. Pemuda Gg. Yakup No 8 RT 26 RW 03', '234', '0895623464379', NULL, '1'),
(60, 419, 'Jono', '123', '333', '081289910359', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `id` int NOT NULL,
  `topic_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int NOT NULL,
  `id_mapel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`id`, `topic_desc`, `number`, `id_mapel`) VALUES
(1, 'Sel sebagai Unit Terkecil Kehidupan dan Bioproses pada Sel', 1, 1),
(2, 'Struktur dan Fungsi Sel Penyusun Jaringan pada Tumbuhan', 2, 1),
(3, 'Struktur dan Fungsi Sel Penyusun Jaringan Hewan', 3, 1),
(4, 'Struktur dan Fungsi Sel Penyusun Jaringan pada Sistem Gerak', 4, 1),
(5, 'Struktur dan Fungsi Sel Penyusun Jaringan pada Sistem Sirkulasi', 5, 1),
(6, 'Struktur dan Fungsi Sel Penyusun Jaringan pada Sistem Pencernaan', 6, 1),
(7, 'Struktur dan Fungsi Sel Penyusun Jaringan pada Sistem Pernapasan/Respirasi', 7, 1),
(8, 'Struktur dan Fungsi Sel Penyusun Jaringan pada Sistem Ekskresi', 8, 1),
(9, 'Struktur dan Fungsi Sel pada Sistem Koordinasi Manusia', 9, 1),
(10, 'Struktur dan Fungsi Sel Penyusun Jaringan pada Sistem Reproduksi', 10, 1),
(11, 'Struktur dan Fungsi Sel-Sel Penyusun Jaringan dalam Sistem Pertahanan Tubuh', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_user` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `level_user`) VALUES
(1, 'admin1@gmail.com', '$2a$10$QGFZIfjT05AN6pUJzhhicenl0d/49i6aNiz0tUUhbywdfiJGs1khe', '1'),
(261, '', '$2y$10$/addiHCTTDAnwv0Mnje9/O12RIePAjk.VnIDDd177A1e5J7WQHD5m', '3'),
(262, '9988', '$2y$10$oaCgjf1TN1FRRvDjl53PzuKiozNfOa275etYpiGmeyT6tNniZTj/6', '3'),
(264, '9961', '$2y$10$0twl4g68RSDTCceCXrA01.ncgFQs4Dc2nnE67TpNp0K/v1wGrckhq', '3'),
(265, '10094', '$2y$10$jh7LbjwwLWo.Nv9mrw8ZV.dJ7J2An/EqM6wYM2zGu2stYrL.m9WtW', '3'),
(267, '10201', '$2y$10$O7HGDRmu9t9E12rjeldNReDMo8EoELogObyaM4FE4T5yYkkoySPuO', '3'),
(268, '10116', '$2y$10$acj1PKkn3oJcYXApi3riBePGD1P5tek0dI.Q7s6sh51Y/XpjQ3zpO', '3'),
(269, '9985', '$2y$10$zbhH.1U6uuLqbP4twDR.Nu5AP9bQinkcrHfVA7ZvDwIItq1KgNv6m', '3'),
(270, '0058396450', '$2y$10$VigEMVhUhnHJsUhF8zzdCepv5a75KG.ZlNUnbgD58DOsSEBYd0OCG', '3'),
(271, '10005', '$2y$10$scrnGgOlNLO6Z2rBYZ9T.ugIFv32kAm9Xi5qqD/QSKmijIjflnze6', '3'),
(273, '10193', '$2y$10$MPFyEfdM/3vvVr459gpfoe7O5JJ6hz9cKPwPH4u6fiEv4f/Mi7/ty', '3'),
(278, '9999', '$2y$10$ehkLZa5lfGOTpsfwRM07cuNZwbgbi42tdaLgjMM3FZU2MMkH0u/wG', '3'),
(279, '9993', '$2y$10$EZto5YnirLDOY8MUOtwLd.7fHsRSRV9HQ/wYH.2CQJFHYNtKwy7vu', '3'),
(284, '10066', '$2y$10$Ywqj4z8gjJoWJfwLH1omwe5Kzmywlg3W167jqBAJ54hG37Xc6OUhe', '3'),
(285, '9951', '$2y$10$xuLTU3meft7QpdGD/aoJZeTwKXg88vRuA15hRK9XIvOFq52UBODUC', '3'),
(287, '9954', '$2y$10$Cm4WM4IkYR5nafBOS9Hi7eIq1erdg0hLA94t2C1InWcqr2lltm7ta', '3'),
(288, '9997', '$2y$10$xNl/7hMVqf6gI/XYe3n1VeV6etktz.BS47J.imh7pNQoJu0PttqzO', '3'),
(289, '10020', '$2y$10$2Y8CNzzosrrekmDplCSQYuyB50LiWXwFY1lshjZC9WUbUbD64BX5q', '3'),
(313, '10111', '$2y$10$CHDJT6eT/HgVBAj0XZXjeeeLUUafvQJpf859cY55O1WNYziTQn8s.', '3'),
(314, '9977', '$2y$10$6I8T8bKteJyp.Lfe8tBB2OnDaK3OlLee6EtWMd03/7mYC8ZhISycC', '3'),
(315, '10022', '$2y$10$hGNQHBCE2zOOPfz5GyM8K.mHD4HT035zFFGNThG8Ri0wgewPn2hc6', '3'),
(316, '9950', '$2y$10$1mYEAQJ.DPfBc0PghDp1KOE1X7E9cnhiUHojzaqK86ovAQf4YLZq2', '3'),
(321, '9949', '$2y$10$Xc5t46n00Q6A6BTk2jbN1.eNrQscjlzpUOYek16NKrQgm8GuhBZTu', '3'),
(322, '10164', '$2y$10$5a4C7OqAincjekQY.dXsyORp/DkVznL6XFr2emkvMTuDutIFbqvkC', '3'),
(342, '10030', '$2y$10$GLXT1agPC0jKrxbxf7q9Seo4qb0vW9SH4LyPWR4gbWWPuN0npuM/q', '3'),
(353, '10053', '$2y$10$W6P39LSLPKri2AOI.BPTgecfd/bGss38hYG3rU5DaDJtH2s60EJre', '3'),
(376, '9984', '$2y$10$7AieTYQXrD3tlFVt/qcmsutrPTjipospyLwbPd4sevoAFXqS3hxVq', '3'),
(377, '9991', '$2y$10$fKciyUEFydjkMn0Enk3tYO3MZU96Qfu/8n3EZDQZTKNtr8Jg3oHwq', '3'),
(378, '10152', '$2y$10$BeHlbXWBz.VMpO4ge2a5puhjO5LY8OYbZvU3awvI4N3P5CM2xYiry', '3'),
(379, '9972', '$2y$10$3HweW3w3DnE8bkUx2h2dfehKwF5nU0acbPTK2AZ71F5UGJhae8qEO', '3'),
(380, '10011', '$2y$10$TKGaNfhZaKmPRoG7hKKCF.WpujgeliScbSazI5Hf/VcWuLGWNpawm', '3'),
(381, '10012', '$2y$10$gUI9ZU0qZQuJuOgA.dajxuhQQc44Y/4l70gdbbUjVBG1DV2tYF8ny', '3'),
(383, '10106', '$2y$10$HEw7ZHCRpFTcuDg/Lhhdcu.wglW/7AbquwI47UJCOGjB4eqR.C3au', '3'),
(385, '10123', '$2y$10$uSA5F6K8f1ryA1Y1UxqUKeDja3hqt5tAOtxQPIyehmJ8sUProvOuu', '3'),
(391, '197012221998021004', '$2y$10$fvJSIT9HU/B2phQvvjSxNe39BJcpE23IwCP8XWB9B8S8YTDHDxfeC', '2'),
(399, '11111', '$2y$10$cUiFu2hogBV4K.dROiybJ.dHX7u6Tk5UaKLdCHcjompezqmWvEFHG', '3'),
(400, '22222', '$2y$10$yQGz85J0BfKqxTAhCTKsUuMhiD7ypfFkHaDfdwGjOA0y9Ls0EKohy', '3'),
(401, '324', '$2a$10$QGFZIfjT05AN6pUJzhhicenl0d/49i6aNiz0tUUhbywdfiJGs1khe', '3'),
(403, '123', '$2y$10$lGs3LyvN4Rhmv5F/n6Rhu.KANminVCBmbWay65Jsr2Zjw/0SM9Ewy', '3'),
(404, '12345', '$2y$10$DiScLbBr5I.R2wq.wpu5Se1yO0hwQroit1TDoFl2.WMV.ZhQ55996', '3'),
(405, '1234567890', '$2y$10$qPTSB43WuxcPrcYftvPxR.6gp9UtClHcBjxR0EQayfBVKRlKbangK', '3'),
(406, '123456', '$2y$10$vKO.j0bdW/lxRQnIlDkIQeTqdmc9wOca9qvg4ieQ1Gkj1gJfpyyW2', '3'),
(407, '220', '$2y$10$19LSiHJVDaoBoiPebeoKzOeMDEo1ceWwzLPsHL/4YMc7yCDRmKhPS', '3'),
(408, '11', '$2y$10$RE0/x/Tkm1f4P0WjcQVDpuIxDQuh8fBGfL9tZkJtjF/ZSb8twH6nS', '3'),
(413, 'yantooo@gmail.com', '$2y$10$U/mwnAW0Io.aFUQfms9igOo5Tt9C7uYyC/5hjSTISygu8KB.ddz/.', '2'),
(414, 'yantooo123123@gmail.com', '$2y$10$DxAQ9Q/kSkrZpRJge4I9Y.0iwwNFcwvd98QqUfvLwz7.sl4nmza0.', '2'),
(415, '123123', '$2y$10$SxcNmuCPFkIU.KxdIl17yOvgQKaTyuD7vJzk0njEvifdT9.SwEUGe', '2'),
(416, 'azrel@gmail.com', '$2y$10$LWFrxL275lQYbx36aKaDVOu0iVaWu3aeJ5YKoX8sae5SoASyBRJpy', '2'),
(417, '234', '$2y$10$fPuAPPMAa/cte9DzT26MMe3U/rU9Nf7R6cAHOpH2rjpkISyQOHLgC', '2'),
(419, '333', '$2y$10$CvleSWYQJpr9YBF5sqI1y.T86Fw4dwIkmCUHwskc5D28IGUzq3dhS', '2'),
(425, '115', '$2y$10$ohQL/DvHRHEcdWJp5yKLUONFDp7co/bFgdDRGj7kDTVBdUHb50lj.', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_attendance`
--
ALTER TABLE `class_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gagal_post_test`
--
ALTER TABLE `gagal_post_test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `level_student`
--
ALTER TABLE `level_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_learned`
--
ALTER TABLE `module_learned`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_question`
--
ALTER TABLE `module_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_question_choice`
--
ALTER TABLE `module_question_choice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelajaran`
--
ALTER TABLE `pelajaran`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `pre_test_answer`
--
ALTER TABLE `pre_test_answer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_test_result`
--
ALTER TABLE `pre_test_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_result`
--
ALTER TABLE `quiz_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_result_e_learning`
--
ALTER TABLE `quiz_result_e_learning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_topic`
--
ALTER TABLE `sub_topic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survey_question`
--
ALTER TABLE `survey_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `survey_result`
--
ALTER TABLE `survey_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class_attendance`
--
ALTER TABLE `class_attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `gagal_post_test`
--
ALTER TABLE `gagal_post_test`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `level_student`
--
ALTER TABLE `level_student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `module_learned`
--
ALTER TABLE `module_learned`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `module_question`
--
ALTER TABLE `module_question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `module_question_choice`
--
ALTER TABLE `module_question_choice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `pelajaran`
--
ALTER TABLE `pelajaran`
  MODIFY `id_mapel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pre_test_answer`
--
ALTER TABLE `pre_test_answer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `pre_test_result`
--
ALTER TABLE `pre_test_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `quiz_result`
--
ALTER TABLE `quiz_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `quiz_result_e_learning`
--
ALTER TABLE `quiz_result_e_learning`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `sub_topic`
--
ALTER TABLE `sub_topic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `survey_question`
--
ALTER TABLE `survey_question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `survey_result`
--
ALTER TABLE `survey_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `survey_question`
--
ALTER TABLE `survey_question`
  ADD CONSTRAINT `survey_question_ibfk_1` FOREIGN KEY (`id_mapel`) REFERENCES `pelajaran` (`id_mapel`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`id_mapel`) REFERENCES `pelajaran` (`id_mapel`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
