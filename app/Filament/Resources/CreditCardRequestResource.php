<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditCardRequestResource\Pages;
use App\Filament\Resources\CreditCardRequestResource\RelationManagers;
use App\Models\CreditCardRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditCardRequestResource extends Resource
{
    protected static ?string $model = CreditCardRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->label('Utilisateur')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->options([
                        'virtuelle' => 'Virtuelle',
                        'physique' => 'Physique',
                    ])
                    ->label('Type de carte')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
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
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Utilisateur')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type de carte')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'virtuelle' => 'info',
                        'physique' => 'primary',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de demande')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approuver')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (CreditCardRequest $record) {
                        $record->update(['status' => 'approved']);
                    }),
                    Tables\Actions\Action::make('reject')
                    ->label('Rejeter')
                    ->icon('heroicon-o-x-circle') // ✅ Correction ici
                    ->color('danger')
                    ->action(function (CreditCardRequest $record) {
                        $record->update(['status' => 'rejected']);
                    }),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        // Comptez le nombre de demandes en attente
        return static::getModel()::where('status', 'pending')->count();
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
            'index' => Pages\ListCreditCardRequests::route('/'),
            'create' => Pages\CreateCreditCardRequest::route('/create'),
            'edit' => Pages\EditCreditCardRequest::route('/{record}/edit'),
        ];
    }
}