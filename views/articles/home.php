<?php
$filtres        = $filtres ?? ["id_auteur" => null, "id_categorie" => null, "q" => ""];
$auteurFiltre   = $filtres["id_auteur"] ?? null;
$categorieFiltre = $filtres["id_categorie"] ?? null;
$recherche      = $filtres["q"] ?? "";
$aDesFiltres    = $auteurFiltre || $categorieFiltre || $recherche !== "";
?>
<?php if (!isConnected()): ?>
<div class="bg-[#1A237E] text-white py-12 px-4 rounded-2xl mb-10 text-center shadow-lg">
    <h1 class="text-3xl font-bold mb-3">Bienvenue sur Plume &amp; Clic</h1>
    <p class="text-indigo-100 mb-6 text-sm">Lisez nos articles et rejoignez la communauté pour commenter et interagir.</p>
    <div class="flex justify-center gap-4">
        <a href="<?= path('auth', 'register') ?>"
           class="px-6 py-2.5 bg-white text-[#1A237E]  font-semibold rounded-lg hover:bg-indigo-50 transition text-sm">
            S'inscrire
        </a>
        <a href="<?= path('auth', 'login') ?>"
           class="px-6 py-2.5 border border-white text-white font-semibold rounded-lg hover:bg-[#141A5F] transition text-sm">
            Se connecter
        </a>
    </div>
</div>
<?php endif; ?>

<div class="max-w-4xl mx-auto">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Articles publiés</h2>

    <!-- Filtres & recherche -->
    <form method="GET" action="<?= path('article', 'home') ?>"
          class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2 lg:col-span-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($recherche) ?>"
                       placeholder="Rechercher un article..."
                       class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1A237E] focus:border-[#1A237E] outline-none transition">
            </div>

            <select name="auteur"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-[#1A237E] focus:border-[#1A237E] outline-none transition">
                <option value="">Tous les auteurs</option>
                <?php foreach (($auteurs ?? []) as $a): ?>
                    <option value="<?= $a['id_utilisateur'] ?>"
                        <?= (string)$auteurFiltre === (string)$a['id_utilisateur'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars(trim(($a['prenom'] ?? '') . ' ' . $a['nom'])) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="categorie"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-[#1A237E] focus:border-[#1A237E] outline-none transition">
                <option value="">Toutes les catégories</option>
                <?php foreach (($categories ?? []) as $c): ?>
                    <option value="<?= $c['id_categorie'] ?>"
                        <?= (string)$categorieFiltre === (string)$c['id_categorie'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['libelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-[#1A237E] text-white text-sm font-medium rounded-lg hover:bg-[#141A5F] transition shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Filtrer
                </button>
                <?php if ($aDesFiltres): ?>
                <a href="<?= path('article', 'home') ?>"
                   title="Réinitialiser les filtres"
                   class="px-3 py-2.5 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <?php if (empty($articles)): ?>
        <p class="text-center text-gray-400 py-16">
            <?= $aDesFiltres ? 'Aucun article ne correspond à votre recherche.' : 'Aucun article publié pour le moment.' ?>
        </p>
    <?php else: ?>
        <div class="space-y-4">
        <?php foreach ($articles as $article): ?>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-start gap-6">

                    <?php if (!empty($article['image'])): ?>
                        <div class="w-full sm:w-48 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                            <img src="<?= WEBROOT ?>uploads/<?= htmlspecialchars($article['image']) ?>"
                                 alt="<?= htmlspecialchars($article['titre']) ?>"
                                 onerror="this.onerror=null;this.src='<?= WEBROOT ?>uploads/image-indisponible.svg'"
                                 loading="lazy"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    <?php else: ?>
                        <div class="w-full sm:w-48 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center border border-gray-200">
                            <span class="text-gray-400 text-xs">Pas d'image</span>
                        </div>
                    <?php endif; ?>

                    <div class="flex-1">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-[#1A237E] border border-blue-100 mb-2">
                            <?= htmlspecialchars($article['categorie_nom']) ?>
                        </span>
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="<?= path('article', 'voir', ['id' => $article['id_article']]) ?>"
                               class="hover:text-[#1A237E] transition">
                                <?= htmlspecialchars($article['titre']) ?>
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Par <strong><?= htmlspecialchars($article['utilisateur_nom']) ?></strong>
                            · <?= date('d/m/Y', strtotime($article['date_publication'])) ?>
                            · <?= $article['nb_commentaires'] ?> commentaire(s)
                            · <i class="fa-solid fa-heart text-red-400"></i> <?= (int)$article['nb_jaimes'] ?>
                        </p>
                        <p class="text-sm text-gray-600 mt-3 line-clamp-2">
                            <?= htmlspecialchars(substr($article['contenu'], 0, 200)) ?>…
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?= path('article', 'voir', ['id' => $article['id_article']]) ?>"
                       class="text-sm font-medium text-[#1A237E]  hover:text-[#141A5F] transition">
                        Lire la suite <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once ROOT . "/views/partials/pagination.php"; ?>
