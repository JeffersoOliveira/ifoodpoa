<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Filament\Resources\MaintenanceResource;
use App\Models\Maintenance;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;

class ViewMaintenance extends Page
{
    protected static string $resource = MaintenanceResource::class;

    protected static string $view = 'filament.resources.maintenance-resource.pages.view-maintenance';


    public Maintenance $maintenance;


    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\EditAction::make(),
    //     ];
    // }
}
