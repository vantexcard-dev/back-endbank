<p>Bonjour {{ $user->name }},</p>

<p>Une nouvelle connexion a été détectée sur votre compte :</p>

<ul>
    <li><strong>Adresse IP :</strong> {{ $ip }}</li>
    <li><strong>Navigateur :</strong> {{ $userAgent }}</li>
    <li><strong>Pays :</strong> {{ $country }}</li>
</ul>

<p>Si ce n'était pas vous, changez votre mot de passe immédiatement.</p>

<p>Cordialement,<br>L'équipe sécurité</p>
