<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerEmailResource\Pages;
use App\Models\CustomerEmail;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerEmailResource extends Resource
{
    protected static ?string $model = CustomerEmail::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $modelLabel = 'Email client';
    protected static ?string $navigationLabel = 'Emails aux clients';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Destinataire')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Client')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
                
                Forms\Components\Section::make('Contenu')
                    ->schema([
                        Forms\Components\Select::make('template')
                            ->label('Template')
                            ->options([
                                'welcome' => 'Email de bienvenue',
                                'promotion' => 'Promotion spéciale',
                                'notification' => 'Notification',
                            ])
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('subject', self::getTemplateSubject($state))),
                            
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected static function getTemplateSubject(string $template): string
    {
        return match($template) {
            'welcome' => 'Bienvenue chez nous !',
            'promotion' => 'Offre spéciale pour vous',
            'notification' => 'Notification importante',
            default => 'Message important',
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('template')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'welcome' => 'success',
                        'promotion' => 'warning',
                        'notification' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('Envoyer')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(fn (CustomerEmail $record) => $record->sendEmail()),
                    
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
            'index' => Pages\ManageCustomerEmails::route('/'),
        ];
    }
}
