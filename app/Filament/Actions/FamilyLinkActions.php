<?php

namespace App\Filament\Actions;

use App\Models\Household;
use App\Support\FamilyLinkShare;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

/**
 * Admin actions to hand a family its private progress link.
 */
class FamilyLinkActions
{
    /**
     * @return array<int, Action|ActionGroup>
     */
    public static function all(bool $compact = false): array
    {
        return [
            self::share($compact),
            ActionGroup::make([self::regenerate(), self::revoke()])
                ->icon(Heroicon::OutlinedEllipsisVertical)
                ->label('Kelola link')
                ->hiddenLabel(),
        ];
    }

    public static function share(bool $compact = false): Action
    {
        $action = Action::make('shareFamilyLink')
            ->tooltip('Link keluarga')
            ->label('Link keluarga')
            ->icon(Heroicon::OutlinedQrCode)
            ->color('info')
            ->authorize('update')
            ->modalHeading('Link pribadi keluarga')
            ->modalWidth('lg')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            // Create the link the first time it is opened.
            ->mountUsing(function (Household $record): void {
                if (! $record->access_token) {
                    $record->generateAccessToken();
                }
            })
            ->modalContent(function (Household $record) {
                $record->refresh();
                $url = $record->accessUrl();

                return view('filament.households.share-link', [
                    'url' => $url,
                    'qr' => FamilyLinkShare::qrSvg($url),
                    'whatsapp' => FamilyLinkShare::whatsappUrl($record, $url),
                    'hasPhone' => filled($record->phone),
                    'lastViewed' => $record->last_viewed_at,
                ]);
            });

        return $compact ? $action->iconButton() : $action;
    }

    public static function regenerate(): Action
    {
        return Action::make('regenerateFamilyLink')
            ->label('Buat ulang link')
            ->icon(Heroicon::OutlinedArrowPath)
            ->authorize('update')
            ->requiresConfirmation()
            ->modalHeading('Buat ulang link keluarga?')
            ->modalDescription('Link yang sudah dibagikan langsung tidak berlaku lagi. Berikan link baru kepada keluarga.')
            ->action(function (Household $record): void {
                $record->generateAccessToken();

                Notification::make()->title('Link baru dibuat')->success()->send();
            });
    }

    public static function revoke(): Action
    {
        return Action::make('revokeFamilyLink')
            ->label('Cabut akses')
            ->icon(Heroicon::OutlinedNoSymbol)
            ->color('danger')
            ->authorize('update')
            ->visible(fn (Household $record) => filled($record->access_token))
            ->requiresConfirmation()
            ->modalHeading('Cabut akses keluarga?')
            ->modalDescription('Link yang sudah dibagikan tidak dapat dibuka lagi sampai link baru dibuat.')
            ->action(function (Household $record): void {
                $record->revokeAccessToken();

                Notification::make()->title('Akses dicabut')->success()->send();
            });
    }
}
