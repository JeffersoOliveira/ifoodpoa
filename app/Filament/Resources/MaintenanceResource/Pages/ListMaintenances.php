<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Enums\MaintenanceStatusEnum;
use App\Filament\Resources\MaintenanceResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMaintenances extends ListRecords
{
    protected static string $resource = MaintenanceResource::class;

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

        foreach (MaintenanceStatusEnum::cases() as $status) {
            $tabs[$status->value] = Tab::make()
                ->label($status->getLabel())
                ->modifyQueryUsing(function (Builder $query) use ($status) {
                    $query->where('status', $status->value);
                });
            // ->view('filament.resources.tabs.' . strtolower($status->name));
        }

        return $tabs;
    }
}
