<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Mail\SuccessionConfirmationMail;

class SuccessionMailController extends Controller
{
    /**
     * Envoi d'email de confirmation de dossier complet
     */
    public function envoyerEmailSuccession(Request $request)
    {
        try {
            // Validation des données avec messages personnalisés
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:100',
                'nom_defunte' => 'required|string|max:100',
                'nom_client' => 'required|string|max:100',
                'reference_dossier' => 'nullable|string|max:50',
                'message_personnalise' => 'nullable|string|max:500'
            ], [
                'email.required' => 'L\'adresse email est obligatoire.',
                'email.email' => 'L\'adresse email n\'est pas valide.',
                'nom_defunte.required' => 'Le nom de la défunte est obligatoire.',
                'nom_client.required' => 'Le nom du client est obligatoire.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $email = $request->email;
            $nomDefunte = $request->nom_defunte;
            $nomClient = $request->nom_client;
            $referenceDossier = $request->reference_dossier ?? $this->genererReferenceDossier();
            $messagePersonnalise = $request->message_personnalise;

            // Enregistrement dans les logs
            Log::info('Envoi d\'email de succession', [
                'destinataire' => $email,
                'nom_defunte' => $nomDefunte,
                'nom_client' => $nomClient,
                'reference' => $referenceDossier,
                'ip' => $request->ip()
            ]);

            // Option 1: Envoi avec Mail::raw (simple)
            // $this->envoyerEmailRaw($email, $nomClient, $nomDefunte, $referenceDossier, $messagePersonnalise);

            // Option 2: Envoi avec une Mailable class (recommandé)
            Mail::to($email)->send(new SuccessionConfirmationMail(
                $nomClient,
                $nomDefunte,
                $referenceDossier,
                $messagePersonnalise
            ));

            // Vérifier si l'email a bien été envoyé
            if (count(Mail::failures()) > 0) {
                throw new \Exception('Échec de l\'envoi à certains destinataires.');
            }

            return response()->json([
                'success' => true,
                'message' => 'L\'email de confirmation de succession a été envoyé avec succès.',
                'reference_dossier' => $referenceDossier,
                'destinataire' => $email
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de succession', [
                'erreur' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'donnees' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi de l\'email.',
                'erreur' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Méthode alternative utilisant Mail::raw
     */
    private function envoyerEmailRaw($email, $nomClient, $nomDefunte, $referenceDossier, $messagePersonnalise = null)
    {
        $date = now()->format('d/m/Y');
        
        $contenuEmail = "
CONFIRMATION DE DOSSIER COMPLET
Référence : {$referenceDossier}
Date : {$date}

Madame {$nomClient},

Nous vous confirmons que le dossier de succession de Madame {$nomDefunte} est désormais complet.

Les démarches de traitement et de déblocage du compte sont en cours, conformément à la réglementation en vigueur.

Vous serez informée dès finalisation de l'opération.
        ";

        // Ajout du message personnalisé si fourni
        if ($messagePersonnalise) {
            $contenuEmail .= "\n\nMessage complémentaire :\n{$messagePersonnalise}\n";
        }

        $contenuEmail .= "\n\nVeuillez agréer nos salutations distinguées.\nService Successions\n\n---\nCeci est un message automatique, merci de ne pas y répondre.";

        Mail::raw($contenuEmail, function ($message) use ($email, $nomDefunte, $referenceDossier) {
            $message->to($email)
                    ->subject("Succession de {$nomDefunte} - Dossier complet ({$referenceDossier})");
        });
    }

    /**
     * Génère une référence de dossier unique
     */
    private function genererReferenceDossier(): string
    {
        return 'SUC-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * API pour prévisualiser l'email sans l'envoyer
     */
    public function previewEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_defunte' => 'required|string',
            'nom_client' => 'required|string',
            'reference_dossier' => 'nullable|string',
            'message_personnalise' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $referenceDossier = $request->reference_dossier ?? $this->genererReferenceDossier();
        $date = now()->format('d/m/Y');

        $htmlContent = view('emails.succession.preview', [
            'nom_client' => $request->nom_client,
            'nom_defunte' => $request->nom_defunte,
            'reference_dossier' => $referenceDossier,
            'date' => $date,
            'message_personnalise' => $request->message_personnalise
        ])->render();

        return response()->json([
            'success' => true,
            'preview' => $htmlContent,
            'reference_dossier' => $referenceDossier
        ]);
    }

    /**
     * Liste des emails envoyés (pour log ou historique)
     */
    public function historiqueEnvois(Request $request)
    {
        // Cette méthode pourrait lire dans un fichier log ou une table de base de données
        // Pour l'instant, nous retournons un exemple
        return response()->json([
            'success' => true,
            'historique' => [
                [
                    'date' => now()->subDays(1)->format('d/m/Y H:i'),
                    'destinataire' => 'client@exemple.com',
                    'nom_defunte' => 'DUPONT Marie',
                    'reference' => 'SUC-2024-0123'
                ],
                [
                    'date' => now()->subDays(3)->format('d/m/Y H:i'),
                    'destinataire' => 'autre@exemple.com',
                    'nom_defunte' => 'MARTIN Jeanne',
                    'reference' => 'SUC-2024-0122'
                ]
            ]
        ]);
    }
}