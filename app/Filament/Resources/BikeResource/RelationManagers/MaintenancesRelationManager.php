<?php

namespace App\Filament\Resources\BikeResource\RelationManagers;

use App\Enums\BikeStatusEnum;
use App\Models\Bike;
use Arr;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenancesRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenances';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Ordem')
                        ->schema([
                            Select::make('bike_id')
                                ->relationship(
                                    'bike',
                                    'patrimony',
                                    function (Builder $query) {
                                        $query->where('status', '!=', BikeStatusEnum::MAINTENANCE);
                                    }
                                )
                                ->required(),

                            DatePicker::make('maintenance_date')
                                ->default(today())
                                ->required()
                                ->reactive()
                                //                                ->reactive()
                                /*->afterStateUpdated(function ($state, callable $set, $livewire) {
                                    $currentDate = Carbon::now();
                                    $selectedDate = Carbon::parse($state);
    //                                    dd($selectedDate,$currentDate,  $state, $livewire);
    //                                  // Verifica se a data selecionada é maior que uma semana a partir de agora
                                    if ($selectedDate->isAfter($currentDate->addWeek())) {
                                        $set('maintenance_date', null);
                                    }

    //                                    dd($selectedDate->format('Y-m-d'),$currentDate->format('Y-m-d'));
                                    if ($selectedDate->addMinute(1410)->isBefore(Carbon::now())) {
                                        $set('maintenance_date', null);
                                    }
                                })*/
                                ->displayFormat('H'),
                            TimePicker::make('maintenance_time')
                                ->label('Horário')
                                ->required()
                                ->format('H:i')
                                ->reactive()
                                ->minutesStep(30)
                                ->rules(function ($get) {
                                    return [
                                        'required',
                                        'after_or_equal:09:00', // Horário mínimo
                                        'before_or_equal:15:30', // Horário máximo
                                    ];
                                })
                                ->validationMessages([
                                    'required' => 'O horário é obrigatório.',
                                    'after_or_equal' => 'O horário deve ser a partir das 09:00.',
                                    'before_or_equal' => 'O horário deve ser até 15:30.',
                                ])
                                ->afterStateUpdated(function ($state, $set, $get, $component) {

                                    // dd($component);
                                    // $date = $get('maintenance_date'); // Obtém a data do formulário
                                    // $set('error', 'Já existem dois agendamentos para este horário.');
                                    // dd(Maintenance::canSchedule($date, $state));
                                    // if ($date && !Maintenance::canSchedule($date, $state)) {
                                    //     // dd($date, $state);
                                    //     $set('error', 'Já existem dois agendamentos para este horário.');
                                    // }
                                }),

                            Select::make('type')
                                ->options([
                                    'preventive' => 'Preventiva',
                                    'corrective' => 'Corretiva'
                                ])
                                ->native(false)
                                ->required()
                                ->columnSpanFull(),
                            TextInput::make('status')
                                ->required()
                                ->maxLength(255)
                                ->hidden()
                                ->default('pending'),
                        ]),
                    Step::make('Check List')
                        ->schema([
                            Group::make([
                                // Toggle::make('check_list.cambio'),
                                Toggle::make('check_list.freio'),
                                Toggle::make('check_list.guidao'),
                                // Toggle::make('check_list.garfo'),
                                // Toggle::make('check_list.guidao'),
                                Toggle::make('check_list.quadro'),
                                Toggle::make('check_list.rodas'),
                                Toggle::make('check_list.sinalizaçao'),
                                Toggle::make('check_list.sistemaEletrico'),
                                Toggle::make('check_list.transmissao'),
                                Textarea::make('description')
                                    ->columnSpanFull()
                                    ->required(),
                            ])
                                ->columns(2)
                                ->relationship('checkList')
                                ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $get): array {
                                    $bike = Bike::find($get('bike_id'));



                                    $data['check_list'] = Arr::add(
                                        $data['check_list'],
                                        "statusAnterior",
                                        $bike->status
                                    );

                                    // dd($bike, $data);

                                    $bike->update(['status' => BikeStatusEnum::MAINTENANCE]);

                                    return $data;
                                }),

                        ])
                        ->columnSpanFull(),
                ]),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('descriptionS')
            ->columns([
                TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bike.patrimony')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('attendant.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('maintenance_date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('maintenance_time')
                    ->time('H:i'),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('view')
                    ->url(fn($record) => route('filament.admin.resources.maintenance.view', ['maintenance' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(function ($record) {

                        // if ($record->repair?->mechanic_id === auth()->id() and $record->status != MaintenanceStatusEnum::COMPLETED) {
                        //     return true;
                        // }

                        return $record->repair()->exists();
                    }),
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(function ($record) {
                    return !$record->repair()->exists();
                }),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
