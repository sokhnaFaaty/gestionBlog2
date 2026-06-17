<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Commentaires signalés</h1>
        <span class="text-sm text-gray-500"><?= count($signalements) ?> commentaire(s) signalé(s)</span>
    </div>

    <?php if (empty($signalements)): ?>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <i class="fa-solid fa-circle-check text-green-400 text-4xl mb-3"></i>
            <p class="text-gray-500 text-sm">Aucun commentaire signalé.</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
        <?php foreach ($signalements as $s): ?>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                <i class="fa-solid fa-flag mr-1"></i>
                                <?= $s['nb_signalements'] ?> signalement(s)
                            </span>
                            <span class="text-xs text-gray-400">
                                <?= date('d/m/Y à H:i', strtotime($s['date_commentaire'])) ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-800 font-medium mb-0.5">
                            <?= htmlspecialchars($s['auteur_nom']) ?>
                            <span class="font-normal text-gray-400">sur</span>
                            <em><?= htmlspecialchars($s['article_titre']) ?></em>
                        </p>
                        <p class="text-sm text-gray-600 mt-1 border-l-4 border-red-100 pl-3">
                            <?= htmlspecialchars($s['contenu']) ?>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <form id="form-sup-com-<?= $s['id_commentaire'] ?>"
                              method="POST"
                              action="<?= path('admin', 'supprimerCommentaireSignale') ?>"
                              class="hidden">
                            <input type="hidden" name="id_commentaire" value="<?= $s['id_commentaire'] ?>">
                        </form>
                        <button type="button"
                                onclick="confirmerAction(this)"
                                data-form="form-sup-com-<?= $s['id_commentaire'] ?>"
                                data-message="Supprimer définitivement ce commentaire ?"
                                class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition">
                            <i class="fa-solid fa-trash mr-1"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
