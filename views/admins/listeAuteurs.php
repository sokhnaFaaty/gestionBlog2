<div class="max-w-5xl mx-auto my-8 px-4">
    <div class="mb-6 pb-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Gestion des auteurs</h2>
        <p class="text-sm text-gray-500 mt-1">Consultez les profils et bannissez les auteurs en infraction.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs uppercase font-semibold">
                    <th class="px-5 py-4">Nom</th>
                    <th class="px-5 py-4">Email</th>
                    <th class="px-5 py-4">Articles</th>
                    <th class="px-5 py-4">Statut</th>
                    <th class="px-5 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
            <?php foreach ($auteurs as $auteur): ?>
            <tr class="hover:bg-gray-50 transition <?= $auteur['banni'] ? 'opacity-60' : '' ?>">
                <td class="px-5 py-4 font-medium text-gray-900">
                    <?= htmlspecialchars($auteur['nom']) ?>
                    <?php if ($auteur['banni']): ?>
                        <span class="ml-2 text-xs text-red-500 font-normal">(banni)</span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-4 text-gray-500"><?= htmlspecialchars($auteur['email']) ?></td>
                <td class="px-5 py-4"><?= $auteur['nb_articles'] ?></td>
                <td class="px-5 py-4">
                    <?php if ($auteur['banni']): ?>
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-700 border border-red-100">Banni</span>
                    <?php else: ?>
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-100">Actif</span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-4 text-right">
                    <form method="POST" action="index.php?controller=admin&action=banirAuteur"
                          onsubmit="return confirm('<?= $auteur['banni'] ? 'Débannir' : 'Bannir' ?> cet auteur ?')">
                  <input type="hidden" name="id_utilisateur" value="<?= $auteur['id_utilisateur'] ?>">
                        <button class="px-3 py-1.5 text-xs font-medium rounded-lg border transition
                            <?= $auteur['banni']
                                ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100'
                                : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' ?>">
                            <?= $auteur['banni'] ? 'Débannir' : 'Bannir' ?>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>