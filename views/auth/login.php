

  <div class="bg-white rounded-2xl shadow-xl border border-gray-200 w-full max-w-md p-8">
    <!-- Logo / Titre -->
    <div class="text-center mb-8">
      <div class="text-4xl mb-2 text-[#1A237E]">    <i class="fa-solid fa-book"></i></div>
      <h1 class="text-2xl font-bold text-gray-900">GESTION BLOG</h1>
      <p class="text-sm text-gray-500 mt-1">Connectez-vous pour accéder au tableau de bord</p>
    </div>
          <span class="text-red-800"> <?=$errors["connect"] ?? "" ?></span>

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
        <input type="password" name="password" id="password" 
               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
               placeholder="••••••••">
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
