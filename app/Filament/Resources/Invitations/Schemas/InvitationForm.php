<?php

namespace App\Filament\Resources\Invitations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InvitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Section::make('General Info')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('theme_id')
                            ->relationship('theme', 'name')
                            ->searchable()
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        DateTimePicker::make('event_date')
                            ->required(),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Content')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('content.groom_name')->label('Groom Name'),
                                TextInput::make('content.bride_name')->label('Bride Name'),
                                TextInput::make('content.groom_fullname')->label('Groom Full Name'),
                                TextInput::make('content.bride_fullname')->label('Bride Full Name'),
                            ]),
                        Textarea::make('content.groom_parents')->label('Groom Parents'),
                        Textarea::make('content.bride_parents')->label('Bride Parents'),
                    ])->collapsible(),

                \Filament\Forms\Components\Section::make('Events')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('content.akad_date')->type('date')->label('Akad Date'),
                                TextInput::make('content.akad_time')->type('time')->label('Akad Time'),
                                TextInput::make('content.akad_venue')->label('Akad Venue'),
                                TextInput::make('content.akad_address')->label('Akad Address'),
                            ]),
                        \Filament\Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('content.resepsi_date')->type('date')->label('Resepsi Date'),
                                TextInput::make('content.resepsi_time')->type('time')->label('Resepsi Time'),
                                TextInput::make('content.resepsi_venue')->label('Resepsi Venue'),
                                TextInput::make('content.resepsi_address')->label('Resepsi Address'),
                            ]),
                        TextInput::make('content.maps_url')->url()->label('Maps URL')->columnSpanFull(),
                    ])->collapsible(),

                \Filament\Forms\Components\Section::make('Settings')
                    ->schema([
                         \Filament\Forms\Components\Grid::make(2)
                            ->schema([
                                \Filament\Forms\Components\Toggle::make('is_published'),
                                \Filament\Forms\Components\Toggle::make('music_enabled'),
                                \Filament\Forms\Components\Toggle::make('gift_enabled'),
                                \Filament\Forms\Components\Toggle::make('rsvp_enabled'),
                                \Filament\Forms\Components\Toggle::make('countdown_enabled'),
                                \Filament\Forms\Components\Toggle::make('wishes_enabled'),
                            ]),
                         TextInput::make('music_url')->url()->label('Music URL')->visible(fn ($get) => $get('music_enabled')),
                    ])->collapsible(),

                \Filament\Forms\Components\Section::make('Gift Accounts')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('gift_accounts')
                            ->schema([
                                TextInput::make('bank')->required(),
                                TextInput::make('account_number')->required(),
                                TextInput::make('account_name')->required(),
                            ])
                            ->columns(3)
                            ->visible(fn ($get) => $get('gift_enabled')),
                    ])->collapsible(),
            ]);
    }
}
