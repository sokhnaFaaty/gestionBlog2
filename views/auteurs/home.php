<div class="max-w-4xl mx-auto">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Articles publiés</h2>

    <?php if (empty($articles)): ?>
        <p class="text-center text-gray-400 py-16">Aucun article publié pour le moment.</p>
    <?php else: ?>
        <div class="space-y-4">
        <?php foreach ($articles as $article): ?>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
                <div class="flex flex-col sm:flex-row items-start gap-6">
                    <?php if (!empty($article['image'])): ?>
                        <div class="w-full sm:w-48 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                            <img src="/uploads/<?= htmlspecialchars($article['image']) ?>"
                                 alt="<?= htmlspecialchars($article['titre']) ?>"
                                 class="w-full h-full object-cover">
                        </div>
                    <?php else: ?>
                        <div class="w-full sm:w-48 h-32 flex-shrink-0 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                            <span class="text-gray-400 text-xs">Pas d image</span>
                        </div>
                    <?php endif; ?>
                    <div class="flex-1">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-100 mb-2">
                            <?= htmlspecialchars($article['categorie_nom']) ?>
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="<?= path('auteur', 'article', ['id' => $article['id_article']]) ?>"
                               class="hover:text-indigo-600 transition">
                                <?= htmlspecialchars($article['titre']) ?>
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Par <strong><?= htmlspecialchars($article['utilisateur_nom']) ?></strong>
                            · <?= date('d/m/Y', strtotime($article['date_publication'])) ?>
                            · <?= $article['nb_commentaires'] ?> commentaire(s)
                        </p>
                        <p class="text-sm text-gray-600 mt-3">
                            <?= htmlspecialchars(substr($article['contenu'], 0, 200)) ?>...
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= path('auteur', 'article', ['id' => $article['id_article']]) ?>"
                       class="text-sm font-medium text-indigo-700 hover:text-indigo-800 transition">
                        Lire la suite &rarr;
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
