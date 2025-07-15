<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CodeTransfertResource\Pages;
use App\Models\CodeTransfert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class CodeTransfertResource extends Resource
{
    protected static ?string $model = CodeTransfert::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Code de Transfert')
                    ->default(fn() => strtoupper(Str::random(10)))
                    ->disabled()
                    ->required(),

                Forms\Components\Select::make('etape')
                    ->label('Étape')
                    ->options([
                        'initiation' => 'Initiation (30%)',
                        'confirmation' => 'Confirmation (90%)',
                        'finalisation' => 'Finalisation (100%)',
                    ])
                    ->required()
                    ->native(false),

                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name') // Afficher le nom de l'utilisateur au lieu de l'ID
                    ->searchable()
                    ->required(),

                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Date de création')
                    ->default(fn() => now())
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('etape')
                    ->label('Étape')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'warning' => 'initiation',
                        'info' => 'confirmation',
                        'success' => 'finalisation',
                    ]),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCodeTransferts::route('/'),
            'create' => Pages\CreateCodeTransfert::route('/create'),
            'edit' => Pages\EditCodeTransfert::route('/{record}/edit'),
        ];
    }
}
