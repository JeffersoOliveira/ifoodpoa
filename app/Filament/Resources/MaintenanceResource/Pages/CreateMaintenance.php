<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Enums\BikeStatusEnum;
use App\Filament\Resources\MaintenanceResource;
use App\Models\Bike;
use App\Models\ChecklistItem;
use App\Models\Maintenance;
use Arr;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mime\Part\Multipart\MixedPart;

class CreateMaintenance extends CreateRecord
{
    protected static string $resource = MaintenanceResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public Bike $bike;

    protected function beforeCreate(): void
    {

        // $checkList = new ChecklistItem();
        // $checkList['description'] = $this->data['check_list']['description'];
        // unset($this->data['check_list']['description']);
        // $checkList['check_list'] = json_encode($this->data['check_list']);
        // $checkList->save();

        // dd($checkList, $this->data);

        // $bike = Bike::find($this->data['bike_id']);
        // $checkList = $this->data['checkList']['check_list'];
        // $checkList = Arr::add($checkList, "statusAnterior", $bike->status);
        // $this->data['checkList']['check_list'] = $checkList;
        // array_push($checkList, "Disponível", 'teste');
        // NAO APAGAR
        // dd($this->data['checkList']['check_list'], $checkList);

        // dd($bike);
        // $bike['status'] = BikeStatusEnum::MAINTENANCE;
        // $bike->save();
    }

    protected function afterCreate(): void
    {
        // $maintenance = $this->record;

        // // Agora você tem o ID do registro recém-criado
        // $maintenanceId = $maintenance->id;

        // $checkList = new ChecklistItem();
        // $checkList['maintenance_id'] = $maintenanceId;
        // $checkList['description'] = $this->data['check_list']['description'];
        // unset($this->data['check_list']['description']);
        // $checkList['check_list'] = json_encode($this->data['check_list']);
        // $checkList->save();


        // Faça algo com o ID, como redirecionar para a página de edição:
        // redirect()->route('maintenance.edit', $maintenanceId);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attendant_id'] = auth()->id();

        if (
            !Maintenance::canSchedule(
                $data['maintenance_date'],
                $data['maintenance_time']
            )
        ) {

            Notification::make()
                ->title('Horário Indisponível')
                ->body('Já existem dois agendamentos para este horário.')
                ->danger()
                ->send();

            // Bloquear a criação
            $this->halt();
        }

        return $data;

    }

}
