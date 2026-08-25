-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql104.infinityfree.com
-- Generation Time: Aug 25, 2026 at 04:46 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_42728150_pc_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ime` varchar(100) NOT NULL,
  `prezime` varchar(100) NOT NULL,
  `adresa` varchar(255) NOT NULL,
  `grad` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefon` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `ime`, `prezime`, `adresa`, `grad`, `email`, `telefon`, `created_at`) VALUES
(1, 2, 'Bla', 'Blabla', 'Ilica 999', '10000 zagreb', 'test@test.hr', '0911234567', '2026-08-23 05:25:26'),
(2, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 05:34:14'),
(3, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 05:43:12'),
(4, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 06:31:27'),
(5, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 06:36:45'),
(6, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 08:07:07'),
(7, 1, 'test', 'test', 'Osobno preuzimanje u trgovini', '—', 'test@test.hr', '0911234567', '2026-08-23 08:32:45'),
(8, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 08:33:04'),
(9, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 08:33:41'),
(10, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 11:43:28'),
(11, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-24 14:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `akcija` varchar(255) NOT NULL,
  `detalji` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_log`
--

INSERT INTO `admin_log` (`id`, `admin_id`, `akcija`, `detalji`, `created_at`) VALUES
(1, 2, 'Dodao kupon', 'LJETO45 (postotak, 45)', '2026-08-23 05:59:52'),
(2, 2, 'Obrisao kupon', 'Kupon #1', '2026-08-23 06:21:39'),
(3, 2, 'Dodao kupon', 'LJETO45 (postotak, 45)', '2026-08-23 06:21:56'),
(4, 2, 'Uredio proizvod', 'Proizvod #9 (RTX 4070): zaliha: 0 → 24', '2026-08-23 06:34:23'),
(5, 2, 'Promijenio status narudžbe', 'Narudžba #1: \'kreirana\' → \'otkazana\'', '2026-08-23 06:38:22'),
(6, 2, 'Promijenio status narudžbe', 'Narudžba #1: \'otkazana\' → \'otkazana\'', '2026-08-23 06:38:34'),
(7, 2, 'Promijenio status narudžbe', 'Narudžba #1: \'otkazana\' → \'povrat\'', '2026-08-23 06:38:53'),
(8, 2, 'Dodao proizvod', 'Proizvod #54 (PREDATOR TRITON 16)', '2026-08-23 07:54:48'),
(9, 2, 'Promijenio pravilo plaćanja kategorije', 'Kategorija #3', '2026-08-23 11:47:11'),
(10, 2, 'Promijenio pravilo plaćanja kategorije', 'Kategorija #3', '2026-08-23 11:47:14'),
(11, 2, 'Promijenio pravilo plaćanja kategorije', 'Kategorija #3', '2026-08-23 11:47:19'),
(12, 2, 'Promijenio pravilo plaćanja kategorije', 'Kategorija #3', '2026-08-23 11:47:44'),
(13, 2, 'Promijenio pravilo plaćanja kategorije', 'Kategorija #2', '2026-08-23 11:47:45'),
(14, 2, 'Promijenio pravilo plaćanja kategorije', 'Kategorija #2', '2026-08-23 22:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `kartica_obavezna` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `slug`, `name`, `kartica_obavezna`) VALUES
(1, 'komponente', 'Komponente', 0),
(2, 'gaming', 'Gaming oprema', 0),
(3, 'laptopi', 'Laptopi', 0);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `tip` enum('postotak','iznos') NOT NULL,
  `vrijednost` decimal(10,2) NOT NULL,
  `vrijedi_od` datetime DEFAULT NULL,
  `vrijedi_do` datetime DEFAULT NULL,
  `max_koristenja` int(11) DEFAULT NULL,
  `broj_koristenja` int(11) NOT NULL DEFAULT 0,
  `aktivan` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `tip`, `vrijednost`, `vrijedi_od`, `vrijedi_do`, `max_koristenja`, `broj_koristenja`, `aktivan`, `created_at`) VALUES
(2, 'LJETO45', 'postotak', '45.00', '2026-08-22 15:21:00', '2026-08-24 15:21:00', 5, 2, 1, '2026-08-23 06:21:56');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_method` enum('osobno preuzimanje','standardna dostava','ekspresna dostava') NOT NULL DEFAULT 'standardna dostava',
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('čeka plaćanje','plaćena','neuspješno plaćanje','povrat') NOT NULL DEFAULT 'čeka plaćanje',
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_status` enum('kreirana','čeka plaćanje','plaćena','u obradi','poslana','dostavljena','otkazana','povrat','neuspješno plaćanje') NOT NULL DEFAULT 'kreirana',
  `tracking_number` varchar(100) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address_id`, `created_at`, `total_amount`, `shipping_cost`, `delivery_method`, `payment_method`, `payment_status`, `coupon_code`, `discount_amount`, `order_status`, `tracking_number`, `sent_at`, `delivered_at`) VALUES
(1, 2, 1, '2026-08-23 05:25:26', '720.00', '0.00', 'standardna dostava', 'Pouzećem', 'povrat', NULL, '0.00', 'povrat', NULL, NULL, NULL),
(2, 1, 2, '2026-08-23 05:34:14', '3100.00', '0.00', 'standardna dostava', 'Pouzećem', 'čeka plaćanje', NULL, '0.00', 'otkazana', NULL, NULL, NULL),
(3, 1, 3, '2026-08-23 05:43:12', '1200.00', '0.00', 'standardna dostava', 'Pouzećem', 'čeka plaćanje', NULL, '0.00', 'otkazana', NULL, NULL, NULL),
(4, 1, 4, '2026-08-23 06:31:27', '110.00', '0.00', 'standardna dostava', 'Kartica', 'plaćena', 'LJETO45', '90.00', 'otkazana', NULL, NULL, NULL),
(5, 1, 5, '2026-08-23 06:36:45', '990.00', '0.00', 'standardna dostava', 'Kartica', 'plaćena', 'LJETO45', '810.00', 'u obradi', NULL, NULL, NULL),
(6, 1, 6, '2026-08-23 08:07:07', '65.00', '5.00', 'standardna dostava', 'Pouzećem', 'čeka plaćanje', NULL, '0.00', 'otkazana', NULL, NULL, NULL),
(7, 1, 7, '2026-08-23 08:32:45', '2500.00', '0.00', 'osobno preuzimanje', 'Pouzećem', 'čeka plaćanje', NULL, '0.00', 'otkazana', NULL, NULL, NULL),
(8, 1, 8, '2026-08-23 08:33:04', '2200.00', '0.00', 'standardna dostava', 'Pouzećem', 'čeka plaćanje', NULL, '0.00', 'otkazana', NULL, NULL, NULL),
(9, 1, 9, '2026-08-23 08:33:41', '4200.00', '0.00', 'standardna dostava', 'Kartica', 'plaćena', NULL, '0.00', 'otkazana', NULL, NULL, NULL),
(10, 1, 10, '2026-08-23 11:43:28', '2200.00', '0.00', 'standardna dostava', 'Kartica', 'plaćena', NULL, '0.00', 'u obradi', NULL, NULL, NULL),
(11, 1, 11, '2026-08-24 14:09:31', '512.00', '12.00', 'ekspresna dostava', 'Kartica', 'čeka plaćanje', NULL, '0.00', 'otkazana', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 7, 'Ryzen 9 7950X', 1, '600.00'),
(2, 1, 21, 'Logitech G Pro X', 1, '120.00'),
(3, 2, 6, 'RTX 4080', 1, '1300.00'),
(4, 2, 7, 'Ryzen 9 7950X', 3, '600.00'),
(5, 3, 9, 'RTX 4070', 1, '1200.00'),
(6, 4, 26, 'Gaming Chair Pro', 1, '200.00'),
(7, 5, 36, 'ASUS ROG Strix', 1, '1800.00'),
(8, 6, 22, 'Razer DeathAdder', 1, '60.00'),
(9, 7, 38, 'MSI Raider', 1, '2500.00'),
(10, 8, 37, 'Lenovo Legion 7', 1, '2200.00'),
(11, 9, 40, 'MacBook Pro M4', 2, '2100.00'),
(12, 10, 37, 'Lenovo Legion 7', 1, '2200.00'),
(13, 11, 11, 'Ryzen 7 7800X3D', 1, '500.00');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` enum('čeka plaćanje','plaćena','neuspješno plaćanje','povrat') NOT NULL DEFAULT 'čeka plaćanje',
  `amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `method`, `status`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pouzećem', 'povrat', '720.00', '2026-08-23 05:25:26', '2026-08-23 06:38:53'),
(2, 2, 'Pouzećem', 'čeka plaćanje', '3100.00', '2026-08-23 05:34:14', '2026-08-23 05:34:14'),
(3, 3, 'Pouzećem', 'čeka plaćanje', '1200.00', '2026-08-23 05:43:12', '2026-08-23 05:43:12'),
(4, 4, 'Kartica', 'plaćena', '110.00', '2026-08-23 06:31:27', '2026-08-23 06:32:26'),
(5, 5, 'Kartica', 'plaćena', '990.00', '2026-08-23 06:36:45', '2026-08-23 06:37:40'),
(6, 6, 'Pouzećem', 'čeka plaćanje', '65.00', '2026-08-23 08:07:07', '2026-08-23 08:07:07'),
(7, 7, 'Pouzećem', 'čeka plaćanje', '2500.00', '2026-08-23 08:32:45', '2026-08-23 08:32:45'),
(8, 8, 'Pouzećem', 'čeka plaćanje', '2200.00', '2026-08-23 08:33:04', '2026-08-23 08:33:04'),
(9, 9, 'Kartica', 'plaćena', '4200.00', '2026-08-23 08:33:41', '2026-08-23 08:33:55'),
(10, 10, 'Kartica', 'plaćena', '2200.00', '2026-08-23 11:43:28', '2026-08-23 11:43:39'),
(11, 11, 'Kartica', 'čeka plaćanje', '512.00', '2026-08-24 14:09:31', '2026-08-24 14:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `poruke`
--

CREATE TABLE `poruke` (
  `id` int(11) NOT NULL,
  `tip` enum('kontakt','potvrda_narudzbe') NOT NULL,
  `ime` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `predmet` varchar(255) NOT NULL,
  `sadrzaj` text NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poruke`
--

INSERT INTO `poruke` (`id`, `tip`, `ime`, `email`, `predmet`, `sadrzaj`, `order_id`, `created_at`) VALUES
(1, 'potvrda_narudzbe', 'test test', 'test@test.hr', 'Potvrda narudžbe #10', 'Poštovani/a test test,\n\nVaša narudžba #10 je zaprimljena.\n\nNaručeni artikli:\n- Lenovo Legion 7 x1 (2200 €)\n\nUkupno: 2200.00 €\n\nHvala na kupnji!\nPC Shop', 10, '2026-08-23 11:43:39'),
(2, 'kontakt', 'test', 'test@test.hr', 'Poruka s kontakt forme od test', 'Testna poruka', NULL, '2026-08-23 11:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `max_per_order` int(11) NOT NULL DEFAULT 5,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `manufacturer`, `price`, `quantity`, `low_stock_threshold`, `max_per_order`, `category_id`, `image`, `description`, `created_at`) VALUES
(6, 'RTX 4080', 'NVIDIA', '1300.00', 100, 5, 5, 1, 'https://images.unsplash.com/photo-1591488320449-011701bb6704?q=80&w=1200&auto=format&fit=crop', 'Vrhunska gaming grafička kartica.', '2026-08-23 03:46:04'),
(7, 'Ryzen 9 7950X', 'AMD', '600.00', 100, 5, 5, 1, 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop', 'Procesor za gaming i multitasking.', '2026-08-23 03:46:04'),
(8, 'DDR5 32GB', 'Corsair', '150.00', 100, 5, 5, 1, 'https://images.unsplash.com/photo-1541029071515-84cc54f84dc5?q=80&w=1200&auto=format&fit=crop', 'Brza memorija nove generacije.', '2026-08-23 03:46:04'),
(9, 'RTX 4070', 'NVIDIA', '1200.00', 25, 5, 5, 1, 'https://www.nvidia.com/content/dam/en-zz/Solutions/geforce/graphic-cards/40-series/rtx-4070-4070ti/geforce-rtx-4070-super-og-1200x630.jpg', 'Vrhunska gaming grafička kartica.', '2026-08-23 03:46:04'),
(10, 'RTX 4060', 'NVIDIA', '1000.00', 100, 5, 5, 1, 'https://www.mall.hr/i/106660152/550/550', 'Vrhunska gaming grafička kartica.', '2026-08-23 03:46:04'),
(11, 'Ryzen 7 7800X3D', 'AMD', '500.00', 100, 5, 5, 1, 'https://www.links.hr/images/thumbs/0275575_procesor-amd-ryzen-7-7800x3d-box-s-am5-42ghz-96mb-cache-8-core-bez-hladnjaka-010501031_550.jpg', 'Procesor za gaming i multitasking.', '2026-08-23 03:46:04'),
(12, 'Intel i9 14900K', 'Intel', '650.00', 100, 5, 5, 1, 'https://www.instar-informatika.hr/slike/velike/procesor-intel-core-i9-14900k-24c32t-60ghz-36mb-lga1700-bx80-69081-inp-14900k_1.jpg', 'Procesor za gaming i multitasking.', '2026-08-23 03:46:04'),
(13, 'Intel i7 14700K', 'Intel', '550.00', 100, 5, 5, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRR3jCmAa31sbybz_lcalpoDDLfxS_lgkMgdw&s', 'Procesor za gaming i multitasking.', '2026-08-23 03:46:04'),
(14, 'DDR5 64GB', 'Corsair', '300.00', 99, 5, 5, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRO-IdOBZVxpmLJ_lsMk0m3_gWPM88oK8mL8A&s', 'Brza memorija nove generacije.', '2026-08-23 03:46:04'),
(15, 'Samsung 990 Pro 1TB', 'Samsung', '200.00', 100, 5, 5, 1, 'https://media.flixcar.com/webp/synd-asset/Samsung-138083343-hr-990pro-nvme-m2-ssd-mz-v9p1t0bw-538096644--Download-Source--zoom.png', 'Uređaj za pohranu podataka velike brzine učitavanja.', '2026-08-23 03:46:04'),
(16, 'Kingston NV2 2 TB', 'Kingston', '450.00', 100, 5, 5, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHFHIwSg8zF3WlBj7cV1HnZ87nBVXBbGd4Sw&s', 'Uređaj za pohranu podataka velike brzine učitavanja.', '2026-08-23 03:46:04'),
(17, 'ASUS ROG X870E', 'ASUS', '640.00', 100, 5, 5, 1, 'https://www.adm.hr/slike/velike/asus-rog-strix-x870e-e-gaming-wifi-amd-x870e-am5-ddr5-atx-90-17051-095200094.webp', 'Izvanredna matična ploča za gaming.', '2026-08-23 03:46:04'),
(18, 'MSI Z890 Gaming Plus', 'MSI', '300.00', 92, 5, 5, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHzm2gMENm3OAJ9veJnweI96QQybRUYmybvg&s', 'Izvanredna matična ploča za gaming.', '2026-08-23 03:46:04'),
(19, 'NZXT Kraken Elite 360 RGB', 'NZXT', '230.00', 90, 5, 5, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT0o-NerdNcWu1GBLgpz0AsOrjnFkXciUhSkLXk2_2gSaEA4x2s5qEm2A&s=10', 'Vodeno hlađenje za gaming računala', '2026-08-23 03:46:04'),
(20, 'Corsair RM850', 'Corsair', '140.00', 99, 5, 5, 1, 'https://assets.corsair.com/image/upload/f_auto,q_auto/content/CP-9020056-UK-RM850-01.png', 'Odlično napajanje za računala visokih performansi.', '2026-08-23 03:46:04'),
(21, 'Logitech G Pro X', 'Logitech', '120.00', 96, 5, 5, 2, 'https://images.unsplash.com/photo-1547394765-185e1e68f34e?q=80&w=1200&auto=format&fit=crop', 'Doživi zvuk studijske kvalitete i komuniciraj kao profesionalac uz Blue VO!CE tehnologiju.', '2026-08-23 03:46:04'),
(22, 'Razer DeathAdder', 'Razer', '60.00', 100, 5, 5, 2, 'https://images.unsplash.com/photo-1613141412501-9012977f1969?q=80&w=1200&auto=format&fit=crop', 'Legendarna ergonomija i vrhunski senzor čine ovaj miš tvojim najjačim oružjem u igri.', '2026-08-23 03:46:04'),
(23, 'SteelSeries Apex', 'SteelSeries', '180.00', 100, 5, 5, 2, 'https://www.mall.hr/i/46096137', 'Najbrži odziv na svijetu i prilagodljivo osvjetljenje za tipkovnicu koja prati tvoj tempo.', '2026-08-23 03:46:04'),
(24, 'HyperX Cloud III', 'HyperX', '110.00', 100, 5, 5, 2, 'https://www.links.hr/images/thumbs/0282063_slusalice-hyperx-cloud-iii-gaming-dts-crno-crvene-010706177_550.jpg', 'Legendarna udobnost i kristalno čist zvuk za maratonske gaming sesije bez umora.', '2026-08-23 03:46:04'),
(25, 'ASUS TUF Monitor', 'ASUS', '250.00', 99, 5, 5, 2, 'https://www.hardsoft.hr/slike/velike/asus-tuf-gaming-vg27vqm-curved-gaming-monitor-27-fhd-1920-x--16110-90lm0510-b03e70.webp', 'Munjevito osvježavanje i besprijekorna slika bez trzanja za potpunu dominaciju na ekranu', '2026-08-23 03:46:04'),
(26, 'Gaming Chair Pro', 'Secretlab', '200.00', 100, 5, 5, 2, 'https://www.autofull.eu/cdn/shop/files/9_20f390c1-3165-41c2-87e8-bc860e56a0ee.jpg?v=1770773683&width=2048', 'Savršena podrška za tvoja leđa uz vrhunski dizajn koji pretvara tvoj kutak u pravu arenu.', '2026-08-23 03:46:04'),
(27, 'RGB Mousepad', 'HyperX', '30.00', 100, 5, 5, 2, 'https://m.media-amazon.com/images/I/61nSvG2CHQL.jpg', 'Dodaj svom setupu novu dimenziju boja uz savršeno glatku površinu za precizne pokrete.', '2026-08-23 03:46:04'),
(28, 'PS5 Controller', 'Sony', '75.00', 96, 5, 5, 2, 'https://cdn.ozone.hr/media/catalog/product/cache/1/image/400x498/a4e40ebdc3e371adff845072e1c73f37/d/u/160cbc8ae71faef2cabf092336769ec1/bezicni-kontroler-dualsense-30.jpg', 'Osjeti svaku eksploziju i napetost uz revolucionarnu haptičku povratnu informaciju.', '2026-08-23 03:46:04'),
(29, 'Xbox Elite Controller', 'Microsoft', '160.00', 100, 5, 5, 2, 'https://www.gamershop.hr/content/product_instances2/405839/xboxone-xbox-one-elite-wireless-controller-series-2.webp', 'Prilagodi kontroler svom stilu igre i postani nezaustavljiv uz dodatne poluge i preciznost.', '2026-08-23 03:46:04'),
(30, 'Gaming Webcam', 'Logitech', '90.00', 100, 5, 5, 2, 'https://m.media-amazon.com/images/I/61DRyuOB3vL.jpg', 'Izgledaj besprijekorno pred kamerom uz Full HD rezoluciju i automatsko fokusiranje', '2026-08-23 03:46:04'),
(31, 'RGB Headset Stand', 'HyperX', '25.00', 100, 5, 5, 2, 'https://images-cdn.ubuy.co.id/633aaf764a3385288a191c4b-havit-rgb-headphones-stand-with-3-5mm.jpg', 'Čuvaj svoje slušalice sa stilom i organiziraj stol uz efektno ambijentalno svjetlo.', '2026-08-23 03:46:04'),
(32, 'Gaming Desk', 'Secretlab', '180.00', 97, 5, 5, 2, 'https://m.media-amazon.com/images/I/81JnWF-jqPL.jpg', 'Stabilna i prostrana površina dizajnirana da izdrži svu tvoju opremu i žestoke mečeve.', '2026-08-23 03:46:04'),
(33, 'Gaming Microphone', 'HyperX', '130.00', 99, 5, 5, 2, 'https://m.media-amazon.com/images/I/71CKdnpt2LL.jpg', 'Neka tvoj glas zvuči profesionalno i čisto bez pozadinske buke.', '2026-08-23 03:46:04'),
(34, 'VR Headset', 'Meta', '450.00', 98, 5, 5, 2, 'https://cdn.thewirecutter.com/wp-content/media/2024/10/vrheadsets-2048px-08406.jpg?width=2048&quality=60&crop=2048:1365&auto=webp', 'Zakorači u nove svjetove i doživi nevjerojatno iskustvo koje briše granicu između stvarnosti i igre.', '2026-08-23 03:46:04'),
(35, 'Elgato Stream Deck', 'Elgato', '150.00', 99, 5, 5, 2, 'https://m.media-amazon.com/images/I/61gtdFnK+UL._AC_UF894,1000_QL80_.jpg', 'Preuzmi potpunu kontrolu nad svojim streamom jednim dodirom tipke.', '2026-08-23 03:46:04'),
(36, 'ASUS ROG Strix', 'ASUS', '1800.00', 99, 5, 5, 3, 'https://dlcdnwebimgs.asus.com/files/media/8b74e7ee-b66a-4420-894e-3c3b980312ee/v2/img/design/color/strix-g-2022-pink.png', 'Beskompromisna snaga i agresivan dizajn za gamere koji žele biti u samom vrhu.', '2026-08-23 03:46:04'),
(37, 'Lenovo Legion 7', 'Lenovo', '2200.00', 99, 5, 5, 3, 'https://p2-ofp.static.pub/fes/cms/2022/04/27/589xqn5kv49awp7q3o70wvhgj8d3vs828507.png', 'Savršen spoj elegantnog aluminijskog kućišta i brutalnih performansi za ozbiljan gaming.', '2026-08-23 03:46:04'),
(38, 'MSI Raider', 'MSI', '2500.00', 99, 5, 5, 3, 'https://www.links.hr/images/thumbs/0245830_laptop-msi-raider-a18-hx-a9wjg-ryzen-9-9955hx3d-64gb-4tb-ssd-nvidia-geforce-rtx-5090-18-uhd-ips-win_550.jpg', 'Osjeti snagu desktop računala u prijenosnom izdanju uz fascinantno RGB osvjetljenje.', '2026-08-23 03:46:04'),
(39, 'Acer Nitro 5', 'Acer', '900.00', 100, 5, 5, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDDEHWwUj2yNMAr5XEcHLjO6_EPRZuS3-_pw&s', 'Najbolji omjer uloženo-dobiveno koji ti omogućuje ulazak u svijet gaminga bez pražnjenja novčanika.', '2026-08-23 03:46:04'),
(40, 'MacBook Pro M4', 'Apple', '2100.00', 100, 5, 5, 3, 'https://cdsassets.apple.com/live/7WUAS350/images/tech-specs/mbp14-m4-2024.png', 'Nevjerojatna brzina novog čipa i najljepši zaslon za kreativce koji ne pristaju na kompromise.', '2026-08-23 03:46:04'),
(41, 'MSI Katana', 'MSI', '1100.00', 100, 5, 5, 3, 'https://storage-asset.msi.com/global/picture/image/feature/nb/GF/Katana-17-A13V/photo17-3.png', 'Oštra preciznost i pouzdanost inspirirana legendarnim mačem, stvorena za pobjednike.', '2026-08-23 03:46:04'),
(42, 'Dell XPS 15', 'Dell', '1900.00', 100, 5, 5, 3, 'https://www.bug.hr/img/premium-model-dell-xps-15-9530-osim-cijenom-istice-se-kvalitetom-izrade-i_lsjkHW.jpg', 'Vrhunac elegancije i snage u najtanjem okviru, stvoren za profesionalce u pokretu.', '2026-08-23 03:46:04'),
(43, 'HP Omen', 'HP', '1500.00', 100, 5, 5, 3, 'https://www.mall.hr/i/39713727/550/550', 'Napredno hlađenje i vrhunske komponente osiguravaju stabilan rad čak i u najžešćim bitkama', '2026-08-23 03:46:04'),
(44, 'ASUS Zenbook', 'ASUS', '1200.00', 100, 5, 5, 3, 'https://dlcdnwebimgs.asus.com/gain/c513878b-1b7e-419a-9cc2-828e5bcbdf91/', 'Nevjerojatno lagan i tanak laptop s OLED zaslonom koji oduzima dah pri svakom korištenju.', '2026-08-23 03:46:04'),
(45, 'Lenovo ThinkPad', 'Lenovo', '1400.00', 100, 5, 5, 3, 'https://p1-ofp.static.pub//fes/cms/2024/03/07/ihyl2i3451w0zhcrk3y8kv3a9piaj7136432.jpg', 'Legendarna izdržljivost i najbolja tipkovnica na tržištu za maksimalnu produktivnost.', '2026-08-23 03:46:04'),
(46, 'Alienware M18', 'Alienware', '3200.00', 100, 5, 5, 3, 'https://i.pcmag.com/imagery/reviews/01piWcwFmGnmLdRrcQlLSJF-5.fit_lim.size_1050x.jpg', 'Ogroman zaslon i ekstremna snaga čine ovaj laptop pravom zvijeri za najzahtjevnije zadatke.', '2026-08-23 03:46:04'),
(47, 'Razer Blade', 'Razer', '2800.00', 100, 5, 5, 3, 'https://m.media-amazon.com/images/I/814PVSAztPL.jpg', '\"MacBook među gaming laptopima\" koji nudi čisti luksuz i nevjerojatnu moć u tankom kućištu.', '2026-08-23 03:46:04'),
(48, 'Gigabyte Aero', 'Gigabyte', '2000.00', 99, 5, 5, 3, 'https://static.gigabyte.com/StaticFile/Image/Global/14950cbb5c4eadc9279fb959915b2c21/ProductRemoveBg/26527', 'Specijaliziran za video obradu i dizajn uz tvornički kalibriran zaslon savršenih boja.', '2026-08-23 03:46:04'),
(49, 'HP Victus', 'HP', '850.00', 97, 5, 5, 3, 'https://www.links.hr/images/thumbs/0298233_laptop-hp-victus-15-fa2130nm-core-i7-13620h-16gb-1tb-ssd-nvidia-geforce-rtx-5060-156-fhd-144hz-ips-_550.jpg', 'Moderan dizajn i odlične performanse po cijeni koja postavlja nove standarde pristupačnosti.', '2026-08-23 03:46:04'),
(50, 'Acer Predator', 'Acer', '1700.00', 97, 5, 5, 3, 'https://cdn.assets.prezly.com/2188e46c-d6da-4272-b778-2b5219c8842f/-/format/auto/Predator-Helios-700_PH717-17_01.png', 'Agresivan izgled i napredna tehnologija hlađenja za najteže gaming izazove.', '2026-08-23 03:46:04'),
(51, 'Razer Balde 14', 'Razer', '3100.00', 20, 5, 5, 3, 'https://sm.pcmag.com/t/pcmag_uk/review/r/razer-blad/razer-blade-14_hsdu.1920.jpg', 'Razer Blade 14 je vrhunski prijenosnik koji spaja iznimne performanse i visoku mobilnost', '2026-08-23 03:46:04'),
(52, 'Računalo', 'PC Shop', '1000.00', 300, 5, 5, 2, 'https://www.bug.hr/img/u-potrazi-ste-za-novim-igracim-racunalom-bacite-oko-na-ovu-konfiguraciju_XKen5k.jpg', 'Računalo za gaming', '2026-08-23 03:46:04'),
(53, 'Alienware - Area-51 18\"', 'Alienware', '6000.00', 20, 5, 5, 3, 'https://m.media-amazon.com/images/I/71fP88COuvL._AC_UF894,1000_QL80_.jpg', 'Alienware - Area-51 18\" 2.5K Gaming Laptop Intel Core Ultra 9 Series 2 275HX 2025 64GB Memory NVIDIA GeForce RTX 5090 2TB Storage - Liquid Teal', '2026-08-23 03:46:04'),
(54, 'PREDATOR TRITON 16', 'ASUS', '2700.00', 30, 5, 5, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTaNOTrbCB_3GmcVL6wWXd6m1Xc-fHH8QnGtRWBBJf7Rw&s', 'Acer Predator Triton 16 (uključujući varijantu Triton Neo 16) moćno je 16-inčno prijenosno računalo stvoreno za igranje i stvaranje sadržaja. Spaja elegantno metalno kućište s Intel Core procesorima, snažnom NVIDIA RTX grafikom i preciznim zaslonom omjera 16:10.', '2026-08-23 07:54:48');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `sort_order`) VALUES
(1, 6, 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop', 1),
(2, 6, 'https://images.unsplash.com/photo-1541029071515-84cc54f84dc5?q=80&w=1200&auto=format&fit=crop', 2),
(3, 7, 'https://images.unsplash.com/photo-1541029071515-84cc54f84dc5?q=80&w=1200&auto=format&fit=crop', 1),
(4, 7, 'https://www.nvidia.com/content/dam/en-zz/Solutions/geforce/graphic-cards/40-series/rtx-4070-4070ti/geforce-rtx-4070-super-og-1200x630.jpg', 2),
(5, 8, 'https://www.nvidia.com/content/dam/en-zz/Solutions/geforce/graphic-cards/40-series/rtx-4070-4070ti/geforce-rtx-4070-super-og-1200x630.jpg', 1),
(6, 8, 'https://www.mall.hr/i/106660152/550/550', 2),
(7, 9, 'https://www.mall.hr/i/106660152/550/550', 1),
(8, 9, 'https://www.links.hr/images/thumbs/0275575_procesor-amd-ryzen-7-7800x3d-box-s-am5-42ghz-96mb-cache-8-core-bez-hladnjaka-010501031_550.jpg', 2),
(9, 10, 'https://www.links.hr/images/thumbs/0275575_procesor-amd-ryzen-7-7800x3d-box-s-am5-42ghz-96mb-cache-8-core-bez-hladnjaka-010501031_550.jpg', 1),
(10, 10, 'https://www.instar-informatika.hr/slike/velike/procesor-intel-core-i9-14900k-24c32t-60ghz-36mb-lga1700-bx80-69081-inp-14900k_1.jpg', 2),
(11, 11, 'https://www.instar-informatika.hr/slike/velike/procesor-intel-core-i9-14900k-24c32t-60ghz-36mb-lga1700-bx80-69081-inp-14900k_1.jpg', 1),
(12, 11, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRR3jCmAa31sbybz_lcalpoDDLfxS_lgkMgdw&s', 2),
(13, 12, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRR3jCmAa31sbybz_lcalpoDDLfxS_lgkMgdw&s', 1),
(14, 12, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRO-IdOBZVxpmLJ_lsMk0m3_gWPM88oK8mL8A&s', 2),
(15, 13, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRO-IdOBZVxpmLJ_lsMk0m3_gWPM88oK8mL8A&s', 1),
(16, 13, 'https://media.flixcar.com/webp/synd-asset/Samsung-138083343-hr-990pro-nvme-m2-ssd-mz-v9p1t0bw-538096644--Download-Source--zoom.png', 2),
(17, 14, 'https://media.flixcar.com/webp/synd-asset/Samsung-138083343-hr-990pro-nvme-m2-ssd-mz-v9p1t0bw-538096644--Download-Source--zoom.png', 1),
(18, 14, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHFHIwSg8zF3WlBj7cV1HnZ87nBVXBbGd4Sw&s', 2),
(19, 15, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHFHIwSg8zF3WlBj7cV1HnZ87nBVXBbGd4Sw&s', 1),
(20, 15, 'https://www.adm.hr/slike/velike/asus-rog-strix-x870e-e-gaming-wifi-amd-x870e-am5-ddr5-atx-90-17051-095200094.webp', 2),
(21, 16, 'https://www.adm.hr/slike/velike/asus-rog-strix-x870e-e-gaming-wifi-amd-x870e-am5-ddr5-atx-90-17051-095200094.webp', 1),
(22, 16, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHzm2gMENm3OAJ9veJnweI96QQybRUYmybvg&s', 2),
(23, 17, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHzm2gMENm3OAJ9veJnweI96QQybRUYmybvg&s', 1),
(24, 17, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT0o-NerdNcWu1GBLgpz0AsOrjnFkXciUhSkLXk2_2gSaEA4x2s5qEm2A&s=10', 2),
(25, 18, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT0o-NerdNcWu1GBLgpz0AsOrjnFkXciUhSkLXk2_2gSaEA4x2s5qEm2A&s=10', 1),
(26, 18, 'https://assets.corsair.com/image/upload/f_auto,q_auto/content/CP-9020056-UK-RM850-01.png', 2),
(27, 19, 'https://assets.corsair.com/image/upload/f_auto,q_auto/content/CP-9020056-UK-RM850-01.png', 1),
(28, 19, 'https://images.unsplash.com/photo-1591488320449-011701bb6704?q=80&w=1200&auto=format&fit=crop', 2),
(29, 20, 'https://images.unsplash.com/photo-1591488320449-011701bb6704?q=80&w=1200&auto=format&fit=crop', 1),
(30, 20, 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop', 2),
(31, 21, 'https://images.unsplash.com/photo-1613141412501-9012977f1969?q=80&w=1200&auto=format&fit=crop', 1),
(32, 21, 'https://www.mall.hr/i/46096137', 2),
(33, 22, 'https://www.mall.hr/i/46096137', 1),
(34, 22, 'https://www.links.hr/images/thumbs/0282063_slusalice-hyperx-cloud-iii-gaming-dts-crno-crvene-010706177_550.jpg', 2),
(35, 23, 'https://www.links.hr/images/thumbs/0282063_slusalice-hyperx-cloud-iii-gaming-dts-crno-crvene-010706177_550.jpg', 1),
(36, 23, 'https://www.hardsoft.hr/slike/velike/asus-tuf-gaming-vg27vqm-curved-gaming-monitor-27-fhd-1920-x--16110-90lm0510-b03e70.webp', 2),
(37, 24, 'https://www.hardsoft.hr/slike/velike/asus-tuf-gaming-vg27vqm-curved-gaming-monitor-27-fhd-1920-x--16110-90lm0510-b03e70.webp', 1),
(38, 24, 'https://www.autofull.eu/cdn/shop/files/9_20f390c1-3165-41c2-87e8-bc860e56a0ee.jpg?v=1770773683&width=2048', 2),
(39, 25, 'https://www.autofull.eu/cdn/shop/files/9_20f390c1-3165-41c2-87e8-bc860e56a0ee.jpg?v=1770773683&width=2048', 1),
(40, 25, 'https://m.media-amazon.com/images/I/61nSvG2CHQL.jpg', 2),
(41, 26, 'https://m.media-amazon.com/images/I/61nSvG2CHQL.jpg', 1),
(42, 26, 'https://cdn.ozone.hr/media/catalog/product/cache/1/image/400x498/a4e40ebdc3e371adff845072e1c73f37/d/u/160cbc8ae71faef2cabf092336769ec1/bezicni-kontroler-dualsense-30.jpg', 2),
(43, 27, 'https://cdn.ozone.hr/media/catalog/product/cache/1/image/400x498/a4e40ebdc3e371adff845072e1c73f37/d/u/160cbc8ae71faef2cabf092336769ec1/bezicni-kontroler-dualsense-30.jpg', 1),
(44, 27, 'https://www.gamershop.hr/content/product_instances2/405839/xboxone-xbox-one-elite-wireless-controller-series-2.webp', 2),
(45, 28, 'https://www.gamershop.hr/content/product_instances2/405839/xboxone-xbox-one-elite-wireless-controller-series-2.webp', 1),
(46, 28, 'https://m.media-amazon.com/images/I/61DRyuOB3vL.jpg', 2),
(47, 29, 'https://m.media-amazon.com/images/I/61DRyuOB3vL.jpg', 1),
(48, 29, 'https://images-cdn.ubuy.co.id/633aaf764a3385288a191c4b-havit-rgb-headphones-stand-with-3-5mm.jpg', 2),
(49, 30, 'https://images-cdn.ubuy.co.id/633aaf764a3385288a191c4b-havit-rgb-headphones-stand-with-3-5mm.jpg', 1),
(50, 30, 'https://m.media-amazon.com/images/I/81JnWF-jqPL.jpg', 2),
(51, 31, 'https://m.media-amazon.com/images/I/81JnWF-jqPL.jpg', 1),
(52, 31, 'https://m.media-amazon.com/images/I/71CKdnpt2LL.jpg', 2),
(53, 32, 'https://m.media-amazon.com/images/I/71CKdnpt2LL.jpg', 1),
(54, 32, 'https://cdn.thewirecutter.com/wp-content/media/2024/10/vrheadsets-2048px-08406.jpg?width=2048&quality=60&crop=2048:1365&auto=webp', 2),
(55, 33, 'https://cdn.thewirecutter.com/wp-content/media/2024/10/vrheadsets-2048px-08406.jpg?width=2048&quality=60&crop=2048:1365&auto=webp', 1),
(56, 33, 'https://m.media-amazon.com/images/I/61gtdFnK+UL._AC_UF894,1000_QL80_.jpg', 2),
(57, 34, 'https://m.media-amazon.com/images/I/61gtdFnK+UL._AC_UF894,1000_QL80_.jpg', 1),
(58, 34, 'https://www.bug.hr/img/u-potrazi-ste-za-novim-igracim-racunalom-bacite-oko-na-ovu-konfiguraciju_XKen5k.jpg', 2),
(59, 35, 'https://www.bug.hr/img/u-potrazi-ste-za-novim-igracim-racunalom-bacite-oko-na-ovu-konfiguraciju_XKen5k.jpg', 1),
(60, 35, 'https://images.unsplash.com/photo-1547394765-185e1e68f34e?q=80&w=1200&auto=format&fit=crop', 2),
(61, 52, 'https://images.unsplash.com/photo-1547394765-185e1e68f34e?q=80&w=1200&auto=format&fit=crop', 1),
(62, 52, 'https://images.unsplash.com/photo-1613141412501-9012977f1969?q=80&w=1200&auto=format&fit=crop', 2),
(63, 36, 'https://p2-ofp.static.pub/fes/cms/2022/04/27/589xqn5kv49awp7q3o70wvhgj8d3vs828507.png', 1),
(64, 36, 'https://www.links.hr/images/thumbs/0245830_laptop-msi-raider-a18-hx-a9wjg-ryzen-9-9955hx3d-64gb-4tb-ssd-nvidia-geforce-rtx-5090-18-uhd-ips-win_550.jpg', 2),
(65, 37, 'https://www.links.hr/images/thumbs/0245830_laptop-msi-raider-a18-hx-a9wjg-ryzen-9-9955hx3d-64gb-4tb-ssd-nvidia-geforce-rtx-5090-18-uhd-ips-win_550.jpg', 1),
(66, 37, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDDEHWwUj2yNMAr5XEcHLjO6_EPRZuS3-_pw&s', 2),
(67, 38, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDDEHWwUj2yNMAr5XEcHLjO6_EPRZuS3-_pw&s', 1),
(68, 38, 'https://cdsassets.apple.com/live/7WUAS350/images/tech-specs/mbp14-m4-2024.png', 2),
(69, 39, 'https://cdsassets.apple.com/live/7WUAS350/images/tech-specs/mbp14-m4-2024.png', 1),
(70, 39, 'https://storage-asset.msi.com/global/picture/image/feature/nb/GF/Katana-17-A13V/photo17-3.png', 2),
(71, 40, 'https://storage-asset.msi.com/global/picture/image/feature/nb/GF/Katana-17-A13V/photo17-3.png', 1),
(72, 40, 'https://www.bug.hr/img/premium-model-dell-xps-15-9530-osim-cijenom-istice-se-kvalitetom-izrade-i_lsjkHW.jpg', 2),
(73, 41, 'https://www.bug.hr/img/premium-model-dell-xps-15-9530-osim-cijenom-istice-se-kvalitetom-izrade-i_lsjkHW.jpg', 1),
(74, 41, 'https://www.mall.hr/i/39713727/550/550', 2),
(75, 42, 'https://www.mall.hr/i/39713727/550/550', 1),
(76, 42, 'https://dlcdnwebimgs.asus.com/gain/c513878b-1b7e-419a-9cc2-828e5bcbdf91/', 2),
(77, 43, 'https://dlcdnwebimgs.asus.com/gain/c513878b-1b7e-419a-9cc2-828e5bcbdf91/', 1),
(78, 43, 'https://p1-ofp.static.pub//fes/cms/2024/03/07/ihyl2i3451w0zhcrk3y8kv3a9piaj7136432.jpg', 2),
(79, 44, 'https://p1-ofp.static.pub//fes/cms/2024/03/07/ihyl2i3451w0zhcrk3y8kv3a9piaj7136432.jpg', 1),
(80, 44, 'https://i.pcmag.com/imagery/reviews/01piWcwFmGnmLdRrcQlLSJF-5.fit_lim.size_1050x.jpg', 2),
(81, 45, 'https://i.pcmag.com/imagery/reviews/01piWcwFmGnmLdRrcQlLSJF-5.fit_lim.size_1050x.jpg', 1),
(82, 45, 'https://m.media-amazon.com/images/I/814PVSAztPL.jpg', 2),
(83, 46, 'https://m.media-amazon.com/images/I/814PVSAztPL.jpg', 1),
(84, 46, 'https://static.gigabyte.com/StaticFile/Image/Global/14950cbb5c4eadc9279fb959915b2c21/ProductRemoveBg/26527', 2),
(85, 47, 'https://static.gigabyte.com/StaticFile/Image/Global/14950cbb5c4eadc9279fb959915b2c21/ProductRemoveBg/26527', 1),
(86, 47, 'https://www.links.hr/images/thumbs/0298233_laptop-hp-victus-15-fa2130nm-core-i7-13620h-16gb-1tb-ssd-nvidia-geforce-rtx-5060-156-fhd-144hz-ips-_550.jpg', 2),
(87, 48, 'https://www.links.hr/images/thumbs/0298233_laptop-hp-victus-15-fa2130nm-core-i7-13620h-16gb-1tb-ssd-nvidia-geforce-rtx-5060-156-fhd-144hz-ips-_550.jpg', 1),
(88, 48, 'https://cdn.assets.prezly.com/2188e46c-d6da-4272-b778-2b5219c8842f/-/format/auto/Predator-Helios-700_PH717-17_01.png', 2),
(89, 49, 'https://cdn.assets.prezly.com/2188e46c-d6da-4272-b778-2b5219c8842f/-/format/auto/Predator-Helios-700_PH717-17_01.png', 1),
(90, 49, 'https://sm.pcmag.com/t/pcmag_uk/review/r/razer-blad/razer-blade-14_hsdu.1920.jpg', 2),
(91, 50, 'https://sm.pcmag.com/t/pcmag_uk/review/r/razer-blad/razer-blade-14_hsdu.1920.jpg', 1),
(92, 50, 'https://m.media-amazon.com/images/I/71fP88COuvL._AC_UF894,1000_QL80_.jpg', 2),
(93, 51, 'https://m.media-amazon.com/images/I/71fP88COuvL._AC_UF894,1000_QL80_.jpg', 1),
(94, 51, 'https://dlcdnwebimgs.asus.com/files/media/8b74e7ee-b66a-4420-894e-3c3b980312ee/v2/img/design/color/strix-g-2022-pink.png', 2),
(95, 53, 'https://dlcdnwebimgs.asus.com/files/media/8b74e7ee-b66a-4420-894e-3c3b980312ee/v2/img/design/color/strix-g-2022-pink.png', 1),
(96, 53, 'https://p2-ofp.static.pub/fes/cms/2022/04/27/589xqn5kv49awp7q3o70wvhgj8d3vs828507.png', 2),
(97, 12, 'https://upload.wikimedia.org/wikipedia/commons/1/12/Intel_i9-14900K.webp', 1),
(98, 22, 'https://upload.wikimedia.org/wikipedia/commons/1/19/Razer_DeathAdder_2013_Edition-front_oblique-ar_16to10-fs_PNr%C2%B00400.jpg', 1),
(99, 22, 'https://upload.wikimedia.org/wikipedia/commons/9/9f/Razer_DeathAdder_2013_Edition-side_left_PNr%C2%B00402.jpg', 2),
(100, 28, 'https://upload.wikimedia.org/wikipedia/commons/e/e0/Playstation_Dualsense_controller.jpg', 1),
(101, 29, 'https://upload.wikimedia.org/wikipedia/commons/c/c5/Xbox_One_Elite_Wireless_Controller_Series_2_%28Model_1797%29.jpg', 1),
(102, 34, 'https://upload.wikimedia.org/wikipedia/commons/9/99/Meta_Quest_3_front_View.jpg', 1),
(103, 34, 'https://upload.wikimedia.org/wikipedia/commons/a/af/Meta_Quest_3_display_unit.jpg', 2),
(104, 40, 'https://upload.wikimedia.org/wikipedia/commons/4/43/MacBook_Pro_%2816-inch%2C_M4_Pro%2C_Silver%29.jpg', 1),
(105, 42, 'https://upload.wikimedia.org/wikipedia/commons/d/df/Dell_XPS_15_%282017%29.png', 1),
(106, 54, 'https://www.mall.hr/i/138796661/550/550', 100),
(107, 54, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSmg4ON4Sdu9YdXpZvSm0SgQ7GfsaZdWNR0WvQnlXUAAgx8pQyfkTT01jQw&s=10', 101);

-- --------------------------------------------------------

--
-- Table structure for table `product_specs`
--

CREATE TABLE `product_specs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_specs`
--

INSERT INTO `product_specs` (`id`, `product_id`, `spec_name`, `spec_value`, `sort_order`) VALUES
(1, 54, 'Zaslon', '16-inčni ekran visoke rezolucije (WQXGA ili 3.2K) s brzinom osvježavanja do 240 Hz ili 165 Hz te vjernim prikazom boja.', 0),
(2, 54, 'Performanse', 'Pokreću ga snažni Intel Core ili Core Ultra procesori u kombinaciji s brzom DDR5 radnom memorijom.', 1),
(3, 54, 'Grafika', 'Dolazi s moćnim NVIDIA GeForce RTX grafičkim karticama (do modela RTX 4070) za glatko igranje i rad u zahtjevnim aplikacijama.', 2),
(4, 54, 'Dizajn i hlađenje', 'Tanak i izdržljiv aluminijski dizajn skriva napredni sustav hlađenja s ventilatorima koji održavaju optimalnu temperaturu komponenti.', 3),
(5, 6, 'Proizvođač čipa', 'NVIDIA', 0),
(6, 6, 'CUDA jezgre', '9728', 1),
(7, 6, 'Memorija', '16GB GDDR6X', 2),
(8, 6, 'Sabirnica memorije', '256-bit', 3),
(9, 6, 'Potrošnja (TDP)', '320W', 4),
(10, 9, 'Proizvođač čipa', 'NVIDIA', 0),
(11, 9, 'CUDA jezgre', '5888', 1),
(12, 9, 'Memorija', '12GB GDDR6X', 2),
(13, 9, 'Sabirnica memorije', '192-bit', 3),
(14, 9, 'Potrošnja (TDP)', '200W', 4),
(15, 10, 'Proizvođač čipa', 'NVIDIA', 0),
(16, 10, 'CUDA jezgre', '3072', 1),
(17, 10, 'Memorija', '8GB GDDR6', 2),
(18, 10, 'Sabirnica memorije', '128-bit', 3),
(19, 10, 'Potrošnja (TDP)', '115W', 4),
(20, 7, 'Broj jezgri', '16', 0),
(21, 7, 'Broj niti', '32', 1),
(22, 7, 'Bazna frekvencija', '4.5 GHz', 2),
(23, 7, 'Boost frekvencija', '5.7 GHz', 3),
(24, 7, 'Socket', 'AM5', 4),
(25, 7, 'Potrošnja (TDP)', '170W', 5),
(26, 11, 'Broj jezgri', '8', 0),
(27, 11, 'Broj niti', '16', 1),
(28, 11, 'Bazna frekvencija', '4.2 GHz', 2),
(29, 11, 'Boost frekvencija', '5.0 GHz', 3),
(30, 11, 'Socket', 'AM5', 4),
(31, 11, 'Cache (3D V-Cache)', '96MB', 5),
(32, 12, 'Broj jezgri', '24 (8P+16E)', 0),
(33, 12, 'Broj niti', '32', 1),
(34, 12, 'Bazna frekvencija', '3.2 GHz', 2),
(35, 12, 'Boost frekvencija', '6.0 GHz', 3),
(36, 12, 'Socket', 'LGA1700', 4),
(37, 12, 'Potrošnja (TDP)', '125W (253W max)', 5),
(38, 13, 'Broj jezgri', '20 (8P+12E)', 0),
(39, 13, 'Broj niti', '28', 1),
(40, 13, 'Bazna frekvencija', '3.4 GHz', 2),
(41, 13, 'Boost frekvencija', '5.6 GHz', 3),
(42, 13, 'Socket', 'LGA1700', 4),
(43, 13, 'Potrošnja (TDP)', '125W', 5),
(44, 8, 'Kapacitet', '32GB (2x16GB)', 0),
(45, 8, 'Vrsta memorije', 'DDR5', 1),
(46, 8, 'Brzina', '6000 MHz', 2),
(47, 8, 'Kašnjenje (CL)', 'CL36', 3),
(48, 14, 'Kapacitet', '64GB (2x32GB)', 0),
(49, 14, 'Vrsta memorije', 'DDR5', 1),
(50, 14, 'Brzina', '5600 MHz', 2),
(51, 14, 'Kašnjenje (CL)', 'CL40', 3),
(52, 15, 'Kapacitet', '1TB', 0),
(53, 15, 'Sučelje', 'PCIe 4.0 NVMe', 1),
(54, 15, 'Brzina čitanja', '7450 MB/s', 2),
(55, 15, 'Brzina pisanja', '6900 MB/s', 3),
(56, 15, 'Format', 'M.2 2280', 4),
(57, 16, 'Kapacitet', '2TB', 0),
(58, 16, 'Sučelje', 'PCIe 4.0 NVMe', 1),
(59, 16, 'Brzina čitanja', '3500 MB/s', 2),
(60, 16, 'Brzina pisanja', '2800 MB/s', 3),
(61, 16, 'Format', 'M.2 2280', 4),
(62, 17, 'Socket', 'AM5', 0),
(63, 17, 'Chipset', 'X870E', 1),
(64, 17, 'Format', 'ATX', 2),
(65, 17, 'Utori za RAM', '4x DDR5, do 192GB', 3),
(66, 17, 'PCIe generacija', 'PCIe 5.0', 4),
(67, 18, 'Socket', 'LGA1851', 0),
(68, 18, 'Chipset', 'Z890', 1),
(69, 18, 'Format', 'ATX', 2),
(70, 18, 'Utori za RAM', '4x DDR5', 3),
(71, 18, 'PCIe generacija', 'PCIe 5.0', 4),
(72, 19, 'Tip hlađenja', 'Vodeno hlađenje (AIO)', 0),
(73, 19, 'Veličina radijatora', '360mm', 1),
(74, 19, 'Broj ventilatora', '3', 2),
(75, 19, 'Zaslon', 'LCD zaslon na pumpi', 3),
(76, 19, 'Kompatibilnost', 'Intel LGA1700 / AMD AM5', 4),
(77, 20, 'Snaga', '850W', 0),
(78, 20, 'Certifikat', '80+ Gold', 1),
(79, 20, 'Modularnost', 'Potpuno modularno', 2),
(80, 20, 'Ventilator', '135mm', 3),
(81, 21, 'Tip', 'Žičane slušalice s mikrofonom', 0),
(82, 21, 'Zvučnici', '50mm', 1),
(83, 21, 'Mikrofon', 'Blue VO!CE tehnologija', 2),
(84, 21, 'Priključak', 'USB / 3.5mm', 3),
(85, 22, 'Senzor', '30000 DPI optički', 0),
(86, 22, 'Broj tipki', '8', 1),
(87, 22, 'Priključak', 'Žičani USB', 2),
(88, 22, 'Težina', '82g', 3),
(89, 23, 'Tip tipkovnice', 'Mehanička', 0),
(90, 23, 'Switch', 'SteelSeries QX2', 1),
(91, 23, 'Pozadinsko osvjetljenje', 'RGB po tipki', 2),
(92, 23, 'Priključak', 'USB', 3),
(93, 24, 'Tip', 'Žičane slušalice', 0),
(94, 24, 'Zvučnici', '53mm', 1),
(95, 24, 'Mikrofon', 'Odvojivi mikrofon', 2),
(96, 24, 'Priključak', 'USB-C / 3.5mm', 3),
(97, 25, 'Dijagonala', '27\"', 0),
(98, 25, 'Rezolucija', '1920x1080', 1),
(99, 25, 'Osvježavanje', '165Hz', 2),
(100, 25, 'Vrijeme odziva', '1ms', 3),
(101, 25, 'Panel', 'VA', 4),
(102, 26, 'Materijal', 'PU koža', 0),
(103, 26, 'Nosivost', 'do 150kg', 1),
(104, 26, 'Naslon', 'Podesiv 90°-180°', 2),
(105, 26, 'Jastuci', 'Za vrat i leđa', 3),
(106, 27, 'Dimenzije', '900x400mm', 0),
(107, 27, 'Osvjetljenje', 'RGB rubno', 1),
(108, 27, 'Površina', 'Glatka tkanina', 2),
(109, 27, 'Podloga', 'Protuklizna guma', 3),
(110, 28, 'Tip', 'DualSense', 0),
(111, 28, 'Povezivanje', 'Bluetooth / USB-C', 1),
(112, 28, 'Baterija', '3.5h - 12h', 2),
(113, 28, 'Posebnosti', 'Haptička povratna informacija', 3),
(114, 29, 'Tip', 'Xbox Elite Series 2', 0),
(115, 29, 'Povezivanje', 'Bluetooth / USB-C', 1),
(116, 29, 'Podesivost', 'Zamjenjivi palčići i okidači', 2),
(117, 29, 'Baterija', 'do 40h', 3),
(118, 30, 'Rezolucija', 'Full HD 1080p', 0),
(119, 30, 'Broj sličica', '30/60 fps', 1),
(120, 30, 'Fokus', 'Automatski fokus', 2),
(121, 30, 'Priključak', 'USB', 3),
(122, 31, 'Materijal', 'Aluminij i plastika', 0),
(123, 31, 'Osvjetljenje', 'RGB baza', 1),
(124, 31, 'USB', 'Ugrađeni USB hub', 2),
(125, 31, 'Kompatibilnost', 'Univerzalna', 3),
(126, 32, 'Dimenzije', '140x60cm', 0),
(127, 32, 'Materijal', 'Čelična konstrukcija + MDF ploča', 1),
(128, 32, 'Nosivost', 'do 100kg', 2),
(129, 32, 'Kabelski menadžment', 'Da', 3),
(130, 33, 'Tip', 'Kondenzatorski USB mikrofon', 0),
(131, 33, 'Frekvencijski opseg', '20Hz - 20kHz', 1),
(132, 33, 'Uzorak snimanja', 'Kardioidni', 2),
(133, 33, 'Priključak', 'USB', 3),
(134, 34, 'Rezolucija', '2064x2208 po oku', 0),
(135, 34, 'Osvježavanje', '120Hz', 1),
(136, 34, 'Praćenje', 'Inside-out praćenje', 2),
(137, 34, 'Kontroleri', '2x uključena', 3),
(138, 35, 'Broj tipki', '15 LCD tipki', 0),
(139, 35, 'Ekran', 'Zaseban LCD po tipki', 1),
(140, 35, 'Povezivanje', 'USB-C', 2),
(141, 35, 'Softver', 'Elgato Stream Deck aplikacija', 3),
(142, 52, 'Procesor', 'Ryzen 7 / Intel i7 klase', 0),
(143, 52, 'Grafička kartica', 'RTX 4060 / 4070 klase', 1),
(144, 52, 'Radna memorija', '16-32GB DDR5', 2),
(145, 52, 'Pohrana', '1TB NVMe SSD', 3),
(146, 36, 'Procesor', 'Intel Core i9', 0),
(147, 36, 'Grafička kartica', 'RTX 4070', 1),
(148, 36, 'Radna memorija', '16GB DDR5', 2),
(149, 36, 'Zaslon', '16\" QHD 240Hz', 3),
(150, 36, 'Pohrana', '1TB SSD', 4),
(151, 37, 'Procesor', 'AMD Ryzen 9', 0),
(152, 37, 'Grafička kartica', 'RTX 4080', 1),
(153, 37, 'Radna memorija', '32GB DDR5', 2),
(154, 37, 'Zaslon', '16\" WQXGA 240Hz', 3),
(155, 37, 'Pohrana', '1TB SSD', 4),
(156, 38, 'Procesor', 'Intel Core i9', 0),
(157, 38, 'Grafička kartica', 'RTX 4090', 1),
(158, 38, 'Radna memorija', '32GB DDR5', 2),
(159, 38, 'Zaslon', '17\" QHD+ 240Hz', 3),
(160, 38, 'Pohrana', '2TB SSD', 4),
(161, 39, 'Procesor', 'Intel Core i5', 0),
(162, 39, 'Grafička kartica', 'RTX 4050', 1),
(163, 39, 'Radna memorija', '16GB DDR5', 2),
(164, 39, 'Zaslon', '15.6\" FHD 144Hz', 3),
(165, 39, 'Pohrana', '512GB SSD', 4),
(166, 40, 'Procesor', 'Apple M4 Pro', 0),
(167, 40, 'Radna memorija', '24GB objedinjena memorija', 1),
(168, 40, 'Zaslon', '14.2\" Liquid Retina XDR', 2),
(169, 40, 'Pohrana', '512GB SSD', 3),
(170, 40, 'Baterija', 'do 18h', 4),
(171, 41, 'Procesor', 'Intel Core i7', 0),
(172, 41, 'Grafička kartica', 'RTX 4060', 1),
(173, 41, 'Radna memorija', '16GB DDR5', 2),
(174, 41, 'Zaslon', '17.3\" FHD 144Hz', 3),
(175, 41, 'Pohrana', '1TB SSD', 4),
(176, 42, 'Procesor', 'Intel Core i7', 0),
(177, 42, 'Grafička kartica', 'RTX 4050', 1),
(178, 42, 'Radna memorija', '16GB DDR5', 2),
(179, 42, 'Zaslon', '15.6\" 3.5K OLED', 3),
(180, 42, 'Pohrana', '512GB SSD', 4),
(181, 43, 'Procesor', 'Intel Core i7', 0),
(182, 43, 'Grafička kartica', 'RTX 4070', 1),
(183, 43, 'Radna memorija', '16GB DDR5', 2),
(184, 43, 'Zaslon', '16.1\" QHD 165Hz', 3),
(185, 43, 'Pohrana', '1TB SSD', 4),
(186, 44, 'Procesor', 'Intel Core Ultra 7', 0),
(187, 44, 'Radna memorija', '16GB LPDDR5', 1),
(188, 44, 'Zaslon', '14\" 2.8K OLED', 2),
(189, 44, 'Pohrana', '1TB SSD', 3),
(190, 44, 'Težina', '1.2kg', 4),
(191, 45, 'Procesor', 'Intel Core i7', 0),
(192, 45, 'Radna memorija', '16GB DDR5', 1),
(193, 45, 'Zaslon', '14\" WUXGA', 2),
(194, 45, 'Pohrana', '512GB SSD', 3),
(195, 45, 'Tipkovnica', 'ThinkPad tipkovnica', 4),
(196, 46, 'Procesor', 'Intel Core i9', 0),
(197, 46, 'Grafička kartica', 'RTX 4090', 1),
(198, 46, 'Radna memorija', '32GB DDR5', 2),
(199, 46, 'Zaslon', '18\" QHD+ 165Hz', 3),
(200, 46, 'Pohrana', '2TB SSD', 4),
(201, 47, 'Procesor', 'Intel Core i9', 0),
(202, 47, 'Grafička kartica', 'RTX 4070', 1),
(203, 47, 'Radna memorija', '32GB DDR5', 2),
(204, 47, 'Zaslon', '16\" QHD+ 240Hz', 3),
(205, 47, 'Pohrana', '1TB SSD', 4),
(206, 48, 'Procesor', 'Intel Core i7', 0),
(207, 48, 'Grafička kartica', 'RTX 4060', 1),
(208, 48, 'Radna memorija', '32GB DDR5', 2),
(209, 48, 'Zaslon', '16\" 4K OLED (tvornički kalibriran)', 3),
(210, 48, 'Pohrana', '1TB SSD', 4),
(211, 49, 'Procesor', 'Intel Core i7', 0),
(212, 49, 'Grafička kartica', 'RTX 4060', 1),
(213, 49, 'Radna memorija', '16GB DDR5', 2),
(214, 49, 'Zaslon', '15.6\" FHD 144Hz', 3),
(215, 49, 'Pohrana', '512GB SSD', 4),
(216, 50, 'Procesor', 'Intel Core i9', 0),
(217, 50, 'Grafička kartica', 'RTX 4080', 1),
(218, 50, 'Radna memorija', '32GB DDR5', 2),
(219, 50, 'Zaslon', '17.3\" QHD 240Hz', 3),
(220, 50, 'Pohrana', '2TB SSD', 4),
(221, 51, 'Procesor', 'AMD Ryzen 9', 0),
(222, 51, 'Grafička kartica', 'RTX 4070', 1),
(223, 51, 'Radna memorija', '32GB DDR5', 2),
(224, 51, 'Zaslon', '14\" QHD+ 240Hz', 3),
(225, 51, 'Pohrana', '1TB SSD', 4),
(226, 53, 'Procesor', 'Intel Core Ultra 9', 0),
(227, 53, 'Grafička kartica', 'RTX 5090', 1),
(228, 53, 'Radna memorija', '64GB DDR5', 2),
(229, 53, 'Zaslon', '18\" 2.5K 165Hz', 3),
(230, 53, 'Pohrana', '2TB SSD', 4);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `date_added`) VALUES
(1, 23, 1, 5, 'Odlična tipkovnica', '2026-08-23 04:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `saved_addresses`
--

CREATE TABLE `saved_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ime` varchar(100) NOT NULL,
  `prezime` varchar(100) NOT NULL,
  `adresa` varchar(255) NOT NULL,
  `grad` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefon` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_addresses`
--

INSERT INTO `saved_addresses` (`id`, `user_id`, `ime`, `prezime`, `adresa`, `grad`, `email`, `telefon`, `created_at`) VALUES
(1, 1, 'test', 'test', 'test 3b', 'Test 10000', 'test@test.hr', '0911234567', '2026-08-23 05:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ime` varchar(100) DEFAULT NULL,
  `prezime` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `admin_pin_hash` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `ime`, `prezime`, `email`, `password`, `failed_attempts`, `locked_until`, `admin_pin_hash`, `role`) VALUES
(1, 'korisnik', 'test', 'test', 'test@test.hr', '$2y$10$q64I1MDBal8fCF4y.KWADuX27bASObO.wOQAYzTYbzc1gEMOFQHuy', 0, NULL, NULL, 'user'),
(2, 'admin', 'Janko', 'Jakopec', 'janko.jakopec@tvz.hr', '$2y$10$aLR0eddU7GP//BwYZZe2Be1.qjazxlJoQ5GOe6QbdZ2XNg4bNCDUu', 0, NULL, '$2y$10$iQvHBLIMzquVk8lh8geDkuP0pdrdnk1hIpBZiX86l8CWStt03qXpW', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `poruke`
--
ALTER TABLE `poruke`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_specs`
--
ALTER TABLE `product_specs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_reviews_user` (`user_id`);

--
-- Indexes for table `saved_addresses`
--
ALTER TABLE `saved_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wish` (`user_id`,`product_id`),
  ADD KEY `fk_wishlist_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `poruke`
--
ALTER TABLE `poruke`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `product_specs`
--
ALTER TABLE `product_specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_addresses`
--
ALTER TABLE `saved_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD CONSTRAINT `fk_adminlog_user` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specs`
--
ALTER TABLE `product_specs`
  ADD CONSTRAINT `fk_specs_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_addresses`
--
ALTER TABLE `saved_addresses`
  ADD CONSTRAINT `fk_saved_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
