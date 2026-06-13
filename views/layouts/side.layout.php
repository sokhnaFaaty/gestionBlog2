<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='UTF-8'>
    <title>Gestion Blog</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class='bg-gray-50 flex h-screen overflow-hidden'>

    <!-- Modal de confirmation réutilisable
         Usage : <button type="button" onclick="confirmerAction(this)"
                         data-form="id-du-formulaire"
                         data-message="Votre message"> -->
    <div id="modal-confirm"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
         role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="fermerModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 border border-red-100 mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
            </div>
            <h3 class="text-center text-lg font-bold text-gray-800 mb-2">Confirmation</h3>
            <p id="modal-message" class="text-center text-sm text-gray-500 mb-6">
                Êtes-vous sûr de vouloir effectuer cette action ?
            </p>
            <div class="flex gap-3">
                <button onclick="fermerModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button id="modal-btn-confirmer"
                        class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside class='w-64 bg-indigo-900 text-white flex-shrink-0 flex flex-col h-full'>

        <!-- Logo -->
        <div class='px-6 py-5 border-b border-indigo-700'>
            <span class='text-xl font-bold tracking-wide text-white'>
                <i class='fa-solid fa-book-open mr-2'></i>GES-BLOG
            </span>
        </div>

        <!-- Infos utilisateur -->
        <div class='px-6 py-4 border-b border-indigo-700'>
            <p class='text-xs text-indigo-300 uppercase font-semibold tracking-wide mb-1'>Connecté en tant que</p>
            <p class='text-sm font-semibold text-white truncate'>
                <?= htmlspecialchars($_SESSION['user']['prenom'] ?? '') ?>
                <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?>
            </p>
            <?php
            switch ($_SESSION['user']['role']) {
                case 'admin':
                    $roleLabel = '<i class="fa-solid fa-shield-halved mr-1"></i> Administrateur';
                    break;
                case 'auteur':
                    $roleLabel = '<i class="fa-solid fa-pen-nib mr-1"></i> Auteur';
                    break;
                case 'lecteur':
                    $roleLabel = '<i class="fa-solid fa-eye mr-1"></i> Lecteur';
                    break;
                default:
                    $roleLabel = $_SESSION['user']['role'];
                    break;
            }
            ?>
            <p class='text-xs text-indigo-300 mt-0.5'><?= $roleLabel ?></p>
        </div>

        <!-- Navigation -->
        <nav class='flex-1 px-3 py-4 space-y-1 overflow-y-auto'>
            <?php
            $currentController = $_REQUEST['controller'] ?? '';
            $currentAction     = $_REQUEST['action'] ?? '';

            // Classe active vs inactive
            $base   = 'flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150';
            $active = $base . ' bg-indigo-700 text-white';
            $normal = $base . ' text-indigo-200 hover:bg-indigo-800 hover:text-white';

            $role = $_SESSION['user']['role'];

            if ($role === 'auteur'): ?>
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Articles</p>
                <a href='<?= path("auteur", "liste") ?>'
                    class='<?= ($currentController === 'auteur' && $currentAction !== 'add') ? $active : $normal ?>'>
                    <i class='fa-solid fa-file-lines w-4 text-center'></i> Mes articles
                </a>
                <a href='<?= path("auteur", "add") ?>'
                    class='<?= ($currentController === 'auteur' && $currentAction === 'add') ? $active : $normal ?>'>
                    <i class='fa-solid fa-plus w-4 text-center'></i> Nouvel article
                </a>

            <?php elseif ($role === 'admin'): ?>
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Tableau de bord</p>
                <a href='<?= path("admin", "dashboard") ?>'
                    class='<?= in_array($currentAction, ['dashboard', 'index']) ? $active : $normal ?>'>
                    <i class='fa-solid fa-gauge w-4 text-center'></i> Vue d'ensemble
                </a>

                <p class='px-4 pt-4 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Gestion</p>
                <a href='<?= path("admin", "listeArticles") ?>'
                    class='<?= $currentAction === 'listeArticles' ? $active : $normal ?>'>
                    <i class='fa-solid fa-newspaper w-4 text-center'></i> Articles
                </a>
                <a href='<?= path("admin", "listeAuteurs") ?>'
                    class='<?= $currentAction === 'listeAuteurs' ? $active : $normal ?>'>
                    <i class='fa-solid fa-users w-4 text-center'></i> Auteurs
                </a>
                <a href='<?= path("admin", "listeCategories") ?>'
                    class='<?= $currentAction === 'listeCategories' ? $active : $normal ?>'>
                    <i class='fa-solid fa-tags w-4 text-center'></i> Catégories
                </a>
                <a href='<?= path("admin", "addAdmin") ?>'
                    class='<?= $currentAction === 'addAdmin' ? $active : $normal ?>'>
                    <i class='fa-solid fa-user-shield w-4 text-center'></i> Ajouter un admin
                </a>
                <a href='<?= path("admin", "listeAdmins") ?>'
                    class='<?= $currentAction === "listeAdmins" ? $active : $normal ?>'>
                    <i class='fa-solid fa-user-shield w-4 text-center'></i> Administrateurs
                </a>

            <?php elseif ($role === 'lecteur'): ?>
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Blog</p>
                <a href='<?= path("lecteur", "liste") ?>'
                    class='<?= $currentAction === 'liste' ? $active : $normal ?>'>
                    <i class='fa-solid fa-newspaper w-4 text-center'></i> Articles publiés
                </a>
            <?php endif; ?>
        </nav>

        <!-- Déconnexion (tout en bas) -->
        <div class='px-3 py-4 border-t border-indigo-700'>
            <a href='<?= path("auth", "logout") ?>'
                class='flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-indigo-200 hover:bg-red-600 hover:text-white transition-colors duration-150'>
                <i class='fa-solid fa-right-from-bracket w-4 text-center'></i> Déconnexion
            </a>
        </div>

    </aside>

    <!-- Contenu principal -->
    <main class='flex-1 overflow-y-auto p-8'>
        <?= $content ?>
    </main>


<script>
function confirmerAction(btn) {
    var formId  = btn.dataset.form;
    var message = btn.dataset.message || 'Êtes-vous sûr de vouloir effectuer cette action ?';
    document.getElementById('modal-message').textContent = message;
    document.getElementById('modal-btn-confirmer').onclick = function () {
        document.getElementById(formId).submit();
    };
    document.getElementById('modal-confirm').classList.remove('hidden');
}

function fermerModal() {
    document.getElementById('modal-confirm').classList.add('hidden');
    document.getElementById('modal-btn-confirmer').onclick = null;
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') fermerModal();
});
</script>
</body>

</html>