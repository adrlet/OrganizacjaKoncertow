-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Sty 2022, 19:19
-- Wersja serwera: 10.4.21-MariaDB
-- Wersja PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `si`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `e_mail` varchar(50) DEFAULT NULL,
  `telephone` varchar(25) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `clients`
--

INSERT INTO `clients` (`id_client`, `firstName`, `surname`, `e_mail`, `telephone`, `address`, `pass`) VALUES
(14, 'Jan', 'Kowalski', NULL, '', '', NULL),
(15, 'Krzysztof', 'Nowak', 'KrzysztofNowak@gmail.com', NULL, NULL, '$2y$10$fenO0igQ71WSWprL.YEhGebzlG1JxSXQ0E10y1xnGtpMaapMUxj/e'),
(16, 'Michał', 'Turek', 'MichalTurek@onet.pl', '', 'ul.Słomiankowa 12', '$2y$10$KcOu4vAroeH7IqGuqoZ4HuJf0im6zXSDzDbWWZYqjxW2yzQVxtO.q'),
(17, 'John', 'Rodo', 'John@Rodo.pl', '', '', '$2y$10$YOawKVtJ.mnfz.v2GZGpCuoz0xvaiRumaT6KVqmTAxrpctyywaNyG'),
(18, 'Jarek', 'Andrzejewski', 'meksyk@onet.pl', '', '', '$2y$10$xDFwxFXka7EC8aHV9GOnZ.HtDB7OG4B7bA8eiO0PeTroF0IiZ4Gl6');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `concert`
--

CREATE TABLE `concert` (
  `id_concert` int(11) NOT NULL,
  `remaining_tickets` int(11) NOT NULL,
  `id_concert_form` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `concert_form`
--

CREATE TABLE `concert_form` (
  `id_concert_form` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `id_client` int(11) NOT NULL,
  `music_genre` varchar(50) NOT NULL,
  `seats_number` int(11) NOT NULL,
  `budget` float NOT NULL,
  `location` varchar(50) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `concert_form`
--

INSERT INTO `concert_form` (`id_concert_form`, `name`, `id_client`, `music_genre`, `seats_number`, `budget`, `location`, `date`) VALUES
(31, 'Wielki kolędowy jarmark', 18, 'Pop', 2000, 100000, 'Warszawa', '2022-01-24 17:40:00'),
(33, 'Koncert testowy2', 18, 'Nowa fala', 2500, 150000, 'Wrocław', '2022-01-24 17:49:00'),
(35, 'Koncert Legancki', 18, 'Nowa fala', 2000, 250000, 'Olsztyn', '2022-01-26 18:45:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `concert_plan`
--

CREATE TABLE `concert_plan` (
  `id_concert_plan` int(11) NOT NULL,
  `termin_start` datetime NOT NULL,
  `id_concert_form` int(11) NOT NULL,
  `termin_end` datetime NOT NULL,
  `seats_number` int(11) NOT NULL,
  `expenses` float NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `concert_plan`
--

INSERT INTO `concert_plan` (`id_concert_plan`, `termin_start`, `id_concert_form`, `termin_end`, `seats_number`, `expenses`, `status`) VALUES
(15, '2022-01-24 17:40:00', 31, '2022-01-24 21:40:00', 2000, 69000, 'Przesłano'),
(16, '2022-01-24 17:49:00', 33, '2022-01-24 19:49:00', 2500, 139500, 'Przesłano'),
(18, '2022-01-26 18:45:00', 35, '2022-01-26 20:45:00', 2000, 0, 'Rozpatrzony');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `contract_performer`
--

CREATE TABLE `contract_performer` (
  `id_contract_performer` int(11) NOT NULL,
  `price` float NOT NULL,
  `id_performer` int(11) NOT NULL,
  `id_concert_plan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `contract_performer`
--

INSERT INTO `contract_performer` (`id_contract_performer`, `price`, `id_performer`, `id_concert_plan`) VALUES
(24, 6000, 6, 15),
(25, 7000, 5, 15),
(26, 8000, 3, 15),
(27, 10000, 4, 15),
(28, 11000, 10, 16),
(29, 12000, 9, 16),
(35, 11000, 10, 18),
(36, 11000, 10, 18);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `contract_place`
--

CREATE TABLE `contract_place` (
  `id_contract_place` int(11) NOT NULL,
  `price` float NOT NULL,
  `id_place` int(11) NOT NULL,
  `id_concert_plan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `contract_place`
--

INSERT INTO `contract_place` (`id_contract_place`, `price`, `id_place`, `id_concert_plan`) VALUES
(9, 10000, 1, 15),
(10, 70000, 9, 16),
(13, 18000, 8, 18);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `contract_service`
--

CREATE TABLE `contract_service` (
  `id_contract_service` int(11) NOT NULL,
  `price` float NOT NULL,
  `id_service` int(11) NOT NULL,
  `id_concert_plan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `contract_service`
--

INSERT INTO `contract_service` (`id_contract_service`, `price`, `id_service`, `id_concert_plan`) VALUES
(27, 7000, 1, 15),
(28, 5000, 4, 15),
(29, 8000, 5, 15),
(30, 8000, 2, 15),
(31, 12500, 7, 16),
(32, 9000, 6, 16),
(33, 11000, 8, 16),
(34, 14000, 9, 16),
(40, 7000, 1, 18),
(41, 8000, 2, 18),
(42, 5000, 4, 18),
(43, 8000, 5, 18);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `performers`
--

CREATE TABLE `performers` (
  `id_performer` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `music_genre` varchar(50) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `performers`
--

INSERT INTO `performers` (`id_performer`, `nickname`, `music_genre`, `price`) VALUES
(1, 'Sepultura', 'Metal', 40000),
(2, 'Rammstein', 'Metal', 30000),
(3, 'Maria Antonina', 'Pop', 8000),
(4, 'One Last Ride', 'Pop', 10000),
(5, 'Zielone Krety', 'Pop', 7000),
(6, 'Rukola', 'Pop', 6000),
(7, 'GSP', 'Hip-Hop', 3000),
(8, 'Młody G Belmondziak', 'Hip-Hop', 4500),
(9, 'Republika', 'Nowa fala', 12000),
(10, 'Lady Pank', 'Nowa fala', 11000);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `places`
--

CREATE TABLE `places` (
  `id_place` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `participants_limit` int(11) NOT NULL,
  `rent_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `places`
--

INSERT INTO `places` (`id_place`, `location`, `address`, `participants_limit`, `rent_price`) VALUES
(1, 'Warszawa', 'Rotmistrza Witolda Pileckiego 122, 02-781', 2000, 10000),
(2, 'Gdańsk', 'plac Dwóch Miast 1 80-344 Ergorena', 15000, 27500),
(3, 'Gdańsk', 'Juliusza Słowackiego 23 80-257 Stary Maneż', 1500, 4500),
(4, 'Warszawa', 'Złota 7/9, 00-019 Warszawa, Klub Hybrydy', 5000, 12000),
(5, 'Poznań', 'Kutrzeby 10 61-719, Aula Artis', 660, 2000),
(6, 'Poznań', 'Przybyszewskiego 37a 60-356, Centrum Kongresowo-Dydaktyczne', 4000, 12000),
(7, 'Olsztyn', 'Zamkowa 1 10-074, Amfiteatr im. Cz. Niemena', 1300, 3500),
(8, 'Olsztyn', 'Kapitańska 11-041, CRS Ukiel', 7000, 18000),
(9, 'Wrocław', 'aleja Śląska 1 54-118, Tarczyński Arena', 45000, 70000),
(10, 'Wrocław', 'aleja Karkonoska 10 53-015, Sala Koncertowa Radia Wrocław', 1500, 4500);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `service_price` float NOT NULL,
  `participants_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `service`
--

INSERT INTO `service` (`service_id`, `company_name`, `service_type`, `service_price`, `participants_limit`) VALUES
(1, 'Varangian Guard', 'Ochrona', 7000, 2000),
(2, 'Sound Blaster', 'Nagłośnienie', 8000, 2000),
(4, 'Tawerna', 'Obsługa', 5000, 2000),
(5, 'Solar Flare', 'Oświetlenie', 8000, 2000),
(6, 'Chłopaki z Baraków', 'Obsługa', 9000, 5000),
(7, 'Czarna Kompania', 'Ochrona', 12500, 5000),
(8, 'Radiacja Gamma', 'Oświetlenie', 11000, 5000),
(9, 'Audio Wave', 'Nagłośnienie', 14000, 5000),
(10, 'Zielone Berety', 'Ochrona', 20000, 10000),
(11, 'Stewards', 'Obsługa', 14500, 10000),
(12, 'Radiacja Gamma', 'Oświetlenie', 17000, 10000),
(13, 'Kolorowe Głośniczki', 'Nagłośnienie', 18000, 10000),
(14, 'Najemnicy', 'Ochrona', 25000, 25000),
(15, 'Studenci', 'Obsługa', 16000, 25000),
(16, 'Professional Lights', 'Oświetlenie', 27000, 25000),
(17, 'Earthquake', 'Nagłośnienie', 22000, 25000);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- Indeksy dla tabeli `concert`
--
ALTER TABLE `concert`
  ADD PRIMARY KEY (`id_concert`),
  ADD KEY `id_concert_form` (`id_concert_form`);

--
-- Indeksy dla tabeli `concert_form`
--
ALTER TABLE `concert_form`
  ADD PRIMARY KEY (`id_concert_form`),
  ADD KEY `id_client` (`id_client`);

--
-- Indeksy dla tabeli `concert_plan`
--
ALTER TABLE `concert_plan`
  ADD PRIMARY KEY (`id_concert_plan`),
  ADD KEY `id_concert_form` (`id_concert_form`);

--
-- Indeksy dla tabeli `contract_performer`
--
ALTER TABLE `contract_performer`
  ADD PRIMARY KEY (`id_contract_performer`),
  ADD KEY `id_performer` (`id_performer`),
  ADD KEY `id_concert_plan` (`id_concert_plan`);

--
-- Indeksy dla tabeli `contract_place`
--
ALTER TABLE `contract_place`
  ADD PRIMARY KEY (`id_contract_place`),
  ADD KEY `id_place` (`id_place`),
  ADD KEY `id_concert_plan` (`id_concert_plan`);

--
-- Indeksy dla tabeli `contract_service`
--
ALTER TABLE `contract_service`
  ADD PRIMARY KEY (`id_contract_service`),
  ADD KEY `id_service` (`id_service`),
  ADD KEY `id_concert_plan` (`id_concert_plan`);

--
-- Indeksy dla tabeli `performers`
--
ALTER TABLE `performers`
  ADD PRIMARY KEY (`id_performer`);

--
-- Indeksy dla tabeli `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id_place`);

--
-- Indeksy dla tabeli `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT dla tabeli `concert`
--
ALTER TABLE `concert`
  MODIFY `id_concert` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `concert_form`
--
ALTER TABLE `concert_form`
  MODIFY `id_concert_form` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT dla tabeli `concert_plan`
--
ALTER TABLE `concert_plan`
  MODIFY `id_concert_plan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT dla tabeli `contract_performer`
--
ALTER TABLE `contract_performer`
  MODIFY `id_contract_performer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT dla tabeli `contract_place`
--
ALTER TABLE `contract_place`
  MODIFY `id_contract_place` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `contract_service`
--
ALTER TABLE `contract_service`
  MODIFY `id_contract_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT dla tabeli `performers`
--
ALTER TABLE `performers`
  MODIFY `id_performer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `places`
--
ALTER TABLE `places`
  MODIFY `id_place` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `concert`
--
ALTER TABLE `concert`
  ADD CONSTRAINT `concert_ibfk_1` FOREIGN KEY (`id_concert_form`) REFERENCES `concert_form` (`id_concert_form`);

--
-- Ograniczenia dla tabeli `concert_form`
--
ALTER TABLE `concert_form`
  ADD CONSTRAINT `concert_form_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Ograniczenia dla tabeli `concert_plan`
--
ALTER TABLE `concert_plan`
  ADD CONSTRAINT `concert_plan_ibfk_1` FOREIGN KEY (`id_concert_form`) REFERENCES `concert_form` (`id_concert_form`);

--
-- Ograniczenia dla tabeli `contract_performer`
--
ALTER TABLE `contract_performer`
  ADD CONSTRAINT `contract_performer_ibfk_1` FOREIGN KEY (`id_performer`) REFERENCES `performers` (`id_performer`),
  ADD CONSTRAINT `contract_performer_ibfk_2` FOREIGN KEY (`id_concert_plan`) REFERENCES `concert_plan` (`id_concert_plan`);

--
-- Ograniczenia dla tabeli `contract_place`
--
ALTER TABLE `contract_place`
  ADD CONSTRAINT `contract_place_ibfk_1` FOREIGN KEY (`id_place`) REFERENCES `places` (`id_place`),
  ADD CONSTRAINT `contract_place_ibfk_2` FOREIGN KEY (`id_concert_plan`) REFERENCES `concert_plan` (`id_concert_plan`);

--
-- Ograniczenia dla tabeli `contract_service`
--
ALTER TABLE `contract_service`
  ADD CONSTRAINT `contract_service_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `service` (`service_id`),
  ADD CONSTRAINT `contract_service_ibfk_2` FOREIGN KEY (`id_concert_plan`) REFERENCES `concert_plan` (`id_concert_plan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
