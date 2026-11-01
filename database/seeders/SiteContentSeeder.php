<?php

namespace Database\Seeders;

use App\Models\Cadre;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Support\SiteContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Loads the default website content from config/posyandu.php into the CMS tables
 * so editors can start from the existing text. Safe to re-run: existing rows are kept.
 */
class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteContent::formDefaults() as $key => $value) {
            if (SiteSetting::where('key', $key)->doesntExist()) {
                SiteSetting::put($key, $value);
            }
        }

        if (Program::count() === 0) {
            foreach (config('posyandu.program') as $index => $program) {
                Program::create([
                    'icon' => $program['icon'],
                    'title' => $program['judul'],
                    'description' => $program['teks'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        if (Faq::count() === 0) {
            foreach (config('posyandu.faq') as $index => $faq) {
                Faq::create([
                    'question' => $faq['tanya'],
                    'answer' => $faq['jawab'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        if (GalleryItem::count() === 0) {
            $disk = Storage::disk('public');

            foreach (config('posyandu.galeri') as $index => $number) {
                $source = public_path("assets/img/Fotos/Foto{$number}.jpg");
                if (! File::exists($source)) {
                    continue;
                }

                $path = "gallery/Foto{$number}.jpg";
                $disk->put($path, File::get($source), 'public');

                GalleryItem::create(['image_path' => $path, 'sort_order' => $index + 1]);
            }
        }

        $leader = config('posyandu.organigram.ketua');
        if ($leader && filled($leader['nama']) && Cadre::count() === 0) {
            Cadre::create([
                'name' => $leader['nama'],
                'position' => $leader['jabatan'],
                'tier' => 'leader',
                'sort_order' => 1,
            ]);
        }
    }
}
