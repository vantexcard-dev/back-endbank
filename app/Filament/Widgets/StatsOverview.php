<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Card;
use App\Models\Loan;
use App\Models\Cheque;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Utilisateurs totaux', User::count())
                ->description('Nombre total d\'utilisateurs')
                ->color('success'),

            Stat::make('Transactions totales', Transaction::count())
                ->description('Nombre total de transactions')
                ->color('warning'),

            Stat::make('Cartes de crédit totales', Card::count())
                ->description('Nombre total de cartes de crédit')
                ->color('primary'),

            Stat::make('Demandes de prêt totales', Loan::count())
                ->description('Nombre total de demandes de prêt')
                ->color('danger'),

            Stat::make('Encaissements de chèque totaux', Cheque::count())
                ->description('Nombre total d\'encaissements de chèque')
                ->color('info'),
            Stat::make('Totale de compte', Account::count())
                ->description('Nombre total de comptes')
                ->color('pending'),
        ];
    }
    protected function getWidgets(): array
{
    return [
        UserChart::class,
        StatsOverview::class,
        AccountChart::class,
    ];
}
}
