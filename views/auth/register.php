<div class="bg-white rounded-2xl shadow-xl border border-gray-200 w-full max-w-md p-8">
    <!-- Titre -->
    <div class="text-center mb-6 text-[#1A237E]">
      <div class="text-4xl mb-2"> <i class="fa-solid fa-pen-to-square"></i>
</div>
      <h1 class="text-2xl font-bold text-gray-900">INSCRIPTION</h1>
      <p class="text-sm text-gray-500 mt-1">Créez votre compte pour rejoindre le blog</p>
    </div>

    <!-- Message d'erreur global -->
    <?php if (isset($errors["global"])): ?>
        <div class="bg-red-50 text-red-800 text-sm p-3 rounded-lg mb-4 text-center border border-red-200">
            <?= $errors["global"] ?>
        </div>
    <?php endif; ?>

    <form action="<?= WEBROOT ?>" method="POST" class="space-y-4">
      <input type="hidden" name="controller" value="auth">
      <input type="hidden" name="action" value="register">

      <!-- Prénom et Nom côte à côte -->
      <div class="grid grid-cols-2 gap-4">
        <!-- Prénom -->
        <div>
          <label for="prenom" class="block text-xs font-medium text-gray-700 mb-1">Prénom</label>
          <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" 
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                 placeholder="Moussa">
          <span class="text-red-800 text-xs block mt-0.5"> <?=$errors["prenom"] ?? "" ?></span>
        </div>
        
        <!-- Nom -->
        <div>
          <label for="nom" class="block text-xs font-medium text-gray-700 mb-1">Nom</label>
          <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" 
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                 placeholder="Diallo">
          <span class="text-red-800 text-xs block mt-0.5"> <?=$errors["nom"] ?? "" ?></span>
        </div>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Adresse email</label>
        <input type="text" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
               placeholder="vous@exemple.fr">
        <span class="text-red-800 text-xs block mt-0.5"> <?=$errors["email"] ?? "" ?></span>
      </div>

      <!-- Mot de passe -->
      <div>
        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Mot de passe</label>
        <input type="password" name="password" id="password" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
               placeholder="••••••••">
        <span class="text-red-800 text-xs block mt-0.5"> <?=$errors["password"] ?? "" ?></span>
      </div>

      <!-- Choix du Rôle -->
      <div>
        <label class="block text-xs font-medium text-gray-700 mb-2">Vous êtes ?</label>
        <div class="grid grid-cols-2 gap-4">
          <label class="flex items-center justify-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
            <input type="radio" name="role" value="lecteur" class="sr-only peer" <?= (isset($_POST['role']) && $_POST['role'] === 'lecteur') ? 'checked' : (!isset($_POST['role']) ? 'checked' : '') ?>>
            <div class="text-center peer-checked:text-[#1A237E] font-medium text-sm">
<span class="block text-xl text-[#1A237E]">
    <i class="fa-solid fa-eye"></i>
</span>Lecteur
            </div>
          </label>
          <label class="flex items-center justify-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
            <input type="radio" name="role" value="auteur" class="sr-only peer" <?= (isset($_POST['role']) && $_POST['role'] === 'auteur') ? 'checked' : '' ?>>
            <div class="text-center peer-checked:text-[#1A237E] font-medium text-sm">
              <span class="block text-xl text-[#1A237E]">
    <i class="fa-solid fa-pen-to-square"></i>
</span>Auteur
            </div>
          </label>
        </div>
        <span class="text-red-800 text-xs block mt-0.5"> <?=$errors["role"] ?? "" ?></span>
      </div>

      <!-- Bouton -->
      <button type="submit" name="register_btn"
              class="w-full py-2.5 bg-[#1A237E] text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm mt-2">
        S'inscrire
      </button>
    </form>

    <!-- Lien vers connexion -->
    <p class="mt-6 text-center text-sm text-gray-500">
      Déjà inscrit ? <a href="<?= WEBROOT ?>?controller=auth&action=login" class="text-indigo-600 hover:underline">Se connecter</a>
    </p>
</div>
