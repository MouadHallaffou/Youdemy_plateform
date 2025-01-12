# Youdemy Platform

Youdemy est une plateforme d'apprentissage en ligne interactive et personnalisée pour les étudiants et les enseignants. Ce projet met en œuvre des principes de programmation orientée objet (OOP) en PHP avec une organisation modulaire pour garantir scalabilité et maintenabilité.

## Table des Matières
- [Fonctionnalités](#fonctionnalites)
- [Technologies Utilisées](#technologies-utilisees)
- [Structure du Projet](#structure-du-projet)
- [Installation](#installation)
- [Usage](#usage)
- [Captures d'écran](#captures-d-ecran)
- [Améliorations futures](#ameliorations-futures)

## Fonctionnalités

### Visiteur
- Consultation du catalogue des cours avec pagination.
- Recherche de cours par mots-clés.
- Inscription avec choix du rôle (enseignant ou étudiant).

### Étudiant
- Inscription et accès à des cours.
- Consultation des détails des cours.
- Accès à la section “Mes cours”.

### Enseignant
- Ajout, modification et suppression de cours.
- Suivi des statistiques des cours (nombre d'inscrits, répartition).

### Administrateur
- Validation des comptes enseignants.
- Gestion des utilisateurs (activation/suspension/suppression).
- Gestion des contenus (cours, catégories, tags).
- Accès aux statistiques globales.

## Technologies Utilisées
- **Backend** : PHP 7.4+ (OOP)
- **Base de données** : MySQL
- **Autoload** : PSR-4 via Composer
- **Frontend** : HTML5, CSS3, JavaScript
- **Gestion des dépendances** : Composer

## Structure du Projet
```
youdemy_platform/
├── src/                     # Répertoire principal pour le code source
│   ├── Config/              # Configuration (ex: connexion à la BDD)
│   │   └── Database.php
│   ├── Controllers/         # Contrôleurs
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── CourseController.php
│   │   ├── CategoryController.php
│   │   └── TagController.php
│   ├── Models/              # Modèles
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Category.php
│   │   └── Tag.php
│   └── Utils/               # Classes utilitaires (ex : validation, helpers)
│       └── Validator.php
├── public/                  # Accessible au public (index, login, etc.)
│   ├── index.php            # Point d'entrée principal
│   ├── login.php            # Connexion
│   ├── register.php         # Inscription
│   └── assets/              # Fichiers CSS, JS, images
├── vendor/                  # Géré par Composer
├── composer.json            # Configuration Composer pour l'autoload
└── .htaccess                # (Optionnel) Réécriture d'URLs

```

## Installation

### Prérequis
- PHP 7.4 ou plus
- MySQL 5.7 ou plus
- Composer installé

### Étapes d'installation
1. Clonez le projet :
   ```bash
   git clone https://github.com/MouadHallaffou/Youdemy_plateform/youdemy.git
   ```

2. Accédez au dossier du projet :
   ```bash
   cd youdemy
   ```

3. Installez les dépendances via Composer :
   ```bash
   composer install
   ```

4. Configurez la base de données :
   - Importez le fichier SQL dans votre serveur MySQL.
   - Configurez `src/Config/Database.php` avec vos paramètres de connexion.

5. Démarrez votre serveur local :
   ```bash
   php -S localhost:8000 -t public
   ```

6. Accédez à l'application dans votre navigateur :
   ```
   http://localhost:8000
   ```

## Usage

### Authentification
- Accédez à `login.php` pour vous connecter.
- Accédez à `register.php` pour créer un compte.

### Dashboard Administrateur
- Accédez aux outils de gestion des utilisateurs et des contenus.

### Ajout d'un Cours (Enseignant)
- Connectez-vous en tant qu'enseignant.
- Accédez à la section “Ajouter un cours”.

## Captures d'écran
- Page de connexion
- Tableau de bord administrateur

## Améliorations futures
- Ajout d'un système de paiement pour les cours premium.
- Intégration d'API pour des cours externes.
- Optimisation du SEO pour le catalogue des cours.
- Mise en place d'une fonctionnalité de messagerie entre utilisateurs.

Créé avec ❤️ par Mouad Hallaffou
