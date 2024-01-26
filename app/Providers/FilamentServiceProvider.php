<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;
use Filament\Panel;

class FilamentServiceProvider extends ServiceProvider
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Settings')
                    ->url(UserResource::getUrl())
                    // ->icon('heroicon-o-cog-6-tooth'),
            ]);
        });

    }
}