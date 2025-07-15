<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Compte expéditeur
                Forms\Components\Select::make('sender_account_id')
                    ->relationship('senderAccount', 'iban')
                    ->label('Compte expéditeur')
                    ->required(),

                // Compte destinataire
                Forms\Components\Select::make('receiver_account_id')
                    ->relationship('receiverAccount', 'iban')
                    ->label('Compte destinataire')
                    ->required(),

                // Nom de l'expéditeur
                Forms\Components\TextInput::make('sender_name')
                    ->label('Nom de l\'expéditeur')
                    ->required(),

                // Nom du destinataire
                Forms\Components\TextInput::make('receiver_name')
                    ->label('Nom du destinataire')
                    ->required(),

                // IBAN expéditeur
                Forms\Components\TextInput::make('sender_iban')
                    ->label('IBAN expéditeur')
                    ->required(),

                // IBAN destinataire
                Forms\Components\TextInput::make('receiver_iban')
                    ->label('IBAN destinataire')
                    ->required(),

                // BIC expéditeur
                Forms\Components\TextInput::make('sender_bic')
                    ->label('BIC expéditeur'),

                // BIC destinataire
                Forms\Components\TextInput::make('receiver_bic')
                    ->label('BIC destinataire'),

                // Montant
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->label('Montant')
                    ->required(),

                // Devise
                Forms\Components\TextInput::make('currency')
                    ->label('Devise')
                    ->required(),

                // Type de transaction
                Forms\Components\Select::make('type')
                    ->options([
                        'transfer' => 'Transfert',
                        'deposit' => 'Dépôt',
                        'withdrawal' => 'Retrait',
                    ])
                    ->label('Type')
                    ->required(),

                // Statut de la transaction
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'completed' => 'Terminé',
                        'failed' => 'Échoué',
                    ])
                    ->label('Statut')
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // IBAN expéditeur
                Tables\Columns\TextColumn::make('senderAccount.iban')
                    ->label('IBAN expéditeur')
                    ->sortable()
                    ->searchable(),

                // IBAN destinataire
                Tables\Columns\TextColumn::make('receiverAccount.iban')
                    ->label('IBAN destinataire')
                    ->sortable()
                    ->searchable(),

                // Nom de l'expéditeur
                Tables\Columns\TextColumn::make('senderAccount.user.name')
                    ->label('Nom de l\'expéditeur')
                    ->sortable()
                    ->searchable(),

                // Nom du destinataire
                Tables\Columns\TextColumn::make('receiverAccount.user.name')
                    ->label('Nom du destinataire')
                    ->sortable()
                    ->searchable(),

                // Montant
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('eur'), // Affiche le montant en euros

                // Devise
                Tables\Columns\TextColumn::make('currency')
                    ->label('Devise'),

                // Type de transaction
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transfer' => 'primary',
                        'deposit' => 'success',
                        'withdrawal' => 'warning',
                    }),

                // Statut de la transaction
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                    }),

                // Date de création
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime(),
            ])
            ->filters([
                // Ajoutez des filtres ici si nécessaire
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}