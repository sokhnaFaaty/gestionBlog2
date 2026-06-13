
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion Blog</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

</head>
<body class="bg-gray-50 font-sans antialiased">
 
  <!-- Navigation -->
  <nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
 
        <!-- Logo + liens -->
        <div class="flex items-center space-x-8">
          <a href="<?= path('admin', 'dashboard') ?>" class="text-xl font-bold text-indigo-600">📖 GES-BLOG</a>
          <div class="hidden sm:flex space-x-2">
            <a href="<?= path('admin', 'dashboard') ?>"
               class="px-3 py-2 rounded-md text-sm font-medium <?= ($_REQUEST['controller'] ?? '') == 'dashboard' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-100' ?> transition">
              Dashboard
            </a>
            <a href="<?= path('auteur', 'liste') ?>"
               class="px-3 py-2 rounded-md text-sm font-medium <?= ($_REQUEST['controller'] ?? '') == 'auteur' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-100' ?> transition">
              Articles
            </a>
            <?php if (hasRole('admin')): ?>
            <a href="<?= path('admin', 'index') ?>"
               class="px-3 py-2 rounded-md text-sm font-medium <?= ($_REQUEST['controller'] ?? '') == 'admin' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-100' ?> transition">
              Administration
            </a>
            <?php endif; ?>
          </div>
        </div>
 
        <!-- Utilisateur connecté + déconnexion -->
        <div class="flex items-center space-x-4">
          <?php if (isConnected()): ?>
            <p class="text-sm text-gray-600">
              Bonjour, <strong><?= htmlspecialchars($_SESSION['user']['prenom'] ?? $_SESSION['user']['nom'] ?? 'Utilisateur') ?></strong>
            </p>
            <a href="<?= path('auth', 'logout') ?>"
               class="px-3 py-2 rounded-md text-sm font-medium bg-red-500 text-white hover:bg-red-600 transition">
              Déconnexion
            </a>
          <?php endif; ?>
        </div>
 
      </div>
    </div>
  </nav>
 
  <!-- Contenu principal -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?= $content ?>
  </main>