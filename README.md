# GES-BLOG 

Application web de gestion de blog développée en PHP avec une architecture MVC procédurale organisée par entité.

## Description

GES-BLOG est une plateforme de blog multi-rôles permettant la publication, la modération et la consultation d'articles avec pagination. Le projet suit une organisation MVC stricte par entité (Article, Commentaire, Catégorie, Utilisateur).

## Fonctionnalités

### Rôles
- **Admin** : gestion complète de la plateforme
- **Auteur** : rédaction et gestion de ses articles
- **Lecteur** : consultation et commentaire des articles

### Features
- Authentification (inscription / connexion / déconnexion)
- Redirection intelligente selon le rôle après connexion
- Publication d'articles avec photo de couverture
- Système de catégories
- Commentaires avec modification et suppression
- Signalement d'articles et de commentaires
- Modération des articles (publier / rejeter / bannir)
- Gestion des auteurs (bannir / débannir)
- Gestion des administrateurs
- Newsletter
- Recherche globale (articles, auteurs, catégories)
- **Pagination** sur toutes les listes

## Stack technique

- **Backend** : PHP 8 (procédural, architecture MVC par entité)
- **Base de données** : PostgreSQL
- **Frontend** : Tailwind CSS, Font Awesome
- **Déploiement** : AlwaysData (SSH)
- **Versioning** : Git / GitHub

## Installation

### Prérequis
- PHP 8+
- PostgreSQL
- Serveur web (Apache / XAMPP)

### Étapes

**1. Cloner le projet**
```bash
git clone 
cd gestionBlog
```

**2. Configurer la base de données**
```bash
psql -U postgres -d gestionBlog -f db.sql
```

**3. Configurer l'environnement**
```bash
cp env.exemple.php env.php
```

Remplir les constantes dans `env.php` :
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'gestionBlog');
define('DB_USERNAME', 'postgres');
define('DB_PASSWORD', 'votre_mot_de_passe');
```

**4. Lancer le serveur**
```bash
php -S localhost:8003 -t public router.php
```

**5. Accéder à l'application**
http://localhost:8003

### Comptes de démonstration
| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@gesblog.fr | password |
| Auteur | auteur@gesblog.fr | password |
| Lecteur | lecteur@gesblog.fr | password |

## Structure du projet
gestionBlog/
├── config/
│   ├── helpers.php          # Fonctions utilitaires (auth, loadView, path...)
│   └── validators.php       # Système de validation des formulaires
├── Controllers/
│   ├── articleController.php
│   ├── commentaireController.php
│   ├── categorieController.php
│   ├── utilisateurController.php
│   ├── authController.php
│   └── newsletterController.php
├── models/
│   ├── articleModel.php
│   ├── commentaireModel.php
│   ├── categorieModel.php
│   ├── utilisateurModel.php
│   ├── authModel.php
│   └── newsletterModel.php
├── views/
│   ├── articles/
│   │   ├── home.php
│   │   ├── liste.php
│   │   ├── listeArticles.php
│   │   ├── article.php
│   │   ├── add_article.php
│   │   └── edit_article.php
│   ├── commentaires/
│   │   └── signalements.php
│   ├── categories/
│   │   ├── listeCategories.php
│   │   └── editCategorie.php
│   ├── utilisateurs/
│   │   ├── dashboard.php
│   │   ├── listeAuteurs.php
│   │   ├── listeAdmins.php
│   │   ├── addAdmin.php
│   │   └── listeNewsletter.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── partials/
│   │   └── pagination.php
│   └── layouts/
│       ├── base.layout.php
│       ├── side.layout.php
│       └── auth.layout.php
├── db/
│   └── database.php
├── public/
│   ├── uploads/
│   ├── css/
│   ├── index.php
│   └── router.php
├── routes/
│   └── web/
│       └── router.php
├── db.sql
├── env.exemple.php
└── README.md

## Architecture MVC

### Organisation par entité
Le projet suit une organisation **par entité** — chaque entité métier a son propre controller, model et dossier de views :

| Entité | Controller | Model | Views |
|--------|-----------|-------|-------|
| Article | `articleController.php` | `articleModel.php` | `views/articles/` |
| Commentaire | `commentaireController.php` | `commentaireModel.php` | `views/commentaires/` |
| Catégorie | `categorieController.php` | `categorieModel.php` | `views/categories/` |
| Utilisateur | `utilisateurController.php` | `utilisateurModel.php` | `views/utilisateurs/` |
| Auth | `authController.php` | `authModel.php` | `views/auth/` |
| Newsletter | `newsletterController.php` | `newsletterModel.php` | — |

### Cycle d'une requête
URL: /article/home
↓
public/index.php       → définit ROOT, WEBROOT, charge helpers/validators
↓
routes/web/router.php  → mappe "article" → articleController.php
↓
articleController.php  → vérifie auth/rôle, appelle les fonctions du model
↓
articleModel.php       → exécute la requête SQL, retourne les données
↓
loadView()             → injecte les données dans la view + layout

### Gestion des permissions
Les vérifications de rôle se font **à l'intérieur de chaque action** :

```php
$add = function () {
    if (!hasRole("auteur") && !hasRole("admin")) {
        redirectTo("article", "home");
    }
    // logique...
};
```

### Helpers principaux
```php
auth()                    // vérifie que l'utilisateur est connecté
isConnected()             // retourne true/false
hasRole(string $role)     // vérifie le rôle
loadView($view, $datas)   // charge une view dans un layout
path($controller, $action) // génère une URL
redirectTo($controller, $action) // redirige
```

## Pagination

La pagination est implémentée sur toutes les listes via un composant réutilisable.

### Fonctionnement
URL: /article/home?page=2
page     = $_GET["page"] ?? 1
offset   = (page - 1) * parPage
total    = countArticlesPublies()
articles = findArticlesPubliesPagines(page, parPage)

### Limites par page
| Page | Par page |
|------|---------|
| Articles publiés | 6 |
| Mes articles (auteur) | 6 |
| Articles admin | 10 |
| Auteurs | 10 |
| Admins | 10 |
| Catégories | 10 |

### Composant pagination
Le composant `views/partials/pagination.php` est inclus dans chaque view concernée et s'adapte automatiquement à la page courante.

## Rewriting d'URL

Le `.htaccess` redirige toutes les requêtes vers `public/index.php` :

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

`index.php` parse l'URL et extrait le controller et l'action :
/article/home → controller=article, action=home
/auth/login   → controller=auth,    action=login

## Upload de photos

Les images sont uploadées dans `public/uploads/` :

```php
// Validation de l'extension
$allowedExtensions = ["jpg", "jpeg", "png", "webp", "gif"];

// Génération d'un nom unique
$imageName = time() . "_" . uniqid() . "." . $fileExtension;

// Déplacement vers le dossier uploads
move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFileDir . $imageName);
```

## Branches Git

| Branche | Description |
|---------|-------------|
| `main` | Production (protégée) |
| `develop` | Développement principal |
| `refacto/organisation-par-entite` | Refactorisation MVC par entité |
| `feature/pagination` | Ajout de la pagination |
| `fix/allBranche` | Soft deletes (à venir) |

## Versions

| Tag | Description |
|-----|-------------|
| `v1.0` | Version initiale  |
| `v1.2.0` | Version initiale stable apres quelques refactors et fix |
| `v2.0.0` | Refacto par entité + pagination |
