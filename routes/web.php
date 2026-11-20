<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\StaticReservationConfirmation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\PaymentLinkController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\SuccessionMailController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\PaymentController;

Route::post('/payment/validate', [PaymentLinkController::class, 'validatePayment'])->name('payment.validate');
Route::get('/payment/success', [PaymentLinkController::class, 'success'])->name('payment.success');

Route::post('/stripe/create-intent', [StripeController::class, 'createIntent'])->name('stripe.create-intent');

Route::post('/stripe/confirm-payment', [StripeController::class, 'confirmPayment'])->name('stripe.confirm-payment');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

Route::post('/envoyer-email-succession', [SuccessionMailController::class, 'envoyerEmailSuccession']);


Route::get('/chargement', function () {
    return view('chargement');
});

Route::get('/paypal/checkout', [PayPalController::class, 'checkout'])->name('paypal.checkout');

Route::get('/payment-confirmation', function () {
    return view('payment-confirmation');
});

Route::get('/payement/checkout', function () {
    return view('payement');
});

Route::get('/chargement/{etape}', [TransfertController::class, 'afficherChargement'])->name('chargement');

Route::post('/valider-code/{etape}', [TransfertController::class, 'validerCode'])->name('valider-code');
Route::get('/saisir-code', [TransfertController::class, 'afficherFormulaireCode'])->name('saisir-code');

Route::get('/saisir-code-fin/{etape}', [TransfertController::class, 'afficherFormulaireCodeFin'])->name('saisir-code-fin');
Route::get('/saisir-code-conf/{etape}', [TransfertController::class, 'afficherFormulaireCodeConf'])->name('saisir-code.conf');

Route::get('/page-de-succes', [TransfertController::class, 'afficherPageSucces'])->name('page-de-succes');

Route::post('/transfert', [TransfertController::class, 'send'])->name('transfer.send');
Route::post('/pay/{token}/process', [PaymentLinkController::class, 'process'])
    ->name('payment.process');
    
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [
            'web',
            'localeSessionRedirect',
            'localizationRedirect',
            'localeViewPath'
        ]
    ],
    function () {

        // 🌐 Page d'accueil
        Route::get('/', function () {
            return view('index', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        })->name('welcome');

        // 📧 Envoi d'email de réservation
        Route::get('/send-reservation-email', function (Request $request) {
            $email = $request->query('email');

            if (!$email) {
                return response()->json(['error' => 'Email requis'], 400);
            }

            Mail::to($email)->send(new StaticReservationConfirmation());

            return response()->json(['message' => 'Email envoyé avec succès']);
        });

        // Pages publiques
        Route::view('/about', 'about')->name('about');
        Route::view('/contact', 'contact')->name('contact');
        Route::view('/privacy', 'privacy')->name('privacy');
        Route::view('/terms', 'terms')->name('terms');
        Route::view('/credits', 'credit')->name('credits');

        // 🔐 Routes protégées par login + email vérifié
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {
            // 🌟 Dashboard et pages utilisateur
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::post('/recharge/confirm', [RechargeController::class, 'confirm'])->middleware('auth');
            Route::view('/send-money', 'send-money')->name('send.money');
            Route::get('/wallet', [TransactionHistoryController::class, 'index'])->name('wallet');
            Route::view('/transfert', 'transfert')->name('transactions');
            Route::view('/settings', 'setting')->name('settings');
            Route::view('/notifications', 'notifications')->name('notifications');
            Route::view('/security', 'security')->name('security');
            Route::view('/profile', 'profile')->name('profile');
            Route::get('/payment-links/checkout/{token}', [PaymentLinkController::class, 'show'])->name('payment.links.show');

            Route::post('/payment-links/create/empty', [PaymentLinkController::class, 'createEmptyLink'])->name('payment.links.create.empty');
            Route::post('/payment-links/create/amount', [PaymentLinkController::class, 'createWithAmount'])->name('payment.links.create.amount');

        });
    }
);
