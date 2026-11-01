<?php

namespace App\Support;

use App\Enums\CadreTier;
use App\Models\Cadre;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Builds the content array for the public landing page.
 *
 * Values edited in the admin CMS (database) win; config/posyandu.php provides
 * defaults so the site still renders before anything is saved or when the
 * database is unreachable.
 */
class SiteContent
{
    /** Keys of the editable site_settings rows. */
    public const KEYS = [
        'tagline', 'schedule', 'about', 'target_audience', 'vision', 'mission',
        'address', 'phone', 'whatsapp', 'email', 'map_query',
    ];

    /**
     * Initial values for the CMS settings form.
     *
     * @return array<string, mixed>
     */
    public static function formDefaults(): array
    {
        $defaults = [
            'tagline' => config('posyandu.tagline'),
            'schedule' => config('posyandu.jadwal'),
            'about' => config('posyandu.tentang'),
            'target_audience' => config('posyandu.sasaran'),
            'vision' => config('posyandu.visi'),
            'mission' => config('posyandu.misi'),
            'address' => config('posyandu.alamat'),
            'phone' => config('posyandu.telepon'),
            'whatsapp' => config('posyandu.whatsapp'),
            'email' => config('posyandu.email'),
            'map_query' => config('posyandu.peta_query'),
        ];

        $stored = SiteSetting::allValues();

        return collect($defaults)
            ->map(fn ($default, $key) => filled($stored[$key] ?? null) ? $stored[$key] : $default)
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public static function landing(): array
    {
        $content = config('posyandu');
        $content['galeri_items'] = collect($content['galeri'])
            ->map(fn (int $n) => ['url' => asset("assets/img/Fotos/Foto{$n}.jpg"), 'title' => null])
            ->all();

        $stored = self::safely(fn () => SiteSetting::allValues());
        if ($stored === null) {
            return $content;
        }

        $setting = fn (string $key, mixed $default) => filled($stored[$key] ?? null) ? $stored[$key] : $default;

        $content['tagline'] = $setting('tagline', $content['tagline']);
        $content['jadwal'] = $setting('schedule', $content['jadwal']);
        $content['tentang'] = $setting('about', $content['tentang']);
        $content['sasaran'] = $setting('target_audience', $content['sasaran']);
        $content['visi'] = $setting('vision', $content['visi']);
        $content['misi'] = $setting('mission', $content['misi']);
        $content['alamat'] = $setting('address', $content['alamat']);
        $content['telepon'] = $setting('phone', $content['telepon']);
        $content['whatsapp'] = $setting('whatsapp', $content['whatsapp']);
        $content['email'] = $setting('email', $content['email']);
        $content['peta_query'] = $setting('map_query', $content['peta_query']);

        $programs = self::safely(fn () => Program::published()->get());
        if ($programs?->isNotEmpty()) {
            $content['program'] = $programs
                ->map(fn (Program $p) => ['icon' => $p->icon, 'judul' => $p->title, 'teks' => $p->description])
                ->all();
        }

        $faqs = self::safely(fn () => Faq::published()->get());
        if ($faqs?->isNotEmpty()) {
            $content['faq'] = $faqs
                ->map(fn (Faq $f) => ['tanya' => $f->question, 'jawab' => $f->answer])
                ->all();
        }

        $gallery = self::safely(fn () => GalleryItem::published()->get());
        if ($gallery?->isNotEmpty()) {
            $content['galeri_items'] = $gallery
                ->map(fn (GalleryItem $g) => [
                    'url' => Storage::disk('public')->url($g->image_path),
                    'title' => $g->title,
                ])
                ->all();
        }

        $cadres = self::safely(fn () => Cadre::query()
            ->where('is_active', true)
            ->where('show_on_website', true)
            ->orderBy('sort_order')->orderBy('id')
            ->get());
        if ($cadres?->isNotEmpty()) {
            $content['organigram'] = self::organigram($cadres, $content['organigram']);
        }

        return $content;
    }

    /**
     * Shape cadres into the organigram structure used by the landing view.
     */
    private static function organigram($cadres, array $fallback): array
    {
        $node = fn (Cadre $c) => ['jabatan' => $c->position, 'nama' => $c->name, 'icon' => self::iconFor($c)];

        $leader = $cadres->firstWhere('tier', CadreTier::Leader);

        // Each tier falls back to the config default until cadres exist for it.
        $officers = $cadres->where('tier', CadreTier::Officer)->map($node)->values()->all();
        $sections = $cadres->where('tier', CadreTier::Section)->map($node)->values()->all();

        return [
            'ketua' => $leader ? ['jabatan' => $leader->position, 'nama' => $leader->name] : $fallback['ketua'],
            'pengurus' => $officers ?: $fallback['pengurus'],
            'seksi' => $sections ?: $fallback['seksi'],
        ];
    }

    private static function iconFor(Cadre $cadre): string
    {
        return $cadre->tier === CadreTier::Officer ? 'bi bi-person-badge' : 'bi bi-heart-pulse';
    }

    /**
     * Run a database read and return null if the database is unavailable.
     */
    private static function safely(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }
}
