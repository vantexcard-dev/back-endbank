<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\AccountsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nom de l\'utilisateur'),

                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->label('Prénom'),

                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->label('Nom de famille'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Adresse email'),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create') // Requis uniquement à la création
                    ->visibleOn('create')
                    ->label('Mot de passe'),

                Forms\Components\DatePicker::make('birth_date')
                    ->required()
                    ->label('Date de naissance'),

                Forms\Components\FileUpload::make('avatar')
                    ->image()
                    ->directory('avatars')
                    ->label('Avatar'),

                Forms\Components\FileUpload::make('passport_photo')
                    ->image()
                    ->directory('passports')
                    ->label('Photo de passeport'),

                Forms\Components\FileUpload::make('selfie')
                    ->image()
                    ->directory('selfies')
                    ->label('Selfie'),

                Forms\Components\TextInput::make('country')
                    ->required()
                    ->label('Pays'),

                Forms\Components\TextInput::make('address')
                    ->required()
                    ->label('Adresse'),

                Forms\Components\FileUpload::make('prof_address')
                    ->image()
                    ->directory('profaddress')
                    ->label('Justificatif Domicile'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable()
                    ->searchable()
                    ->label('Prénom'),

                Tables\Columns\TextColumn::make('last_name')
                    ->sortable()
                    ->searchable()
                    ->label('Nom de famille'),

                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Adresse email'),

                Tables\Columns\TextColumn::make('country')
                    ->sortable()
                    ->searchable()
                    ->label('Pays'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Date de création'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AccountsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}