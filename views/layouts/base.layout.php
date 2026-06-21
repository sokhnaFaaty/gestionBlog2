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

  <!-- Modal de confirmation réutilisable
       Usage : <button type="button" onclick="confirmerAction(this)"
                       data-form="id-du-formulaire"
                       data-message="Votre message"> -->
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
  <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">

        <!-- Logo + liens -->
        <div class="flex items-center space-x-8">
          <a href="<?= path('admin', 'dashboard') ?>" class="text-xl font-bold text-[#1A237E]"><i class="fa-solid fa-book-open mr-2"></i>GES-BLOG</a>
          <div class="hidden sm:flex space-x-2">
            <?php if (!hasRole('lecteur')): ?>
            <a href="<?= path('admin', 'dashboard') ?>"
               class="px-3 py-2 rounded-md text-sm font-medium <?= ($_REQUEST['controller'] ?? '') == 'dashboard' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-100' ?> transition">
              Dashboard
            </a>
            <?php endif; ?>
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

  <!-- Footer -->
  <footer class="bg-[#1A237E] text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- Logo + description -->
        <div>
          <a href="<?= path('lecteur', 'home') ?>" class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-book-open"></i> GES-BLOG
          </a>
          <p class="mt-3 text-indigo-200 text-sm leading-relaxed">
            Découvrez des articles de qualité rédigés par nos auteurs. Rejoignez la communauté et partagez vos idées.
          </p>
          <div class="flex gap-3 mt-5">
            <a href="#" class="w-8 h-8 rounded-full bg-indigo-700 hover:bg-indigo-600 flex items-center justify-center transition" aria-label="Twitter">
              <i class="fa-brands fa-x-twitter text-xs"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-indigo-700 hover:bg-indigo-600 flex items-center justify-center transition" aria-label="Instagram">
              <i class="fa-brands fa-instagram text-xs"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-indigo-700 hover:bg-indigo-600 flex items-center justify-center transition" aria-label="Facebook">
              <i class="fa-brands fa-facebook-f text-xs"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-indigo-700 hover:bg-indigo-600 flex items-center justify-center transition" aria-label="LinkedIn">
              <i class="fa-brands fa-linkedin-in text-xs"></i>
            </a>
          </div>
        </div>

        <!-- Liens rapides -->
        <div>
          <h3 class="text-sm font-semibold text-indigo-100 uppercase tracking-wider mb-4">Navigation</h3>
          <ul class="space-y-2.5">
            <li>
              <a href="<?= path('lecteur', 'home') ?>" class="text-indigo-200 hover:text-white text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-house w-4"></i>Accueil
              </a>
            </li>
            <li>
              <a href="<?= path('lecteur', 'liste') ?>" class="text-indigo-200 hover:text-white text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-newspaper w-4"></i>Articles
              </a>
            </li>
            <?php if (!isConnected()): ?>
            <li>
              <a href="<?= path('auth', 'register') ?>" class="text-indigo-200 hover:text-white text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-user-plus w-4"></i>S'inscrire
              </a>
            </li>
            <li>
              <a href="<?= path('auth', 'login') ?>" class="text-indigo-200 hover:text-white text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-right-to-bracket w-4"></i>Se connecter
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 class="text-sm font-semibold text-indigo-100 uppercase tracking-wider mb-4">Newsletter</h3>
          <p class="text-indigo-200 text-sm mb-4 leading-relaxed">
            Inscrivez-vous à notre newsletter pour ne manquer aucune publication.
          </p>
          <form class="flex gap-2" method="POST" action="<?= path('newsletter', 'subscribe') ?>">
            <input type="email" name="email" placeholder="Votre email..."
                   required
                   class="flex-1 px-3 py-2 rounded-lg text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-300 min-w-0">
            <button type="submit"
                    class="px-4 py-2 bg-white text-[#1A237E] text-sm font-semibold rounded-lg hover:bg-indigo-50 transition whitespace-nowrap">
              S'inscrire
            </button>
          </form>
        </div>

      </div>
    </div>

    
  </footer>

<script>
function confirmerAction(btn) {
    var formId  = btn.dataset.form;
    var message = btn.dataset.message || 'Êtes-vous sûr de vouloir effectuer cette action ?';
    document.getElementById('modal-message').textContent = message;
    document.getElementById('modal-btn-confirmer').onclick = function () {
        document.getElementById(formId).submit();
    };
    document.getElementById('modal-confirm').classList.remove('hidden');
}

function fermerModal() {
    document.getElementById('modal-confirm').classList.add('hidden');
    document.getElementById('modal-btn-confirmer').onclick = null;
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') fermerModal();
});
</script>
</body>
</html>
