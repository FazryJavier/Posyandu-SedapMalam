<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="referrer" content="no-referrer">
    <title>Perkembangan Anak - {{ $posyandu['nama'] }}</title>
    <link rel="icon" href="{{ asset('assets/logo-posyandu.png') }}">
    {{-- No external fonts, scripts or images: nothing on this page may leak the private link. --}}
    <style>
        :root {
            --navy: #37517e;
            --sky: #47b2e4;
            --ink: #1f2a3d;
            --muted: #5c6b84;
            --line: #d6dfeb;
            --bg: #f2f6fb;
            --good: #1e8e5a;
            --warn: #b7791f;
            --alert: #c0392b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font: 18px/1.55 system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        }

        header.top {
            background: var(--navy);
            color: #fff;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        header.top img { height: 44px; width: auto; background: #fff; border-radius: 8px; padding: 4px; }
        header.top strong { display: block; font-size: 19px; line-height: 1.2; }
        header.top span { font-size: 14px; opacity: .85; }

        main { max-width: 640px; margin: 0 auto; padding: 16px; }

        h1 { font-size: 24px; line-height: 1.25; margin: 8px 0 4px; color: var(--navy); }
        h2 { font-size: 22px; margin: 0; color: var(--navy); }
        h3 { font-size: 18px; margin: 20px 0 6px; color: var(--navy); }

        .lead { margin: 0 0 14px; color: var(--muted); }

        .privacy {
            background: #fff8e6;
            border: 1px solid #f1d58a;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 16px;
            margin-bottom: 16px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(55, 81, 126, .12);
            padding: 18px;
            margin-bottom: 18px;
        }

        .card .age { margin: 0; color: var(--muted); font-size: 16px; }

        .advice {
            border-radius: 12px;
            padding: 12px 14px;
            margin: 14px 0;
            font-weight: 600;
            border-left: 6px solid var(--sky);
            background: #eaf6fc;
        }

        .advice.good { background: #e6f6ee; border-color: var(--good); }
        .advice.warn { background: #fff4e0; border-color: var(--warn); }
        .advice.alert { background: #fdeaea; border-color: var(--alert); }

        .measures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 6px 0 4px;
        }

        .measure {
            background: var(--bg);
            border-radius: 12px;
            padding: 10px 12px;
        }

        .measure small { display: block; color: var(--muted); font-size: 14px; }
        .measure b { font-size: 22px; color: var(--navy); }
        .measure b i { font-size: 14px; font-style: normal; font-weight: 600; color: var(--muted); }

        .chart { margin: 14px 0 0; }
        .chart figcaption { font-weight: 700; color: var(--navy); margin-bottom: 2px; }
        .chart figcaption span { font-weight: 400; color: var(--muted); }
        .chart svg { width: 100%; height: auto; display: block; }

        details { margin-top: 14px; }
        summary { cursor: pointer; font-weight: 700; color: var(--navy); padding: 8px 0; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 16px; }
        th, td { padding: 8px 6px; text-align: left; border-bottom: 1px solid var(--line); white-space: nowrap; }
        th { color: var(--muted); font-weight: 600; font-size: 14px; }

        .empty { text-align: center; padding: 28px 18px; color: var(--muted); }

        .next {
            background: var(--navy);
            color: #fff;
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 18px;
        }

        .next b { display: block; font-size: 20px; }

        footer { color: var(--muted); font-size: 15px; padding: 4px 0 28px; }
        footer a { color: var(--navy); font-weight: 700; }

        @media (min-width: 520px) {
            .measures { grid-template-columns: repeat(3, 1fr); }
        }

        @media print {
            header.top, .privacy { display: none; }
            .card { box-shadow: none; border: 1px solid var(--line); break-inside: avoid; }
        }
    </style>
</head>

<body>
    <header class="top">
        <img src="{{ asset('assets/logo-posyandu.png') }}" alt="">
        <div>
            <strong>{{ $posyandu['nama'] }}</strong>
            <span>Pantauan tumbuh kembang anak</span>
        </div>
    </header>

    <main>
        <h1>Perkembangan Anak{{ $progress['household_head'] ? ' Keluarga '.$progress['household_head'] : '' }}</h1>
        <p class="lead">Data dari penimbangan di posyandu.</p>

        <div class="privacy" role="note">
            <b>Halaman ini pribadi.</b> Hanya keluarga Anda yang punya link ini. Mohon jangan dibagikan ke orang lain.
        </div>

        @forelse ($progress['children'] as $child)
            <section class="card" aria-labelledby="anak-{{ $loop->iteration }}">
                <h2 id="anak-{{ $loop->iteration }}">{{ $child['name'] }}</h2>
                <p class="age">{{ $child['gender'] === 'male' ? 'Laki-laki' : 'Perempuan' }}, usia {{ $child['age'] }}</p>

                <div class="advice {{ $child['advice']['level'] }}">{{ $child['advice']['message'] }}</div>

                @if ($child['latest'])
                    <h3>Penimbangan terakhir, {{ $child['latest']->measured_on->translatedFormat('d F Y') }}</h3>
                    <div class="measures">
                        <div class="measure"><small>Berat badan</small><b>{{ rtrim(rtrim(number_format((float) $child['latest']->weight_kg, 2, ',', ''), '0'), ',') }} <i>kg</i></b></div>
                        <div class="measure"><small>Tinggi / panjang</small><b>{{ $child['latest']->height_cm !== null ? rtrim(rtrim(number_format((float) $child['latest']->height_cm, 1, ',', ''), '0'), ',') : '-' }} <i>cm</i></b></div>
                        <div class="measure"><small>BMI</small><b>{{ $child['latest']->bmi !== null ? number_format((float) $child['latest']->bmi, 1, ',', '') : '-' }}</b></div>
                        <div class="measure"><small>Lingkar kepala</small><b>{{ $child['latest']->head_circumference_cm !== null ? rtrim(rtrim(number_format((float) $child['latest']->head_circumference_cm, 1, ',', ''), '0'), ',') : '-' }} <i>cm</i></b></div>
                        <div class="measure"><small>Lingkar lengan</small><b>{{ $child['latest']->arm_circumference_cm !== null ? rtrim(rtrim(number_format((float) $child['latest']->arm_circumference_cm, 1, ',', ''), '0'), ',') : '-' }} <i>cm</i></b></div>
                    </div>

                    @include('family._chart', ['chart' => $child['weight_chart'], 'title' => 'Berat badan', 'unit' => 'kg', 'color' => '#47b2e4'])
                    @include('family._chart', ['chart' => $child['height_chart'], 'title' => 'Tinggi badan', 'unit' => 'cm', 'color' => '#1e8e5a'])
                    @include('family._chart', ['chart' => $child['bmi_chart'], 'title' => 'BMI', 'unit' => 'indeks', 'color' => '#b7791f'])

                    <details>
                        <summary>Lihat riwayat penimbangan ({{ $child['records']->count() }})</summary>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr><th>Tanggal</th><th>Usia</th><th>BB (kg)</th><th>TB (cm)</th><th>BMI</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($child['records'] as $record)
                                        <tr>
                                            <td>{{ $record->measured_on->translatedFormat('d M Y') }}</td>
                                            <td>{{ $record->age_in_months }} bln</td>
                                            <td>{{ number_format((float) $record->weight_kg, 2, ',', '') }}</td>
                                            <td>{{ $record->height_cm !== null ? number_format((float) $record->height_cm, 1, ',', '') : '-' }}</td>
                                            <td>{{ $record->bmi !== null ? number_format((float) $record->bmi, 1, ',', '') : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                @endif
            </section>
        @empty
            <div class="card empty">
                Belum ada anak balita yang tercatat pada keluarga ini.
                Silakan hubungi kader posyandu bila ada data yang perlu diperbarui.
            </div>
        @endforelse

        @if ($progress['next_session'])
            <div class="next">
                Posyandu berikutnya
                <b>{{ $progress['next_session']->held_on->translatedFormat('l, d F Y') }}</b>
                @if ($progress['next_session']->location)
                    <span>{{ $progress['next_session']->location }}</span>
                @endif
            </div>
        @elseif (! empty($posyandu['jadwal']))
            <div class="next">
                Jadwal posyandu
                <b>{{ $posyandu['jadwal'] }}</b>
            </div>
        @endif

        <footer>
            <p>
                Informasi ini membantu memantau pertumbuhan dan <b>bukan diagnosis</b>.
                Bila ada kekhawatiran, tanyakan kepada kader atau tenaga kesehatan.
            </p>
            @if (! empty($posyandu['whatsapp']) || ! empty($posyandu['telepon']))
                <p>Hubungi kader: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $posyandu['whatsapp'] ?? $posyandu['telepon']) }}">{{ $posyandu['whatsapp'] ?? $posyandu['telepon'] }}</a></p>
            @endif
            <p>{{ $posyandu['nama'] }} &middot; {{ $posyandu['alamat'] }}</p>
        </footer>
    </main>
</body>

</html>
