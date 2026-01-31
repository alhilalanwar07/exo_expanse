<?php

namespace App\Filament\Resources\Themes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ThemeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('view_file')
                    ->required()
                    ->maxLength(255),
                TextInput::make('thumbnail_url')
                    ->url()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_premium')
                    ->required(),
            ]);
    }
}
