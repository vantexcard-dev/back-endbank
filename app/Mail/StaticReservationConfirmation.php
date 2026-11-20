<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class StaticReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Paiement en attente')
                    ->html($this->getStaticTemplate());
    }

    protected function getStaticTemplate()
    {
        $currentDate = Date::now()->format('d/m/Y');
        
        // Retourne votre template HTML complet tel quel
        return <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal - Paiement en attente</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo {
            width: 120px;
        }
        .alert-box {
            background-color: #FFF8E6;
            border-left: 4px solid #FFC439;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #003087;
            margin: 10px 0;
        }
        .details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #0070BA;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: bold;
            margin: 15px 0;
        }
        .footer {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg" alt="PayPal" class="logo">
        </div>

        <h2>Paiement en attente de vérification</h2>

        <div class="alert-box">
            Nous avons détecté une transaction inhabituelle sur votre compte et l'avons mise en attente pour vérification.
        </div>

        <p>Bonjour IMITSHOP LIMITED,</p>

        <p>Un paiement d'un montant de :</p>
        <div class="amount">1 956,00 €</div>
        <p>a été initié vers votre compte PayPal et est actuellement en attente.</p>

        <div class="details">
            <p><strong>Détails de la transaction :</strong></p>
            <p>Date : 19/08/2025 01:30</p>
            <p>Destinataire : Jean Moise</p>
            <p>Référence : TXN123456</p>
        </div>

        <p>Si vous n'avez pas demander cette transaction :</p>
        <a href="#" class="button">Signaler un problème</a>

        <p>Si vous reconnaissez cette opération, aucune action n'est requise. Le paiement sera traité une fois la vérification terminée.</p>

        <p>Pour votre sécurité, nous vous recommandons de :</p>
        <ul>
            <li>Vérifier votre historique de transactions</li>
            <li>Mettre à jour votre mot de passe</li>
            <li>Activer l'authentification à deux facteurs</li>
        </ul>

        <div class="footer">
            <p>© 2025 PayPal, Inc. Tous droits réservés.</p>
            <p>PayPal Europe S.à r.l. et Cie, S.C.A.</p>
            <p>22-24 Boulevard Royal, L-2449 Luxembourg</p>
            <p><a href="#">Politique de confidentialité</a> | <a href="#">Conditions d'utilisation</a></p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}