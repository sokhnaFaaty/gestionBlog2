<div class="max-w-4xl mx-auto my-8 px-4">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Articles publiés</h2>
        <p class="text-sm text-gray-500 mt-1">Découvrez les dernières publications.</p>
    </div>

    <?php if (empty($articles)): ?>
        <p class="text-center text-gray-400 py-16">Aucun article publié pour le moment.</p>
    <?php else: ?>
        <div class="space-y-4">
        <?php foreach ($articles as $article): ?>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-100 mb-2">
                            <?= htmlspecialchars($article['categorie_nom']) ?>
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="index.php?controller=lecteur&action=article&id=<?= $article['id_article'] ?>"
                               class="hover:text-indigo-600 transition">
                                <?= htmlspecialchars($article['titre']) ?>
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Par <strong><?= htmlspecialchars($article['utilisateur_nom']) ?></strong>
                            · <?= date('d/m/Y', strtotime($article['date_publication'])) ?>
                            · <?= $article['nb_commentaires'] ?> commentaire(s)
                        </p>
                        <p class="text-sm text-gray-600 mt-3 line-clamp-2">
                            <?= htmlspecialchars(substr($article['contenu'], 0, 200)) ?>…
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="index.php?controller=lecteur&action=article&id=<?= $article['id_article'] ?>"
                       class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                        Lire la suite →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>