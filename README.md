# Plume & Clic

Application web de gestion de blog développée en PHP avec une architecture MVC procédurale organisée par entité.

## Description

Plume & Clic est une plateforme de blog multi-rôles permettant la publication, la modération et la consultation d'articles avec pagination. Le projet suit une organisation MVC stricte par entité (Article, Commentaire, Catégorie, Utilisateur), enrichie d'une API JSON pour les interactions JavaScript et d'une couche de sécurité renforcée.

## Fonctionnalités

### Rôles
- **Admin** : gestion complète de la plateforme (articles, auteurs, catégories, signalements, newsletter, administrateurs)
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
- Gestion des auteurs (bannir / débannir) et des administrateurs
- Newsletter (stockée en base de données)
- Notifications
- Pages Contact et À propos
- Recherche globale (articles, auteurs, catégories)
- **Pagination** sur toutes les listes
- Page **404** personnalisée avec retour à l'accueil

## Sécurité

- **Mots de passe hachés (bcrypt)** via `password_hash()` / `password_verify()`, avec migration automatique des anciens comptes en clair à la connexion
- **Protection CSRF globale** : jeton injecté dans tous les formulaires et les appels `fetch`, garde centrale dans `public/index.php` (réponse 419 si jeton manquant)
- **Sessions sécurisées** : `session_regenerate_id()` au login, cookie de session en `HttpOnly` + `Secure` + `SameSite=Lax`
- **Uploads contrôlés** : taille max 2 Mo, vérification du MIME réel via `finfo`, `public/uploads/.htaccess` bloquant l'exécution de scripts
- **Détection du protocole** derrière proxy/CDN (`X-Forwarded-Proto`) pour éviter le mixed content
- Erreurs de base de données journalisées, `display_errors` désactivé en production

## Stack technique

- **Backend** : PHP 8 (procédural, architecture MVC par entité + API JSON)
- **Base de données** : PostgreSQL
- **Frontend** : Tailwind CSS (CDN), Font Awesome
- **Déploiement** : AlwaysData (SSH)
- **Versioning** : Git / GitHub

## Installation

### Prérequis
- PHP 8+
- PostgreSQL
- Serveur web (Apache / XAMPP) ou PHP intégré

### Étapes

**1. Cloner le projet**
```bash
git clone https://github.com/sokhnaFaaty/gestionBlog2.git
cd gestionBlog2
```

**2. Configurer la base de données**
```bash
psql -U postgres -d plume_blog -f db/database.sql
psql -U postgres -d plume_blog -f db/migration_contact.sql
psql -U postgres -d plume_blog -f db/migration_notifications.sql
```
(adaptez les fichiers SQL et le nom de la base selon votre configuration)

**3. Configurer l'environnement**
```bash
cp env.exemple.php env.php
```

Remplir les constantes dans `env.php` :
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'plume_blog');
define('DB_USERNAME', 'postgres');
define('DB_PASSWORD', 'votre_mot_de_passe');
```

**4. Lancer le serveur**
```bash
php -S localhost:8003 -t public public/router.php
```

**5. Accéder à l'application**
http://localhost:8003

## Structure du projet
```
gestionBlog2/
├── config/
│   ├── config.php              # Constantes globales
│   ├── helpers.php             # Fonctions utilitaires (auth, loadView, path, CSRF...)
│   └── validators.php          # Système de validation des formulaires
├── Controllers/
│   ├── articleController.php   # + articleJsController.php (API JSON)
│   ├── authController.php      # + authJsController.php
│   ├── categorieController.php # + categorieJsController.php
│   ├── commentaireController.php # + commentaireJsController.php
│   ├── newsletterController.php  # + newsletterJsController.php
│   ├── notificationJsController.php
│   ├── pageController.php      # + pageJsController.php
│   └── utilisateurController.php # + utilisateurJsController.php
├── models/
│   ├── articleModel.php  commentaireModel.php  categorieModel.php
│   ├── utilisateurModel.php    # Auth + notification + contact + newsletter
│   ├── authModel.php  notificationModel.php  contactModel.php  newsletterModel.php
├── views/
│   ├── articles/  categories/  commentaires/  utilisateurs/
│   ├── auth/      pages/(404, apropos, contact)
│   ├── partials/  (pagination, header, footer)
│   └── layouts/   (base.layout.php, side.layout.php, auth.layout.php)
├── db/
│   └── database.php            # Connexion PDO
├── public/
│   ├── index.php  router.php  .htaccess
│   └── uploads/  (images, .htaccess de protection)
├── routes/web/router.php
├── env.exemple.php
└── README.md
```

## Architecture MVC

### Organisation par entité
Le projet suit une organisation **par entité** — chaque entité métier a ses contrôleurs (classique + JSON), son modèle et son dossier de vues :

| Entité | Controller | Model | Views |
|--------|-----------|-------|-------|
| Article | `articleController.php` | `articleModel.php` | `views/articles/` |
| Commentaire | `commentaireController.php` | `commentaireModel.php` | `views/commentaires/` |
| Catégorie | `categorieController.php` | `categorieModel.php` | `views/categories/` |
| Utilisateur | `utilisateurController.php` | `utilisateurModel.php` | `views/utilisateurs/` |
| Auth | `authController.php` | `authModel.php` | `views/auth/` |
| Newsletter | `newsletterController.php` | `newsletterModel.php` | — |
| Page | `pageController.php` | — | `views/pages/` |
| Notifications | — (JS) | `notificationModel.php` | — |

Le suffixe **`JsController`** désigne les contrôleurs d'API renvoyant du **JSON** (utilisés par les appels `fetch`), avec réponses normalisées (401, 403, 404, 422…).

### Cycle d'une requête
```
URL: /article/home
↓
public/index.php       → définit ROOT, WEBROOT (proxy-aware), sessions, CSRF, charge helpers/validators
↓
routes/web/router.php  → mappe "article" (ou "articlejs") vers le contrôleur ; sinon page 404
↓
articleController.php  → vérifie auth/rôle, appelle les fonctions du model
↓
articleModel.php       → exécute la requête SQL, retourne les données
↓
loadView()             → injecte les données dans la view + layout
```

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
csrfToken()               // jeton CSRF de session
verifierCsrf($token)      // vérifie le jeton
loadJson($datas, $code)   // réponse JSON (contrôleurs JsController)
notFound()                // rend la page 404 avec HTTP 404
```

## Pagination

La pagination est implémentée sur toutes les listes via le composant réutilisable `views/partials/pagination.php`.

### Limites par page
| Page | Par page |
|------|---------|
| Articles publiés | 6 |
| Mes articles (auteur) | 6 |
| Articles admin | 10 |
| Auteurs | 10 |
| Admins | 10 |
| Catégories | 10 |

## Rewriting d'URL

Le `.htaccess` de `public/` redirige toutes les requêtes vers `public/index.php` :

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

`index.php` parse l'URL et extrait le controller et l'action :
```
/article/home → controller=article, action=home
/auth/login   → controller=auth,    action=login
```

## Upload de photos

Les images sont uploadées dans `public/uploads/` avec un contrôle renforcé (taille et MIME réel) :

```php
// Taille maximale : 2 Mo
if ($image["size"] > 2 * 1024 * 1024) {
    // erreur
}

// Vérification du type réel du fichier (et non de l'extension)
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($image["tmp_name"]);
if (!in_array($mime, ["image/jpeg", "image/png", "image/webp", "image/gif"])) {
    // erreur
}

// Génération d'un nom unique + déplacement
$imageName = time() . "_" . uniqid() . "." . $extension;
move_uploaded_file($image["tmp_name"], $uploadFileDir . $imageName);
```

Le dossier `public/uploads/` contient aussi un `.htaccess` qui **bloque l'exécution de scripts** et désactive le listing de dossier.

## Branches Git

| Branche | Description |
|---------|-------------|
| `main` | Documentation et historique du projet |
| `develop` | Développement principal |
| `fix/production` | Correctifs de sécurité et de production |
| `feature/*`, `refactor/*`, `feat/*` | Fonctionnalités et refactorisations |

## Versions

| Tag | Description |
|-----|-------------|
| `v1.0` | Version initiale  |
| `v1.2.0` | Version initiale stable apres quelques refactors et fix |
| `v2.0.0` | Refacto par entité + pagination |
| `v2.1.0` | Durcissement de la sécurité (CSRF, hash des mots de passe, sessions, uploads) |