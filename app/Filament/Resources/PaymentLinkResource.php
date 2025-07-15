<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentLinkResource\Pages;
use App\Models\PaymentLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentLinkResource extends Resource
{
    protected static ?string $model = PaymentLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->label('Utilisateur')
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->label('Montant')
                    ->required(),

                Forms\Components\TextInput::make('token')
                    ->label('Token')
                    ->default(fn () => \Illuminate\Support\Str::random(32))
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'paid' => 'Payé',
                        'expired' => 'Expiré',
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

                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('eur'),

                Tables\Columns\TextColumn::make('token')
                    ->label('Token')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'expired' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentLinks::route('/'),
            'create' => Pages\CreatePaymentLink::route('/create'),
            'edit' => Pages\EditPaymentLink::route('/{record}/edit'),
        ];
    }
}
