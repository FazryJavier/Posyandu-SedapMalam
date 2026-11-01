<?php

/*
|--------------------------------------------------------------------------
| Konten Landing Page Posyandu Sedap Malam
|--------------------------------------------------------------------------
|
| Seluruh teks di halaman publik (`/`) dibaca dari berkas ini agar mudah
| diubah tanpa menyentuh tampilan. Nilai `null` pada kontak dan jadwal akan
| menyembunyikan baris terkait atau menampilkan teks pengganti.
|
*/

return [
    'nama' => 'Posyandu Sedap Malam',
    'tagline' => 'Melayani kesehatan ibu dan anak warga RW 11, Kelurahan Sukaluyu, Kota Bandung.',
    'alamat' => 'Jl. Rereng Wulung No. 11, RW 11, Kelurahan Sukaluyu, Kecamatan Cibeunying Kaler, Kota Bandung',
    'peta_query' => 'Posyandu Sedap Malam Sukaluyu Bandung',

    // Isi bila sudah ada, contoh: '0812-3456-7890'. Nilai null = disembunyikan.
    'telepon' => null,
    'whatsapp' => null,
    'email' => null,
    'instagram' => null,

    // Isi dengan hari dan jam pelaksanaan, contoh: 'Setiap Sabtu minggu ke-2, pukul 08.00 - 11.00 WIB'.
    'jadwal' => null,
    'jadwal_pengganti' => 'Posyandu diselenggarakan sekali setiap bulan. Hari dan jam pelaksanaan diumumkan oleh kader, silakan hubungi kader atau datang langsung ke lokasi.',

    'tentang' => [
        'Posyandu (Pos Pelayanan Terpadu) adalah kegiatan kesehatan bersumber daya masyarakat yang dikelola dari, oleh, dan untuk masyarakat. Posyandu Sedap Malam hadir di RW 11 Kelurahan Sukaluyu, Kecamatan Cibeunying Kaler, Kota Bandung, untuk memudahkan warga memantau kesehatan ibu dan anak di lingkungan sendiri.',
        'Seluruh kegiatan dijalankan oleh para kader yang secara sukarela melayani warga, mulai dari penimbangan balita, pencatatan pertumbuhan, imunisasi, hingga penyuluhan kesehatan keluarga.',
    ],

    'sasaran' => [
        'Bayi dan balita (0 - 5 tahun)',
        'Ibu hamil dan ibu menyusui',
        'Pasangan usia subur',
        'Seluruh warga yang membutuhkan informasi kesehatan',
    ],

    'visi' => 'Mewujudkan ibu dan anak yang sehat, cerdas, dan bebas stunting di lingkungan RW 11 Kelurahan Sukaluyu.',
    'misi' => [
        'Menyelenggarakan penimbangan dan pemantauan tumbuh kembang balita secara rutin.',
        'Membantu warga memperoleh imunisasi dasar yang lengkap bagi bayi dan balita.',
        'Memberikan penyuluhan gizi, kesehatan ibu dan anak, serta pola hidup bersih dan sehat.',
        'Meningkatkan peran serta masyarakat dan kader dalam pembangunan kesehatan.',
    ],

    // Alur pelayanan Posyandu "Sistem 5 Meja".
    'meja' => [
        ['judul' => 'Pendaftaran', 'teks' => 'Kader mencatat kehadiran bayi, balita, ibu hamil, dan ibu menyusui yang datang.', 'icon' => 'bx bx-edit'],
        ['judul' => 'Penimbangan', 'teks' => 'Berat badan, tinggi atau panjang badan, serta lingkar kepala anak diukur.', 'icon' => 'bx bx-tachometer'],
        ['judul' => 'Pencatatan', 'teks' => 'Hasil pengukuran dicatat pada Buku KIA atau KMS untuk melihat grafik pertumbuhan.', 'icon' => 'bx bx-file'],
        ['judul' => 'Penyuluhan', 'teks' => 'Kader memberi penyuluhan gizi, pola asuh, dan arahan sesuai hasil penimbangan.', 'icon' => 'bx bx-chat'],
        ['judul' => 'Pelayanan Kesehatan', 'teks' => 'Imunisasi, pemberian vitamin A, konsultasi KB, dan pemeriksaan ibu hamil oleh tenaga kesehatan.', 'icon' => 'bx bx-plus-medical'],
    ],

    'program' => [
        ['icon' => 'bx bx-tachometer', 'judul' => 'Penimbangan dan Pemantauan Tumbuh Kembang', 'teks' => 'Mengukur berat dan tinggi badan anak secara berkala untuk memantau pertumbuhan, mendeteksi gizi kurang atau stunting sejak dini, dan memberi rujukan bila diperlukan.'],
        ['icon' => 'bx bx-injection', 'judul' => 'Imunisasi', 'teks' => 'Pemberian imunisasi dasar dan lanjutan bersama tenaga kesehatan agar bayi dan balita terlindungi dari penyakit berbahaya.'],
        ['icon' => 'bx bx-heart', 'judul' => 'Kesehatan Ibu dan Anak', 'teks' => 'Pemeriksaan dan pendampingan bagi ibu hamil, ibu menyusui, serta bayi dan balita, termasuk pemberian tablet tambah darah dan vitamin A.'],
        ['icon' => 'bx bx-bowl-hot', 'judul' => 'Gizi dan Pemberian Makanan Tambahan', 'teks' => 'Edukasi pola makan seimbang, MPASI, serta pemberian makanan tambahan untuk mendukung tumbuh kembang anak.'],
        ['icon' => 'bx bx-group', 'judul' => 'Keluarga Berencana', 'teks' => 'Informasi dan konsultasi perencanaan keluarga bagi pasangan usia subur.'],
        ['icon' => 'bx bx-shield-plus', 'judul' => 'Pencegahan Penyakit', 'teks' => 'Sosialisasi pola hidup bersih dan sehat, pencegahan penyakit menular, serta kebersihan lingkungan keluarga.'],
    ],

    // Jadwal imunisasi dasar dan lanjutan sesuai pedoman Kementerian Kesehatan RI.
    'imunisasi' => [
        ['usia' => '0 - 24 jam', 'vaksin' => 'Hepatitis B (HB-0)'],
        ['usia' => '1 bulan', 'vaksin' => 'BCG, Polio tetes 1'],
        ['usia' => '2 bulan', 'vaksin' => 'DPT-HB-Hib 1, Polio tetes 2'],
        ['usia' => '3 bulan', 'vaksin' => 'DPT-HB-Hib 2, Polio tetes 3'],
        ['usia' => '4 bulan', 'vaksin' => 'DPT-HB-Hib 3, Polio tetes 4, Polio suntik (IPV)'],
        ['usia' => '9 bulan', 'vaksin' => 'Campak-Rubella (MR)'],
        ['usia' => '18 bulan', 'vaksin' => 'DPT-HB-Hib lanjutan, Campak-Rubella (MR) lanjutan'],
    ],
    'imunisasi_catatan' => 'Jadwal di atas bersifat umum. Konsultasikan kondisi anak Anda kepada kader atau tenaga kesehatan sebelum imunisasi.',

    'edukasi' => [
        ['icon' => 'bx bx-heart-circle', 'judul' => 'ASI Eksklusif', 'teks' => 'Berikan hanya ASI selama 6 bulan pertama tanpa makanan atau minuman lain, lalu lanjutkan ASI hingga usia 2 tahun atau lebih.'],
        ['icon' => 'bx bx-food-menu', 'judul' => 'MPASI dan Gizi Seimbang', 'teks' => 'Mulai usia 6 bulan, berikan makanan pendamping ASI yang beragam: karbohidrat, protein hewani, protein nabati, lemak, sayur, dan buah.'],
        ['icon' => 'bx bx-time-five', 'judul' => '1000 Hari Pertama Kehidupan', 'teks' => 'Masa sejak kehamilan hingga anak berusia 2 tahun menentukan tumbuh kembang dan kecerdasan anak serta mencegah stunting.'],
        ['icon' => 'bx bx-book-heart', 'judul' => 'Rutin Memantau Buku KIA', 'teks' => 'Bawa Buku KIA atau KMS setiap kunjungan agar grafik pertumbuhan anak dapat dipantau dari bulan ke bulan.'],
        ['icon' => 'bx bx-droplet', 'judul' => 'Hidup Bersih dan Sehat', 'teks' => 'Biasakan cuci tangan pakai sabun, minum air matang, memakai jamban sehat, dan menjaga kebersihan lingkungan rumah.'],
        ['icon' => 'bx bx-handicap', 'judul' => 'Kesehatan Ibu Hamil', 'teks' => 'Periksakan kehamilan secara rutin, konsumsi tablet tambah darah, dan kenali tanda bahaya kehamilan.'],
    ],

    'galeri' => [1, 2, 3, 4, 6, 7, 8, 9, 10],

    // Organigram: isi 'nama' pada setiap jabatan bila sudah tersedia; bila kosong hanya jabatan yang ditampilkan.
    'organigram' => [
        'ketua' => ['jabatan' => 'Ketua Posyandu', 'nama' => 'Wini Sukarwinah'],
        'pengurus' => [
            ['jabatan' => 'Wakil Ketua', 'nama' => null, 'icon' => 'bi bi-person-badge'],
            ['jabatan' => 'Sekretaris', 'nama' => null, 'icon' => 'bi bi-journal-text'],
            ['jabatan' => 'Bendahara', 'nama' => null, 'icon' => 'bi bi-wallet2'],
        ],
        'seksi' => [
            ['jabatan' => 'Kader Kesehatan Ibu dan Anak', 'nama' => null, 'icon' => 'bi bi-heart-pulse'],
            ['jabatan' => 'Kader Keluarga Berencana', 'nama' => null, 'icon' => 'bi bi-people'],
            ['jabatan' => 'Kader Imunisasi', 'nama' => null, 'icon' => 'bi bi-shield-plus'],
            ['jabatan' => 'Kader Gizi', 'nama' => null, 'icon' => 'bi bi-basket'],
            ['jabatan' => 'Kader Pencegahan Penyakit', 'nama' => null, 'icon' => 'bi bi-shield-check'],
        ],
    ],

    'faq' => [
        ['tanya' => 'Apa itu Posyandu?', 'jawab' => 'Posyandu adalah Pos Pelayanan Terpadu, yaitu kegiatan kesehatan dasar yang dikelola oleh masyarakat bersama kader dan didampingi tenaga kesehatan, terutama untuk kesehatan ibu dan anak.'],
        ['tanya' => 'Siapa saja yang dapat datang ke Posyandu?', 'jawab' => 'Bayi, balita, ibu hamil, ibu menyusui, dan pasangan usia subur. Warga lain yang membutuhkan informasi kesehatan juga dipersilakan bertanya kepada kader.'],
        ['tanya' => 'Apa yang perlu dibawa saat datang?', 'jawab' => 'Bawa Buku KIA atau Kartu Menuju Sehat (KMS) anak agar hasil penimbangan dan imunisasi dapat dicatat. Bagi anak yang baru pertama kali datang, kader akan membantu pendaftarannya.'],
        ['tanya' => 'Apakah ada biaya?', 'jawab' => 'Pelayanan dasar Posyandu pada umumnya tidak dipungut biaya. Untuk kepastian, silakan tanyakan langsung kepada kader.'],
        ['tanya' => 'Mengapa anak perlu ditimbang setiap bulan?', 'jawab' => 'Penimbangan rutin membantu mengetahui apakah berat dan tinggi badan anak naik sesuai usianya, sehingga masalah gizi atau stunting dapat dikenali dan ditangani lebih awal.'],
        ['tanya' => 'Bagaimana jika anak saya sedang sakit?', 'jawab' => 'Bila anak demam, batuk, atau sakit, sampaikan kepada kader. Kader akan menyarankan pemeriksaan ke puskesmas atau tenaga kesehatan dan imunisasi dapat ditunda sesuai anjuran.'],
    ],
];
