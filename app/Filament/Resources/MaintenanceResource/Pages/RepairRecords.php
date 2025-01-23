<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Enums\BikeStatusEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Filament\Resources\MaintenanceResource;
use App\Models\Maintenance;
use App\Models\Repair;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Pages\Actions\Action;

class RepairRecords extends Page
{
    protected static string $resource = MaintenanceResource::class;

    protected static string $view = 'filament.resources.maintenance-resource.pages.repair-records';

    public Maintenance $maintenance;

    public $itemsRepaired;

    public $items;

    public function mount($maintenanceId): void
    {

        // TODO: Revisar o preload
        $this->maintenance = Maintenance::findOrFail($maintenanceId);
        $this->itemsRepaired = $this->maintenance->checkList['check_list'];
        $this->items = $this->setItems($this->maintenance->checkList['check_list']);

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Toggle::make('items.freio')
                    ->label('Freio')
                    ->required()
                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
                    ->extraAttributes(fn($state) => [
                        'class' => $state ? 'bg-red-500' : 'bg-gray-400',
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        $this->items['freio'] = $state;
                        $set('itemsRepaired.freio', []);
                    }),

                CheckboxList::make('itemsRepaired.freio')
                    ->options([
                        'cabo_dianteiro' => 'Cabo Dian.',
                        'cabo_traseiro' => 'Cabo Tras.',
                        'pastilha_dianteiro' => 'Pastilha Dian.',
                        'pastilha_traseiro' => 'Pastilha Tras.',
                        'pinça_dianteiro' => 'Pinça Dian.',
                        'pinça_traseiro' => 'Pinça Tras.',
                        'conduite_dianteiro' => 'Conduite Dian.',
                        'conduite_traseiro' => 'Conduite Tras.',
                        'regulagem_traseiro' => 'Regulagem Freio Tras.',
                        'regulagem_dianteiro' => 'Regulagem Freio Dian.'
                    ])
                    ->label('')
                    ->required()
                    ->visible(fn($get) => $get('items.freio') === true) // Condicional com o estado do toggle_value
                    ->columns(2),

                Toggle::make('items.guidao')
                    ->label('Guidão')
                    ->required()
                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
                    ->extraAttributes(fn($state) => [
                        'class' => $state ? 'bg-red-500' : 'bg-gray-400',
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        $this->items['guidao'] = $state;
                        $set('itemsRepaired.guidao', []);
                    }),

                CheckboxList::make('itemsRepaired.guidao')
                    ->options([
                        'manete_ld' => 'Manete LD',
                        'manete_le' => 'Manete LE',
                        'manopla' => 'Manopla',
                        'revoshift' => 'Revoshift',
                        'avanco' => 'Avanço',
                        'guidao' => 'Guidão',
                        'campainha' => 'Campainha'
                    ])
                    ->label('Nome')
                    ->required()
                    ->visible(fn($get) => $get('items.guidao') === true) // Condicional com o estado do toggle_value
                    ->columns(2),

                Toggle::make('items.quadro')
                    ->label('Quadro')
                    ->required()
                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
                    ->extraAttributes(fn($state) => [
                        'class' => $state ? 'bg-red-500' : 'bg-gray-400',
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        $this->items['quadro'] = $state;
                        $set('itemsRepaired.quadro', ['quadro']);
                    }),

                Toggle::make('items.rodas')
                    ->label('Roda')
                    ->required()
                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
                    ->extraAttributes(fn($state) => [
                        'class' => $state ? 'bg-red-500' : 'bg-gray-400',
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        $this->items['rodas'] = $state;
                        $set('itemsRepaired.rodas', []);
                    }),

                CheckboxList::make('itemsRepaired.rodas')
                    ->options([
                        'motor' => 'Motor',
                        'engrenagem motor' => 'Engrenagem Motor',
                        'roda_montada' => 'Roda Montada',
                        'aro/lamina' => 'Aro/Lamina'
                    ])
                    ->extraAttributes([
                        'class' => 'px-6  ',
                    ])
                    ->label('')
                    ->required()
                    ->visible(fn($get) => $get('items.rodas') === true) // Condicional com o estado do toggle_value
                    ->columns(2),

                Toggle::make('sinalizaçao')
                    ->label('Sinalizaçao')
                    ->required()
                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
                    ->extraAttributes(fn($state) => [
                        'class' => $state ? 'bg-red-500' : 'bg-gray-400',
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        $this->sinalizaçao = $state;
                        $set('itemsRepaired.sinalizacao', ['sinalizacao']);
                    }),


                //                Toggle::make('cambio')
//                    ->label('Cambio')
//                    ->required()
//                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
//                    ->afterStateUpdated(function ($state, $set) {
//                        $this->rodas = $state;
//                        $set('cambioItem', []);
//                    }),



                Toggle::make('items.sistemaEletrico')
                    ->label('Sistema Elétrico')
                    ->required()
                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
                    ->extraAttributes(fn($state) => [
                        'class' => $state ? 'bg-red-500' : 'bg-gray-400',
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        $this->items['sistemaEletrico'] = $state;
                        $set('itemsRepaired.sistemaEletrico', []);
                    }),

                CheckboxList::make('itemsRepaired.sistemaEletrico')
                    ->options([
                        'painel' => 'Painel',
                        'modulo' => 'Modulo',
                        'chicote' => 'Chicote',
                        'sensor_pedal' => 'Sensor Pedal',
                        'disco_magnetico' => 'Disco Magnetico'
                    ])
                    ->label('')
                    ->required()
                    ->visible(fn($get) => $get('items.sistemaEletrico') === true) // Condicional com o estado do toggle_value

                    ->columns(2),

                Toggle::make('items.transmissao')
                    ->label('Transmissao')
                    ->required()
                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
                    ->extraAttributes(fn($state) => [
                        'class' => $state ? 'bg-red-500' : 'bg-gray-400',
                    ])
                    ->afterStateUpdated(function ($state, $set) {
                        $this->items['transmissao'] = $state;
                        $set('itemsRepaired.transmissao', []);
                    }),

                CheckboxList::make('itemsRepaired.transmissao')
                    ->options([
                        'pedivela' => 'Pedivela',
                        'cambio' => 'Cambio',
                        'cabo_cambio' => 'Cabo Cambio',
                        'cassete_7v' => 'Cassete 7v.',
                        'corrente' => 'Corrente'
                    ])
                    ->label('')
                    ->required()
                    ->visible(fn($get) => $get('items.transmissao') === true) // Condicional com o estado do toggle_value
                    ->columns(2),


//                Toggle::make('garfo')
//                    ->label('Garfo')
//                    ->required()
//                    ->reactive() // Reativo, assim a interface é atualizada ao mudar
//                    ->afterStateUpdated(function ($state, $set) {
//                        $this->roda = $state;
//                        $set('garfoItem', []);
//                    }),





                // Textarea::make('descricao')
                //     ->columnSpanFull()
                //     ->rows(7)
                //     ->extraAttributes([
                //         'class' => 'border border-gray-400',
                //     ]),

            ]);
    }

    public function submit()
    {

        //TODO: Atualizar data da finalizaçao da manutençao

        $this->maintenance->bike->update(['status' => $this->itemsRepaired['statusAnterior']]);
        $this->maintenance->update(['status' => MaintenanceStatusEnum::COMPLETED]);
        $this->maintenance->repair->update(['repaired' => $this->itemsRepaired]);

        // $bike = Bike::find($this->data['bike_id']);
        dd($this->itemsRepaired, json_encode($this->itemsRepaired));
    }

    protected function getActions(): array
    {
        return [
            Action::make('customButton')
                ->label('Iniciar Manutenção')
                ->visible(function ($record) {
                    return !$this->maintenance->repair()->exists();
                })
                ->action('startMaintenance'), // Chama o método definido abaixo
        ];
    }

    protected function setItems($check_list): array
    {
        $items = [];
        foreach ($check_list as $key => $value) {
            $items[$key] = false;
        }

        return $items;
    }

    public function startMaintenance()
    {
        // dd(auth()->id());
        $this->maintenance->update(['status' => MaintenanceStatusEnum::IN_PROGRESS]);
        $this->maintenance->repair()->updateOrCreate([
            'mechanic_id' => auth()->id(),
        ]);
        // Lógica executada ao clicar no botão
        Notification::make()
            ->title('Você iniciou a manutençao.')
            // ->body('Você clicou no botão.')
            ->success() // Define o tipo da notificação
            ->send();

        // dump($this->maintenance);
    }
}
