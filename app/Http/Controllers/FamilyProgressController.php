<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Support\FamilyProgress;
use App\Support\SiteContent;
use Illuminate\Http\Response;

/**
 * Private, login-free page where a family follows its children's growth.
 * Access is granted by the secret token in the URL (see Household::generateAccessToken()).
 */
class FamilyProgressController extends Controller
{
    public function show(string $token): Response
    {
        $household = Household::findByAccessToken($token);

        // Unknown, revoked and soft-deleted households all look the same: a plain 404.
        abort_if($household === null, 404);

        $household->forceFill(['last_viewed_at' => now()])->saveQuietly();

        $site = SiteContent::landing();

        return response()
            ->view('family.progress', [
                'progress' => FamilyProgress::for($household),
                'posyandu' => $site,
            ])
            ->withHeaders([
                'X-Robots-Tag' => 'noindex, nofollow, noarchive',
                'Cache-Control' => 'no-store, private, max-age=0',
                'Referrer-Policy' => 'no-referrer',
            ]);
    }
}
