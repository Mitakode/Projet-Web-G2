<?php

namespace App\Controllers;

class FooterPageController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function page(string $pageType)
    {
        $content = $this->getPageContent($pageType);
        echo $this->twig->render('FooterPage.html.twig', [
            'pageType' => $pageType,
            'content' => $content
        ]);
    }

    private function getPageContent(string $pageType): array
    {
        $cguBody = <<<'HTML'
<p>Dernière mise à jour : mars 2026</p>

<h3>1. Objet et acceptation</h3>
<p>
ThePiston est une plateforme dédiée à la gestion des offres d'emploi et à la mise
en relation entre étudiants, entreprises et établissements d'enseignement.
L'utilisation du service implique l'acceptation complète des présentes conditions.
</p>

<h3>2. Services proposés</h3>
<p>
La plateforme permet la publication et la consultation d'offres, la gestion de
candidatures et l'accès à des espaces utilisateurs. Certaines fonctionnalités
sont réservées aux utilisateurs authentifiés.
</p>

<h3>3. Conditions d'accès</h3>
<p>
L'inscription est gratuite. L'utilisateur s'engage à fournir des informations
exactes et à préserver la confidentialité de ses identifiants.
</p>

<h3>4. Engagements utilisateur</h3>
<p>
L'utilisateur s'engage à respecter les lois en vigueur, à ne pas publier de
contenu illicite et à ne pas perturber le fonctionnement de la plateforme.
</p>

<h3>5. Responsabilité</h3>
<p>
ThePiston met en œuvre des moyens raisonnables pour assurer la continuité du
service, sans garantie d'absence totale d'interruption ou d'erreur.
</p>

<h3>6. Évolution des conditions</h3>
<p>
Ces conditions peuvent être mises à jour à tout moment. La poursuite de
l'utilisation de la plateforme vaut acceptation de la version en vigueur.
</p>
HTML;

        $contactBody = <<<'HTML'
<p>
Notre équipe reste disponible pour toute question relative au fonctionnement de
la plateforme ou à vos démarches de candidature.
</p>

<h3>Contacts e-mail</h3>
<p><strong>Support :</strong> support@thepiston.fr</p>
<p><strong>Partenariats :</strong> partenaires@thepiston.fr</p>
<p><strong>Signalement :</strong> abuse@thepiston.fr</p>

<h3>Délais de réponse</h3>
<p>
Les demandes sont traitées du lundi au vendredi, de 9h00 à 18h00 (CET).
</p>

<h3>Conseil</h3>
<p>
Pour accélérer le traitement, merci d'indiquer un objet précis et de joindre
les éléments utiles à l'analyse de votre demande.
</p>
HTML;

        $legalBody = <<<'HTML'
<h3>Éditeur de la plateforme</h3>
<p>
<strong>Nom :</strong> ThePiston SAS<br/>
<strong>Forme :</strong> Société par Actions Simplifiée<br/>
<strong>Siège social :</strong> 123 Rue de l'Emploi, 75000 Paris, France<br/>
<strong>SIRET :</strong> 123 456 789 00012<br/>
<strong>TVA intracommunautaire :</strong> FR12 345 678 900
</p>

<h3>Direction de publication</h3>
<p><strong>Responsable :</strong> Jean Dupont, Directeur Général</p>

<h3>Hébergement</h3>
<p>
<strong>Hébergeur :</strong> CloudServer France<br/>
<strong>Adresse :</strong> 456 Avenue de la Technologie, 13000 Marseille, France
</p>

<h3>Données personnelles</h3>
<p>
Pour toute demande relative à vos données, contactez le DPO à l'adresse
suivante : dpo@thepiston.fr.
</p>

<h3>Propriété intellectuelle</h3>
<p>
Les contenus, visuels et structures de la plateforme sont protégés. Toute
reproduction non autorisée est interdite.
</p>
HTML;

        $privacyBody = <<<'HTML'
<p>
ThePiston applique les principes du RGPD pour la collecte et le traitement des
données personnelles.
</p>

<h3>1. Données traitées</h3>
<p>
Nom, prénom, e-mail, informations de profil, candidatures et données techniques
liées à l'usage du service.
</p>

<h3>2. Finalités</h3>
<p>
Gestion des comptes, suivi des candidatures, amélioration du service et respect
obligations légales.
</p>

<h3>3. Cookies</h3>
<p>
Des cookies peuvent être utilisés pour le fonctionnement du service et la mesure
d'audience, selon la réglementation applicable.
</p>

<h3>4. Conservation</h3>
<p>
Les données sont conservées pendant la durée nécessaire aux finalités du
traitement et aux obligations légales.
</p>

<h3>5. Droits</h3>
<p>
Vous disposez des droits d'accès, rectification, effacement, limitation et
portabilité. Contact : privacy@thepiston.fr.
</p>
HTML;

        $termsBody = <<<'HTML'
<p>
Ces conditions encadrent l'utilisation de la plateforme et les obligations
réciproques des utilisateurs et de l'éditeur.
</p>

<h3>1. Définitions</h3>
<p>
<strong>Plateforme :</strong> service ThePiston.<br/>
<strong>Utilisateur :</strong> personne accédant au service.<br/>
<strong>Services :</strong> fonctionnalités proposées sur la plateforme.
</p>

<h3>2. Accès</h3>
<p>
L'accès est gratuit, avec création de compte pour les fonctionnalités avancées.
L'utilisateur est responsable de ses identifiants.
</p>

<h3>3. Bon usage</h3>
<p>
Il est interdit de publier des contenus illicites, de détourner les services ou
d'automatiser des actions sans autorisation.
</p>

<h3>4. Résiliation</h3>
<p>
Le compte peut être suspendu ou supprimé en cas de violation des règles
applicables.
</p>
HTML;

        $pages = [
            'cgu' => [
                'title' => 'Conditions Générales d\'Utilisation',
                'body' => $cguBody
            ],
            'contact' => [
                'title' => 'Nous Contacter',
                'body' => $contactBody
            ],
            'legal' => [
                'title' => 'Mentions Légales',
                'body' => $legalBody
            ],
            'privacy' => [
                'title' => 'Politique de Confidentialité',
                'body' => $privacyBody
            ],
            'terms' => [
                'title' => 'Conditions d\'Utilisation',
                'body' => $termsBody
            ]
        ];

        return $pages[$pageType] ?? [
            'title' => 'Page non trouvée',
            'body' => '<h2>Page non trouvée</h2><p>La page que vous recherchez n\'existe pas.</p>'
        ];
    }
}
