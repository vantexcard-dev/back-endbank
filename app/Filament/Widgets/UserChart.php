<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;

// Ensure TrendValue exists in the correct namespace or remove its usage if unavailable.
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue; // Import the correct namespace for TrendValue

class UserChart extends ChartWidget
{
    protected static ?string $heading = 'Utilisateurs';

    protected function getData(): array
    {
        $data = Trend::model(User::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
