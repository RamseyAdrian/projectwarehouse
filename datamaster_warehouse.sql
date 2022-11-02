-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2022 at 11:10 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datamaster_warehouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_admin`
--

CREATE TABLE `data_admin` (
  `admin_id` int(11) NOT NULL,
  `office_id` int(15) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `admin_username` varchar(50) NOT NULL,
  `admin_password` varchar(100) NOT NULL,
  `admin_telp` varchar(20) NOT NULL,
  `admin_email` varchar(50) NOT NULL,
  `admin_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_admin`
--

INSERT INTO `data_admin` (`admin_id`, `office_id`, `admin_name`, `admin_username`, `admin_password`, `admin_telp`, `admin_email`, `admin_address`) VALUES
(99, 11, 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', '082286486', 'adrianramsey@gmail.com', 'Jalan Terus Pantang Mundur '),
(245, 2, 'Admin SUMUT', 'a_sumut', '61d7b9e39a01793ce9b32ac5bd398c8b', '0218371279', 'sumut@email.com', 'Provinsi Sumatera Utara');

-- --------------------------------------------------------

--
-- Table structure for table `data_cart`
--

CREATE TABLE `data_cart` (
  `user_id` int(20) NOT NULL,
  `office_id` int(15) NOT NULL,
  `product_id` int(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `unit_id` int(15) NOT NULL,
  `quantity` int(20) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `data_category`
--

CREATE TABLE `data_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_category`
--

INSERT INTO `data_category` (`category_id`, `category_name`) VALUES
(1, 'Laptop'),
(3, 'Headphone'),
(4, 'Pakaian Pria'),
(5, 'Pakaian Wanita'),
(6, 'Smartphone'),
(7, 'Alat Tulis'),
(8, 'Buah'),
(15, 'Sayur'),
(102, 'Keyboard'),
(795262890, 'Berbagai Kertas'),
(1003224087, 'Alat Tulis Kantor'),
(1073600112, 'Aksesoris Komputer'),
(1191287753, 'Tinta'),
(1449068914, 'Alat Perekat'),
(1556681427, 'USB/Flashdisk'),
(1722651780, 'Amplop'),
(2088256575, 'Barang Cetakan');

-- --------------------------------------------------------

--
-- Table structure for table `data_office`
--

CREATE TABLE `data_office` (
  `office_id` int(15) NOT NULL,
  `office_name` varchar(50) NOT NULL,
  `office_address` text NOT NULL,
  `office_telp` varchar(25) NOT NULL,
  `office_fax` varchar(25) NOT NULL,
  `office_email` varchar(50) NOT NULL,
  `office_head` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_office`
--

INSERT INTO `data_office` (`office_id`, `office_name`, `office_address`, `office_telp`, `office_fax`, `office_email`, `office_head`) VALUES
(1, 'Perwakilan Aceh', 'Jalan Banda Aceh - Medan KM 4 Tanjung, Kec. Ingin Jaya, Kabupaten Aceh Besar, Aceh 23116', '2147483647', '2147483647', 'pengaduan.aceh@ombudsman.go.id', 'Dian Rubianty, S.E.Ak., M.P.A.'),
(2, 'Perwakilan Sumatera Utara', 'Jalan Sei Besitang No. 3, Sei Sikambing D, Medan Petisah, Kota Medan, Sumatera Utara 20119', '2147483647', '614147176', 'pengaduan.sumut@ombudsman.go.id', 'Abyadi Siregar'),
(3, 'Perwakilan Riau', 'l. Hang Tuah No. 34, Suka Mulia, Kecamatan Sail Pekanbaru Kota 28131', '2147483647', '761888100', 'pengaduan.riau@ombudsman.go.id', 'H. Ahmad Fitri, SE'),
(4, 'Perwakilan Sumatera Barat', 'l. Sawahan No. 58, Kel. Sawahan Timur, Kec. Padang Timur, Kota Padang', '2147483647', '751892521', 'pengaduan.sumbar@ombudsman.go.id', 'Yefri Heriani, S.Sos., M.Si.'),
(5, 'Perwakilan Jambi', 'Jalan Empu Sendok No. 07 RT. 17/05 (Lorong depan UNBARI) Kelurahan Solok Sipin, Kecamatan Danau Sipin Kota Jambi 36121', '0811-959-3737', '0741- 3066814', 'pengaduan.jambi@ombudsman.go.id', 'Saiful Roswandi, S.Pd.I., M.H.'),
(6, 'Perwakilan Sumatera Selatan', 'Jln. Radio No. 1 Kel.20 ilir DIV, Kec. ilir timur 1, Palembang ', '08117970137', '(0711)7443647', 'pengaduan.sumsel@ombudsman.go.id', 'M. Adrian Agustiansyah, SH. M.Hum'),
(7, 'Perwakilan Bengkulu', 'Jalan Adam Malik KM 8 No. 270 Kelurahan Jalan Gedang Kecamatan Gading Cempaka, Kota Bengkulu Bengkulu 38225', '08119723737', '08119723737', 'pengaduan.bengkulu@ombudsman.go.id', 'Herdi Puryanto, S.E.'),
(8, 'Perwakilan Kepulauan Bangka Belitung', 'Jalan Ahmad Yani No.3 Pangkalpinang – Bangka Belitung 33126', '08119733737/08117121137', '(0717) 9114193', 'pengaduan.babel@ombudsman.go.id', 'Shulby Yozar Ariadhy, S.IP., MPA., M.Sc.'),
(9, 'Perwakilan Lampung', 'Jalan Cut Mutia No. 137, Pengajaran, Teluk Betung Utara Bandar Lampung 35212', '08119803737', '(0721) 251373', 'pengaduan.lampung@ombudsman.go.id', 'Nur Rakhman Yusuf'),
(10, 'Perwakilan Kepulauan Riau', 'Gedung Graha Pena Lt.1 Ruang 103 JL . Raya Batam Center Kel.Teluk Tering Kec.Batam Kota 29461', '(0778) 474599', '(0778) 474601', 'pengaduan.kepri@ombudsman.go.id', 'Dr. Lagat Parroha Patar Siadari, SE., M.H'),
(11, 'Perwakilan Jakarta Raya', 'Jl. HR. Rasuna Said Kav. C-19 Lantai 3, Kuningan, Jakarta Selatan 12940 ', '(021) 25983721', '(021) 25983721', 'pengaduan.jakartaraya@ombudsman.go.id', 'Dedy Irsan, S.H'),
(12, 'Perwakilan Jawa Barat', 'Jalan Kebonwaru Utara No. 1 Bandung Kode Pos : 40271, Jawa Barat ', '(022) 7103733', '(022) 7219902', 'pengaduan.jabar@ombudsman.go.id', 'Drs. Dan Satriana'),
(13, 'Perwakilan Jawa Tengah', 'JL. Siwalan No.5 Kelurahan Wonodri Semarang Selatan Semarang', '(024) 844.2627', '0811.998.3737', 'pengaduan.jateng@ombudsman.go.id', 'Siti Farida, S.H., M.H.'),
(14, 'Perwakilan D I Yogyakarta', 'Jl. Affandi CT X/II Caturtunggal, Depok, Sleman. Kode pos : 55281 ', '(0274) 520054', '(0274) 520462', 'pengaduan.yogyakarta@ombudsman.go.id', 'Budhi Masthuri, SH'),
(15, 'Perwakilan Jawa Timur', 'Jl. Ngagel Timur No.56, Surabaya', '(031) 99443737', '(031) 5041537', 'pengaduan.jatim[at]ombudsman.go.id', 'Agus Muttaqin, S.H.'),
(16, 'Perwakilan Banten', 'Jalan Kolonel TB Suwandi Lingkar Selatan Kelurahan Lontar Baru, Kecamatan Serang, Kota Serang, Banten (Depan Radar Banten).', '(0254) 7913737', '08111273737', 'pengaduan.banten@ombudsman.go.id', 'Awidya Mahadewi, SS.,MAP.'),
(17, 'Perwakilan Bali', 'Jl. Melati No. 14 Dangin Puri Kangin, Denpasar Timur, Kota Denpasar, Bali 80233', '(0361) 2096942', '08111303737 ', 'pengaduan.bali[at]ombudsman.go.id', 'Ni Nyoman Sri Widhiyanti, S.H.'),
(18, 'Perwakilan Nusa Tenggara Barat', 'Jl. Majapahit No.12 A Mataram. Kode Pos : 83115', '(0370) 649630', '08111323737', 'pengaduan.ntb@ombudsman.go.id', 'Adhar Hakim, SH, MH'),
(19, 'Perwakilan Nusa Tenggara Timur', 'Jl. El Tari No. 17, Kelurahan Oebobo, Kecamatan Oebobo, Kota Kupang', '(0380) 8438187', '0811-145-3737', 'pengaduan.ntt@ombudsman.go.id', 'Darius Beda Daton, SH'),
(20, 'Perwakilan Kalimantan Barat', 'Jl. Surya No.2A Kelurahan Akcaya Pontianak Selatan Pontianak', '(0561) 8173737', '08112463737', 'pengaduan.kalbar@ombudsman.go.id', 'Agus Priyadi, SH'),
(21, 'Perwakilan Kalimantan Tengah', 'Jl. G. Obos No.80 Rt.01 RW.012 Kel. Menteng Kec. Jekan Raya Kota Palangka Raya Provinsi Kalimantan Tengah', ' (0536) 4211682', '(0536) 4211682', 'pengaduan.kalteng@ombudsman.go.id', 'Dr. R. Biroum Bernardianto, M. Si'),
(22, 'Perwakilan Kalimantan Selatan', 'Jl. Let. Jend. S. Parman No. 57 Banjarmasin 70116', '(0511) 3367 412', '(0511) 3367 411', 'pengaduan.kalsel@ombudsman.go.id', 'Hadi Rahman, S.I.P., MPA (Mgmt.)'),
(23, 'Perwakilan Kalimantan Timur', 'Kantor 1. Alamat: Perum Rawa Indah Pemda Kaltim, Blok A No 1. Jalan MT Haryono, RT 02/ Kelurahan Karang Anyar, Kecamatan Sungai Kunjang, Kota Samarinda. Kode Pos 75125 . Kantor 2. Alamat: Jl.Jenderal Sudirman No.03, Gedung Inhutani Lt. 2, Kelandasan Ulu, Balikpapan Kota. Kode Pos. 76112', '0541-2086525/0542-8515417', '08111713737', 'pengaduan.kaltim@ombudsman.go.id', 'Kusharyanto'),
(24, 'Perwakilan Sulawesi Utara', 'Jl. Sam Ratulangi No.21 Ronotana, Manado', '(0431) 7282769', '(0431) 7282769 / 08111843', 'pengaduan.sulut[at]ombudsman.go.id', 'Meilany Fransisca Limpar, S.H.,M.H.'),
(25, 'Perwakilan Sulawesi Tengah', 'Jl. Chairil Anwar No. 17 Palu, Sulawesi Tengah 94114', '(0451) 4016505 / (0451) 4', '08112353737', 'pengaduan.sulteng[at]ombudsman.go.id', 'H. Sofyan Farid Lembah, SH'),
(26, 'Perwakilan Sulawesi Selatan', 'Kompleks Plaza Alauddin Blok BB No. 17, Jl. Sultan Alauddin, Gn. Sari, Rappocini, Kota Makassar, Sulawesi Selatan 90221 ', '(0411) 8224082', '08112363737', 'pengaduan.sulsel[at]ombudsman.go.id', 'Ismu Iskandar, ST., M.M.'),
(27, 'Perwakilan Sulawesi Tenggara', 'Jl. Drs.H.Abd.Silondae No. 114, Kota Kendari, 93111', '(0401) 3415554', '08112403737', 'pengaduan.sultra[at]ombudsman.go.id', 'Mastri Susilo, S.Pd.'),
(28, 'Perwakilan Gorontalo', 'Jl. 23 Januari No. 186 Kel. Biawao Kec. Kota Selatan Kode Pos 96111', '0435 (852-9435) ', '08112433737', 'pengaduan.gorontalo[at]ombudsman.go.id', 'Alim S. Niode'),
(29, 'Perwakilan Sulawesi Barat', 'Jl. Soekarno Hatta Nomor 137 Kelurahan Karema Kecamatan Kabupaten Mamuju', '(0426) 2322049 ', '(0426) 2322049', 'pengaduan.sulbar@ombudsman.go.id', 'Lukman Umar, S.Pd., M.Si.'),
(30, 'Perwakilan Maluku', 'Jl. Dr. J. Leimena - Poka Kec. Teluk Ambon, Kota Ambon - Maluku 97223', '(0911) 348873, 348770 ', '08111463737', 'pengaduan.maluku@ombudsman.go.id', 'Hasan Slamat'),
(31, 'Perwakilan Maluku Utara', 'Jl. Merdeka No. 13 Kel. Santiong, Kec. Ternate Tengah, Kota Ternate, Provinsi Maluku Utara 97722', '(0921) 3124362', '08112483737', 'pengaduan.malut@ombudsman.go.id', 'Sofyan Ali, SE'),
(32, 'Perwakilan Papua Barat', 'Jalan Merdeka no. 2, Kab.Manokwari, Papua Barat', '(0986) 2210655', '08112543737', 'pengaduan.papuabarat@ombudsman.go.id', 'Ir. Musa Yosep Sombuk, M.Si, MAAPD'),
(33, 'Perwakilan Papua', 'Jl. Ardipura I, Kelurahan Ardipura, Distrik Jayapura Selatan, Kota Jayapura', '08112673737', '08112673737', 'pengaduan.papua@ombudsman.go.id', 'Iwanggin Sabar Olif, SH'),
(34, 'Perwakilan Kalimantan Utara', 'Jl. Kusuma bangsa No.33 Tarakan, Kalimantan Utara 77121', '(0551) 3805684', '08112743737', 'pengaduan.kaltara@ombudsman.go.id', 'Maria Ulfah, S.E., M.Si.');

-- --------------------------------------------------------

--
-- Table structure for table `data_order`
--

CREATE TABLE `data_order` (
  `cart_id` int(15) NOT NULL,
  `user_id` int(15) NOT NULL,
  `office_id` int(15) NOT NULL,
  `created` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `items_approved` int(10) DEFAULT NULL,
  `times_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_order`
--

INSERT INTO `data_order` (`cart_id`, `user_id`, `office_id`, `created`, `status`, `items_approved`, `times_updated`) VALUES
(1163327406, 234, 11, '2022-11-01 06:50:09', 'Berhasil Diambil', 1, '2022-11-01 07:16:44'),
(1205612291, 234, 11, '2022-11-02 10:20:20', 'Berhasil Diambil', 2, '2022-11-02 12:23:19'),
(1331086609, 234, 11, '2022-11-01 07:23:03', 'Tidak Disetujui Admin', 1, '2022-11-01 07:24:57'),
(1455912358, 234, 11, '2022-11-02 12:24:27', 'Berhasil Diambil', 1, '2022-11-02 12:27:16'),
(1810798801, 234, 11, '2022-11-02 12:24:43', 'Berhasil Diambil', 2, '2022-11-02 15:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `data_product`
--

CREATE TABLE `data_product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `office_id` int(15) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` int(11) NOT NULL,
  `product_description` text NOT NULL,
  `product_image` varchar(100) NOT NULL,
  `product_status` tinyint(1) NOT NULL,
  `stock` int(15) NOT NULL,
  `stock_point` int(15) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_product`
--

INSERT INTO `data_product` (`product_id`, `category_id`, `office_id`, `unit_id`, `product_name`, `product_price`, `product_description`, `product_image`, `product_status`, `stock`, `stock_point`, `date_created`) VALUES
(1, 1, 11, 1973793895, 'Acer Predator', 10000000, '<p>Lorem</p>\r\n', 'produk1663293692.jpg', 1, 7, 5, '2022-11-02 08:39:41'),
(2, 3, 11, 1973793895, 'Headphone ROG', 5000000, '<p>Lorem</p>\r\n', 'produk1663293765.jpg', 1, 6, 5, '2022-11-02 08:37:54'),
(3, 6, 11, 1973793895, 'Rog Phone', 18000000, '<p>Handphone geming banget</p>\r\n\r\n<p>&nbsp;</p>\r\n', 'produk1663377340.png', 1, 29, 0, '2022-10-31 16:29:10'),
(4, 6, 11, 1973793895, 'Iphone ', 20000000, '<p>Iphone geming banget</p>\r\n', 'produk1663377374.jpg', 1, 17, 0, '2022-10-31 16:28:52'),
(6, 5, 4, 1973793895, 'Kemeja Wanita', 150000, '<p>Kemeja Cewe Geming</p>\r\n', 'produk1663377458.jpg', 1, 30, 0, '2022-10-31 19:19:39'),
(7, 7, 6, 1973793895, 'Spidol Warna Warni', 10000, '<p>Spidol Snowman</p>\r\n', 'produk1663377502.jpg', 1, 26, 0, '2022-10-31 19:19:45'),
(8, 7, 5, 1973793895, 'Pulpen Bagus', 12000, '<p>Pulpen pilot geming</p>\r\n', 'produk1663377537.jpg', 1, 40, 0, '2022-10-31 19:19:51'),
(9, 8, 7, 1973793895, 'Nanas Madu', 15000, '<p>Nanas paling manis di dunia</p>\r\n', 'produk1663377578.jpg', 1, 20, 0, '2022-10-31 19:19:57'),
(21, 7, 12, 1973793895, 'Spidol 12', 0, '<p>Spidol warna warni</p>\r\n', 'produk1664758412.jpg', 1, 30, 0, '2022-10-31 19:20:03'),
(44, 15, 11, 362289108, 'Jagung', 0, '<p>Jagung kuning</p>\r\n', 'produk1664758136.jpg', 1, 12, 0, '2022-10-31 16:29:00'),
(975, 6, 11, 362289108, 'Samsung S20 Ultra', 15000000, '<p>Samsung S20 buat geming uye</p>\r\n', 'produk1664704464.png', 1, 30, 0, '2022-10-31 16:29:30'),
(990, 6, 11, 362289108, 'Samsung S20 Lite', 12000000, '<p>lorem ipsum dorem lorem</p>\r\n', 'produk1664704256.png', 1, 24, 0, '2022-10-31 16:29:23'),
(999, 6, 11, 362289108, 'Samsung S20', 13000000, '<p>Lorem ipsum</p>\r\n', 'produk1664704103.png', 1, 24, 0, '2022-10-31 16:29:17'),
(1100121, 1, 11, 1711668626, 'Legion 5 Pro', 18000000, '<p>Legion 5 Pro&nbsp;</p>\r\n', 'produk1667259304.jpeg', 1, 7, 6, '2022-10-31 16:38:56'),
(1312424, 7, 11, 1973793895, 'Buku Tulis', 0, '', 'produk1667268671.jpeg', 1, 8, 6, '2022-11-02 06:32:37'),
(112687898, 1449068914, 11, 1222535816, 'Lakban Putih', 0, '', 'produk1667360212.jpeg', 1, 7, 5, '2022-11-02 06:32:55'),
(343578320, 1, 11, 226197290, 'Legion 7 Pro', 0, '<p>Legion 7 Pro</p>\r\n', 'produk1667264406.jpeg', 1, 8, 7, '2022-11-02 06:34:49'),
(413634779, 1449068914, 11, 1973793895, 'Lakban Hitam', 0, '', 'produk1667360270.jpeg', 1, 5, 4, '2022-11-02 06:37:01'),
(494196003, 15, 17, 1865191190, 'Sayur Kol', 0, '', 'produk1667269310.jpg', 1, 0, 12, '2022-10-31 19:21:50'),
(495728242, 6, 11, 226197290, 'Xiaomi Blackshark', 0, '', 'produk1667360329.jpeg', 1, 7, 5, '2022-11-02 06:36:06'),
(877418278, 795262890, 11, 164930724, 'Kertas Folio', 0, '', 'produk1667360063.png', 1, 8, 4, '2022-11-02 06:36:40'),
(973071678, 1003224087, 11, 1973793895, 'Kalkulator', 0, '<p>Kalkulator uye</p>\r\n', 'produk1667379019.jpeg', 1, 7, 5, '2022-11-02 08:50:35'),
(1039066942, 1003224087, 11, 1973793895, 'Gunting', 0, '', 'produk1667360102.jpeg', 1, 0, 4, '2022-11-02 03:35:02'),
(1332968871, 7, 12, 164930724, 'Kertas HVS', 0, '', 'produk1667268060.png', 1, 0, 5, '2022-10-31 19:01:00'),
(1342997879, 1073600112, 11, 226197290, 'Keyboard Wireless', 0, '', 'produk1667359995.jpeg', 1, 0, 5, '2022-11-02 03:33:15'),
(1354934101, 1191287753, 11, 226197290, 'Tinta Cartridge Hitam', 0, '', 'produk1667269352.jpeg', 1, 0, 3, '2022-10-31 19:22:32'),
(1372845152, 1556681427, 6, 226197290, 'Flashdisk 16GB', 0, '', 'produk1667269241.jpg', 1, 0, 5, '2022-10-31 19:20:41'),
(1601988398, 1722651780, 14, 1007087517, 'Amplop Kecil', 0, '', 'produk1667269277.jpeg', 1, 0, 11, '2022-10-31 19:21:17'),
(1621134896, 1449068914, 11, 1170976619, 'Lem Fox', 0, '', 'produk1667360157.jpeg', 1, 0, 3, '2022-11-02 04:14:21'),
(1705632047, 1722651780, 11, 165324393, 'Amplop Putih', 0, '', 'produk1667360020.jpeg', 1, 0, 2, '2022-11-02 03:33:40'),
(1852198471, 5, 16, 1865191190, 'Kemeja Wanita', 0, '', 'produk1667268264.jpg', 1, 0, 12, '2022-10-31 19:04:24'),
(1871276211, 1, 2, 1973793895, 'Legion 5', 0, '<p>Legion 5 Biasa</p>\r\n', 'produk1667264342.jpg', 1, 0, 5, '2022-10-31 17:59:02'),
(2125807336, 7, 11, 1973793895, 'Buku Folio', 0, '', 'produk1667359638.jpeg', 1, 9, 7, '2022-11-02 06:35:03'),
(2144384152, 1556681427, 11, 226197290, 'Flashdisk 32 GB', 0, '<p>Flashdisk 32 GB</p>\r\n', 'produk1667359595.jpg', 1, 0, 8, '2022-11-02 03:26:35');

-- --------------------------------------------------------

--
-- Table structure for table `data_superadmin`
--

CREATE TABLE `data_superadmin` (
  `super_admin_id` int(11) NOT NULL,
  `office_id` int(15) NOT NULL,
  `super_name` varchar(50) NOT NULL,
  `super_username` varchar(50) NOT NULL,
  `super_password` varchar(100) NOT NULL,
  `super_telp` varchar(20) NOT NULL,
  `super_email` varchar(50) NOT NULL,
  `super_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_superadmin`
--

INSERT INTO `data_superadmin` (`super_admin_id`, `office_id`, `super_name`, `super_username`, `super_password`, `super_telp`, `super_email`, `super_address`) VALUES
(1, 11, 'Ombudsman', 'ombudsman', '36ca45f981dc4643324744c3a0ea8ca8', '909092', 'ombudsman@email.com', 'Kantor Utama Ombudsman RI'),
(100, 11, 'Super Admin', 'super', 'super', '0812345678', 'super@email.com', 'DKI Jakarta');

-- --------------------------------------------------------

--
-- Table structure for table `data_transaction`
--

CREATE TABLE `data_transaction` (
  `order_id` int(255) NOT NULL,
  `cart_id` int(15) NOT NULL,
  `user_id` int(20) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_telp` varchar(20) NOT NULL,
  `office_id` int(20) NOT NULL,
  `office_name` varchar(50) NOT NULL,
  `product_id` int(20) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category_id` int(20) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `unit_id` int(15) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `quantity` int(20) NOT NULL,
  `created` datetime DEFAULT NULL,
  `red_flag` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `data_unit`
--

CREATE TABLE `data_unit` (
  `unit_id` int(15) NOT NULL,
  `unit_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_unit`
--

INSERT INTO `data_unit` (`unit_id`, `unit_name`) VALUES
(164930724, 'Rim'),
(165324393, 'Lembar'),
(226197290, 'Unit'),
(362289108, 'Box'),
(578006496, 'Kodi'),
(1007087517, 'Box Kecil'),
(1170976619, 'Rol'),
(1222535816, 'Set'),
(1648533409, 'Pack'),
(1711668626, 'Box Besar'),
(1865191190, 'Lusin'),
(1973793895, 'Buah');

-- --------------------------------------------------------

--
-- Table structure for table `data_user`
--

CREATE TABLE `data_user` (
  `user_id` int(11) NOT NULL,
  `office_id` int(15) NOT NULL,
  `user_location` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_telp` varchar(20) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_user`
--

INSERT INTO `data_user` (`user_id`, `office_id`, `user_location`, `user_name`, `user_username`, `user_password`, `user_telp`, `user_email`, `user_address`) VALUES
(14, 2, 'Indonesia', 'Sumut', 'sumut', '6f5f5e65f6f815070cea49441ca15c9c', '0218371279', 'sumut@email.com', 'Provinsi Sumatera Utara'),
(23, 3, 'Jakarta', 'Riau', 'riau', 'bba51a5b9935b92f046ca2048c2f7fe8', '14414121', 'riau@email,com', 'Provinsi Riau'),
(24, 4, 'Jakarta', 'Sumbar', 'sumbar', 'e88a6e1036af5bdb28c7e6ecbd6afc00', '1328478719', 'sumbar@email.com', 'Provinsi Sumatera Barat'),
(25, 5, 'Jakarta', 'Jambi', 'jambi', 'de9e80cc2a1e2f163d7fcc65dcedf180', '02918307213', 'jambi@email.com', 'Provinsi Jambi'),
(123, 1, 'Jakarta', 'Aceh', 'aceh', '6cf48ee2e9efb24ab194ec39777699b1', '0212839213', 'aceh@email.com', 'Provinsi Aceh'),
(150, 11, 'Jakarta', 'Jakarta', 'jakarta', '629ab14fab772d78a58eea752bdfc0dc', '02112345678', 'jakarta@email.com', 'DKI Jakarta'),
(234, 11, 'Jakarta', 'User', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', '02107381979', 'user@email.com', 'Jalan Setiabudi');

-- --------------------------------------------------------

--
-- Table structure for table `stocking_item`
--

CREATE TABLE `stocking_item` (
  `stocking_id` int(15) NOT NULL,
  `product_id` int(15) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `category_id` int(15) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `unit_id` int(15) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `stocking_before` int(15) NOT NULL,
  `stocking_after` int(15) NOT NULL,
  `admin_id` int(15) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `office_id` int(15) NOT NULL,
  `office_name` varchar(50) NOT NULL,
  `modified` datetime NOT NULL,
  `quantity` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stocking_item`
--

INSERT INTO `stocking_item` (`stocking_id`, `product_id`, `product_name`, `category_id`, `category_name`, `unit_id`, `unit_name`, `stocking_before`, `stocking_after`, `admin_id`, `admin_name`, `office_id`, `office_name`, `modified`, `quantity`) VALUES
(129575688, 1100121, 'Legion 5 Pro', 1, 'Laptop', 1711668626, 'Box Besar', 0, 7, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-01 06:38:56', 7),
(217763497, 112687898, 'Lakban Putih', 1449068914, 'Alat Perekat', 1222535816, 'Set', 0, 7, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 13:32:55', 7),
(519886335, 1, 'Acer Predator', 1, 'Laptop', 1973793895, 'Buah', 0, 7, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 15:39:41', 7),
(1144711616, 343578320, 'Legion 7 Pro', 1, 'Laptop', 226197290, 'Unit', 0, 8, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 13:34:49', 8),
(1194492583, 1312424, 'Buku Tulis', 7, 'Alat Tulis', 1973793895, 'Buah', 0, 8, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 13:32:37', 8),
(1373004334, 495728242, 'Xiaomi Blackshark', 6, 'Smartphone', 226197290, 'Unit', 0, 7, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 13:36:06', 7),
(1634281027, 877418278, 'Kertas Folio', 795262890, 'Berbagai Kertas', 164930724, 'Rim', 0, 8, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 13:36:40', 8),
(1710969313, 973071678, 'Kalkulator', 1003224087, 'Alat Tulis Kantor', 1973793895, 'Buah', 0, 7, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 15:50:35', 7),
(1894600463, 2125807336, 'Buku Folio', 7, 'Alat Tulis', 1973793895, 'Buah', 0, 9, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 13:35:03', 9),
(1990107067, 413634779, 'Lakban Hitam', 1449068914, 'Alat Perekat', 1973793895, 'Buah', 0, 5, 99, 'Admin', 11, 'Perwakilan Jakarta Raya', '2022-11-02 13:37:01', 5);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `order_id` int(15) NOT NULL,
  `cart_id` int(15) NOT NULL,
  `user_id` int(15) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_telp` int(20) NOT NULL,
  `office_id` int(15) NOT NULL,
  `office_name` varchar(50) NOT NULL,
  `product_id` int(15) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `category_id` int(15) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `unit_id` int(15) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `quantity` int(15) NOT NULL,
  `created` datetime NOT NULL,
  `red_flag` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`order_id`, `cart_id`, `user_id`, `user_name`, `user_email`, `user_telp`, `office_id`, `office_name`, `product_id`, `product_name`, `category_id`, `category_name`, `unit_id`, `unit_name`, `quantity`, `created`, `red_flag`, `status`, `notes`) VALUES
(115310661, 1331086609, 234, 'User', 'user@email.com', 2107381979, 11, 'Perwakilan Jakarta Raya', 2, 'Headphone ROG', 3, 'Headphone', 1973793895, 'Buah', 2, '2022-11-01 07:24:57', 'not red', 'Tidak Disetujui Admin', ''),
(154379271, 1455912358, 234, 'User', 'user@email.com', 2107381979, 11, 'Perwakilan Jakarta Raya', 1, 'Acer Predator', 1, 'Laptop', 1973793895, 'Buah', 4, '2022-11-02 12:27:16', 'not red', 'Berhasil Diambil', ''),
(382312388, 1163327406, 234, 'User', 'user@email.com', 2107381979, 11, 'Perwakilan Jakarta Raya', 1, 'Acer Predator', 1, 'Laptop', 1973793895, 'Buah', 1, '2022-11-01 07:16:44', 'not red', 'Berhasil Diambil', ''),
(490762911, 1205612291, 234, 'User', 'user@email.com', 2107381979, 11, 'Perwakilan Jakarta Raya', 2, 'Headphone ROG', 3, 'Headphone', 1973793895, 'Buah', 3, '2022-11-02 12:23:19', 'not red', 'Berhasil Diambil', ''),
(1144172775, 1205612291, 234, 'User', 'user@email.com', 2107381979, 11, 'Perwakilan Jakarta Raya', 1, 'Acer Predator', 1, 'Laptop', 1973793895, 'Buah', 3, '2022-11-02 12:23:19', 'not red', 'Berhasil Diambil', ''),
(1405404201, 1810798801, 234, 'User', 'user@email.com', 2107381979, 11, 'Perwakilan Jakarta Raya', 2, 'Headphone ROG', 3, 'Headphone', 1973793895, 'Buah', 4, '2022-11-02 15:37:54', 'not red', 'Berhasil Diambil', ''),
(2004257938, 1810798801, 234, 'User', 'user@email.com', 2107381979, 11, 'Perwakilan Jakarta Raya', 1, 'Acer Predator', 1, 'Laptop', 1973793895, 'Buah', 2, '2022-11-02 15:37:54', 'not red', 'Berhasil Diambil', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_admin`
--
ALTER TABLE `data_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `data_cart`
--
ALTER TABLE `data_cart`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `office_id` (`office_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `data_category`
--
ALTER TABLE `data_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `data_office`
--
ALTER TABLE `data_office`
  ADD PRIMARY KEY (`office_id`);

--
-- Indexes for table `data_order`
--
ALTER TABLE `data_order`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `data_product`
--
ALTER TABLE `data_product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `office_id` (`office_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `data_superadmin`
--
ALTER TABLE `data_superadmin`
  ADD PRIMARY KEY (`super_admin_id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `data_transaction`
--
ALTER TABLE `data_transaction`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `office_id` (`office_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `cart_id` (`cart_id`);

--
-- Indexes for table `data_unit`
--
ALTER TABLE `data_unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `data_user`
--
ALTER TABLE `data_user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `stocking_item`
--
ALTER TABLE `stocking_item`
  ADD PRIMARY KEY (`stocking_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `office_id` (`office_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `office_id` (`office_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_user`
--
ALTER TABLE `data_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=556;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
