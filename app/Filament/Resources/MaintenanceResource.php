<?php

namespace App\Filament\Resources;

use App\Enums\BikeStatusEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Filament\Resources\MaintenanceResource\Pages;
use App\Models\Bike;
use App\Models\Maintenance;
use App\Services\Traits\CanPermissionTrait;
use Arr;
use Carbon\Carbon;
use Filament\Forms;
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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MaintenanceResource extends Resource
{
    use CanPermissionTrait;

    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    // protected static ?string $navigationGroup = 'Configuraçoes';
    protected static ?string $slug = 'maintenance';
    protected static ?string $label = 'Manutenção';
    protected static ?string $navigationLabel = 'Manutenções';
    protected static ?string $pluralLabel = 'Manutenções';

    public static function form(Form $form): Form
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
                                ->default("09:00")
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

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('maintenance_date', 'desc')
            ->paginationPageOptions([10, 25, 50])
            ->columns([
                TextColumn::make('bike.patrimony')
                    ->numeric()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->sortable()
                    ->toggleable()
                    ->dateTime('d/m/Y  H:i'),
                TextColumn::make('attendant.name')
                    ->label('Criado por')
                    ->numeric(),
                TextColumn::make('maintenance_date')
                    ->label('Dia')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('maintenance_time')
                    ->label('Hora')
                    ->time('H:i'),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\Filter::make('maintenance_date')
                    ->label('Data')
                    ->form([
                        Forms\Components\DatePicker::make('maintenance_date')
                            ->date()
                            // ->default(today())
                            ->label('Data'),
                    ])
                    ->query(
                        fn($query, $data) =>
                        $query->when(
                            $data['maintenance_date'] ?? null,
                            fn($q) =>
                            $q->where('maintenance_date', $data['maintenance_date'])
                        )
                    )
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['maintenance_date']) {
                            return null;
                        }
                        //TODO: formatar da data
                        return 'Agendado para: '. $data['maintenance_date'];
                    }),

                //
            ])
            ->actions([

                EditAction::make()
                    ->visible(function ($record) {
                        return (!auth()->user()->hasAnyRoles('Mechanic') &&
                            !$record->repair()->exists());
                    }),


                Action::make('view')
                    ->url(fn($record) => route('filament.admin.resources.maintenance.view', ['maintenance' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(function ($record) {

                        // if ($record->repair?->mechanic_id === auth()->id() and $record->status != MaintenanceStatusEnum::COMPLETED) {
                        //     return true;
                        // }

                        return $record->repair()->exists();
                    }),

                Action::make('Manutençao')
                    ->url(fn($record) => route('filament.admin.resources.maintenance.repairrecords', ['maintenanceId' => $record->id]))
                    ->openUrlInNewTab() // (Opcional) Abre em uma nova aba
                    ->icon('heroicon-o-arrow-right') // Define o ícone do botão
                    ->color('primary')
                    ->visible(function ($record) {

                        if (auth()->user()->hasAnyRoles('Mechanic') and $record->status !== MaintenanceStatusEnum::COMPLETED) {
                            return true;
                        }

                        return false;
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
            'view' => Pages\ViewMaintenance::route('/{maintenance}'),
            'repairrecords' => Pages\RepairRecords::route('/{maintenanceId}/repairrecords'),
        ];
    }

}
