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
|App/

├── config/                 # Configuration générale du projet
│   └──  database.php      

├── controllers/                 # Contrôleurs pour la gestion des fonctionnalités
│   ├── AuthController.php       # Contrôleur pour l'authentification (login, register, logout)
│   ├── UserController.php       # Contrôleur pour la gestion des utilisateurs (profil, liste, etc.)
│   ├── CourseController.php     # Contrôleur pour la gestion des cours (ajout, modification, inscription)
│   ├── AdminController.php      # Contrôleur pour les fonctionnalités spécifiques aux administrateurs
│   └── CategoryController.php   # Contrôleur pour la gestion des catégories

├── models/                 # Classes et interactions avec la base de données (CRUD)
│   ├── User.php            # Classe représentant les utilisateurs
│   ├── Course.php          # Classe représentant les cours
│   └── Category.php        # Classe représentant les catégories

├── views/                  # Fichiers de vue pour l'affichage des pages
│   ├── auth/               # Vues liées à l'authentification
│   │   ├── login.php       # Page de connexion
│   │   └── register.php    # Page d'inscription
│   ├── courses/            # Vues pour les pages relatives aux cours
│   │   ├── list.php        # Liste des cours disponibles
│   │   └── details.php     # Détails d'un cours spécifique
│   ├── admin/              # Vues pour les fonctionnalités administratives
│   │   ├── dashboard.php   # Tableau de bord pour l'administrateur
│   │   └── users.php       # Liste des utilisateurs gérés par l'administrateur
│   └── shared/             # Éléments réutilisables sur toutes les pages
│       ├── header.php      # En-tête commun à toutes les pages
│       └── footer.php      # Pied de page commun à toutes les pages

├── public/                 # Fichiers accessibles au public
│   ├── login.php           # Accès direct à la page de connexion
│   ├── register.php        # Accès direct à la page d'inscription
│   └── assets/             # Dossier contenant les fichiers statiques
│       ├── css/            # Fichiers de style CSS
│       ├── js/             # Scripts JavaScript
│       └── images/         # Images utilisées sur le site

├── index.php               # Point d'entrée principal du projet

├── .env                    # Variables d'environnement pour configurer l'application
├── composer.json           # Fichier de configuration pour Composer, incluant l'autoloading des classes
└── README.md               # Documentation du projet

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
