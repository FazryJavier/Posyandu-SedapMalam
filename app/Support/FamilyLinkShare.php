<?php

namespace App\Support;

use App\Models\Household;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Helpers for handing a family its private progress link: QR code and WhatsApp message.
 * Everything is generated locally, so the token never reaches a third-party service.
 */
class FamilyLinkShare
{
    public static function qrSvg(string $url, int $size = 240): string
    {
        $writer = new Writer(new ImageRenderer(new RendererStyle($size, 1), new SvgImageBackEnd));

        // Drop the XML prolog so the SVG can be embedded inline in HTML.
        return trim(preg_replace('/^<\?xml[^>]*\?>\s*/', '', $writer->writeString($url)));
    }

    public static function whatsappUrl(Household $household, string $url): string
    {
        $text = 'Assalamualaikum, berikut link pribadi untuk melihat perkembangan anak di Posyandu Sedap Malam: '
            .$url.' (mohon jangan dibagikan ke orang lain).';

        $number = self::internationalNumber($household->phone);

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }

    /**
     * Convert a local Indonesian number (08xx...) to the international form wa.me expects.
     */
    public static function internationalNumber(?string $phone): string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);

        return match (true) {
            $digits === '' => '',
            str_starts_with($digits, '0') => '62'.substr($digits, 1),
            str_starts_with($digits, '62') => $digits,
            default => $digits,
        };
    }
}
