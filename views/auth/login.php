

  <div class="bg-white/85 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/40 w-full max-w-md p-8">
    <!-- Logo / Titre -->
    <div class="text-center mb-8">
      <div class="text-4xl mb-2 text-[#1A237E]">    <i class="fa-solid fa-book"></i></div>
      <h1 class="text-2xl font-bold text-gray-900">GESTION BLOG</h1>
      <p class="text-sm text-gray-500 mt-1">Connectez-vous pour accéder au tableau de bord</p>
    </div>
          <?php if (!empty($errors["banned"])): ?>
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-2">
            <i class="fa-solid fa-ban text-red-500 mt-0.5 text-sm"></i>
            <div>
              <p class="text-sm font-semibold text-red-700">Compte suspendu</p>
              <p class="text-xs text-red-500 mt-0.5"><?= htmlspecialchars($errors["banned"]) ?></p>
            </div>
          </div>
          <?php elseif (!empty($errors["connect"])): ?>
          <span class="text-red-800 text-sm"> <?= htmlspecialchars($errors["connect"]) ?></span>
          <?php endif; ?>

    <!-- TODO: Remplacer action par le contrôleur PHP d'authentification -->
    <form action="<?= WEBROOT ?>" method="POST" class="space-y-5">
      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
        <input type="text" name="email" id="email"  value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
               placeholder="vous@exemple.fr">
        <span class="text-red-800"> <?=$errors["email"] ?? "" ?></span>
      </div>

      <!-- Mot de passe -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
        <div class="relative">
          <input type="password" name="password" id="password"
                 class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                 placeholder="••••••••">
          <button type="button" data-toggle-password="password"
                  class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                  aria-label="Afficher le mot de passe">
            <i class="fa-solid fa-eye"></i>
          </button>
        </div>
      <span class="text-red-800"> <?=$errors["password"] ?? "" ?></span>

      </div>
<input type="hidden" name="controller" value="auth">
    <input type="hidden" name="action" value="login">
      <!-- Bouton -->
      <button type="submit" name="connect"
              class="w-full py-2.5 bg-[#1A237E] text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
        Se connecter
      </button>
    </form>

    <!-- Identifiants de démonstration -->
    <p class="mt-6 text-center text-xs text-gray-400">
      Démo : admin@gesBlog.fr / password
    </p>
    <p class="mt-6 text-center text-sm text-gray-500">
  Pas encore de compte ? <a href="<?= path('auth', 'register') ?>" class="text-indigo-600 hover:underline">S'inscrire</a>
</p>
  </div>

<script>
  document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.getElementById(btn.getAttribute('data-toggle-password'));
      var icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
        btn.setAttribute('aria-label', 'Masquer le mot de passe');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
        btn.setAttribute('aria-label', 'Afficher le mot de passe');
      }
    });
  });
</script>
