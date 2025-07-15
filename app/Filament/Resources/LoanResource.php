<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Prêts';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $modelLabel = 'Prêt';
    protected static ?string $pluralModelLabel = 'Prêts';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Utilisateur')
                ->relationship('user', 'name')
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('amount')
                ->label('Montant (€)')
                ->numeric()
                ->required()
                ->prefix('€'),

            Forms\Components\TextInput::make('interest_rate')
                ->label("Taux d'intérêt (%)")
                ->numeric()
                ->required()
                ->suffix('%'),

            Forms\Components\TextInput::make('total_interest')
                ->label('Intérêts totaux (€)')
                ->numeric()
                ->required()
                ->prefix('€'),

            Forms\Components\TextInput::make('repayment_years')
                ->label('Durée (années)')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('payment_date')
                ->label('Date de paiement')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Statut')
                ->options([
                    'pending' => 'En attente',
                    'approved' => 'Approuvé',
                    'rejected' => 'Rejeté',
                    'paid' => 'Payé',
                ])
                ->required()
                ->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('interest_rate')
                    ->label('Taux (%)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_interest')
                    ->label('Intérêts')
                    ->money('EUR'),

                Tables\Columns\TextColumn::make('repayment_years')
                    ->label('Années'),

                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Date de paiement')
                    ->date('d/m/Y'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'gray' => 'paid',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'En attente',
                        'approved' => 'Approuvé',
                        'rejected' => 'Rejeté',
                        'paid' => 'Payé',
                        default => ucfirst($state),
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'approved' => 'Approuvé',
                        'rejected' => 'Rejeté',
                        'paid' => 'Payé',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit'   => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
