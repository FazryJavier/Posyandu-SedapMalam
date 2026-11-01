<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ $posyandu['nama'] }} - Kesehatan Ibu dan Anak, Sukaluyu Bandung</title>
    <meta content="{{ $posyandu['nama'] }}, RW 11 Kelurahan Sukaluyu, Kota Bandung. Penimbangan balita, imunisasi, kesehatan ibu dan anak, penyuluhan gizi, dan keluarga berencana."
        name="description">
    <meta content="posyandu, sedap malam, sukaluyu, bandung, kesehatan ibu dan anak, imunisasi, penimbangan balita, stunting"
        name="keywords">
    <meta property="og:title" content="{{ $posyandu['nama'] }}">
    <meta property="og:description" content="{{ $posyandu['tagline'] }}">
    <meta property="og:image" content="{{ asset('assets/logo-posyandu.png') }}">
    <link href="{{ asset('assets/logo-posyandu.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i&display=swap"
        rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto">
                <a href="#hero"><img src="{{ asset('assets/logo-posyandu.png') }}"
                        alt="Logo {{ $posyandu['nama'] }}"></a>
            </h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Beranda</a></li>
                    <li><a class="nav-link scrollto" href="#about">Tentang</a></li>
                    <li><a class="nav-link scrollto" href="#layanan">Layanan</a></li>
                    <li><a class="nav-link scrollto" href="#imunisasi">Imunisasi</a></li>
                    <li><a class="nav-link scrollto" href="#portfolio">Galeri</a></li>
                    <li><a class="nav-link scrollto" href="#team">Kader</a></li>
                    <li><a class="nav-link scrollto" href="#contact">Lokasi</a></li>
                    <li><a class="getstarted" href="{{ url('/login') }}">Login</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section id="hero" class="d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
                    data-aos="fade-up" data-aos-delay="200">
                    <h1>{{ $posyandu['nama'] }}</h1>
                    <h2>{{ $posyandu['tagline'] }}</h2>
                    <div class="d-flex justify-content-center justify-content-lg-start flex-wrap">
                        <a href="#layanan" class="btn-get-started scrollto">Lihat Layanan</a>
                        <a href="#contact" class="btn-hero-secondary scrollto">Lokasi Kami</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('assets/logo-posyandu.png') }}" class="img-fluid animated"
                        alt="Logo {{ $posyandu['nama'] }}">
                </div>
            </div>
        </div>
    </section>

    <main id="main">
        {{-- Info singkat --}}
        <section id="info" class="info-strip">
            <div class="container">
                <div class="row g-0 info-strip-box" data-aos="fade-up">
                    <div class="col-md-4 info-item">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <h4>Lokasi</h4>
                            <p>RW 11, Kelurahan Sukaluyu, Kota Bandung</p>
                        </div>
                    </div>
                    <div class="col-md-4 info-item">
                        <i class="bi bi-calendar-check"></i>
                        <div>
                            <h4>Jadwal</h4>
                            <p>{{ $posyandu['jadwal'] ?? 'Sekali setiap bulan' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 info-item">
                        <i class="bi bi-people"></i>
                        <div>
                            <h4>Sasaran</h4>
                            <p>Bayi, balita, ibu hamil, dan ibu menyusui</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Tentang kami, visi, dan misi --}}
        <section id="about" class="about">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Tentang Kami</h2>
                    <p>Mengenal {{ $posyandu['nama'] }} dan kegiatan yang kami jalankan bersama warga.</p>
                </div>

                <div class="row content">
                    <div class="col-lg-6">
                        @foreach ($posyandu['tentang'] as $paragraf)
                            <p>{{ $paragraf }}</p>
                        @endforeach
                        <p class="mb-2"><strong>Sasaran layanan:</strong></p>
                        <ul>
                            @foreach ($posyandu['sasaran'] as $sasaran)
                                <li><i class="ri-check-double-line"></i> {{ $sasaran }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0">
                        <div class="visi-misi">
                            <h3><i class="bi bi-bullseye"></i> Visi</h3>
                            <p>{{ $posyandu['visi'] }}</p>
                            <h3><i class="bi bi-list-check"></i> Misi</h3>
                            <ol>
                                @foreach ($posyandu['misi'] as $misi)
                                    <li>{{ $misi }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Alur pelayanan (Sistem 5 Meja) --}}
        <section id="layanan" class="alur section-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Alur Pelayanan</h2>
                    <p>Setiap kunjungan ke Posyandu melewati lima meja pelayanan agar tumbuh kembang anak terpantau
                        dengan baik.</p>
                </div>

                <div class="row justify-content-center">
                    @foreach ($posyandu['meja'] as $meja)
                        <div class="col-xl col-lg-4 col-md-6 d-flex align-items-stretch mb-4" data-aos="zoom-in"
                            data-aos-delay="{{ ($loop->index % 3 + 1) * 100 }}">
                            <div class="meja-box">
                                <span class="meja-no">{{ $loop->iteration }}</span>
                                <div class="meja-icon"><i class="{{ $meja['icon'] }}"></i></div>
                                <h4>Meja {{ $loop->iteration }}</h4>
                                <h5>{{ $meja['judul'] }}</h5>
                                <p>{{ $meja['teks'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        {{-- Program kerja --}}
        <section id="services" class="services">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Program Kerja</h2>
                    <p>Kegiatan rutin yang dijalankan para kader untuk menjaga kesehatan ibu, bayi, dan balita di
                        lingkungan kami.</p>
                </div>

                <div class="row">
                    @foreach ($posyandu['program'] as $program)
                        <div class="col-xl-4 col-md-6 d-flex align-items-stretch mb-4" data-aos="zoom-in"
                            data-aos-delay="{{ ($loop->index % 3 + 1) * 100 }}">
                            <div class="icon-box">
                                <div class="icon"><i class="{{ $program['icon'] }}"></i></div>
                                <h4>{{ $program['judul'] }}</h4>
                                <p>{{ $program['teks'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        {{-- Statistik (disembunyikan bila database tidak tersedia) --}}
        @if ($stats)
            <section id="stats" class="stats">
                <div class="container" data-aos="fade-up">
                    <div class="row text-center">
                        <div class="col-md-4 stat-item">
                            <i class="bi bi-emoji-smile"></i>
                            <span class="stat-number">{{ number_format($stats['anak'], 0, ',', '.') }}</span>
                            <p>Anak Terdata</p>
                        </div>
                        <div class="col-md-4 stat-item">
                            <i class="bi bi-house-heart"></i>
                            <span class="stat-number">{{ number_format($stats['keluarga'], 0, ',', '.') }}</span>
                            <p>Keluarga Terdaftar</p>
                        </div>
                        <div class="col-md-4 stat-item">
                            <i class="bi bi-person-hearts"></i>
                            <span class="stat-number">{{ number_format($stats['kader'], 0, ',', '.') }}</span>
                            <p>Kader Bertugas</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Jadwal imunisasi --}}
        <section id="imunisasi" class="imunisasi section-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Jadwal Imunisasi</h2>
                    <p>Imunisasi melindungi anak dari penyakit berbahaya. Berikut jadwal imunisasi dasar dan lanjutan
                        pada bayi dan balita.</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="table-responsive imunisasi-table">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Usia</th>
                                        <th scope="col">Imunisasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posyandu['imunisasi'] as $baris)
                                        <tr>
                                            <th scope="row">{{ $baris['usia'] }}</th>
                                            <td>{{ $baris['vaksin'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="imunisasi-note"><i class="bi bi-info-circle"></i> {{ $posyandu['imunisasi_catatan'] }}
                        </p>
                    </div>
                </div>

            </div>
        </section>

        {{-- Edukasi kesehatan --}}
        <section id="edukasi" class="edukasi services">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Tips Kesehatan Ibu dan Anak</h2>
                    <p>Informasi singkat untuk mendukung tumbuh kembang anak dan kesehatan keluarga.</p>
                </div>

                <div class="row">
                    @foreach ($posyandu['edukasi'] as $tips)
                        <div class="col-xl-4 col-md-6 d-flex align-items-stretch mb-4" data-aos="fade-up"
                            data-aos-delay="{{ ($loop->index % 3 + 1) * 100 }}">
                            <div class="tips-box">
                                <div class="tips-icon"><i class="{{ $tips['icon'] }}"></i></div>
                                <div>
                                    <h4>{{ $tips['judul'] }}</h4>
                                    <p>{{ $tips['teks'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        {{-- CTA --}}
        <section id="cta" class="cta">
            <div class="container" data-aos="zoom-in">
                <div class="row">
                    <div class="col-lg-9 text-center text-lg-start">
                        <h3>Pantau Perkembangan Anak Anda</h3>
                        <p>Datanglah ke Posyandu secara rutin agar berat badan, tinggi badan, dan status gizi anak
                            terpantau dengan baik. Kader kami siap membantu dan menjawab pertanyaan Anda.</p>
                    </div>
                    <div class="col-lg-3 cta-btn-container text-center">
                        <a class="cta-btn align-middle scrollto" href="#contact">Lihat Lokasi</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Galeri --}}
        <section id="portfolio" class="portfolio">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Foto Kegiatan</h2>
                    <p>Dokumentasi kegiatan {{ $posyandu['nama'] }}.</p>
                </div>

                <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="100">
                    @foreach ($posyandu['galeri'] as $nomor)
                        <div class="col-lg-4 col-md-6 portfolio-item">
                            <div class="portfolio-img">
                                <img src="{{ asset("assets/img/Fotos/Foto{$nomor}.jpg") }}" class="img-fluid"
                                    loading="lazy"
                                    alt="Kegiatan {{ $posyandu['nama'] }}, foto {{ $loop->iteration }}">
                            </div>
                            <div class="portfolio-info">
                                <h4>Kegiatan Posyandu</h4>
                                <p>Dokumentasi {{ $loop->iteration }}</p>
                                <a href="{{ asset("assets/img/Fotos/Foto{$nomor}.jpg") }}"
                                    data-gallery="portfolioGallery" class="portfolio-lightbox preview-link"
                                    title="Kegiatan Posyandu, dokumentasi {{ $loop->iteration }}"><i
                                        class="bx bx-plus"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        {{-- Struktur kader (organigram) --}}
        <section id="team" class="team section-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Struktur Kader Posyandu</h2>
                    <p>Susunan pengurus dan kader yang melayani warga dengan sukarela.</p>
                </div>

                @php($org = $posyandu['organigram'])
                <div class="org-chart">
                    <div class="org-node org-ketua">
                        <div class="org-icon"><i class="bi bi-person-fill"></i></div>
                        <h4>{{ $org['ketua']['jabatan'] }}</h4>
                        @if ($org['ketua']['nama'])
                            <span>{{ $org['ketua']['nama'] }}</span>
                        @endif
                    </div>

                    <div class="org-connector" aria-hidden="true"></div>

                    <div class="org-group">
                        <h5 class="org-label">Pengurus</h5>
                        <ul class="org-row org-row-3">
                            @foreach ($org['pengurus'] as $anggota)
                                <li class="org-item">
                                    <div class="org-node">
                                        <div class="org-icon"><i class="{{ $anggota['icon'] }}"></i></div>
                                        <div class="org-text">
                                            <h4>{{ $anggota['jabatan'] }}</h4>
                                            @if ($anggota['nama'])
                                                <span>{{ $anggota['nama'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="org-connector" aria-hidden="true"></div>

                    <div class="org-group">
                        <h5 class="org-label">Kader Seksi</h5>
                        <ul class="org-row">
                            @foreach ($org['seksi'] as $anggota)
                                <li class="org-item">
                                    <div class="org-node">
                                        <div class="org-icon"><i class="{{ $anggota['icon'] }}"></i></div>
                                        <div class="org-text">
                                            <h4>{{ $anggota['jabatan'] }}</h4>
                                            @if ($anggota['nama'])
                                                <span>{{ $anggota['nama'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </section>

        {{-- FAQ --}}
        <section id="faq" class="faq">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Pertanyaan yang Sering Diajukan</h2>
                    <p>Jawaban singkat untuk pertanyaan yang paling sering ditanyakan warga.</p>
                </div>

                <div class="faq-list">
                    <ul>
                        @foreach ($posyandu['faq'] as $faq)
                            <li data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                                <i class="bx bx-help-circle icon-help"></i>
                                <a data-bs-toggle="collapse" data-bs-target="#faq-list-{{ $loop->iteration }}"
                                    class="{{ $loop->first ? '' : 'collapsed' }}" role="button"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $faq['tanya'] }}
                                    <i class="bx bx-chevron-down icon-show"></i><i
                                        class="bx bx-chevron-up icon-close"></i>
                                </a>
                                <div id="faq-list-{{ $loop->iteration }}"
                                    class="collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent=".faq-list">
                                    <p>{{ $faq['jawab'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </section>

        {{-- Lokasi dan kontak --}}
        <section id="contact" class="contact section-bg">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Lokasi dan Kontak</h2>
                    <p>Kunjungi {{ $posyandu['nama'] }} di RW 11 Kelurahan Sukaluyu.</p>
                </div>

                <div class="row">
                    <div class="col-lg-4 d-flex align-items-start">
                        <div class="info">
                            <div class="address">
                                <i class="bi bi-geo-alt"></i>
                                <h4>Alamat</h4>
                                <p>{{ $posyandu['alamat'] }}</p>
                            </div>

                            <div class="schedule">
                                <i class="bi bi-calendar-check"></i>
                                <h4>Jadwal</h4>
                                <p>{{ $posyandu['jadwal'] ?? $posyandu['jadwal_pengganti'] }}</p>
                            </div>

                            @if ($posyandu['telepon'] || $posyandu['whatsapp'])
                                <div class="phone">
                                    <i class="bi bi-telephone"></i>
                                    <h4>Telepon</h4>
                                    <p>{{ $posyandu['telepon'] ?? $posyandu['whatsapp'] }}</p>
                                </div>
                            @endif

                            @if ($posyandu['email'])
                                <div class="email">
                                    <i class="bi bi-envelope"></i>
                                    <h4>Email</h4>
                                    <p>{{ $posyandu['email'] }}</p>
                                </div>
                            @endif

                            <a class="btn-directions"
                                href="https://www.google.com/maps/search/?api=1&query={{ urlencode($posyandu['peta_query']) }}"
                                target="_blank" rel="noopener">
                                <i class="bi bi-sign-turn-right"></i> Petunjuk Arah
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-8 mt-4 mt-lg-0">
                        <div class="map-wrap">
                            <iframe title="Peta lokasi {{ $posyandu['nama'] }}"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.951065668113!2d107.62802407573913!3d-6.896456667490032!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e7afd59fefad%3A0x18776b22dfc7f31b!2sPosyandu%20Sedap%20Malam!5e0!3m2!1sid!2sid!4v1704899220422!5m2!1sid!2sid"
                                width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6 footer-contact">
                        <h3>{{ $posyandu['nama'] }}</h3>
                        <p>{{ $posyandu['alamat'] }}</p>
                        @if ($posyandu['telepon'])
                            <p><strong>Telepon:</strong> {{ $posyandu['telepon'] }}</p>
                        @endif
                        @if ($posyandu['email'])
                            <p><strong>Email:</strong> {{ $posyandu['email'] }}</p>
                        @endif
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Menu</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#about" class="scrollto">Tentang</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#layanan" class="scrollto">Layanan</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#imunisasi" class="scrollto">Jadwal
                                    Imunisasi</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#faq" class="scrollto">FAQ</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6 footer-links">
                        <h4>Informasi</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#portfolio" class="scrollto">Foto
                                    Kegiatan</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#team" class="scrollto">Kader
                                    Posyandu</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#contact" class="scrollto">Lokasi dan
                                    Kontak</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{ url('/login') }}">Login Kader</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container footer-bottom clearfix">
            <div class="copyright">
                &copy; {{ date('Y') }} <strong><span>{{ $posyandu['nama'] }}</span></strong>. Hak cipta dilindungi.
            </div>
            <div class="credits">
                Template <a href="https://bootstrapmade.com/" target="_blank" rel="noopener">Arsha oleh
                    BootstrapMade</a>
            </div>
        </div>
    </footer>

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center" aria-label="Kembali ke atas"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
