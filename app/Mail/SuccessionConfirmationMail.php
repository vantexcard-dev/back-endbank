<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nomClient;
    public $nomDefunte;
    public $referenceDossier;
    public $messagePersonnalise;
    public $date;

    /**
     * Create a new message instance.
     */
    public function __construct($nomClient, $nomDefunte, $referenceDossier, $messagePersonnalise = null)
    {
        $this->nomClient = $nomClient;
        $this->nomDefunte = $nomDefunte;
        $this->referenceDossier = $referenceDossier;
        $this->messagePersonnalise = $messagePersonnalise;
        $this->date = now()->format('d/m/Y');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Succession de {$this->nomDefunte} - Dossier complet ({$this->referenceDossier})")
                    ->markdown('emails.succession.confirmation')
                    ->with([
                        'nomClient' => $this->nomClient,
                        'nomDefunte' => $this->nomDefunte,
                        'referenceDossier' => $this->referenceDossier,
                        'messagePersonnalise' => $this->messagePersonnalise,
                        'date' => $this->date
                    ]);
    }
}