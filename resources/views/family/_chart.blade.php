@php($color = $color ?? '#47b2e4')

@if ($chart)
    <figure class="chart">
        <figcaption>{{ $title }} <span>({{ $unit }})</span></figcaption>
        <svg viewBox="0 0 {{ \App\Support\SvgLineChart::WIDTH }} {{ \App\Support\SvgLineChart::HEIGHT }}" role="img"
            aria-label="Grafik {{ $title }}">
            <line x1="28" x2="292" y1="120" y2="120" stroke="#d6dfeb" stroke-width="1" />
            <polyline points="{{ $chart['points'] }}" fill="none" stroke="{{ $color }}" stroke-width="3"
                stroke-linejoin="round" stroke-linecap="round" />
            @foreach ($chart['dots'] as $dot)
                <circle cx="{{ $dot['x'] }}" cy="{{ $dot['y'] }}" r="4.5" fill="#fff" stroke="{{ $color }}"
                    stroke-width="2.5" />
                <text x="{{ $dot['x'] }}" y="{{ $dot['y'] - 9 }}" text-anchor="middle" font-size="11" font-weight="700"
                    fill="#37517e">{{ rtrim(rtrim(number_format($dot['value'], 1, ',', ''), '0'), ',') }}</text>
                <text x="{{ $dot['x'] }}" y="142" text-anchor="middle" font-size="11" fill="#5c6b84">{{ $dot['label'] }}</text>
            @endforeach
        </svg>
    </figure>
@endif
