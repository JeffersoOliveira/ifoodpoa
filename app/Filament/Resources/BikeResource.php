<?php

namespace App\Filament\Resources;

use App\Enums\BikeStatusEnum;
use App\Filament\Resources\BikeResource\Pages;
use App\Filament\Resources\BikeResource\RelationManagers\MaintenancesRelationManager;
use App\Models\Bike;
use App\Services\Traits\CanPermissionTrait;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;

class BikeResource extends Resource
{
    use CanPermissionTrait;

    protected static ?string $model = Bike::class;
    protected static ?string $slug = 'bikes';
    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';
    // protected static ?string $navigationGroup = 'Configuraçoes';
    protected static ?string $label = 'Bike';
    protected static ?string $navigationLabel = 'Bikes';
    protected static ?string $pluralLabel = 'Bikes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('patrimony')
                    ->label('Patrimônio')
                    ->required()
                    ->maxLength(255),
                // Select::make('status')
                //     ->label('Status')
                //     ->options(BikeStatusEnum::class)
                //     ->native(false)
                //     ->required(),
                Select::make('status')
                ->label('Status')
                ->options(BikeStatusEnum::class)
                ->native(false)
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    $set('show_falta_pecas', $state === BikeStatusEnum::MISSING_PARTS->value);
                    logger()->info(
                        'Valor de show_falta_pecas:',
                        [$get('show_falta_pecas')]
                    );
                    logger()->info(
                        'Valor de state:',
                        [$state, BikeStatusEnum::MISSING_PARTS->value]
                    );
                    logger()->info(
                        'Valor de state:',
                        [$state === BikeStatusEnum::MISSING_PARTS ]
                    );
                }),

                TextInput::make('series')
                    ->label('Nº de Série')
                    ->required()
                    ->maxLength(255),

                Hidden::make('show_falta_pecas')
                    ->default(false),


                Repeater::make('itemsFaltaPeças')
                    ->label('Detalhes da Falta de Peças')
                    ->hidden(function($get){
                       return !$get('show_falta_pecas');
                    })
                    ->schema([
                        Select::make('check_list')
                            ->label('Peças')
                            ->default('freio')
                            ->options([
                                'freio' => 'Freio',
                                'guidao' => 'Guidão',
                                'quadro' => 'Quadro',
                                'rodas' => 'Rodas',
                                'sinalizaçao' => 'Sinalização',
                                'sistemaEletrico' => 'Sistema Elétrico',
                                'transmissao' => 'Transmissão',
                            ]),
                        TextInput::make('name')->required()
                            ->default('teste'),
                    ])
                    ->columnSpanFull()
                    ->addActionLabel('Adicionar Peças')
                    ->addActionAlignment(Alignment::Start)
                    ->columns(2),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patrimony')
                    ->label('Patrimônio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->badge()
                    ->label('Status')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('series')
                    ->label('Nº de Série')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MaintenancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBikes::route('/'),
            'create' => Pages\CreateBike::route('/create'),
            'edit' => Pages\EditBike::route('/{record}/edit'),
            'delete' => Pages\DeleteBike::route('/{record}/delete'),
        ];
    }
}
