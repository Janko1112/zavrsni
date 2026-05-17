-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 03:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pc_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `category`, `image`, `description`) VALUES
(6, 'RTX 4080', 1300.00, 100, 'komponente', 'https://images.unsplash.com/photo-1591488320449-011701bb6704?q=80&w=1200&auto=format&fit=crop', 'Vrhunska gaming grafička kartica.'),
(7, 'Ryzen 9 7950X', 600.00, 100, 'komponente', 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop', 'Procesor za gaming i multitasking.'),
(8, 'DDR5 32GB', 150.00, 100, 'komponente', 'https://images.unsplash.com/photo-1541029071515-84cc54f84dc5?q=80&w=1200&auto=format&fit=crop', 'Brza memorija nove generacije.'),
(9, 'RTX 4070', 1200.00, 100, 'komponente', 'https://www.nvidia.com/content/dam/en-zz/Solutions/geforce/graphic-cards/40-series/rtx-4070-4070ti/geforce-rtx-4070-super-og-1200x630.jpg', 'Vrhunska gaming grafička kartica.'),
(10, 'RTX 4060', 1000.00, 100, 'komponente', 'https://www.mall.hr/i/106660152/550/550', 'Vrhunska gaming grafička kartica.'),
(11, 'Ryzen 7 7800X3D', 500.00, 100, 'komponente', 'https://www.links.hr/images/thumbs/0275575_procesor-amd-ryzen-7-7800x3d-box-s-am5-42ghz-96mb-cache-8-core-bez-hladnjaka-010501031_550.jpg', 'Procesor za gaming i multitasking.'),
(12, 'Intel i9 14900K', 650.00, 100, 'komponente', 'https://www.instar-informatika.hr/slike/velike/procesor-intel-core-i9-14900k-24c32t-60ghz-36mb-lga1700-bx80-69081-inp-14900k_1.jpg', 'Procesor za gaming i multitasking.'),
(13, 'Intel i7 14700K', 550.00, 100, 'komponente', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRR3jCmAa31sbybz_lcalpoDDLfxS_lgkMgdw&s', 'Procesor za gaming i multitasking.'),
(14, 'DDR5 64GB', 300.00, 100, 'komponente', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRO-IdOBZVxpmLJ_lsMk0m3_gWPM88oK8mL8A&s', 'Brza memorija nove generacije.'),
(15, 'Samsung 990 Pro 1TB', 200.00, 100, 'komponente', 'https://media.flixcar.com/webp/synd-asset/Samsung-138083343-hr-990pro-nvme-m2-ssd-mz-v9p1t0bw-538096644--Download-Source--zoom.png', 'Uređaj za pohranu podataka velike brzine učitavanja.'),
(16, 'Kingston NV2 2 TB', 450.00, 100, 'komponente', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHFHIwSg8zF3WlBj7cV1HnZ87nBVXBbGd4Sw&s', 'Uređaj za pohranu podataka velike brzine učitavanja.'),
(17, 'ASUS ROG X870E', 640.00, 100, 'komponente', 'https://www.adm.hr/slike/velike/asus-rog-strix-x870e-e-gaming-wifi-amd-x870e-am5-ddr5-atx-90-17051-095200094.webp', 'Izvanredna matična ploča za gaming.'),
(18, 'MSI Z890 Gaming Plus', 300.00, 100, 'komponente', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHzm2gMENm3OAJ9veJnweI96QQybRUYmybvg&s', 'Izvanredna matična ploča za gaming.'),
(19, 'NZXT Kraken Elite 360 RGB', 230.00, 95, 'komponente', 'https://www.nabava.net/slike/products/33/09/43750933/nzxt-kraken-elite-360-rgb_d17de9dc.png', 'Vodeno hlađenje za gaming računala'),
(20, 'Corsair RM850', 140.00, 100, 'komponente', 'https://assets.corsair.com/image/upload/f_auto,q_auto/content/CP-9020056-UK-RM850-01.png', 'Odlično napajanje za računala visokih performansi.'),
(21, 'Logitech G Pro X', 120.00, 100, 'gaming', 'https://images.unsplash.com/photo-1547394765-185e1e68f34e?q=80&w=1200&auto=format&fit=crop', 'Doživi zvuk studijske kvalitete i komuniciraj kao profesionalac uz Blue VO!CE tehnologiju.'),
(22, 'Razer DeathAdder', 60.00, 100, 'gaming', 'https://images.unsplash.com/photo-1613141412501-9012977f1969?q=80&w=1200&auto=format&fit=crop', 'Legendarna ergonomija i vrhunski senzor čine ovaj miš tvojim najjačim oružjem u igri.'),
(23, 'SteelSeries Apex', 180.00, 100, 'gaming', 'https://www.mall.hr/i/46096137', 'Najbrži odziv na svijetu i prilagodljivo osvjetljenje za tipkovnicu koja prati tvoj tempo.'),
(24, 'HyperX Cloud III', 110.00, 100, 'gaming', 'https://www.links.hr/images/thumbs/0282063_slusalice-hyperx-cloud-iii-gaming-dts-crno-crvene-010706177_550.jpg', 'Legendarna udobnost i kristalno čist zvuk za maratonske gaming sesije bez umora.'),
(25, 'ASUS TUF Monitor', 250.00, 100, 'gaming', 'https://www.hardsoft.hr/slike/velike/asus-tuf-gaming-vg27vqm-curved-gaming-monitor-27-fhd-1920-x--16110-90lm0510-b03e70.webp', 'Munjevito osvježavanje i besprijekorna slika bez trzanja za potpunu dominaciju na ekranu'),
(26, 'Gaming Chair Pro', 200.00, 100, 'gaming', 'https://www.autofull.eu/cdn/shop/files/9_20f390c1-3165-41c2-87e8-bc860e56a0ee.jpg?v=1770773683&width=2048', 'Savršena podrška za tvoja leđa uz vrhunski dizajn koji pretvara tvoj kutak u pravu arenu.'),
(27, 'RGB Mousepad', 30.00, 100, 'gaming', 'https://m.media-amazon.com/images/I/61nSvG2CHQL.jpg', 'Dodaj svom setupu novu dimenziju boja uz savršeno glatku površinu za precizne pokrete.'),
(28, 'PS5 Controller', 75.00, 100, 'gaming', 'https://cdn.ozone.hr/media/catalog/product/cache/1/image/400x498/a4e40ebdc3e371adff845072e1c73f37/d/u/160cbc8ae71faef2cabf092336769ec1/bezicni-kontroler-dualsense-30.jpg', 'Osjeti svaku eksploziju i napetost uz revolucionarnu haptičku povratnu informaciju.'),
(29, 'Xbox Elite Controller', 160.00, 100, 'gaming', 'https://www.gamershop.hr/content/product_instances2/405839/xboxone-xbox-one-elite-wireless-controller-series-2.webp', 'Prilagodi kontroler svom stilu igre i postani nezaustavljiv uz dodatne poluge i preciznost.'),
(30, 'Gaming Webcam', 90.00, 100, 'gaming', 'https://m.media-amazon.com/images/I/61DRyuOB3vL.jpg', 'Izgledaj besprijekorno pred kamerom uz Full HD rezoluciju i automatsko fokusiranje'),
(31, 'RGB Headset Stand', 25.00, 100, 'gaming', 'https://images-cdn.ubuy.co.id/633aaf764a3385288a191c4b-havit-rgb-headphones-stand-with-3-5mm.jpg', 'Čuvaj svoje slušalice sa stilom i organiziraj stol uz efektno ambijentalno svjetlo.'),
(32, 'Gaming Desk', 180.00, 100, 'gaming', 'https://m.media-amazon.com/images/I/81JnWF-jqPL.jpg', 'Stabilna i prostrana površina dizajnirana da izdrži svu tvoju opremu i žestoke mečeve.'),
(33, 'Gaming Microphone', 130.00, 100, 'gaming', 'https://m.media-amazon.com/images/I/71CKdnpt2LL.jpg', 'Neka tvoj glas zvuči profesionalno i čisto bez pozadinske buke.'),
(34, 'VR Headset', 450.00, 100, 'gaming', 'https://cdn.thewirecutter.com/wp-content/media/2024/10/vrheadsets-2048px-08406.jpg?width=2048&quality=60&crop=2048:1365&auto=webp', 'Zakorači u nove svjetove i doživi nevjerojatno iskustvo koje briše granicu između stvarnosti i igre.'),
(35, 'Elgato Stream Deck', 150.00, 100, 'gaming', 'https://m.media-amazon.com/images/I/61gtdFnK+UL._AC_UF894,1000_QL80_.jpg', 'Preuzmi potpunu kontrolu nad svojim streamom jednim dodirom tipke.'),
(36, 'ASUS ROG Strix', 1800.00, 100, 'laptopi', 'https://dlcdnwebimgs.asus.com/files/media/8b74e7ee-b66a-4420-894e-3c3b980312ee/v2/img/design/color/strix-g-2022-pink.png', 'Beskompromisna snaga i agresivan dizajn za gamere koji žele biti u samom vrhu.'),
(37, 'Lenovo Legion 7', 2200.00, 100, 'laptopi', 'https://p2-ofp.static.pub/fes/cms/2022/04/27/589xqn5kv49awp7q3o70wvhgj8d3vs828507.png', 'Savršen spoj elegantnog aluminijskog kućišta i brutalnih performansi za ozbiljan gaming.'),
(38, 'MSI Raider', 2500.00, 100, 'laptopi', 'https://www.links.hr/images/thumbs/0245830_laptop-msi-raider-a18-hx-a9wjg-ryzen-9-9955hx3d-64gb-4tb-ssd-nvidia-geforce-rtx-5090-18-uhd-ips-win_550.jpg', 'Osjeti snagu desktop računala u prijenosnom izdanju uz fascinantno RGB osvjetljenje.'),
(39, 'Acer Nitro 5', 900.00, 100, 'laptopi', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDDEHWwUj2yNMAr5XEcHLjO6_EPRZuS3-_pw&s', 'Najbolji omjer uloženo-dobiveno koji ti omogućuje ulazak u svijet gaminga bez pražnjenja novčanika.'),
(40, 'MacBook Pro M4', 2100.00, 100, 'laptopi', 'https://cdsassets.apple.com/live/7WUAS350/images/tech-specs/mbp14-m4-2024.png', 'Nevjerojatna brzina novog čipa i najljepši zaslon za kreativce koji ne pristaju na kompromise.'),
(41, 'MSI Katana', 1100.00, 100, 'laptopi', 'https://storage-asset.msi.com/global/picture/image/feature/nb/GF/Katana-17-A13V/photo17-3.png', 'Oštra preciznost i pouzdanost inspirirana legendarnim mačem, stvorena za pobjednike.'),
(42, 'Dell XPS 15', 1900.00, 100, 'laptopi', 'https://www.bug.hr/img/premium-model-dell-xps-15-9530-osim-cijenom-istice-se-kvalitetom-izrade-i_lsjkHW.jpg', 'Vrhunac elegancije i snage u najtanjem okviru, stvoren za profesionalce u pokretu.'),
(43, 'HP Omen', 1500.00, 100, 'laptopi', 'https://www.mall.hr/i/39713727/550/550', 'Napredno hlađenje i vrhunske komponente osiguravaju stabilan rad čak i u najžešćim bitkama'),
(44, 'ASUS Zenbook', 1200.00, 100, 'laptopi', 'https://dlcdnwebimgs.asus.com/gain/c513878b-1b7e-419a-9cc2-828e5bcbdf91/', 'Nevjerojatno lagan i tanak laptop s OLED zaslonom koji oduzima dah pri svakom korištenju.'),
(45, 'Lenovo ThinkPad', 1400.00, 100, 'laptopi', 'https://p1-ofp.static.pub//fes/cms/2024/03/07/ihyl2i3451w0zhcrk3y8kv3a9piaj7136432.jpg', 'Legendarna izdržljivost i najbolja tipkovnica na tržištu za maksimalnu produktivnost.'),
(46, 'Alienware M18', 3200.00, 100, 'laptopi', 'https://i.pcmag.com/imagery/reviews/01piWcwFmGnmLdRrcQlLSJF-5.fit_lim.size_1050x.jpg', 'Ogroman zaslon i ekstremna snaga čine ovaj laptop pravom zvijeri za najzahtjevnije zadatke.'),
(47, 'Razer Blade', 2800.00, 100, 'laptopi', 'https://m.media-amazon.com/images/I/814PVSAztPL.jpg', '\"MacBook među gaming laptopima\" koji nudi čisti luksuz i nevjerojatnu moć u tankom kućištu.'),
(48, 'Gigabyte Aero', 2000.00, 100, 'laptopi', 'https://static.gigabyte.com/StaticFile/Image/Global/14950cbb5c4eadc9279fb959915b2c21/ProductRemoveBg/26527', 'Specijaliziran za video obradu i dizajn uz tvornički kalibriran zaslon savršenih boja.'),
(49, 'HP Victus', 850.00, 100, 'laptopi', 'https://www.links.hr/images/thumbs/0298233_laptop-hp-victus-15-fa2130nm-core-i7-13620h-16gb-1tb-ssd-nvidia-geforce-rtx-5060-156-fhd-144hz-ips-_550.jpg', 'Moderan dizajn i odlične performanse po cijeni koja postavlja nove standarde pristupačnosti.'),
(50, 'Acer Predator', 1700.00, 100, 'laptopi', 'https://cdn.assets.prezly.com/2188e46c-d6da-4272-b778-2b5219c8842f/-/format/auto/Predator-Helios-700_PH717-17_01.png', 'Agresivan izgled i napredna tehnologija hlađenja za najteže gaming izazove.'),
(51, 'Razer Balde 14', 3100.00, 20, 'laptopi', 'https://sm.pcmag.com/t/pcmag_uk/review/r/razer-blad/razer-blade-14_hsdu.1920.jpg', 'Razer Blade 14 je vrhunski prijenosnik koji spaja iznimne performanse i visoku mobilnost');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin123', 'admin'),
(2, 'user', 'user123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
