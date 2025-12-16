-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Des 2025 pada 15.06
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chronosquest`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `type` enum('deadline','event','holiday') DEFAULT 'event'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `title`, `event_date`, `type`) VALUES
(1, 'Deadline Tugas HTML', '2025-12-08', 'deadline'),
(2, 'Ujian Akhir', '2025-12-11', 'event'),
(3, 'Libur Nasional', '2025-12-16', 'holiday');

-- --------------------------------------------------------

--
-- Struktur dari tabel `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `content_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `materials`
--

INSERT INTO `materials` (`id`, `title`, `description`, `category`, `content_url`, `created_at`) VALUES
(1, 'Bab 1: Dasar HTML', 'Pelajari elemen dasar pembangun web.', 'HTML', NULL, '2025-12-05 17:25:38'),
(2, 'Bab 2: Styling CSS', 'Memahami selektor dan layouting.', 'CSS', NULL, '2025-12-05 17:25:38'),
(3, 'Bab 3: Interaksi JS', 'Dasar pemrograman logika web.', 'Javascript', NULL, '2025-12-05 17:25:38'),
(4, 'Bab 4: Database SQL', 'Konsep penyimpanan data.', 'Database', NULL, '2025-12-05 17:25:38'),
(5, 'Bab 5: Integrasi Backend dengan PHP', 'Menghubungkan code HTML, CSS, dan JS dengan code PHP', 'Database', '', '2025-12-05 19:12:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `quests`
--

CREATE TABLE `quests` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('Daily','Weekly','Main') NOT NULL,
  `target_count` int(11) DEFAULT 1,
  `reward_points` int(11) DEFAULT 100,
  `is_locked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `quests`
--

INSERT INTO `quests` (`id`, `title`, `description`, `type`, `target_count`, `reward_points`, `is_locked`) VALUES
(1, 'Fondasi Web', 'Kuasai struktur dasar HTML5 dan semantik untuk membangun kerangka web yang kokoh.', 'Main', 5, 100, 0),
(2, 'Seni Tata Letak', 'Pelajari Flexbox dan Grid System untuk menciptakan layout CSS yang responsif dan estetis.', 'Main', 5, 150, 0),
(3, 'Logika Automata', 'Memahami variabel, loop, dan fungsi dasar dalam JavaScript untuk menghidupkan web.', 'Main', 5, 200, 0),
(4, 'Manipulasi DOM', 'Kendalikan elemen halaman secara dinamis menggunakan Event Listener dan DOM API.', 'Main', 5, 250, 0),
(5, 'Alkimia Data', 'Pelajari query SQL dasar (SELECT, INSERT, UPDATE) untuk mengelola basis data.', 'Main', 5, 300, 0),
(6, 'Mesin Server', 'Pengenalan sintaks PHP dan integrasi backend sederhana.', 'Main', 5, 350, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quest_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('a','b','c','d') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quest_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'Apa kepanjangan dari HTML?', 'Hyper Text Markup Language', 'High Tech Modern Language', 'Hyperlink Text Mode List', 'Home Tool Markup Level', 'a'),
(2, 1, 'Tag mana yang digunakan untuk membuat paragraf?', '<p>', '<br>', '<div>', '<span>', 'a'),
(3, 1, 'Apa fungsi tag <a>?', 'Membuat tabel', 'Membuat link', 'Membuat gambar', 'Membuat list', 'b'),
(4, 1, 'Elemen HTML mana yang paling besar ukurannya?', '<h6>', '<h3>', '<h1>', '<p>', 'c'),
(5, 1, 'Atribut apa yang digunakan untuk memasukkan link gambar?', 'href', 'link', 'src', 'alt', 'c'),
(6, 2, 'Apa kepanjangan CSS?', 'Creative Style Sheets', 'Cascading Style Sheets', 'Computer Style System', 'Colorful Style Sheets', 'b'),
(7, 2, 'Properti untuk mengubah warna teks adalah?', 'font-color', 'text-color', 'color', 'background-color', 'c'),
(8, 2, 'Simbol untuk ID selector di CSS adalah?', '.', '#', '*', '@', 'b'),
(9, 2, 'Untuk membuat flex container, kita gunakan display: ...?', 'block', 'inline', 'flex', 'grid', 'c'),
(10, 2, 'Properti \"padding\" mengatur jarak di bagian...?', 'Luar border', 'Dalam border', 'Antar elemen', 'Bawah elemen', 'b'),
(11, 3, 'Keyword mana yang digunakan untuk mendeklarasikan variabel yang nilainya bisa diubah?', 'const', 'let', 'final', 'static', 'b'),
(12, 3, 'Apa output dari: console.log(2 + \"2\")?', '4', '22', 'Error', 'NaN', 'b'),
(13, 3, 'Manakah operator perbandingan yang mengecek nilai DAN tipe data?', '==', '=', '===', '!=', 'c'),
(14, 3, 'Index array dalam JavaScript dimulai dari angka?', '1', '0', '-1', 'Bebas', 'b'),
(15, 3, 'Struktur perulangan manakah yang benar?', 'loop (i = 0; i < 5)', 'for (let i = 0; i < 5; i++)', 'repeat (5)', 'foreach (i in 5)', 'b'),
(16, 4, 'Method untuk mengambil elemen berdasarkan ID adalah?', 'getElementByClass', 'querySelector', 'getElementById', 'scanDocument', 'c'),
(17, 4, 'Properti untuk mengubah teks di dalam elemen tanpa memproses HTML adalah?', 'innerHTML', 'innerText', 'value', 'style', 'b'),
(18, 4, 'Event apa yang terjadi saat user menekan tombol mouse?', 'onhover', 'keydown', 'click', 'submit', 'c'),
(19, 4, 'Objek global yang merepresentasikan halaman web adalah?', 'window', 'browser', 'document', 'html', 'c'),
(20, 4, 'Bagaimana cara mengubah warna background elemen via JS?', 'element.color = \"red\"', 'element.style.backgroundColor = \"red\"', 'element.bg = \"red\"', 'element.css(\"background\", \"red\")', 'b'),
(21, 5, 'Perintah untuk mengambil SEMUA kolom dari tabel users adalah?', 'GET * FROM users', 'SELECT all FROM users', 'SELECT * FROM users', 'SHOW users', 'c'),
(22, 5, 'Klausa untuk memfilter data berdasarkan kondisi tertentu?', 'ORDER BY', 'WHERE', 'GROUP BY', 'FILTER', 'b'),
(23, 5, 'Perintah untuk menghapus data dari tabel?', 'REMOVE', 'DROP', 'DELETE FROM', 'ERASE', 'c'),
(24, 5, 'Manakah perintah untuk menambahkan data baru?', 'ADD NEW', 'INSERT INTO', 'UPDATE', 'CREATE ROW', 'b'),
(25, 5, 'Untuk mengurutkan data dari terbesar ke terkecil digunakan?', 'ASC', 'Z-A', 'TOP', 'DESC', 'd'),
(26, 6, 'Semua variabel di PHP harus diawali dengan simbol?', '@', '#', '$', '%', 'c'),
(27, 6, 'Perintah dasar untuk menampilkan teks ke layar di PHP?', 'print_line', 'echo', 'console.log', 'write', 'b'),
(28, 6, 'Setiap statement PHP harus diakhiri dengan?', '.', ':', ';', '}', 'c'),
(29, 6, 'Operator untuk menggabungkan dua string di PHP adalah?', '+', '&', '.', ',', 'c'),
(30, 6, 'Superglobal untuk mengambil data dari form metode POST adalah?', '$_GET', '$_REQUEST', '$POST_DATA', '$_POST', 'd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `day_name` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `room` varchar(50) DEFAULT 'Online'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`id`, `day_name`, `subject_name`, `time_start`, `time_end`, `room`) VALUES
(1, 'Senin', 'Matematika Diskrit', '08:00:00', '10:00:00', 'R. 301'),
(2, 'Senin', 'Pemrograman Web Lanjut', '13:00:00', '15:00:00', 'Lab Komputer 2'),
(3, 'Selasa', 'Statistika & Probabilitas', '09:00:00', '11:00:00', 'R. 202'),
(4, 'Selasa', 'Jaringan Komputer', '14:00:00', '16:00:00', 'Lab Jaringan'),
(5, 'Rabu', 'Algoritma & Pemrograman', '08:00:00', '10:00:00', 'Lab Komputer 1'),
(6, 'Rabu', 'Desain Interface (UI/UX)', '13:00:00', '15:00:00', 'R. 204'),
(7, 'Kamis', 'Sistem Basis Data', '10:00:00', '12:00:00', 'Lab Komputer 2'),
(8, 'Kamis', 'Etika Profesi', '15:30:00', '17:30:00', 'Online / Zoom'),
(9, 'Jumat', 'Bahasa Inggris Teknis', '08:30:00', '10:30:00', 'R. 101'),
(10, 'Jumat', 'Kewirausahaan', '13:30:00', '15:30:00', 'Aula Utama');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `class_type` enum('ACT','BCF','CMS','None') DEFAULT 'None',
  `level` int(11) DEFAULT 1,
  `points` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('student','admin') DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `class_type`, `level`, `points`, `created_at`, `role`) VALUES
(1, 'Admin Chronos', 'admin', '$2y$10$aEzex24X7nbfPQrC8qPDDu6woZfPzWWqfQl.Fqach2VBqDre30PEu', 'CMS', 99, 9999, '2025-12-05 17:25:39', 'admin'),
(2, 'Jamal', 'jamal', '$2y$10$87PhQKIi8.Lv94LSTYC6DeW3YqfbVqqTBmqQXa8qnBjtz33thAB8S', 'ACT', 1, 0, '2025-12-05 17:34:47', 'student'),
(24, 'Muhammad Sumbul', 'sumbul', '$2y$10$szYMjEy6IycZV.O5MME9JucDtojGjmX7EAHa9EHL1q0OrOYNPaCdS', 'CMS', 1, 100, '2025-12-14 23:18:51', 'student');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_quests`
--

CREATE TABLE `user_quests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quest_id` int(11) DEFAULT NULL,
  `current_progress` int(11) DEFAULT 0,
  `is_claimed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_quests`
--

INSERT INTO `user_quests` (`id`, `user_id`, `quest_id`, `current_progress`, `is_claimed`) VALUES
(1, 24, 1, 100, 1),
(2, 24, 2, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_todo`
--

CREATE TABLE `user_todo` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `task_name` varchar(255) NOT NULL,
  `is_checked` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_todo`
--

INSERT INTO `user_todo` (`id`, `user_id`, `task_name`, `is_checked`, `created_at`) VALUES
(1, 1, 'Mengerjakan Tugas PHP', 0, '2025-12-05 17:47:57'),
(2, 1, 'Review Materi CSS', 1, '2025-12-05 17:47:57'),
(3, 1, 'Persiapan Quiz', 0, '2025-12-05 17:47:57'),
(4, 2, 'Tugas 1', 0, '2025-12-05 18:32:30'),
(5, 2, 'Tugas 2', 0, '2025-12-05 18:32:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `quests`
--
ALTER TABLE `quests`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quest_id` (`quest_id`);

--
-- Indeks untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `user_quests`
--
ALTER TABLE `user_quests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quest_id` (`quest_id`);

--
-- Indeks untuk tabel `user_todo`
--
ALTER TABLE `user_todo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `quests`
--
ALTER TABLE `quests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `user_quests`
--
ALTER TABLE `user_quests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_todo`
--
ALTER TABLE `user_todo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `quests` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_quests`
--
ALTER TABLE `user_quests`
  ADD CONSTRAINT `user_quests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_quests_ibfk_2` FOREIGN KEY (`quest_id`) REFERENCES `quests` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_todo`
--
ALTER TABLE `user_todo`
  ADD CONSTRAINT `user_todo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
