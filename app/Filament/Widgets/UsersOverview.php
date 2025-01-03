<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends BaseWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::count())
                ->description('Admin'),
            // Stat::make('Users', User::count())
            //     ->description('Admin'),

            // Stat::make('Users', User::count())
            //     ->description('32k increase'),

            // Stat::make('Users', User::count())
            //     ->description('32k increase'),
        ];
    }
}
