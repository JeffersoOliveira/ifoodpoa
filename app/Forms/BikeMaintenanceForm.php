namespace App\Forms;

use App\Models\Bike;
use App\Models\Maintenance;
use App\Enums\BikeStatusEnum;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class BikeMaintenanceForm
{
    /**
     * Retorna apenas o schema do formulário
     */
    public static function schema(): array
    {
        return [
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
                            ]),

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
                            Toggle::make('check_list.freio'),
                            Toggle::make('check_list.guidao'),
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

                                $bike->update(['status' => BikeStatusEnum::MAINTENANCE]);

                                return $data;
                            }),
                    ])
                    ->columnSpanFull(),
            ]),
        ];
    }
}
