<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='UTF-8'>
    <title>Gestion Blog</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class='bg-gray-50 flex h-screen overflow-hidden'>
        <!-- ═══════════════════════════════════════════════════════
         MODAL DE CONFIRMATION RÉUTILISABLE
         Usage : <button onclick="confirmerAction(this)" 
                         data-form="id-du-formulaire"
                         data-message="Votre message de confirmation">
    ════════════════════════════════════════════════════════════ -->
    <div id="modal-confirm"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
         role="dialog" aria-modal="true">
 
        <!-- Fond sombre -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             onclick="fermerModal()"></div>
 
        <!-- Boîte du modal -->
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10">
 
            <!-- Icône -->
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 border border-red-100 mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
            </div>
 
            <!-- Titre -->
            <h3 class="text-center text-lg font-bold text-gray-800 mb-2">Confirmation</h3>
 
            <!-- Message dynamique -->
            <p id="modal-message" class="text-center text-sm text-gray-500 mb-6">
                Êtes-vous sûr de vouloir effectuer cette action ?
            </p>
 
            <!-- Boutons -->
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


    <!-- Modal de confirmation réutilisable
         Usage : <button type="button" onclick="confirmerAction(this)"
                         data-form="id-du-formulaire"
                         data-message=" message"> -->
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
    <aside class='w-64 bg-[#1A237E]  text-white flex-shrink-0 flex flex-col h-full'>

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
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Blog</p>
                <a href='<?= path("auteur", "home") ?>'
                    class='<?= in_array($currentAction, ["home", "article"]) ? $active : $normal ?>'>
                    <i class='fa-solid fa-newspaper w-4 text-center'></i> Articles publiés
                </a>

                <p class='px-4 pt-4 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Mes articles</p>
                <a href='<?= path("auteur", "liste") ?>'
                    class='<?= in_array($currentAction, ["liste", "index", "edit"]) ? $active : $normal ?>'>
                    <i class='fa-solid fa-file-lines w-4 text-center'></i> Mes articles
                </a>
                <a href='<?= path("auteur", "add") ?>'
                    class='<?= ($currentAction === "add") ? $active : $normal ?>'>
                    <i class='fa-solid fa-plus w-4 text-center'></i> Nouvel article
                </a>

            <?php elseif ($role === 'admin'): ?>
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Blog</p>
                <a href='<?= path("auteur", "home") ?>'
                    class='<?= in_array($currentAction, ["home", "article"]) && $currentController === "auteur" ? $active : $normal ?>'>
                    <i class='fa-solid fa-eye w-4 text-center'></i> Voir les articles
                </a>

                <p class='px-4 pt-4 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Tableau de bord</p>
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
                <a href='<?= path("admin", "signalementsCommentaires") ?>'
                    class='<?= $currentAction === "signalementsCommentaires" ? $active : $normal ?>'>
                    <i class='fa-solid fa-comment-slash w-4 text-center'></i> Signalements
                </a>
                <a href='<?= path("admin", "listeNewsletters") ?>'
                    class='<?= $currentAction === "listeNewsletters" ? $active : $normal ?>'>
                    <i class='fa-solid fa-envelope w-4 text-center'></i> Newsletter
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

    <!-- Zone droite : navbar + contenu -->
    <div class='flex-1 flex flex-col overflow-hidden'>

        <!-- Barre supérieure -->
        <header class='bg-[#1A237E] px-6 py-3 flex items-center justify-between flex-shrink-0 rounded-[10px] mx-3 mt-1'>
            <!-- Recherche -->
            <div class='relative w-80' id='search-wrapper'>
                <i class='fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm'></i>
                <input type='text'
                       id='search-global'
                       placeholder='Rechercher global...'
                       autocomplete='off'
                       class='w-full pl-9 pr-4 py-2 text-sm bg-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 transition'>
                <div id='search-results'
                     class='hidden absolute top-full mt-1 left-0 w-full bg-white rounded-lg shadow-lg z-50 max-h-80 overflow-y-auto text-sm border border-gray-100'>
                </div>
            </div>

            <!-- Droite : cloche + avatar -->
            <div class='flex items-center gap-5'>
                <!-- Cloche -->
                <button class='relative text-white hover:text-indigo-200 transition'>
                    <i class='fa-solid fa-bell text-lg'></i>
                </button>

                <!-- Avatar + nom -->
                <div class='flex items-center gap-3'>
                    <div class='w-9 h-9 rounded-full bg-white text-[#1A237E] flex items-center justify-center text-sm font-bold uppercase'>
                        <?= mb_substr($_SESSION['user']['prenom'] ?? '', 0, 1) . mb_substr($_SESSION['user']['nom'] ?? '', 0, 1) ?>
                    </div>
                    <span class='text-sm font-medium text-white'>
                        <?= htmlspecialchars(($_SESSION['user']['prenom'] ?? '') . ' ' . ($_SESSION['user']['nom'] ?? '')) ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Contenu principal -->
        <main class='flex-1 overflow-y-auto p-8'>
            <?php /** @var string $content */ echo $content; ?>
        </main>

    </div>


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

<script>
(function () {
    var input   = document.getElementById('search-global');
    var results = document.getElementById('search-results');
    var timer   = null;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = this.value.trim();
        if (q.length < 2) { results.classList.add('hidden'); return; }
        timer = setTimeout(function () { doSearch(q); }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('search-wrapper').contains(e.target)) {
            results.classList.add('hidden');
        }
    });

    function doSearch(q) {
        fetch('<?= WEBROOT ?>admin/search?q=' + encodeURIComponent(q))
            .then(function (r) { return r.json(); })
            .then(function (data) { render(data); });
    }

    function render(data) {
        var html = '';

        if (data.articles && data.articles.length) {
            html += '<div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-50">Articles</div>';
            data.articles.forEach(function (a) {
                html += '<a href="<?= WEBROOT ?>admin/listeArticles" class="flex items-center gap-2 px-3 py-2 hover:bg-indigo-50 text-gray-700 border-b border-gray-50">'
                      + '<i class="fa-solid fa-newspaper text-indigo-400 text-xs w-4 shrink-0"></i>'
                      + '<span class="truncate flex-1">' + esc(a.titre) + '</span>'
                      + '<span class="ml-auto text-xs text-gray-400 shrink-0">' + esc(a.statut) + '</span>'
                      + '</a>';
            });
        }

        if (data.auteurs && data.auteurs.length) {
            html += '<div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-50 border-t border-gray-100">Auteurs</div>';
            data.auteurs.forEach(function (u) {
                html += '<a href="<?= WEBROOT ?>admin/listeAuteurs" class="flex items-center gap-2 px-3 py-2 hover:bg-indigo-50 text-gray-700 border-b border-gray-50">'
                      + '<i class="fa-solid fa-user text-indigo-400 text-xs w-4 shrink-0"></i>'
                      + '<span class="truncate">' + esc(u.prenom + ' ' + u.nom) + '</span>'
                      + '</a>';
            });
        }

        if (data.categories && data.categories.length) {
            html += '<div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-50 border-t border-gray-100">Catégories</div>';
            data.categories.forEach(function (c) {
                html += '<a href="<?= WEBROOT ?>admin/listeCategories" class="flex items-center gap-2 px-3 py-2 hover:bg-indigo-50 text-gray-700 border-b border-gray-50">'
                      + '<i class="fa-solid fa-tag text-indigo-400 text-xs w-4 shrink-0"></i>'
                      + '<span class="truncate">' + esc(c.libelle) + '</span>'
                      + '</a>';
            });
        }

        if (!html) {
            html = '<div class="px-3 py-4 text-center text-gray-400">Aucun résultat</div>';
        }

        results.innerHTML = html;
        results.classList.remove('hidden');
    }

    function esc(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
</body>

</html>