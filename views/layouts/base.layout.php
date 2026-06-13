
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
  <div id="modal-confirm"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
         role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="fermerModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 border border-red-100 mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
            </div>
            <h3 class="text-center text-lg font-bold text-gray-800 mb-2">Confirmation</h3>
            <p id="modal-message" class="text-center text-sm text-gray-500 mb-6">
                Êtes-vous sûr de vouloir effectuer cette action ?
            </p>
            <div class="flex gap-3">
                <button onclick="fermerModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button id="modal-btn-confirmer"
                        class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
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