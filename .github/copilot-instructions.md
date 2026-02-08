# AI Copilot Instructions for Exo-Expanse

This is a Laravel 12 wedding invitation builder using Filament admin panel, Livewire 4 for reactive components, and theme-based customization system.

## Architecture Overview

**Core Domain**: Wedding invitations with customizable themes and guest management.

**Data Model**:
- `User` → owns multiple `Invitation`s
- `Invitation` → belongs to `Theme`, has many `Guest`s, `Wish`es, and `InvitationPhoto`s
- `Theme` → customizable styling with color, font, and layout configuration
- `Guest` → tracks RSVP status using `GuestStatus` enum

**Key Services**:
- [InvitationService](app/Services/InvitationService.php) — Create/update/delete invitations with slug auto-generation
- [GuestService](app/Services/GuestService.php) — Manage guest records
- [GuestImportService](app/Services/GuestImportService.php) — Bulk import from CSV

## Critical Workflows

**Start Development**: Run `composer run dev` — starts PHP server, queue listener, logs, and Vite bundler concurrently.

**Code Formatting**: Always run `vendor/bin/pint --dirty` before finalizing changes.

**Testing**:
- Run all: `php artisan test --compact`
- Single file: `php artisan test --compact tests/Feature/ExampleTest.php`
- Filter by name: `php artisan test --compact --filter=testName`

**Admin Panel Access**: Filament auto-discovers resources in `app/Filament/Resources/`. Users need `role = 'admin'` to access `/admin`.

## Theme Customization System

**Livewire 4 Theme Customizer** — Dynamic, real-time theme customization with CSS variables:
- [ThemeCustomizer](app/Livewire/ThemeCustomizer.php) — Livewire component for UI
- [ThemeBuilderService](app/Services/ThemeBuilderService.php) — CSS generation and validation
- `theme_customization` column stores colors, fonts, and custom CSS

**Usage**:
```blade
<livewire:theme-customizer :invitation="$invitation" />
```

**Model Methods**:
```php
$invitation->getThemeStyles();           // Get CSS with variables
$invitation->getThemeConfig();           // Export config for frontend
$invitation->updateThemeCustomization(); // Save customization
```

Full documentation in [THEME_CUSTOMIZATION.md](THEME_CUSTOMIZATION.md) and [THEME_QUICK_START.md](THEME_QUICK_START.md).

## Filament Patterns

Resources use organized schema structure:
- `Schemas/` — Form and Infolist configurations (e.g., [InvitationForm](app/Filament/Resources/Invitations/Schemas/InvitationForm.php))
- `Tables/` — Table column and action definitions
- `Pages/` — Create/Edit/View/List pages (auto-discovered)

**Conditional Fields**: Use `$get` parameter with `visible()` or `disabled()`:
```php
TextInput::make('music_url')
    ->visible(fn ($get) => $get('music_enabled'))
```

**Common Mistake**: Filament 5 namespaces changed — form fields in `Filament\Forms\Components`, layout in `Filament\Schemas\Components`, utilities in `Filament\Schemas\Components\Utilities`.

## Livewire Component Patterns

[InvitationPage](app/Livewire/InvitationPage.php) exemplifies reactive public views. Mount data in `mount()`, validate authorization, render with dynamic theme selection.

- Public routes render Livewire components directly: `Route::livewire('/i/{slug}', ...)`
- Use `->layout('layouts.guest', [])` to wrap components in custom layout

## Database & Models

Always use Eloquent relationships with explicit return types:
```php
public function guests(): HasMany {
    return $this->hasMany(Guest::class);
}
```

Use `casts()` method for type casting (not property), e.g., `casts()` returns array with `'event_date' => 'datetime'`.

Soft deletes enabled on `Invitation` — use `SoftDeletes` trait and `withTrashed()` when querying deleted records.

## Project-Specific Conventions

- **Slug Generation**: `InvitationService::generateUniqueSlug()` ensures unique, URL-safe invitation identifiers
- **Theme System**: Themes control layout via `view_file`, styling via color/font/width fields in database
- **Storage**: Public files stored via `Storage::disk('public')` — use `->visibility('public')` for Filament file uploads
- **Enums**: Status fields use enums (e.g., `GuestStatus`) — located in `app/Enums/`

## Frontend

**Vite + Tailwind**: `npm run dev` watches assets, `npm run build` for production.

If UI changes don't appear: User may need `npm run build` or `composer run dev` for Vite manifest update.

Use Alpine.js for interactivity; Livewire handles reactive state.

## Bootstrap Configuration

[bootstrap/app.php](bootstrap/app.php) configures middleware and routing — no separate Kernel.php in Laravel 12. Console commands auto-discovered from `app/Console/Commands/`.

## External Dependencies

Refer to [GEMINI.md](GEMINI.md) for package versions and Laravel Boost tool guidance (search-docs, tinker, database-query, browser-logs).
