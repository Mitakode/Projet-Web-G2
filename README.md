# 🎓 The Piston - Plateforme de Recherche de Stages

> Application web permettant d'informatiser et de faciliter la recherche de stages pour les étudiants, la gestion des offres pour les pilotes de promotion, et le suivi des entreprises partenaires.

## 📝 Présentation du projet

Dans le cadre de la recherche de stage, cette plateforme regroupe les offres, centralise les données des entreprises ayant déjà accueilli des stagiaires, et permet aux étudiants de postuler directement en ligne. 

L'application propose des interfaces adaptées à trois profils d'utilisateurs :
* **Administrateur :** Accès total à la plateforme et gestion globale.
* **Pilote de promotion :** Suivi des élèves et des candidatures associées.
* **Étudiant :** Recherche de stages, gestion de wish-list et envoi de candidatures (CV + Lettre de motivation).

## 🚀 Fonctionnalités Principales

### 👤 Gestion des Utilisateurs & Accès
* Authentification sécurisée avec gestion des rôles et permissions (`BlockAccess.php`).
* Tableaux de bord personnalisés (`DashboardAdmin`, `DashboardStudent`).
* CRUD complet pour les comptes Étudiants et Pilotes.

### 🏢 Gestion des Entreprises
* Recherche multicritères, création, modification et suppression d'entreprises.
* Système d'évaluation des entreprises par les utilisateurs autorisés.

### 💼 Gestion des Offres de Stage
* Recherche avancée d'offres par durée, type d'offre, etc.
* Création, modification et suppression d'offres.
* Tableau de bord statistique (répartition par durée, top wish-list, moyenne de candidatures).

### 📄 Candidatures & Wish-list
* Postuler directement en ligne avec dépôt sécurisé de CV et Lettre de Motivation (`FileUploader.php`, `zone-depot.js`).
* Suivi des candidatures pour les étudiants et les pilotes.
* Pagination asynchrone des résultats (`pagination-scroll.js`, `Paginator.php`).

## 🛠️ Stack Technique & Spécifications

Ce projet a été développé en respectant un cahier des charges technique strict (sans utilisation de frameworks Front/Back-end complets ni de CMS).

* **Architecture :** Modèle-Vue-Contrôleur (MVC)
* **Backend :** PHP (POO, respect des conventions PSR-12), Autoloading via **Composer**.
* **Frontend :** HTML5, CSS3 (Mobile First, Responsive), JavaScript Vanilla.
* **Base de données :** SGBD SQL relationnel avec exploitation des clés étrangères (`SqlDatabase.php`).
* **Moteur de template :** **Twig** (fichiers `.html.twig` dans le dossier `vue/`).
* **Serveur :** Apache (Configuration Vhost pointant vers la racine).

### 🔒 Sécurité & Bonnes Pratiques
* Protection contre les failles : Injections SQL (requêtes préparées), XSS.
* Routage d'URL hiérarchisé depuis un point d'entrée unique (`index.php`).
* Tests unitaires intégrés avec **PHPUnit** (`CompanyModelTest.php`).
* Optimisation SEO : Fichiers `robots.txt` et `sitemap.xml` présents à la racine.
