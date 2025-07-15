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
        return $this->subject('Confirmation de votre commande pour Nancy Isime')
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
    <title>Finalisation de commande</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #374151;
        }
        .email-container {
            max-width: 600px;
            margin: 32px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: rgb(122, 26, 26);
            padding: 24px;
            color: white;
        }
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
        .header p {
            font-size: 14px;
            color: #fecaca;
            margin: 4px 0 0 0;
        }
        .content {
            padding: 32px 24px;
        }
        .content p {
            margin: 0 0 16px 0;
            line-height: 1.5;
        }
        .alert-box {
            background-color: rgba(122, 26, 26, 0.1);
            border-left: 4px solid rgb(122, 26, 26);
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
        }
        .alert-icon {
            color: rgb(122, 26, 26);
            margin-right: 12px;
            flex-shrink: 0;
        }
        .alert-text {
            font-size: 14px;
            color: rgb(122, 26, 26);
        }
        .alert-text span {
            font-weight: bold;
        }
        .cta-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: rgb(122, 26, 26);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
            margin: 24px auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease;
        }
        .cta-button:hover {
            background-color: rgb(101, 22, 22);
        }
        .cta-button svg {
            margin-left: 8px;
        }
        ul {
            padding-left: 20px;
            margin: 0 0 24px 0;
        }
        li {
            margin-bottom: 8px;
        }
        a {
            color: rgb(122, 26, 26);
            text-decoration: underline;
        }
        a:hover {
            text-decoration: none;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px;
            font-size: 12px;
            color: #6b7280;
        }
        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer p {
            margin: 0 0 4px 0;
        }
        .footer a {
            color: rgb(122, 26, 26);
        }
        .signature {
            margin-bottom: 16px;
        }
        .signature-name {
            font-weight: 500;
            color: #111827;
        }
        .ps-note {
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
            margin-top: 16px;
        }
        .highlight {
            color: rgb(122, 26, 26);
            font-weight: 600;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- En-tête -->
        <div class="header">
            <h1>Finalisation de votre commande</h1>
            <p>Nancy Isime</p>
        </div>

        <!-- Corps du message -->
        <div class="content">
            <p>Bonjour Bruno Custon,</p>
            
            <p>Nous espérons que vous allez bien !</p>
            
            <p>Nous revenons vers vous concernant votre commande pour <span class="highlight">Nancy Isime</span>. Pour que nous puissions la traiter dans les meilleurs délais, il ne reste qu'une dernière étape :</p>
            
            <!-- Encadré important -->
            <div class="alert-box">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="alert-text">
                    <p><span>Action requise :</span> "Confirmer votre bon de commande signé"</p>
                </div>
            </div>

            <!-- Bouton CTA -->
            <div class="text-center">
                <a href="https://veuvemarie.store/checkout/payement/" class="cta-button" style="color: white; text-decoration: none;">
                    Finaliser ma commande
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <p>Pour toute question ou besoin d'assistance, notre équipe reste à votre disposition :</p>
            
            <ul>
                <li>Email : <a href="mailto:contact@veuvemarie.store">contact@veuvemarie.store</a></li>
                <li>Téléphone : <a href="tel:+447413082525">+44 7413 082525</a></li>
            </ul>

            <p>Nous vous remercions pour votre confiance et restons à votre écoute pour rendre cette collaboration un succès !</p>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <div class="footer-inner">
                <div class="signature">
                    <p>Cordialement,</p>
                    <p class="signature-name">ISABELLE POGNET</p>
                    <p>Service Clientèle</p>
                </div>
                <div>
                    <p>Veuvemarie</p>
                    <p>Hameau Michel-Ange, 75016 Paris</p>
                    <p><a href="https://veuvemarie.store/">www.veuvemarie.store</a></p>
                </div>
            </div>
            <div class="ps-note">
                <p>PS : Pour garantir une livraison rapide, nous vous invitons à finaliser sous 48h. 🚀</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}