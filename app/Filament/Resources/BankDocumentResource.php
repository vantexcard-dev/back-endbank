<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankDocumentResource\Pages;
use App\Models\BankDocument;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class BankDocumentResource extends Resource
{
    protected static ?string $model = BankDocument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Document bancaire';
    protected static ?string $navigationLabel = 'Documents bancaires';
    protected static ?string $navigationGroup = 'Documents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de base')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Client')
                            ->options(User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->columnSpan(2),
                            
                        Forms\Components\Select::make('document_type')
                            ->label('Type de document')
                            ->options([
                                'loan_agreement' => 'Contrat de prêt',
                                'anti_money_laundering' => 'Certificat de non blanchiment',
                                'payment_proof' => 'Preuve de paiement',
                                'account_statement' => 'Relevé de compte',
                                'balance_certificate' => 'Certificat de solde',
                            ])
                            ->required()
                            ->live()
                            ->columnSpan(2),
                            
                        Forms\Components\Select::make('language')
                            ->label('Langue du document')
                            ->options([
                                'fr' => 'Français',
                                'en' => 'English',
                                'es' => 'Español',
                                'de' => 'Deutsch',
                            ])
                            ->default('fr')
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->columns(6),
                    
                // Section pour Contrat de prêt
                Forms\Components\Section::make('Détails du prêt')
                    ->schema([
                        Forms\Components\TextInput::make('metadata.amount')
                            ->numeric()
                            ->required()
                            ->minValue(100)
                            ->label('Montant du prêt (€)'),
                            
                        Forms\Components\TextInput::make('metadata.duration')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(360)
                            ->label('Durée (mois)'),
                            
                        Forms\Components\TextInput::make('metadata.rate')
                            ->numeric()
                            ->required()
                            ->minValue(0.1)
                            ->maxValue(20)
                            ->step(0.1)
                            ->label('Taux d\'intérêt (%)'),
                            
                        Forms\Components\TextInput::make('metadata.monthly_payment')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->label('Mensualité (€)')
                            ->prefix('€'),
                            
                        Forms\Components\DatePicker::make('metadata.start_date')
                            ->required()
                            ->minDate(now()->subYear())
                            ->maxDate(now()->addYear())
                            ->label('Date de début'),
                            
                        Forms\Components\Textarea::make('metadata.special_conditions')
                            ->label('Conditions particulières')
                            ->columnSpanFull(),
                    ])
                    ->hidden(fn ($get) => $get('document_type') !== 'loan_agreement')
                    ->columns(3),
                    
                // Section pour Certificat de non blanchiment
                Forms\Components\Section::make('Certificat de non blanchiment')
                    ->schema([
                        Forms\Components\DatePicker::make('metadata.certification_date')
                            ->required()
                            ->label('Date de certification'),
                            
                        Forms\Components\TextInput::make('metadata.certification_number')
                            ->required()
                            ->label('Numéro de certification'),
                            
                        Forms\Components\Select::make('metadata.certification_type')
                            ->options([
                                'initial' => 'Certificat initial',
                                'renewal' => 'Renouvellement',
                                'exceptional' => 'Vérification exceptionnelle'
                            ])
                            ->required()
                            ->label('Type de certification'),
                            
                        Forms\Components\Textarea::make('metadata.declaration')
                            ->label('Déclaration')
                            ->default('Je déclare sur l\'honneur que les fonds proviennent de sources légitimes.')
                            ->columnSpanFull(),
                            
                        Forms\Components\Toggle::make('metadata.verified')
                            ->label('Vérification complétée')
                            ->required(),
                    ])
                    ->hidden(fn ($get) => $get('document_type') !== 'anti_money_laundering')
                    ->columns(2),
                    
                // Section pour Preuve de paiement
                Forms\Components\Section::make('Détails du paiement')
                    ->schema([
                        Forms\Components\TextInput::make('metadata.payment_amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->label('Montant payé (€)'),
                            
                        Forms\Components\DatePicker::make('metadata.payment_date')
                            ->required()
                            ->label('Date de paiement')
                            ->default(now())
                            ->maxDate(now()),
                            
                        Forms\Components\Select::make('metadata.payment_method')
                            ->options([
                                'transfer' => 'Virement bancaire',
                                'check' => 'Chèque',
                                'cash' => 'Espèces',
                                'card' => 'Carte bancaire',
                                'other' => 'Autre'
                            ])
                            ->required()
                            ->label('Méthode de paiement')
                            ->default('transfer'),
                            
                        Forms\Components\TextInput::make('metadata.transaction_reference')
                            ->label('Référence de transaction'),
                            
                        Forms\Components\Textarea::make('metadata.payment_notes')
                            ->label('Notes additionnelles')
                            ->columnSpanFull(),
                    ])
                    ->hidden(fn ($get) => $get('document_type') !== 'payment_proof')
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('document_type_name')
                    ->label('Type de document')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Contrat de prêt' => 'primary',
                        'Certificat de non blanchiment' => 'warning',
                        'Preuve de paiement' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('language')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->color(fn ($state) => match($state) {
                        'fr' => 'blue',
                        'en' => 'red',
                        'es' => 'green',
                        'de' => 'yellow',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('file_path')
                    ->label('Statut')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->label('Généré'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('document_type')
                    ->options([
                        'loan_agreement' => 'Contrat de prêt',
                        'anti_money_laundering' => 'Certificat de non blanchiment',
                        'payment_proof' => 'Preuve de paiement',
                        'account_statement' => 'Relevé de compte',
                        'balance_certificate' => 'Certificat de solde',
                    ]),
                    
                Tables\Filters\SelectFilter::make('language')
                    ->options([
                        'fr' => 'Français',
                        'en' => 'English',
                        'es' => 'Español',
                        'de' => 'Deutsch',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('generate')
                    ->label('Générer')
                    ->icon('heroicon-o-document-text')
                    ->action(function (BankDocument $record) {
                        try {
                            $path = $record->generateDocument();
                            
                            Notification::make()
                                ->title('Document généré')
                                ->body('Le document a été créé avec succès')
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Erreur')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                                
                            throw $e;
                        }
                    })
                    ->hidden(fn (BankDocument $record) => !empty($record->file_path))
                    ->visible(fn (BankDocument $record) => in_array($record->document_type, [
                        'loan_agreement', 
                        'anti_money_laundering', 
                        'payment_proof'
                    ])),
                    
                Tables\Actions\Action::make('download')
                    ->label('Télécharger')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (BankDocument $record) => Storage::url($record->file_path))
                    ->openUrlInNewTab()
                    ->hidden(fn (BankDocument $record) => empty($record->file_path)),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankDocuments::route('/'),
            'create' => Pages\CreateBankDocument::route('/create'),
            'edit' => Pages\EditBankDocument::route('/{record}/edit'),
        ];
    }
}