<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChequeResource\Pages;
use App\Models\Cheque;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class ChequeResource extends Resource
{
    protected static ?string $model = Cheque::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->label('Utilisateur')
                    ->required(),

                Forms\Components\TextInput::make('cheque_number')
                    ->label('Numéro de Chèque')
                    ->unique(ignoreRecord: true)
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'cleared' => 'Encaisse',
                        'cancelled' => 'Annulé',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Utilisateur')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('cheque_number')
                    ->label('Numéro de Chèque')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->sortable()
                    ->money('EUR'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'cleared' => 'success',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('encashed_at')
                    ->label('Date d\'encaissement')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Ajouter l'action pour encaisser le chèque
                Action::make('encash')
                    ->label('Encaisser')
                    ->action(function (Cheque $record) {
                        $record->encash();
                        return redirect()->to('/admin/cheques');
                    })
                    ->color('success')
                    ->requiresConfirmation() // Confirmer l'action
                    ->icon('heroicon-o-check-circle'), // Icône de l'action
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCheques::route('/'),
            'create' => Pages\CreateCheque::route('/create'),
            'edit' => Pages\EditCheque::route('/{record}/edit'),
        ];
    }
}
