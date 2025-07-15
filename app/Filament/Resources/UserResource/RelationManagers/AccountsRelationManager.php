<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'accounts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('iban')
                    ->label('IBAN')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('bic')
                    ->label('BIC')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('balance')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('currency')
                    ->label('Devise')
                    ->required()
                    ->maxLength(10),

                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'pending'  => 'Pending',
                        'locked'   => 'Locked',
                        'deleted'  => 'Deleted',
                        'valid'    => 'Valid',
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('iban')
            ->columns([
                Tables\Columns\TextColumn::make('iban'),
                Tables\Columns\TextColumn::make('bic'),
                Tables\Columns\TextColumn::make('balance')
                    ->money(fn ($record) => $record->currency),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning'   => 'pending',
                        'danger'    => 'locked',
                        'danger'    => 'deleted',
                        'success'   => 'valid',
                        'success'   => 'active',
                        'secondary' => 'inactive',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
