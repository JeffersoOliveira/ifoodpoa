<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Services\Traits\CanPermissionTrait;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use CanPermissionTrait;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Configuraçoes';
    protected static ?string $slug = 'users';
    protected static ?string $label = 'Usuário';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $pluralLabel = 'Usuários';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Select::make('roles')
                    ->multiple()
                    ->preload()
                    ->optionsLimit(3)
                    ->relationship('roles', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copiado'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    //    public static function getRelations(): array
//    {
//        return [
//            //
//        ];
//    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'delete' => Pages\DeleteUser::route('/{record}/delete'),
        ];
    }
}
