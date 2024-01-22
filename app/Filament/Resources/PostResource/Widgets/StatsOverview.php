<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    // protected static string $view = 'filament.resources.post-resource.widgets.stats-overview';

    protected function getCards(): array
    {
        return [
            Card::make('All Post', Post::all()->count())
            ->description('All Post'),
            // ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Card::make('Publish', Post::where('status', true)->count()),
            Card::make('Draft', Post::where('status', false)->count()),
        ];
    }
}
