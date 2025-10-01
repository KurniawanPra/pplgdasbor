INSERT INTO users (nama_lengkap, username, email, password, role, created_at, updated_at) VALUES
(''Administrator'', ''admin'', ''admin@class.local'', ''$2b$10$vaHSA0oc3OC57HOybkFe7OfUqhpjIUwxpIij8xySzSehtdbKoPqXK'', ''administrator'', NOW(), NOW()),
(''Wali Kelas'', ''wali'', ''wali@class.local'', ''$2b$10$4vVLNBkHzh0CwUuyY6yUF.971/sc6Qvp6JPRVSOSellYzSVRKe1n.'', ''wali_kelas'', NOW(), NOW()),
(''Ketua Kelas'', ''ketua'', ''ketua@class.local'', ''$2b$10$4vVLNBkHzh0CwUuyY6yUF.971/sc6Qvp6JPRVSOSellYzSVRKe1n.'', ''ketua'', NOW(), NOW()),
(''Wakil Ketua'', ''wakil'', ''wakil@class.local'', ''$2b$10$w70gAk1H/Ouq.5we4hiUCe6Tup5upyLsjMJUlYr77QDAPO4A.aG.u'', ''wakil_ketua'', NOW(), NOW()),
(''Sekretaris Kelas'', ''sekretaris'', ''sekretaris@class.local'', ''$2b$10$vs5k.D7Fk5rEa/FlwUitD.4vEq/2b5LlI.GOPSCkz3X5bMAAnkLZC'', ''sekretaris'', NOW(), NOW()),
(''Bendahara Kelas'', ''bendahara'', ''bendahara@class.local'', ''$2b$10$OjdXPb.CiMJbz.Z4WeIBjelJwVwJXyJhDUAUn6xKTvZAMOPgnCtFO'', ''bendahara'', NOW(), NOW()),
(''Siswa Satu'', ''siswa01'', ''siswa01@class.local'', ''$2b$10$OaJEh19XqxjXZK2U92m4du8TRlJc2nBnKmwh47o7QZacxzvGGrK4e'', ''anggota'', NOW(), NOW());

INSERT INTO anggota (id_absen, nis, nama_lengkap, panggilan, jenis_kelamin, jabatan, sosmed, nomor_hp, email, status, cita_cita, tujuan_hidup, hobi, foto, user_id, created_at, updated_at) VALUES
(100, ''W0001'', ''Siti Rahmawati'', ''Bu Siti'', ''P'', ''anggota'', ''@walikelas'', ''0812300999'', ''wali@class.local'', ''nonaktif'', ''Membimbing generasi'', ''Menginspirasi siswa'', ''Membaca'', NULL, 2, NOW(), NOW()),
(1, ''20231001'', ''Ahmad Fadli'', ''Ahmad'', ''L'', ''ketua'', ''@ahmad'', ''0812300001'', ''siswa01@class.local'', ''aktif'', ''Programmer Profesional'', ''Mengabdi pada bangsa'', ''Membaca'', NULL, 3, NOW(), NOW()),
(2, ''20231002'', ''Dewi Lestari'', ''Dewi'', ''P'', ''wakil'', ''@dewi'', ''0812300002'', ''siswa02@class.local'', ''aktif'', ''UI/UX Designer'', ''Membanggakan orang tua'', ''Mendaki'', NULL, 4, NOW(), NOW()),
(3, ''20231003'', ''Rina Sari'', ''Rina'', ''P'', ''sekretaris'', ''@rina'', ''0812300003'', ''siswa03@class.local'', ''aktif'', ''Akuntan'', ''Berbagi ilmu'', ''Menulis'', NULL, 5, NOW(), NOW()),
(4, ''20231004'', ''Budi Santoso'', ''Budi'', ''L'', ''bendahara'', ''@budi'', ''0812300004'', ''siswa04@class.local'', ''aktif'', ''Enterpreneur'', ''Membangun usaha mandiri'', ''Olahraga'', NULL, 6, NOW(), NOW()),
(5, ''20231005'', ''Siswa Satu'', ''Siswa01'', ''L'', ''anggota'', ''@siswa01'', ''0812300005'', ''siswa05@class.local'', ''aktif'', ''Programmer'', ''Mengabdi pada bangsa'', ''Membaca'', NULL, 7, NOW(), NOW()),
(6, ''20231006'', ''Siswa 06'', ''S06'', ''P'', ''anggota'', ''@s06'', ''0812300006'', ''siswa06@class.local'', ''aktif'', ''Desainer'', ''Berkarir di industri kreatif'', ''Memasak'', NULL, NULL, NOW(), NOW()),
(7, ''20231007'', ''Siswa 07'', ''S07'', ''L'', ''anggota'', ''@s07'', ''0812300007'', ''siswa07@class.local'', ''aktif'', ''Animator'', ''Menjadi inspirasi'', ''Menggambar'', NULL, NULL, NOW(), NOW()),
(8, ''20231008'', ''Siswa 08'', ''S08'', ''P'', ''anggota'', ''@s08'', ''0812300008'', ''siswa08@class.local'', ''aktif'', ''Dokter'', ''Menolong sesama'', ''Fotografi'', NULL, NULL, NOW(), NOW()),
(9, ''20231009'', ''Siswa 09'', ''S09'', ''L'', ''anggota'', ''@s09'', ''0812300009'', ''siswa09@class.local'', ''aktif'', ''Game Developer'', ''Membuat game edukasi'', ''Gaming'', NULL, NULL, NOW(), NOW()),
(10, ''20231010'', ''Siswa 10'', ''S10'', ''P'', ''anggota'', ''@s10'', ''0812300010'', ''siswa10@class.local'', ''aktif'', ''Data Scientist'', ''Memajukan teknologi'', ''Membaca'', NULL, NULL, NOW(), NOW()),
(11, ''20231011'', ''Siswa 11'', ''S11'', ''L'', ''anggota'', ''@s11'', ''0812300011'', ''siswa11@class.local'', ''aktif'', ''Pilot'', ''Mengelilingi dunia'', ''Bersepeda'', NULL, NULL, NOW(), NOW()),
(12, ''20231012'', ''Siswa 12'', ''S12'', ''P'', ''anggota'', ''@s12'', ''0812300012'', ''siswa12@class.local'', ''aktif'', ''Guru'', ''Mencerdaskan anak bangsa'', ''Membaca'', NULL, NULL, NOW(), NOW()),
(13, ''20231013'', ''Siswa 13'', ''S13'', ''L'', ''anggota'', ''@s13'', ''0812300013'', ''siswa13@class.local'', ''aktif'', ''Ahli Jaringan'', ''Membangun konektivitas'', ''Teknologi'', NULL, NULL, NOW(), NOW()),
(14, ''20231014'', ''Siswa 14'', ''S14'', ''P'', ''anggota'', ''@s14'', ''0812300014'', ''siswa14@class.local'', ''aktif'', ''Penulis'', ''Menerbitkan novel'', ''Menulis'', NULL, NULL, NOW(), NOW()),
(15, ''20231015'', ''Siswa 15'', ''S15'', ''L'', ''anggota'', ''@s15'', ''0812300015'', ''siswa15@class.local'', ''aktif'', ''Arsitek'', ''Membangun kota'', ''Arsitektur'', NULL, NULL, NOW(), NOW()),
(16, ''20231016'', ''Siswa 16'', ''S16'', ''P'', ''anggota'', ''@s16'', ''0812300016'', ''siswa16@class.local'', ''aktif'', ''Ilmuwan'', ''Menemukan inovasi'', ''Membaca'', NULL, NULL, NOW(), NOW()),
(17, ''20231017'', ''Siswa 17'', ''S17'', ''L'', ''anggota'', ''@s17'', ''0812300017'', ''siswa17@class.local'', ''aktif'', ''Teknisi'', ''Membuka lapangan kerja'', ''Otomotif'', NULL, NULL, NOW(), NOW()),
(18, ''20231018'', ''Siswa 18'', ''S18'', ''P'', ''anggota'', ''@s18'', ''0812300018'', ''siswa18@class.local'', ''aktif'', ''Apoteker'', ''Menjadi ahli kesehatan'', ''Membaca'', NULL, NULL, NOW(), NOW()),
(19, ''20231019'', ''Siswa 19'', ''S19'', ''L'', ''anggota'', ''@s19'', ''0812300019'', ''siswa19@class.local'', ''aktif'', ''Chef'', ''Membuka restoran'', ''Memasak'', NULL, NULL, NOW(), NOW()),
(20, ''20231020'', ''Siswa 20'', ''S20'', ''P'', ''anggota'', ''@s20'', ''0812300020'', ''siswa20@class.local'', ''aktif'', ''Psikolog'', ''Membantu orang lain'', ''Mendengar musik'', NULL, NULL, NOW(), NOW()),
(21, ''20231021'', ''Siswa 21'', ''S21'', ''L'', ''anggota'', ''@s21'', ''0812300021'', ''siswa21@class.local'', ''aktif'', ''Peneliti'', ''Menemukan obat'', ''Eksperimen'', NULL, NULL, NOW(), NOW()),
(22, ''20231022'', ''Siswa 22'', ''S22'', ''P'', ''anggota'', ''@s22'', ''0812300022'', ''siswa22@class.local'', ''aktif'', ''UI/UX'', ''Membuat produk bermanfaat'', ''Design'', NULL, NULL, NOW(), NOW()),
(23, ''20231023'', ''Siswa 23'', ''S23'', ''L'', ''anggota'', ''@s23'', ''0812300023'', ''siswa23@class.local'', ''aktif'', ''Animator'', ''Berkarya bagi anak'', ''Animasi'', NULL, NULL, NOW(), NOW()),
(24, ''20231024'', ''Siswa 24'', ''S24'', ''P'', ''anggota'', ''@s24'', ''0812300024'', ''siswa24@class.local'', ''aktif'', ''Perawat'', ''Merawat orang sakit'', ''Menjahit'', NULL, NULL, NOW(), NOW()),
(25, ''20231025'', ''Siswa 25'', ''S25'', ''L'', ''anggota'', ''@s25'', ''0812300025'', ''siswa25@class.local'', ''aktif'', ''Photographer'', ''Menangkap momen'', ''Fotografi'', NULL, NULL, NOW(), NOW()),
(26, ''20231026'', ''Siswa 26'', ''S26'', ''P'', ''anggota'', ''@s26'', ''0812300026'', ''siswa26@class.local'', ''aktif'', ''Animator'', ''Menghibur dunia'', ''Menggambar'', NULL, NULL, NOW(), NOW()),
(27, ''20231027'', ''Siswa 27'', ''S27'', ''L'', ''anggota'', ''@s27'', ''0812300027'', ''siswa27@class.local'', ''aktif'', ''Programmer'', ''Membangun startup'', ''Coding'', NULL, NULL, NOW(), NOW()),
(28, ''20231028'', ''Siswa 28'', ''S28'', ''P'', ''anggota'', ''@s28'', ''0812300028'', ''siswa28@class.local'', ''aktif'', ''Animator'', ''Menghibur anak-anak'', ''Menggambar'', NULL, NULL, NOW(), NOW()),
(29, ''20231029'', ''Siswa 29'', ''S29'', ''L'', ''anggota'', ''@s29'', ''0812300029'', ''siswa29@class.local'', ''aktif'', ''Security Engineer'', ''Menjaga dunia digital'', ''Game'', NULL, NULL, NOW(), NOW()),
(30, ''20231030'', ''Siswa 30'', ''S30'', ''P'', ''anggota'', ''@s30'', ''0812300030'', ''siswa30@class.local'', ''aktif'', ''Penulis'', ''Berbagi cerita inspiratif'', ''Menulis'', NULL, NULL, NOW(), NOW());

INSERT INTO struktur_kelas (jabatan, anggota_id, created_at, updated_at) VALUES
(''wali_kelas'', 100, NOW(), NOW()),
(''ketua'', 1, NOW(), NOW()),
(''wakil'', 2, NOW(), NOW()),
(''sekretaris'', 3, NOW(), NOW()),
(''bendahara'', 4, NOW(), NOW())
ON DUPLICATE KEY UPDATE anggota_id = VALUES(anggota_id), updated_at = NOW();

INSERT INTO pengumuman (judul, isi, audience, published_at, created_by, updated_at) VALUES
(''Rapat Koordinasi'', ''Rapat perangkat kelas akan dilaksanakan pada hari Jumat pukul 14.00 di ruang kelas.'', ''pengurus'', NOW(), 2, NOW()),
(''Pengumpulan SPP'', ''Mohon seluruh anggota menyelesaikan pembayaran SPP sebelum tanggal 10 setiap bulannya.'', ''anggota'', NOW(), 6, NOW()),
(''Informasi Libur'', ''Sekolah akan libur pada tanggal 17 Agustus dalam rangka peringatan Hari Kemerdekaan.'', ''semua'', NOW(), 2, NOW());

INSERT INTO spp_records (anggota_id, bulan, tahun, jumlah, status, tanggal_bayar, catatan, created_by, updated_by, created_at, updated_at) VALUES
(5, 8, 2025, 150000, ''lunas'', '2025-08-05', ''Dibayar lunas'', 6, 6, NOW(), NOW()),
(6, 8, 2025, 150000, ''belum'', NULL, NULL, 6, NULL, NOW(), NOW()),
(7, 8, 2025, 150000, ''cicil'', '2025-08-12', ''Sisa 50k'', 6, 6, NOW(), NOW());

INSERT INTO attendance_records (anggota_id, tanggal, status, keterangan, recorded_by, created_at, updated_at) VALUES
(5, '2025-08-01', ''hadir'', NULL, 5, NOW(), NOW()),
(6, '2025-08-01', ''izin'', ''Sakit ringan'', 5, NOW(), NOW()),
(7, '2025-08-01', ''hadir'', NULL, 5, NOW(), NOW());

INSERT INTO weekly_tasks (judul, deskripsi, deadline, is_completed, created_by, updated_by, created_at, updated_at) VALUES
(''Buat Poster Kegiatan'', ''Ketua dan wakil menyiapkan poster kegiatan kelas minggu depan'', DATE_ADD(CURDATE(), INTERVAL 5 DAY), 0, 3, NULL, NOW(), NOW()),
(''Lengkapi Data SPP'', ''Bendahara memastikan seluruh data pembayaran SPP bulan berjalan sudah terinput'', DATE_ADD(CURDATE(), INTERVAL 3 DAY), 0, 6, NULL, NOW(), NOW());

INSERT INTO roster (hari, nama_mapel, created_at, updated_at) VALUES
(''Senin'', ''Bahasa Indonesia'', NOW(), NOW()),
(''Senin'', ''Matematika'', NOW(), NOW()),
(''Selasa'', ''Pemrograman Web'', NOW(), NOW()),
(''Rabu'', ''Basis Data'', NOW(), NOW()),
(''Kamis'', ''PBO'', NOW(), NOW()),
(''Jumat'', ''Pendidikan Agama'', NOW(), NOW());

INSERT INTO piket (hari, anggota_id, created_at, updated_at) VALUES
(''Senin'', 1, NOW(), NOW()),
(''Senin'', 2, NOW(), NOW()),
(''Selasa'', 3, NOW(), NOW()),
(''Selasa'', 4, NOW(), NOW()),
(''Rabu'', 5, NOW(), NOW()),
(''Rabu'', 6, NOW(), NOW()),
(''Kamis'', 7, NOW(), NOW()),
(''Kamis'', 8, NOW(), NOW()),
(''Jumat'', 9, NOW(), NOW()),
(''Jumat'', 10, NOW(), NOW())
ON DUPLICATE KEY UPDATE anggota_id = VALUES(anggota_id), updated_at = NOW();
