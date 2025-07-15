<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExternalEmailResource\Pages;
use App\Models\ExternalEmail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExternalEmailResource extends Resource
{
    protected static ?string $model = ExternalEmail::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $modelLabel = 'Email externe';
    protected static ?string $navigationLabel = 'Emails externes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Destinataire')
                    ->schema([
                        Forms\Components\TextInput::make('recipient_email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email du destinataire'),
                            
                        Forms\Components\TextInput::make('recipient_name')
                            ->maxLength(255)
                            ->label('Nom du destinataire (optionnel)'),
                    ]),
                
                Forms\Components\Section::make('Contenu')
                    ->schema([
                        Forms\Components\Select::make('template')
                            ->label('Template')
                            ->options([
                                'general' => 'Général',
                                'invitation' => 'Invitation',
                                'custom' => 'Personnalisé',
                            ])
                            ->reactive(),
                            
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->label('Sujet'),
                            
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->label('Contenu'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('recipient_email')
                    ->searchable()
                    ->label('Email'),
                    
                Tables\Columns\TextColumn::make('recipient_name')
                    ->searchable()
                    ->label('Nom'),
                    
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->label('Sujet'),
                    
                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Envoyé le'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('Envoyer')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(fn (ExternalEmail $record) => $record->sendEmail()),
                    
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
            'index' => Pages\ManageExternalEmails::route('/'),
        ];
    }
}
