-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 22, 2025 at 07:18 AM
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
(17, 1, 60),
(18, 1, 61),
(19, 3, 61);

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
(104, 429, 2),
(105, 430, 3),
(115, 444, 2),
(116, 445, 2),
(117, 446, 2),
(118, 447, 1),
(119, 448, 1),
(120, 448, 1);

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
(7, 'Reproduksi Sel', 3, 7, '2');

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
(133, 2, 429),
(139, 6, 451),
(140, 7, 451),
(141, 1, 451);

-- --------------------------------------------------------

--
-- Table structure for table `module_prerequisites`
--

CREATE TABLE `module_prerequisites` (
  `id` int NOT NULL,
  `module_id` int NOT NULL,
  `prerequisite_module_id` int NOT NULL,
  `weight` decimal(3,2) DEFAULT '1.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `module_prerequisites`
--

INSERT INTO `module_prerequisites` (`id`, `module_id`, `prerequisite_module_id`, `weight`) VALUES
(1, 2, 1, '1.00'),
(2, 3, 2, '1.00'),
(3, 4, 3, '1.00'),
(4, 5, 4, '1.00'),
(5, 6, 5, '1.00'),
(6, 7, 6, '1.00');

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
(1, 1, 'Menurut Campbell, Reece, & Mitchel sel merupakan . . .', 0),
(2, 1, 'Unit struktural dan fungsional terkecil makhluk hidup adalah pengertian dari ...', 0),
(3, 1, 'Sel disebut sebagai unit fungsional terkecil dalam kehidupan karena . . .', 0),
(4, 1, 'Sel dapat melakukan berbagai proses kehidupan misalnya . . .\n<br/>1.	respirasi\n<br/>2.	transportasi\n<br/>3.	reproduksi\n<br/>4.	ekskresi\n<br/>5.	sekresi\n<br/>6.	sintesis\n<br/>Jawaban yang benar dari pilihan diatas adalah . . .', 0),
(5, 1, 'Istilah sel pertama kali dinyatakan oleh . . .', 0),
(6, 1, 'Sel juga merupakan unit . . . makhluk hidup yang menurunkan sifat genetis dari satu generasi kepada generasi berikutnya.', 0),
(7, 1, 'Sel merupakan ruangan-ruangan kecil yang dibatasi oleh dinding adalah pengertian sel menurut . . .', 0),
(8, 2, 'Senyawa kimia penyusun sel disebut . . .', 0),
(9, 2, 'komponen kimia sebuah sel terdiri atas . . .', 0),
(10, 2, 'unsur makro adalah . . .', 0),
(11, 2, 'Senyawa organik terdiri atas . . .', 0),
(12, 2, 'Air merupakan senyawa penyusun sel terbesar. Berapa banyak kontribusi air terhadap sel?', 0),
(13, 2, 'Gas yang terlibat dalam aktivitas sel adalah . .', 0),
(14, 2, 'penyimpan informasi genetis dalam sel dan bersama-sama dengan protein histon membentuk kromosom adalah . . .', 0),
(15, 3, 'berdasarkan strukturnya, sel dapat dibedakan menjadi . . . jenis.', 0),
(16, 3, 'Makhluk hidup yang termasuk prokariotik adalah . . .', 0),
(17, 3, 'Dinding sel berfungsi sebagai . . .', 0),
(18, 3, 'Membran plasma tersusun dari . . .', 0),
(19, 3, 'Struktur sel eukariotik terdiri atas tiga komponen utama yaitu . . .', 0),
(20, 3, 'Macam-macam organel penyusun sel adalah . . .', 0),
(21, 3, '1) Tempat sintesis polisakarida seperti mukus, selulosa, dan hemiselulosa. \n<br/>2) Menetralkan racun (detoksifikasi)\n<br/>3) Menyintesis lemak dan kolesterol\n<br/>4) Membentuk akrosom pada sperma, kuning telur pada sel telur, dan lisosom.\n<br/>Fungsi dari kompleks golgi adalah . . .', 0),
(22, 4, 'Bagian sel yang hanya dimiliki oleh sel tumbuhan yaitu . . .', 0),
(23, 4, 'Sel hewan dan sel tumbuhan termasuk . . .', 0),
(24, 4, 'Sel hewan memiliki . . . yang tidak dimiliki oleh sel tumbuhan.', 0),
(25, 4, 'lapisan terluar yang tersusun dari selulosa, hemiselulosa, dan pektin adalah . . .', 0),
(26, 4, 'Vakuola mempunyai beberapa fungsi sebagai berikut, kecuali . . .', 0),
(27, 4, 'Kromoplas yaitu . . .', 0),
(28, 4, 'Sel hewan memiliki . . . sentriol di dalam sentrosom', 0),
(29, 5, 'Perpindahan molekul atau ion yang melewati membran ada dua macam yaitu . . .', 0),
(30, 5, 'Difusi adalah . . .', 0),
(31, 5, 'Contoh transpor pasif yaitu . . .', 0),
(32, 5, 'Transpor pasif merupakan . . .', 0),
(33, 5, 'Contoh transpor aktif antara lain :\n<br/>1. pompa natrium-kalium, \n<br/>2.endositosis\n<br/>3.eksositosis\n<br/>Contoh transpor aktif yang benar pada soal diatas adalah . . .', 0),
(34, 5, 'Air akan masuk ke dalam sel jika konsentrasi larutan dalam sel tinggi sehingga terjadi . . .', 0),
(35, 5, 'Eksosmosis pada tumbuhan akan mengakibatkan . . .', 0),
(36, 6, 'Sintesis protein berlangsung di  . . .', 0),
(37, 6, 'Sintesis protein adalah  . . .', 0),
(38, 6, 'Translasi adalah . . .', 0),
(39, 6, 'Proses transkripsi akan berhenti setelah sampai pada terminator, yakni urutan DNA yang berfungsi menghentikan transkripsi. Proses ini terjadi pada tahapan . . .', 0),
(40, 6, 'Translasi terdiri atas berapa tahapan?', 0),
(41, 6, 'kodon start AUG bertugas untuk  . . .', 0),
(42, 6, 'Yang berperan mengatur homeostasis, metabolisme, reproduksi, pertumbuhan, dan perkembangan adalah . . .', 0),
(43, 7, 'Reproduksi sel merupakan proses penggandaan materi genetik (DNA) yang terdapat di dalam nukleus sehingga . . .', 0),
(44, 7, 'peristiwa pembelahan sel yang terjadi pada sel-sel somatis serta menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya. Pengertian dari . . .', 0),
(45, 7, 'Pembelahan mitosis bertujuan . . .', 0),
(46, 7, 'Adapun tahap-tahap pembelahan mitosis meliputi :\n<br/>1.profase \n<br/>2.prometafase, \n<br/>3.metafase, \n<br/>4. anafase \n<br/>5.telofase\n<br/>tahap-tahap pembelahan yang benar adalah . . .', 3),
(47, 7, 'Tanaman mengalami pertumbuhan karena . . .', 0),
(48, 7, 'Sel melangsungkan pembelahan mitosis sehingga . . .', 0),
(49, 7, 'Pertumbuhan adalah . . .', 0);

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
(212, '1,2,5', 46),
(213, '3,2,4', 46),
(214, '5,4,1', 46),
(215, '1,2,3,4,5', 46),
(260, 'unsur makro, unsur mikro, senyawa organik', 9),
(261, 'senyawa organik, dan senyawa anorganik.', 9),
(262, 'unsur makro, unsur mikro, senyawa organik, dan senyawa anorganik.', 9),
(263, 'unsur mikro, senyawa organik, dan senyawa anorganik.', 9),
(288, 'proses penggandaan materi genetik (DNA) yang terdapat di dalam nukleus sehingga menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 49),
(289, 'pembelahan mitosis sehingga dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 49),
(290, 'peristiwa pembelahan sel yang terjadi pada sel-sel somatis serta menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 49),
(291, 'suatu proses pertambahan ukuran, baik volume, bobot, dan jumlah sel yang bersifat irreversible.', 49),
(292, 'menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 48),
(293, 'menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 48),
(294, 'dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 48),
(295, 'semua jawaban salah.', 48),
(296, 'adanya penambahan jumlah sel sebagai hasil pembelahan mitosis.', 47),
(297, 'Pertumbuhan umumnya terjadi pada daerah meristem (titik tumbuh)', 47),
(298, 'terdapat pada ujung akar dan ujung batang.', 47),
(299, 'peristiwa pembelahan sel secara mitosis', 47),
(300, 'dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 45),
(301, 'untuk menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 45),
(302, 'untuk menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 45),
(303, 'untuk pertumbuhan dan regenerasi sel.', 45),
(304, 'Reproduksi sel', 44),
(305, 'Pembelahan mitosis', 44),
(306, 'pembelahan amitosis', 44),
(307, 'pembelahan meiosis', 44),
(308, 'menghasilkan sel-sel anakan yang memiliki materi genetik yang identik', 43),
(309, 'menghasilkan dua sel anak dengan genotipe sama dan identik dengan induknya', 43),
(310, 'dapat mengalami perubahan bentuk, ukuran, dan jumlahnya bertambah banyak.', 43),
(311, 'semua jawaban salah.', 43),
(312, 'Hormon', 42),
(313, 'Enzim', 42),
(314, 'Protein', 42),
(315, 'DNA', 42),
(316, 'mengatalis pembentukan ikatan peptida antara asam amino yang baru', 41),
(317, 'memberi sinyal untuk menghentikan translasi', 41),
(318, 'membawa kode untuk membentuk asam amino metionin', 41),
(319, 'melepas dari ribosom menuju ke sitoplasma', 41),
(320, '2', 40),
(321, '3', 40),
(322, '4', 40),
(323, '5', 40),
(324, 'Inisiasi', 39),
(325, 'Elongasi', 39),
(326, 'Translasi', 39),
(327, 'Terminasi', 39),
(328, 'proses penerjemahan gen menjadi urutan asam amino yang akan disintesis menjadi polipeptida (protein)', 38),
(329, 'proses pelekatan antara tRNA dengan asam amino dengan bantuan enzim aminoasil-tRNA sintetase.', 38),
(330, 'Proses transkripsi diawali dari sintesis RNA dari salah satu rantai DNA sense atau rantai cetakan. Adapun rantai DNA komplemennya disebut rantai antisense', 38),
(331, 'menentukan tempat dimulainya transkripsi dan menentukan satu rantai DNA yang akan digunakan sebagai cetakan', 38),
(332, 'proses penerjemahan gen menjadi urutan asam amino yang akan disintesis menjadi polipeptida (protein)', 37),
(333, 'proses pelekatan antara tRNA dengan asam amino dengan bantuan enzim aminoasil-tRNA sintetase.', 37),
(334, 'Proses transkripsi diawali dari sintesis RNA dari salah satu rantai DNA sense atau rantai cetakan. Adapun rantai DNA komplemennya disebut rantai antisense', 37),
(335, 'menentukan tempat dimulainya transkripsi dan menentukan satu rantai DNA yang akan digunakan sebagai cetakan', 37),
(336, 'membran', 36),
(337, 'badan golgi', 36),
(338, 'ribosom', 36),
(339, 'RNA', 36),
(344, 'kehancuran sel karena robeknya membran plasma (lisis).', 35),
(345, 'kerusakan sel.', 35),
(346, 'terlepasnya membran dari dinding sel', 35),
(347, 'pengerutan sel (krenasi).', 35),
(348, 'eksosmosis', 34),
(349, 'endosmosis', 34),
(350, 'plasmolisis', 34),
(351, 'eksosmosis dan endosmosis', 34),
(352, '1 dan 2', 33),
(353, '2 dan 3', 33),
(354, '1 dan 3', 33),
(355, 'Semua jawaban benar', 33),
(356, 'perpindahan molekul-molekul zat dari konsentrasi tinggi ke konsentrasi rendah baik melalui membran plasma ataupun tidak.', 32),
(357, 'perpindahan molekul-molekul pelarut (misal air) dari larutan berkonsentrasi rendah (hipotonik) ke larutan berkonsentrasi tinggi (hipertonik) melalui selaput (membran) semipermeabel.', 32),
(358, 'perpindahan molekul air melalui membran semipermeabel dari larutan yang kadar airnya tinggi ke larutan yang kadar airnya rendah', 32),
(359, 'perpindahan molekul atau ion tanpa menggunakan energi sel.', 32),
(368, 'difusi dan eksositosis', 31),
(369, 'endositosis dan eksositosis.', 31),
(370, 'pompa natrium-kalium', 31),
(371, 'difusi dan osmosis.', 31),
(372, 'perpindahan molekul-molekul zat dari konsentrasi tinggi ke konsentrasi rendah baik melalui membran plasma ataupun tidak.', 30),
(373, 'perpindahan molekul-molekul pelarut (misal air) dari larutan berkonsentrasi rendah (hipotonik) ke larutan berkonsentrasi tinggi (hipertonik) melalui selaput (membran) semipermeabel.', 30),
(374, 'perpindahan molekul air melalui membran semipermeabel dari larutan yang kadar airnya tinggi ke larutan yang kadar airnya rendah', 30),
(375, 'perpindahan molekul atau ion tanpa menggunakan energi sel.', 30),
(376, 'transpor pasif dan transpor tidak aktif', 29),
(377, 'transpor aktif dan transpor aktif', 29),
(378, 'transpor pasif dan transpor aktif', 29),
(379, 'semua jawaban salah', 29),
(380, 'dua', 28),
(381, 'tiga', 28),
(382, 'empat', 28),
(383, 'lima', 28),
(384, 'plastida yang mengandung klorofil dan pigmen fotosintetik lainnya', 27),
(385, 'plastida yang mengandung pigmen nonfotosintetik', 27),
(386, 'plastida yang tidak berwarna', 27),
(387, 'plastida yang berwarna', 27),
(388, 'Tempat menyimpan zat makanan seperti amilum dan gula.', 26),
(389, 'mengatur keluar masuknya zat', 26),
(390, 'Menyimpan pigmen.', 26),
(391, 'Menyimpan minyak asiri.', 26),
(392, 'dinding sel', 25),
(393, 'vakuola', 25),
(394, 'plastida', 25),
(395, 'sentriol', 25),
(396, 'dinding sel', 24),
(397, 'vakuola', 24),
(398, 'plastida', 24),
(399, 'sentriol', 24),
(400, 'sel prokariotik', 23),
(401, 'sel eukariotik', 23),
(402, 'sitoplasma', 23),
(403, 'membran plasma', 23),
(404, 'plastida', 22),
(405, 'sentrosom', 22),
(406, 'mikrotubulus', 22),
(407, 'mitokondria', 22),
(412, '1 dan 2', 21),
(413, '1 dan 4', 21),
(414, '2 dan 3', 21),
(415, '2 dan 4', 21),
(416, 'Inti Sel (Nukleus)', 20),
(417, 'Retikulum Endoplasma (RE)', 20),
(418, 'Ribosom', 20),
(419, 'Semua jawaban benar', 20),
(424, 'membran plasma, sitoplasma, dan Kompleks Golgi', 19),
(425, 'membran plasma, sitoplasma, dan organel-organel sel.', 19),
(426, 'sitoplasma, Lisosom, dan organel-organel sel.', 19),
(427, 'kompleks Golgi, Lisosom, dan organel-organel sel', 19),
(428, 'karbohidrat, lemak, dan protein.', 18),
(429, 'peptidoglikan, lipid, dan protein', 18),
(430, 'air, protein, lipid, mineral, dan enzim-enzim', 18),
(431, 'peptidoglikan, lipid, dan lemak', 18),
(432, 'pelindung molekuler sel terhadap lingkungan di sekitarnya.', 17),
(433, 'untuk mencerna makanan secara intraseluler dan untuk melakukan proses metabolisme sel.', 17),
(434, 'penghasil energi.', 17),
(435, 'pelindung dan pemberi bentuk tubuh', 17),
(436, 'bakteri', 16),
(437, 'ganggang biru', 16),
(438, 'a dan b benar', 16),
(439, 'a dan b salah', 16),
(440, '2', 15),
(441, '3', 15),
(442, '4', 15),
(443, '5', 15),
(444, 'RNA', 14),
(445, 'DNA', 14),
(446, 'Lipid', 14),
(447, 'Enzim', 14),
(448, 'NH3', 13),
(449, 'NaCl', 13),
(450, 'CaSO4', 13),
(451, 'NaHCO3', 13),
(452, '40-45%', 12),
(453, '45-50%', 12),
(454, '50-60%', 12),
(455, '60-70%', 12),
(456, 'unsur yang terdapat dalam jumlah sangat sedikit', 10),
(457, 'unsur terbesar yang menyusun sebuah sel.', 10),
(458, 'makromolekul yang sangat penting untuk kelangsungan hidup sel.', 10),
(459, 'penyimpan informasi genetis dalam sel dan bersama-sama dengan protein histon membentuk kromosom.', 10),
(460, 'asam nukleat', 11),
(461, 'asam amino dan nukleotida', 11),
(462, 'karbohidrat dan protein,', 11),
(463, 'mikromolekul dan makromolekul.', 11),
(468, 'protoplasma', 8),
(469, 'hereditas', 8),
(470, 'ekskresi', 8),
(471, 'sekresi', 8),
(472, 'Robert Hooke', 7),
(473, 'Rene Dutrochet', 7),
(474, 'Alexander Braun', 7),
(475, 'Rudolph Vircow', 7),
(480, 'protoplasma', 6),
(481, 'hereditas', 6),
(482, 'ekskresi', 6),
(483, 'sekresi', 6),
(484, 'Johannes Purkinje', 5),
(485, 'Robert Hooke', 5),
(486, 'Theodor Schwann', 5),
(487, 'Rudolf Virchow', 5),
(488, '1,2,3,6', 4),
(489, '2,4,5,6', 4),
(490, '1,3,5,6', 4),
(491, 'semua benar', 4),
(492, 'merupakan penyusun yang mendasar bagi tubuh makhluk hidup', 3),
(493, 'di dalam sel terjadi berbagai reaksi-reaksi kimia kehidupan', 3),
(494, 'dapat menurunkan sifat genetis dari satu generasi ke generasi berikutnya', 3),
(495, 'sel tidak dapat dibagi-bagi lagi menjadi lebih kecil', 3),
(504, 'bagian yang terdiri dari nukelus (inti sel) dan nukleolus (anak inti)', 1),
(505, 'Sel adalah unit dasar kehidupan makhluk hidup', 1),
(506, 'kumpulan materi paling sederhana yang dapat hidup', 1),
(507, 'mata rantai terakhir dalam rantai besar yang membentuk jaringan organ, sistem, dan individu.', 1),
(508, 'sel', 2),
(509, 'sel prokariotik', 2),
(510, 'sel eukariotik', 2),
(511, 'reproduksi sel', 2);

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
-- Table structure for table `post_test_adaptive_result`
--

CREATE TABLE `post_test_adaptive_result` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `module_id` int NOT NULL,
  `correct_answers` int NOT NULL DEFAULT '0',
  `total_questions` int NOT NULL DEFAULT '0',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` enum('lulus','gagal') NOT NULL DEFAULT 'gagal',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `post_test_adaptive_result`
--

INSERT INTO `post_test_adaptive_result` (`id`, `student_id`, `module_id`, `correct_answers`, `total_questions`, `score`, `status`, `created_at`) VALUES
(1, 450, 7, 4, 7, '57.14', 'gagal', '2025-11-22 05:14:08'),
(2, 450, 7, 2, 7, '28.57', 'gagal', '2025-11-22 05:30:08'),
(3, 450, 7, 5, 7, '71.43', 'lulus', '2025-11-22 05:31:44'),
(4, 451, 6, 7, 7, '100.00', 'lulus', '2025-11-22 07:07:44'),
(5, 451, 7, 5, 7, '71.43', 'lulus', '2025-11-22 07:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `post_test_e_learning_result`
--

CREATE TABLE `post_test_e_learning_result` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `module_id` int NOT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `status` enum('lulus','gagal') NOT NULL,
  `attempt` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `post_test_e_learning_result`
--

INSERT INTO `post_test_e_learning_result` (`id`, `student_id`, `module_id`, `nilai`, `status`, `attempt`, `created_at`) VALUES
(5, 433, 3, '42.86', 'gagal', 1, '2025-11-19 16:14:38'),
(6, 442, 1, '100.00', 'lulus', 1, '2025-11-21 02:49:48'),
(7, 451, 1, '100.00', 'lulus', 1, '2025-11-22 07:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `pretest_modul`
--

CREATE TABLE `pretest_modul` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `module_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer_id` int NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pretest_modul`
--

INSERT INTO `pretest_modul` (`id`, `student_id`, `module_id`, `question_id`, `answer_id`, `is_correct`, `created_at`) VALUES
(64, 449, 1, 2, 508, 0, '2025-11-21 18:44:34'),
(65, 449, 1, 4, 488, 0, '2025-11-21 18:44:34'),
(66, 449, 1, 3, 495, 0, '2025-11-21 18:44:34'),
(67, 449, 2, 9, 260, 0, '2025-11-21 18:44:34'),
(68, 449, 2, 12, 453, 0, '2025-11-21 18:44:34'),
(69, 449, 2, 8, 468, 0, '2025-11-21 18:44:34'),
(70, 449, 3, 21, 412, 0, '2025-11-21 18:44:34'),
(71, 449, 3, 16, 439, 0, '2025-11-21 18:44:34'),
(72, 449, 3, 17, 434, 0, '2025-11-21 18:44:34'),
(73, 449, 4, 22, 406, 0, '2025-11-21 18:44:34'),
(74, 449, 4, 23, 402, 0, '2025-11-21 18:44:34'),
(75, 449, 4, 26, 390, 0, '2025-11-21 18:44:34'),
(76, 449, 5, 31, 370, 0, '2025-11-21 18:44:34'),
(77, 449, 5, 29, 378, 0, '2025-11-21 18:44:34'),
(78, 449, 5, 30, 374, 0, '2025-11-21 18:44:34'),
(79, 449, 6, 37, 334, 0, '2025-11-21 18:44:34'),
(80, 449, 6, 39, 326, 0, '2025-11-21 18:44:34'),
(81, 449, 6, 36, 338, 0, '2025-11-21 18:44:34'),
(82, 449, 7, 48, 293, 0, '2025-11-21 18:44:34'),
(83, 449, 7, 43, 310, 0, '2025-11-21 18:44:34'),
(84, 449, 7, 46, 212, 0, '2025-11-21 18:44:34'),
(85, 450, 1, 2, 0, 1, '2025-11-21 18:55:37'),
(86, 450, 1, 4, 1, 0, '2025-11-21 18:55:37'),
(87, 450, 1, 5, 0, 1, '2025-11-21 18:55:37'),
(88, 450, 2, 14, 0, 1, '2025-11-21 18:55:37'),
(89, 450, 2, 11, 0, 1, '2025-11-21 18:55:37'),
(90, 450, 2, 10, 2, 0, '2025-11-21 18:55:37'),
(91, 450, 3, 19, 0, 1, '2025-11-21 18:55:37'),
(92, 450, 3, 21, 3, 0, '2025-11-21 18:55:37'),
(93, 450, 3, 20, 0, 1, '2025-11-21 18:55:37'),
(94, 450, 4, 24, 3, 0, '2025-11-21 18:55:37'),
(95, 450, 4, 26, 0, 1, '2025-11-21 18:55:37'),
(96, 450, 4, 23, 1, 0, '2025-11-21 18:55:37'),
(97, 450, 5, 33, 0, 1, '2025-11-21 18:55:37'),
(98, 450, 5, 34, 0, 1, '2025-11-21 18:55:37'),
(99, 450, 5, 30, 3, 0, '2025-11-21 18:55:38'),
(100, 450, 6, 41, 0, 1, '2025-11-21 18:55:38'),
(101, 450, 6, 37, 0, 1, '2025-11-21 18:55:38'),
(102, 450, 6, 40, 0, 1, '2025-11-21 18:55:38'),
(103, 450, 7, 43, 2, 0, '2025-11-21 18:55:38'),
(104, 450, 7, 46, 0, 0, '2025-11-21 18:55:38'),
(105, 450, 7, 47, 3, 0, '2025-11-21 18:55:38'),
(106, 451, 1, 4, 0, 1, '2025-11-22 06:55:12'),
(107, 451, 1, 2, 3, 0, '2025-11-22 06:55:12'),
(108, 451, 1, 5, 0, 1, '2025-11-22 06:55:12'),
(109, 451, 2, 13, 1, 0, '2025-11-22 06:55:12'),
(110, 451, 2, 14, 0, 1, '2025-11-22 06:55:12'),
(111, 451, 2, 10, 2, 0, '2025-11-22 06:55:12'),
(112, 451, 3, 19, 1, 0, '2025-11-22 06:55:12'),
(113, 451, 3, 17, 0, 1, '2025-11-22 06:55:12'),
(114, 451, 3, 18, 3, 0, '2025-11-22 06:55:12'),
(115, 451, 4, 25, 0, 1, '2025-11-22 06:55:12'),
(116, 451, 4, 23, 1, 0, '2025-11-22 06:55:12'),
(117, 451, 4, 24, 0, 1, '2025-11-22 06:55:12'),
(118, 451, 5, 31, 3, 0, '2025-11-22 06:55:12'),
(119, 451, 5, 34, 0, 1, '2025-11-22 06:55:12'),
(120, 451, 5, 32, 2, 0, '2025-11-22 06:55:12'),
(121, 451, 6, 36, 3, 0, '2025-11-22 06:55:12'),
(122, 451, 6, 37, 3, 0, '2025-11-22 06:55:12'),
(123, 451, 6, 38, 2, 0, '2025-11-22 06:55:12'),
(124, 451, 7, 47, 1, 0, '2025-11-22 06:55:12'),
(125, 451, 7, 43, 2, 0, '2025-11-22 06:55:12'),
(126, 451, 7, 44, 1, 0, '2025-11-22 06:55:12'),
(127, 451, 1, 4, 0, 1, '2025-11-22 06:55:15'),
(128, 451, 1, 2, 3, 0, '2025-11-22 06:55:15'),
(129, 451, 1, 5, 0, 1, '2025-11-22 06:55:15'),
(130, 451, 2, 13, 1, 0, '2025-11-22 06:55:15'),
(131, 451, 2, 14, 0, 1, '2025-11-22 06:55:15'),
(132, 451, 2, 10, 2, 0, '2025-11-22 06:55:15'),
(133, 451, 3, 19, 1, 0, '2025-11-22 06:55:15'),
(134, 451, 3, 17, 0, 1, '2025-11-22 06:55:15'),
(135, 451, 3, 18, 3, 0, '2025-11-22 06:55:15'),
(136, 451, 4, 25, 0, 1, '2025-11-22 06:55:15'),
(137, 451, 4, 23, 1, 0, '2025-11-22 06:55:15'),
(138, 451, 4, 24, 0, 1, '2025-11-22 06:55:15'),
(139, 451, 5, 31, 3, 0, '2025-11-22 06:55:15'),
(140, 451, 5, 34, 0, 1, '2025-11-22 06:55:15'),
(141, 451, 5, 32, 2, 0, '2025-11-22 06:55:15'),
(142, 451, 6, 36, 3, 0, '2025-11-22 06:55:15'),
(143, 451, 6, 37, 3, 0, '2025-11-22 06:55:15'),
(144, 451, 6, 38, 2, 0, '2025-11-22 06:55:15'),
(145, 451, 7, 47, 1, 0, '2025-11-22 06:55:15'),
(146, 451, 7, 43, 2, 0, '2025-11-22 06:55:15'),
(147, 451, 7, 44, 1, 0, '2025-11-22 06:55:15');

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
(268, 429, 1, 1, 1, 1, 1, 1, 1),
(269, 430, 1, 1, 1, 1, 1, 1, 1),
(280, 444, 1, 1, 1, 1, 1, 1, 1),
(281, 445, 1, 1, 1, 1, 1, 1, 1),
(282, 446, 1, 1, 1, 1, 1, 1, 1);

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
(251, 429, 3),
(252, 430, 3),
(255, 444, 3),
(256, 445, 3),
(257, 446, 3),
(258, 447, 1),
(259, 448, 1),
(260, 448, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_result`
--

CREATE TABLE `quiz_result` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `nilai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(44, 451, 51);

-- --------------------------------------------------------

--
-- Table structure for table `result_hasil_pretest`
--

CREATE TABLE `result_hasil_pretest` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `module_id` int NOT NULL,
  `total_questions` int NOT NULL DEFAULT '3',
  `correct_answers` int NOT NULL DEFAULT '0',
  `score` int NOT NULL DEFAULT '0',
  `percentage` decimal(5,2) DEFAULT '0.00',
  `gnn_prediction` float DEFAULT NULL,
  `gnn_confidence` float DEFAULT NULL,
  `gnn_predicted_level` int DEFAULT NULL,
  `method` varchar(50) DEFAULT 'IRT',
  `recommended_level` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `result_hasil_pretest`
--

INSERT INTO `result_hasil_pretest` (`id`, `student_id`, `module_id`, `total_questions`, `correct_answers`, `score`, `percentage`, `gnn_prediction`, `gnn_confidence`, `gnn_predicted_level`, `method`, `recommended_level`, `created_at`, `updated_at`) VALUES
(22, 449, 1, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:44:34', '2025-11-21 18:44:34'),
(23, 449, 2, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:44:34', '2025-11-21 18:44:34'),
(24, 449, 3, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:44:34', '2025-11-21 18:44:34'),
(25, 449, 4, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:44:34', '2025-11-21 18:44:34'),
(26, 449, 5, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:44:34', '2025-11-21 18:44:34'),
(27, 449, 6, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:44:34', '2025-11-21 18:44:34'),
(28, 449, 7, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:44:34', '2025-11-21 18:44:34'),
(29, 450, 1, 3, 2, 85, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:55:38', '2025-11-21 18:55:38'),
(30, 450, 2, 3, 2, 85, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:55:38', '2025-11-21 18:55:38'),
(31, 450, 3, 3, 2, 85, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:55:38', '2025-11-21 18:55:38'),
(32, 450, 4, 3, 1, 50, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:55:38', '2025-11-21 18:55:38'),
(33, 450, 5, 3, 2, 85, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:55:38', '2025-11-21 18:55:38'),
(34, 450, 6, 3, 3, 100, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:55:38', '2025-11-21 18:55:38'),
(35, 450, 7, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-21 18:55:38', '2025-11-21 18:55:38'),
(36, 451, 1, 3, 2, 85, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-22 06:55:12', '2025-11-22 06:55:15'),
(37, 451, 2, 3, 1, 50, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-22 06:55:12', '2025-11-22 06:55:15'),
(38, 451, 3, 3, 1, 50, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-22 06:55:12', '2025-11-22 06:55:15'),
(39, 451, 4, 3, 2, 85, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-22 06:55:12', '2025-11-22 06:55:15'),
(40, 451, 5, 3, 1, 50, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-22 06:55:12', '2025-11-22 06:55:15'),
(41, 451, 6, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-22 06:55:12', '2025-11-22 06:55:15'),
(42, 451, 7, 3, 0, 0, '0.00', NULL, NULL, NULL, 'IRT', 1, '2025-11-22 06:55:12', '2025-11-22 06:55:15');

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
(152, 426, '117', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(155, 429, '55', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(156, 430, '66', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(169, 444, '221', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(170, 445, '222', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(171, 446, '225', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(172, 447, '2221', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(173, 448, '2222', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(174, 449, '2223', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(175, 450, '2224', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1),
(176, 451, '2226', 'Sigma Guntur', 'Basuki rahmat street', '089604191747', 1);

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
(241, 2, 426),
(244, 2, 429),
(245, 3, 430),
(261, 2, 444),
(262, 2, 445),
(263, 2, 446),
(264, 2, 447),
(265, 2, 448),
(266, 2, 449),
(267, 2, 450),
(268, 2, 451);

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
(60, 419, 'Jono', '123', '333', '081289910359', NULL, '1'),
(61, 432, 'Sigma Guntur', 'Basuki rahmat street', '555', '089604191747', NULL, '1');

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
(426, '117', '$2y$10$sRe0PKyaYhlTrCKTMps/zO6rrE/yuNUQJfVMhNVr2h1Yby/AIKvT.', '3'),
(427, '44', '$2y$10$3D9ImwFzBvlHlIzGc7WFj.SGr9kDzt7oTi25wjzvQfRLxR1C8SLQC', '3'),
(428, '223', '$2y$10$ZzXPPOBUu/nwHXWtLH.TE.Y2qnHM7vIndyjeT8XyKp2L5WIrH4kW.', '3'),
(429, '55', '$2y$10$/vSnWuSgPp/2B2TOgQ5UFuf5Gz6oXRHScuFgTkpVYE8ded9KQHbkq', '3'),
(430, '66', '$2y$10$g1ZK8dUCb5.7.Q94B9CUw.7HhTuS39ffk96/AMhl4WrzdR7VMqtkm', '3'),
(432, '555', '$2y$10$oUp4osvRIB1TczC0UC3qBelfGZ0JlrW.AYj2qlkwuVRz..LkcT5ZW', '2'),
(444, '221', '$2y$10$gpnPjTp3BO6XY4qn5I7gheUyN56/SXq/Xdd38hciTMPHnHMEpra2S', '3'),
(445, '222', '$2y$10$N6/puRqdGL2nDgBY6ow1sOGgplfCBq/Zrkj8FCkUF40Peegx62cfe', '3'),
(446, '225', '$2y$10$h6gWJMc9MbOke2FSZFycU.PyK5OB3LIlPVX1CewsFHi5i9TpBaJUy', '3'),
(447, '2221', '$2y$10$Z.N8CsF.qhlStUdzXb6ome7S70AoLNAL0OjMYA9SZo1jEmJtidj8S', '3'),
(448, '2222', '$2y$10$8T4/QwRxNkVnIzzq611ai.ln70LcwDEYzYVlFv2mo.trw4.hN9Qxa', '3'),
(449, '2223', '$2y$10$m.A3HohAn7pqGyW2j67LqegYB1YcjPjJ1dZI7DbAX8LMs/yUuG18W', '3'),
(450, '2224', '$2y$10$idy0TJH0ZYQwefSO06KoJ.zDHRw3Sl42NT6ETRWVCUDaZTNgYK.Zm', '3'),
(451, '2226', '$2y$10$0z/9UrHgmelRNNZI7RZlZeDEpEZd9oP8yNcQi1udTGH6kwuXg5m7m', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_attendance`
--
ALTER TABLE `class_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attendance_class_id` (`class_id`),
  ADD KEY `fk_attendance_teacher_id` (`teacher_id`);

--
-- Indexes for table `level_student`
--
ALTER TABLE `level_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_levet_student_student_id` (`student_id`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_materi_module_id` (`module_id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_sub_topic_id` (`sub_topic_id`);

--
-- Indexes for table `module_learned`
--
ALTER TABLE `module_learned`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_learned_module_id` (`module_id`),
  ADD KEY `fk_module_learned_users_id` (`student_id`);

--
-- Indexes for table `module_prerequisites`
--
ALTER TABLE `module_prerequisites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_module_id` (`module_id`),
  ADD KEY `idx_prerequisite` (`prerequisite_module_id`);

--
-- Indexes for table `module_question`
--
ALTER TABLE `module_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_question_module_id` (`module_id`);

--
-- Indexes for table `module_question_choice`
--
ALTER TABLE `module_question_choice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_question_choice_question_id` (`question_id`);

--
-- Indexes for table `pelajaran`
--
ALTER TABLE `pelajaran`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `post_test_adaptive_result`
--
ALTER TABLE `post_test_adaptive_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_test_adaptive_student` (`student_id`),
  ADD KEY `fk_post_test_adaptive_module` (`module_id`),
  ADD KEY `idx_student_module` (`student_id`,`module_id`);

--
-- Indexes for table `post_test_e_learning_result`
--
ALTER TABLE `post_test_e_learning_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_module` (`student_id`,`module_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_posttest_elearning_module` (`module_id`);

--
-- Indexes for table `pretest_modul`
--
ALTER TABLE `pretest_modul`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pretest_modul_student_id` (`student_id`),
  ADD KEY `fk_pretest_modul_module_id` (`module_id`),
  ADD KEY `idx_student_module` (`student_id`,`module_id`);

--
-- Indexes for table `pre_test_answer`
--
ALTER TABLE `pre_test_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pre_test_answer_student_id` (`student_id`);

--
-- Indexes for table `pre_test_result`
--
ALTER TABLE `pre_test_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pre_test_result_users_id` (`student_id`);

--
-- Indexes for table `quiz_result`
--
ALTER TABLE `quiz_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lquiz_result_users_id` (`student_id`);

--
-- Indexes for table `quiz_result_e_learning`
--
ALTER TABLE `quiz_result_e_learning`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quiz_result_e_learning_users_id` (`student_id`);

--
-- Indexes for table `result_hasil_pretest`
--
ALTER TABLE `result_hasil_pretest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_module` (`student_id`,`module_id`),
  ADD KEY `fk_result_pretest_student_id` (`student_id`),
  ADD KEY `fk_result_pretest_module_id` (`module_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_topic`
--
ALTER TABLE `sub_topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sub_topic_topic_id` (`topic_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_survey_result_users_id` (`student_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `level_student`
--
ALTER TABLE `level_student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `module_prerequisites`
--
ALTER TABLE `module_prerequisites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `module_question`
--
ALTER TABLE `module_question`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `module_question_choice`
--
ALTER TABLE `module_question_choice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=512;

--
-- AUTO_INCREMENT for table `pelajaran`
--
ALTER TABLE `pelajaran`
  MODIFY `id_mapel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_test_adaptive_result`
--
ALTER TABLE `post_test_adaptive_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `post_test_e_learning_result`
--
ALTER TABLE `post_test_e_learning_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pretest_modul`
--
ALTER TABLE `pretest_modul`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `pre_test_answer`
--
ALTER TABLE `pre_test_answer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `pre_test_result`
--
ALTER TABLE `pre_test_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `quiz_result`
--
ALTER TABLE `quiz_result`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `quiz_result_e_learning`
--
ALTER TABLE `quiz_result_e_learning`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `result_hasil_pretest`
--
ALTER TABLE `result_hasil_pretest`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=452;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `class_attendance`
--
ALTER TABLE `class_attendance`
  ADD CONSTRAINT `fk_attendance_class_id` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_attendance_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `level_student`
--
ALTER TABLE `level_student`
  ADD CONSTRAINT `fk_levet_student_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `fk_materi_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `fk_module_sub_topic_id` FOREIGN KEY (`sub_topic_id`) REFERENCES `sub_topic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module_learned`
--
ALTER TABLE `module_learned`
  ADD CONSTRAINT `fk_module_learned_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_module_learned_users_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module_question`
--
ALTER TABLE `module_question`
  ADD CONSTRAINT `fk_module_question_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module_question_choice`
--
ALTER TABLE `module_question_choice`
  ADD CONSTRAINT `fk_module_question_choice_question_id` FOREIGN KEY (`question_id`) REFERENCES `module_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_test_adaptive_result`
--
ALTER TABLE `post_test_adaptive_result`
  ADD CONSTRAINT `fk_post_test_adaptive_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_post_test_adaptive_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pretest_modul`
--
ALTER TABLE `pretest_modul`
  ADD CONSTRAINT `fk_pretest_modul_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pretest_modul_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pre_test_answer`
--
ALTER TABLE `pre_test_answer`
  ADD CONSTRAINT `fk_pre_test_answer_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pre_test_result`
--
ALTER TABLE `pre_test_result`
  ADD CONSTRAINT `fk_pre_test_result_users_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_result`
--
ALTER TABLE `quiz_result`
  ADD CONSTRAINT `fk_lquiz_result_users_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_result_e_learning`
--
ALTER TABLE `quiz_result_e_learning`
  ADD CONSTRAINT `fk_quiz_result_e_learning_users_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `result_hasil_pretest`
--
ALTER TABLE `result_hasil_pretest`
  ADD CONSTRAINT `fk_result_pretest_module_id` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_result_pretest_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_topic`
--
ALTER TABLE `sub_topic`
  ADD CONSTRAINT `fk_sub_topic_topic_id` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `survey_question`
--
ALTER TABLE `survey_question`
  ADD CONSTRAINT `survey_question_ibfk_1` FOREIGN KEY (`id_mapel`) REFERENCES `pelajaran` (`id_mapel`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `survey_result`
--
ALTER TABLE `survey_result`
  ADD CONSTRAINT `fk_survey_result_users_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `topic`
--
ALTER TABLE `topic`
  ADD CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`id_mapel`) REFERENCES `pelajaran` (`id_mapel`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
