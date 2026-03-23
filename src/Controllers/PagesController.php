<?php

namespace App\Controllers;

class PagesController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function page(string $pageType)
    {
        $content = $this->getPageContent($pageType);
        echo $this->twig->render('FooterPage.html.twig', ['pageType' => $pageType, 'content' => $content]);
    }

    private function getPageContent(string $pageType): array
    {
        $pages = [
            'cgu' => [
                'title' => 'Conditions Générales d\'Utilisation',
                'body' => '
                    <p>Dernière mise à jour : mars 2026</p>
                    
                    <h3>1. Objet et Acceptation</h3>
                    <p>ThePiston est une plateforme numérique dédiée à la gestion des offres d\'emploi et à la mise en relation entre étudiants, entreprises et établissements d\'enseignement. En accédant et en utilisant notre plateforme, vous acceptez l\'intégralité de ces conditions générales d\'utilisation.</p>
                    
                    <h3>2. Services Offerts</h3>
                    <p>ThePiston propose les services suivants : publication d\'offres d\'emploi, consultation d\'offres, candidature à des postes, gestion de dashboards utilisateurs, et accessibilité à des ressources pédagogiques. L\'accès à ces services est réservé aux utilisateurs inscrits et authentifiés.</p>
                    
                    <h3>3. Conditions d\'Accès et d\'Inscription</h3>
                    <p>Pour utiliser notre plateforme, vous devez être âgé d\'au moins 18 ans et accepter ces conditions. L\'inscription est gratuite. Vous êtes responsable de la confidentialité de vos identifiants et de toutes les activités qui en découleraient. Vous vous engagez à fournir des informations exactes et complètes lors de votre inscription.</p>
                    
                    <h3>4. Responsabilités de l\'Utilisateur</h3>
                    <p>Vous vous engagez à utiliser la plateforme de manière légale, éthique et conforme aux réglementations applicables. Vous vous interdisez notamment de : publier du contenu offensant, diffamatoire ou illégal ; usurper l\'identité d\'une tierce partie ; ou exploiter la plateforme à des fins frauduleuses.</p>
                    
                    <h3>5. Limitation de Responsabilité</h3>
                    <p>ThePiston décline toute responsabilité pour les dommages indirects, accidentels ou consécutifs liés à l\'utilisation de la plateforme. Le contenu fourni est à titre informatif et ne représente pas une garantie formelle.</p>
                    
                    <h3>6. Modifications</h3>
                    <p>ThePiston se réserve le droit de modifier ces conditions à tout moment. Toute modification sera notifiée via la plateforme. La continuation d\'utilisation après modification vaut acceptation des nouvelles conditions.</p>
                '
            ],
            'contact' => [
                'title' => 'Nous Contacter',
                'body' => '
                    <p>Nous sommes à votre écoute pour répondre à vos questions, suggestions ou signaler tout problème.</p>
                    
                    <h3>Adresse E-mail</h3>
                    <p><strong>Support Général :</strong> support@thepiston.fr</p>
                    <p><strong>Partenariats Entreprises :</strong> partenaires@thepiston.fr</p>
                    <p><strong>Signaler un Problème :</strong> abuse@thepiston.fr</p>
                    
                    <h3>Heures de Réponse</h3>
                    <p>Nos équipes répondent aux demandes du lundi au vendredi, de 9h00 à 18h00 (heure d\'Europe centrale). Les demandes reçues en dehors de ces horaires seront traitées le jour ouvrable suivant.</p>
                    
                    <h3>Formulaire de Contact Rapide</h3>
                    <p>Pour une réponse plus rapide, utilisez le formulaire disponible directement sur la plateforme. Veuillez fournir un titre clair et une description détaillée de votre demande.</p>
                    
                    <h3>Réseaux Sociaux</h3>
                    <p>Suivez-nous sur nos canaux de communication pour les dernières actualités et mises à jour : LinkedIn, Twitter, et Facebook.</p>
                '
            ],
            'legal' => [
                'title' => 'Mentions Légales',
                'body' => '                
                    <h3>Éditeur de la Plateforme</h3>
                    <p><strong>Nom :</strong> ThePiston SAS<br/>
                    <strong>Forme juridique :</strong> Société par Actions Simplifiée<br/>
                    <strong>Capital social :</strong> 50 000€<br/>
                    <strong>Siège social :</strong> 123 Rue de l\'Emploi, 75000 Paris, France<br/>
                    <strong>Numéro SIRET :</strong> 123 456 789 00012<br/>
                    <strong>Numéro TVA :</strong> FR12 345 678 900</p>
                    
                    <h3>Directeur de Publication</h3>
                    <p><strong>Responsable :</strong> Jean Dupont, Directeur Général</p>
                    
                    <h3>Hébergement</h3>
                    <p><strong>Hébergeur :</strong> CloudServer France<br/>
                    <strong>Adresse :</strong> 456 Avenue de la Technologie, 13000 Marseille, France</p>
                    
                    <h3>Données Personnelles et RGPD</h3>
                    <p>ThePiston traite vos données personnelles conformément au Règlement Général sur la Protection des Données (RGPD). Pour exploiter votre droit d\'accès, de modification ou de suppression, veuillez contacter notre Délégué à la Protection des Données à dpo@thepiston.fr.</p>
                    
                    <h3>Propriété Intellectuelle</h3>
                    <p>Tous les contenus, logos, images et structures présents sur la plateforme sont la propriété exclusive de ThePiston ou de ses partenaires licenciés. Toute reproduction, distribution ou modification sans autorisation est interdite.</p>
                '
            ],
            'privacy' => [
                'title' => 'Politique de Confidentialité',
                'body' => '
                    <p>Chez ThePiston, la protection de vos données personnelles est notre priorité absolue.</p>
                    
                    <h3>1. Données Collectées</h3>
                    <p>Nous collectons les données suivantes lors de votre inscription et utilisation : nom, prénom, adresse e-mail, numéro de téléphone, informations de profil professionnel, historique de candidatures, et données de navigation.</p>
                    
                    <h3>2. Utilisation des Données</h3>
                    <p>Vos données sont utilisées pour : fournir les services de la plateforme, améliorer nos services, communiquer avec vous, vous adresser des offres pertinentes, et respecter nos obligations légales. Nous ne partageons jamais vos données avec des tiers sans votre consentement explicite.</p>
                    
                    <h3>3. Cookies et Suivi</h3>
                    <p>Notre plateforme utilise des cookies pour améliorer votre expérience utilisateur et analyser l\'usage de la plateforme. Vous pouvez gérer vos préférences de cookies dans les paramètres de votre navigateur.</p>
                    
                    <h3>4. Durée de Conservation</h3>
                    <p>Vos données personnelles sont conservées aussi longtemps que nécessaire pour la fourniture des services. Vous pouvez demander la suppression de votre compte et l\'effacement de vos données à tout moment.</p>
                    
                    <h3>5. Sécurité</h3>
                    <p>Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos données contre l\'accès non autorisé, l\'altération ou la divulgation.</p>
                    
                    <h3>6. Vos Droits</h3>
                    <p>Vous disposez d\'un droit d\'accès, de rectification, d\'effacement et de portabilité de vos données. Pour exercer ces droits, contactez-nous à privacy@thepiston.fr.</p>
                '
            ],
            'terms' => [
                'title' => 'Conditions d\'Utilisation',
                'body' => '
                    <h2>Conditions d\'Utilisation</h2>
                    <p>Ces conditions régissent l\'utilisation de la plateforme ThePiston et les services qu\'elle propose.</p>
                    
                    <h3>1. Définitions</h3>
                    <p><strong>Plateforme :</strong> Site web et application mobile ThePiston<br/>
                    <strong>Utilisateur :</strong> Toute personne ayant accès à la plateforme<br/>
                    <strong>Services :</strong> Ensemble des fonctionnalités et contenus proposés</p>
                    
                    <h3>2. Accès à la Plateforme</h3>
                    <p>L\'accès à la plateforme est gratuit, mais vous devez créer un compte pour accéder à certains services. Vous êtes responsable du maintien de la confidentialité de vos identifiants de connexion.</p>
                    
                    <h3>3. Obligations des Utilisateurs</h3>
                    <p>En utilisant ThePiston, vous vous engagez à : respecter les lois et réglementations applicables ; ne pas publier de contenu offensant ou trompeur ; ne pas surcharger les serveurs ; ne pas utiliser des robots ou scripts automatisés sans autorisation.</p>
                    
                    <h3>4. Contenu Généré par l\'Utilisateur</h3>
                    <p>Vous conservez la propriété du contenu que vous créez mais accordez à ThePiston une licence mondiale pour l\'utiliser et l\'afficher sur la plateforme. Vous vous engagez à ne publier que du contenu dont vous disposez des droits.</p>
                    
                    <h3>5. Restrictions de Responsabilité</h3>
                    <p>ThePiston n\'est pas responsable de : dégâts causés par des tiers ; perte de données ou d\'accès ; interruptions temporaires des services ; erreurs dans le contenu.<br/>
                    Votre responsabilité est limitée aux dommages directs n\'excédant pas le montant que vous avez payé pour utiliser nos services (le cas échéant).</p>
                    
                    <h3>6. Résiliation</h3>
                    <p>ThePiston se réserve le droit de suspendre ou de résilier votre compte en cas de violation de ces conditions ou de comportement contraire à l\'éthique. Vous pouvez également résilier votre compte à tout moment.</p>
                '
            ]
        ];

        return $pages[$pageType] ?? [
            'title' => 'Page non trouvée',
            'body' => '<h2>Page non trouvée</h2><p>La page que vous recherchez n\'existe pas. Veuillez retourner à l\'accueil.</p>'
        ];
    }
}
