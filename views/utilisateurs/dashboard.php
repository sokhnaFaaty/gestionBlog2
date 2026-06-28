<div class="mx-auto  mb-6 px-6">
    <div class="mb-5">
        <h2 class="text-2xl font-bold text-gray-800">Tableau de bord</h2>
        <p class="text-sm text-gray-500 ">
            Bonjour <strong><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>,
            voici un aperçu de la plateforme.
        </p>
    </div>

    <!-- Stats -->
     <div class=" my-6 rounded-[10px] bg-gray-100 p-6">

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5">
        <div class="bg-[#EEF0FF] rounded-[5px] border-r-4 border-[#1A237E] shadow-sm p-5">
            <p class="text-xs text-[#1A237E] uppercase font-semibold tracking-wide">Articles total</p>
            <p class="text-3xl font-bold text-[#1A237E] mt-1"><?= $total_articles ?></p>
        </div>
        <div class="bg-[#FFFAF1] rounded-[5px] border-r-4 border-[#9A7B40] shadow-sm p-5">
            <p class="text-xs text-[#9A7B40] uppercase font-semibold tracking-wide">En attente</p>
            <p class="text-3xl font-bold text-[#9A7B40] mt-1"><?= $articles_en_attente ?></p>
        </div>
        <div class="bg-[#E3FFE7] rounded-[5px] border-r-4 border-[#096D16] shadow-sm p-5">
            <p class="text-xs text-[#096D16] uppercase font-semibold tracking-wide">Publiés</p>
            <p class="text-3xl font-bold text-[#096D16] mt-1"><?= $articles_publies ?></p>
        </div>
        <div class="bg-[#FFECEC] rounded-[5px] border-r-4 border-[#FD0D0D] shadow-sm p-5">
            <p class="text-xs text-[#FD0D0D] uppercase font-semibold tracking-wide">Rejetés</p>
            <p class="text-3xl font-bold text-[#FD0D0D] mt-1"><?= $articles_rejetes ?></p>
        </div>
        <div class="bg-[#EFEFEF] rounded-[5px] border-r-4 border-[#000000] shadow-sm p-5">
            <p class="text-xs text-[#000000] uppercase font-semibold tracking-wide">Auteurs</p>
            <p class="text-3xl font-bold text-[#000000] mt-1"><?= $total_auteurs ?></p>
        </div>
        <div class="bg-[#F8F3FF] rounded-[5px] border-r-4 border-[#9747FF] shadow-sm p-5">
            <p class="text-xs text-[#9747FF] uppercase font-semibold tracking-wide">Commentaires</p>
            <p class="text-3xl font-bold text-[#9747FF] mt-1"><?= $total_commentaires ?></p>
        </div>
    </div>

     </div>
    <!-- Derniers articles -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mt-4">
        <div class="px-6 py-4">
            <h3 class="font-semibold text-gray-800">5 derniers articles</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-[#EFEFEF] text-xs uppercase text-black font-semibold">
                    <th class="px-6 py-3">Titre</th>
                    <th class="px-6 py-3">Auteur</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
            <?php foreach ($derniers_articles as $a): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 font-medium text-gray-900">
                        <?= htmlspecialchars($a['titre']) ?>
                    </td>
                    <td class="px-6 py-3 text-gray-500">
                        <?= htmlspecialchars($a['utilisateur_nom']) ?>
                    </td>
                    <td class="px-6 py-3 text-gray-500">
                        <?= date('d/m/Y', strtotime($a['date_publication'])) ?>
                    </td>
                    <td class="px-6 py-3">
                        <?php
                        $cls = "bg-yellow-50 text-yellow-700 border-yellow-100";
                        if ($a['statut'] === 'Publie')  $cls = "bg-green-50 text-green-700 border-green-100";
                        if ($a['statut'] === 'Rejete')  $cls = "bg-red-50 text-red-700 border-red-100";
                        ?>
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold border <?= $cls ?>">
                            <?= htmlspecialchars($a['statut']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Liens rapides (admin seulement) -->
    <?php if (hasRole('admin')): ?>
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="<?= path('admin', 'listeArticles', ['statut' => 'En attente']) ?>"
           class="block bg-yellow-50 border border-yellow-200 rounded-xl p-5 hover:bg-yellow-100 transition">
            <p class="font-semibold text-yellow-800">Articles en attente</p>
            <p class="text-sm text-yellow-600 mt-1">Valider ou rejeter</p>
        </a>
        <a href="<?= path('admin', 'listeArticles') ?>"
           class="block bg-indigo-50 border border-indigo-200 rounded-xl p-5 hover:bg-indigo-100 transition">
            <p class="font-semibold text-indigo-800">Tous les articles</p>
            <p class="text-sm text-indigo-600 mt-1">Gérer les signalements</p>
        </a>
        <a href="<?= path('admin', 'listeAuteurs') ?>"
           class="block bg-gray-50 border border-gray-200 rounded-xl p-5 hover:bg-gray-100 transition">
            <p class="font-semibold text-gray-800">Auteurs</p>
            <p class="text-sm text-gray-500 mt-1">Voir les profils</p>
        </a>
    </div>
    <?php endif; ?>
</div>