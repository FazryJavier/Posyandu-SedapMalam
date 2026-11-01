<div x-data="{
        copied: false,
        copy() {
            const input = this.$refs.link;
            input.select();
            (navigator.clipboard ? navigator.clipboard.writeText(input.value) : Promise.resolve(document.execCommand('copy')))
                .then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); });
        }
    }" style="display: grid; gap: 1rem;">
    <p style="margin: 0; font-size: 0.875rem; opacity: .8;">
        Berikan link atau QR ini kepada orang tua. Dengan link ini mereka bisa melihat perkembangan anak
        <strong>tanpa login</strong>. Hanya orang yang memegang link yang dapat membukanya.
    </p>

    <div style="display: flex; justify-content: center;">
        <div style="background: #fff; padding: 12px; border-radius: 12px; width: 264px; height: 264px; box-shadow: 0 1px 4px rgba(0,0,0,.2);">
            {!! $qr !!}
        </div>
    </div>

    <div style="display: flex; gap: .5rem; align-items: center;">
        <div style="flex: 1; min-width: 0;">
            <x-filament::input.wrapper>
                <x-filament::input type="text" readonly x-ref="link" value="{{ $url }}" x-on:focus="$event.target.select()" />
            </x-filament::input.wrapper>
        </div>
        <x-filament::button type="button" color="gray" x-on:click="copy()">
            <span x-show="!copied">Salin</span>
            <span x-show="copied" x-cloak>Tersalin</span>
        </x-filament::button>
    </div>

    <x-filament::button tag="a" href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" color="success"
        icon="heroicon-o-chat-bubble-left-right">
        Kirim lewat WhatsApp{{ $hasPhone ? '' : ' (pilih kontak sendiri)' }}
    </x-filament::button>

    <p style="margin: 0; font-size: 0.75rem; opacity: .7;">
        @if ($lastViewed)
            Terakhir dibuka keluarga: {{ $lastViewed->translatedFormat('d F Y, H:i') }}.
        @else
            Link ini belum pernah dibuka.
        @endif
        Bila link tersebar atau salah kirim, gunakan <em>Buat ulang link</em> atau <em>Cabut akses</em>.
    </p>
</div>
