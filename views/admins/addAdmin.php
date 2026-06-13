<div class="max-w-lg mx-auto my-8 px-4">

    <div class="mb-6 pb-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Ajouter un administrateur</h2>
        <p class="text-sm text-gray-500 mt-1">
            Le compte créé aura un accès complet à l'espace d'administration.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

        <?php if (isset($errors['global'])): ?>
            <div class="bg-red-50 text-red-800 text-sm p-3 rounded-lg mb-5 border border-red-200">
                <?= $errors['global'] ?>
            </div>
        <?php endif; ?>

        <form method="POST"
              action="index.php?controller=admin&action=addAdmin"
              class="space-y-5">

            <!-- Prénom + Nom -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="prenom" class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                    <input type="text" name="prenom" id="prenom"
                           value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                           placeholder="Moussa"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <?php if (isset($errors['prenom'])): ?>
                        <span class="text-red-600 text-xs mt-1 block"><?= $errors['prenom'] ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                    <input type="text" name="nom" id="nom"
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                           placeholder="Diallo"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <?php if (isset($errors['nom'])): ?>
                        <span class="text-red-600 text-xs mt-1 block"><?= $errors['nom'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                <input type="text" name="email" id="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="admin@gesblog.fr"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                <?php if (isset($errors['email'])): ?>
                    <span class="text-red-600 text-xs mt-1 block"><?= $errors['email'] ?></span>
                <?php endif; ?>
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                <input type="password" name="password" id="password"
                       placeholder="••••••••"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                <?php if (isset($errors['password'])): ?>
                    <span class="text-red-600 text-xs mt-1 block"><?= $errors['password'] ?></span>
                <?php endif; ?>
            </div>

            <!-- Indicateur rôle -->
            <div class="flex items-center gap-2 px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                </svg>
                Ce compte sera automatiquement créé avec le rôle <strong class="ml-1">Administrateur</strong>.
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="index.php?controller=admin&action=listeAuteurs"
                   class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit" name="btn_ajouter_admin"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Créer le compte
                </button>
            </div>
        </form>
    </div>
</div>