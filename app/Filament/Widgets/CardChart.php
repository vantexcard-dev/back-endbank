<?php

namespace App\Filament\Widgets;

use App\Models\Card;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class CardChart extends ChartWidget
{
    protected static ?string $heading = 'Carte de credit';

    protected function getData(): array
    {
        $data = Trend::model(Card::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Comptes',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
    protected function getType(): string
    {
        return 'bubble';
    }
}
