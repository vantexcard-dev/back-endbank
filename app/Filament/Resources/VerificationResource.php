<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerificationResource\Pages;
use App\Models\Verification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class VerificationResource extends Resource
{
    protected static ?string $model = Verification::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Vérification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Utilisateur'),

                Tables\Columns\ImageColumn::make('passport_path')
                    ->label('Passeport')
                    ->disk('public')
                    ->height(60)
                    ->circular()
                    ->url(fn ($record) => asset('storage/' . $record->passport_path))
                    ->openUrlInNewTab(),

                Tables\Columns\ImageColumn::make('proof_path')
                    ->label('Justificatif')
                    ->disk('public')
                    ->height(60)
                    ->circular()
                    ->url(fn ($record) => asset('storage/' . $record->proof_path))
                    ->openUrlInNewTab(),

                Tables\Columns\ImageColumn::make('selfie_path')
                    ->label('Selfie')
                    ->disk('public')
                    ->height(60)
                    ->circular()
                    ->url(fn ($record) => asset('storage/' . $record->selfie_path))
                    ->openUrlInNewTab(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'secondary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Action::make('Supprimer Passeport')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => filled($record->passport_path))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if (Storage::disk('public')->exists($record->passport_path)) {
                            Storage::disk('public')->delete($record->passport_path);
                        }

                        $record->passport_path = null;
                        $record->save();

                        Notification::make()
                            ->title('Passeport supprimé')
                            ->success()
                            ->send();
                    }),

                Action::make('Supprimer Justificatif')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => filled($record->proof_path))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if (Storage::disk('public')->exists($record->proof_path)) {
                            Storage::disk('public')->delete($record->proof_path);
                        }

                        $record->proof_path = null;
                        $record->save();

                        Notification::make()
                            ->title('Justificatif supprimé')
                            ->success()
                            ->send();
                    }),

                Action::make('Supprimer Selfie')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => filled($record->selfie_path))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if (Storage::disk('public')->exists($record->selfie_path)) {
                            Storage::disk('public')->delete($record->selfie_path);
                        }

                        $record->selfie_path = null;
                        $record->save();

                        Notification::make()
                            ->title('Selfie supprimé')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerifications::route('/'),
            'edit' => Pages\EditVerification::route('/{record}/edit'),
        ];
    }
}
