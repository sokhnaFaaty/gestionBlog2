<div class="max-w-3xl mx-auto my-8 px-4">

    <!-- Article -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 mb-6">
        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-100 mb-3">
            <?= htmlspecialchars($article['categorie_nom']) ?>
        </span>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">
            <?= htmlspecialchars($article['titre']) ?>
        </h1>
        <p class="text-sm text-gray-500 mb-6">
            Par <strong><?= htmlspecialchars($article['utilisateur_nom']) ?></strong>
            · <?= date('d/m/Y à H:i', strtotime($article['date_publication'])) ?>
        </p>
        <div class="text-gray-700 leading-relaxed whitespace-pre-line">
            <?= htmlspecialchars($article['contenu']) ?>
        </div>

        <?php if (isConnected()): ?>
        <div class="mt-6 pt-4 border-t border-gray-100">
            <form id="form-signaler-art-<?= $article['id_article'] ?>"
                  method="POST"
                  action="<?= path('lecteur', 'signalerArticle') ?>"
                  class="hidden">
                <input type="hidden" name="id_article" value="<?= $article['id_article'] ?>">
                <button class="text-xs text-gray-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-flag"></i> Signaler cet article
                </button>
            </form>
            <button type="button"
                    onclick="confirmerAction(this)"
                    data-form="form-signaler-art-<?= $article['id_article'] ?>"
                    data-message="Signaler cet article ?"
                    class="text-xs text-gray-400 hover:text-red-500 transition">
                <i class="fa-solid fa-flag"></i> Signaler cet article
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Commentaires -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            <?= count($commentaires) ?> commentaire(s)
        </h2>

        <?php if (empty($commentaires)): ?>
            <p class="text-sm text-gray-400">Soyez le premier à commenter.</p>
        <?php else: ?>
            <div class="space-y-4">
            <?php foreach ($commentaires as $c): ?>
                <div class="border-l-4 border-indigo-100 pl-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                <?= htmlspecialchars($c['utilisateur_nom']) ?>
                                <span class="font-normal text-gray-400 ml-2">
                                    <?= date('d/m/Y à H:i', strtotime($c['date_commentaire'])) ?>
                                </span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($c['contenu']) ?></p>
                        </div>
                        <?php if (isConnected()): ?>
                        <div class="flex-shrink-0">
                            <form id="form-signaler-com-<?= $c['id_commentaire'] ?>"
                                  method="POST"
                                  action="<?= path('lecteur', 'signalerCommentaire') ?>"
                                  class="hidden">
                                <input type="hidden" name="id_commentaire" value="<?= $c['id_commentaire'] ?>">
                                <input type="hidden" name="id_article"     value="<?= $article['id_article'] ?>">
                            </form>
                            <button type="button"
                                    onclick="confirmerAction(this)"
                                    data-form="form-signaler-com-<?= $c['id_commentaire'] ?>"
                                    data-message="Signaler ce commentaire ?"
                                    class="text-xs text-gray-300 hover:text-red-400 transition">
                                <i class="fa-solid fa-flag"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Formulaire commentaire -->
    <?php if (isConnected()): ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Laisser un commentaire</h2>
        <form method="POST" action="<?= path('lecteur', 'ajouterCommentaire') ?>" class="space-y-4">
            <input type="hidden" name="id_article" value="<?= $article['id_article'] ?>">
            <div>
                <textarea name="contenu" rows="4" placeholder="Votre commentaire..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition resize-none"><?= htmlspecialchars($_POST['contenu'] ?? '') ?></textarea>
                <?php if (isset($errors['contenu'])): ?>
                    <span class="text-red-600 text-xs mt-1 block"><?= $errors['contenu'] ?></span>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Publier le commentaire
                </button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
        <p class="text-sm text-gray-500 mb-3">Vous devez avoir un compte pour commenter ou signaler.</p>
        <div class="flex justify-center gap-3">
            <a href="<?= path('auth', 'register') ?>"
               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                S'inscrire
            </a>
            <a href="<?= path('auth', 'login') ?>"
               class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition">
                Se connecter
            </a>
        </div>
    </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="<?= path('lecteur', 'home') ?>" class="text-sm text-gray-500 hover:text-gray-700 transition">
            ← Retour aux articles
        </a>
    </div>
</div>
