<?php

namespace App\Filament\Resources\BikeResource\Pages;

use App\Enums\BikeStatusEnum;
use App\Filament\Resources\BikeResource;
use App\Models\Bike;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBikes extends ListRecords
{
    protected static string $resource = BikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'todos' => Tab::make()
                ->label('Todos')
        ];

        foreach (BikeStatusEnum::cases() as $status) {
            $tabs[$status->value] = Tab::make()
                ->label($status->getLabel())
                ->badge(Bike::where('status', $status->value)->count())
                ->badgeColor($status->getColor())
                ->modifyQueryUsing(function (Builder $query) use ($status) {
                    $query->where('status', $status->value);
                });
            // ->view('filament.resources.tabs.' . strtolower($status->name));
        }

        return $tabs;
    }

}
