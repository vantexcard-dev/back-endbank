<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsMessageResource\Pages;
use App\Models\SmsMessage;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SmsMessageResource extends Resource
{
    protected static ?string $model = SmsMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $modelLabel = 'Message SMS';
    protected static ?string $navigationLabel = 'Envoi de SMS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Destinataire')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Client existant')
                            ->options(User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->reactive(),
                            
                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->label('Numéro de téléphone')
                            ->rules(['regex:/^\+?[0-9\s\-\(\)]{10,}$/']),
                    ]),
                
                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->maxLength(1600)
                            ->label('Contenu du SMS')
                            ->hint(fn ($state) => strlen($state) . '/160 caractères')
                            ->reactive(),
                            
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Brouillon',
                                'pending' => 'En attente',
                                'sent' => 'Envoyé',
                                'failed' => 'Échec',
                            ])
                            ->default('draft')
                            ->disabled(fn ($context) => $context === 'edit'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->toggleable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Numéro')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->words(5)
                    ->tooltip(fn ($record) => $record->message),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Envoyé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Brouillon',
                        'pending' => 'En attente',
                        'sent' => 'Envoyé',
                        'failed' => 'Échec',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('Envoyer')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(fn (SmsMessage $record) => $record->sendSms())
                    ->hidden(fn (SmsMessage $record) => $record->status === 'sent'),
                    
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
            'index' => Pages\ListSmsMessages::route('/'),
            'create' => Pages\CreateSmsMessage::route('/create'),
            'edit' => Pages\EditSmsMessage::route('/{record}/edit'),
        ];
    }
}