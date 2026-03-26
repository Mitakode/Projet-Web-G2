<?php

// Fonction pour valider et sécuriser une entrée utilisateur
function validateInput($input)
{
    // Supprimer les espaces inutiles au début et à la fin
    // trim() enlève les espaces, tabulations ou retours à la ligne superflus
    $input = trim($input);

    // Échapper les caractères spéciaux pour prévenir les attaques XSS
    // htmlspecialchars() convertit les caractères spéciaux en entités HTML
    // ENT_QUOTES : convertit les guillemets simples et doubles
    // 'UTF-8' : encodage utilisé pour gérer correctement les caractères accentués
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    // Validation de la chaîne avec une expression régulière
    // /^[a-zA-Z0-9\s\-]+$/u : accepte uniquement les lettres, chiffres, espaces et tirets
    // ^ et $ : la chaîne doit correspondre entièrement à ce motif
    // u : active le support UTF-8 pour les caractères multilingues
    if (!preg_match('/^[a-zA-Z0-9\s\-\_]+$/u', $input)) {
        // Si la chaîne contient des caractères non autorisés, on stoppe le script
        die('Entrée invalide.');
    }
    // Retourner l'entrée validée et sécurisée
    return $input;
}
