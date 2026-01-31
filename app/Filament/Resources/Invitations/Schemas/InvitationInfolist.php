<?php

namespace App\Filament\Resources\Invitations\Schemas;

use App\Models\Invitation;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InvitationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('theme.name')
                    ->label('Theme'),
                TextEntry::make('slug'),
                TextEntry::make('title'),
                TextEntry::make('event_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('settings')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Invitation $record): bool => $record->trashed()),
            ]);
    }
}
