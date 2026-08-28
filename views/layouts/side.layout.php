<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Blog</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .page-fade { animation: fadeUp .5s ease both; }
        @media (prefers-reduced-motion: reduce) { .page-fade { animation: none; } }
    </style>
</head>

<body class='bg-gray-50 flex h-screen overflow-hidden'>

    <!-- Modal de confirmation -->
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

    <!-- Overlay mobile (fond sombre quand sidebar ouverte) -->
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"
         onclick="fermerSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
           class='fixed lg:static inset-y-0 left-0 z-30
                  w-64 bg-[#1A237E] text-white flex-shrink-0 flex flex-col h-full
                  transform -translate-x-full lg:translate-x-0 transition-transform duration-300'>

        <!-- Logo -->
        <div class='px-6 py-5 border-b border-indigo-700 flex items-center justify-between'>
            <span class='text-xl font-bold tracking-wide text-white'>
                <i class='fa-solid fa-book-open mr-2'></i>GES-BLOG
            </span>
            <!-- Bouton fermer sidebar sur mobile -->
            <button onclick="fermerSidebar()" class='lg:hidden text-indigo-300 hover:text-white'>
                <i class='fa-solid fa-xmark text-xl'></i>
            </button>
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

            $base   = 'flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150';
            $active = $base . ' bg-indigo-700 text-white';
            $normal = $base . ' text-indigo-200 hover:bg-indigo-800 hover:text-white';

            $role = $_SESSION['user']['role'];

            if ($role === 'auteur'): ?>
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Blog</p>
                <a href='<?= path("article", "home") ?>'
                    class='<?= in_array($currentAction, ["home", "article"]) ? $active : $normal ?>'>
                    <i class='fa-solid fa-newspaper w-4 text-center'></i> Articles publiés
                </a>

                <p class='px-4 pt-4 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Mes articles</p>
                <a href='<?= path("article", "liste") ?>'
                    class='<?= in_array($currentAction, ["liste", "index", "edit"]) ? $active : $normal ?>'>
                    <i class='fa-solid fa-file-lines w-4 text-center'></i> Mes articles
                </a>
                <a href='<?= path("article", "add") ?>'
                    class='<?= ($currentAction === "add") ? $active : $normal ?>'>
                    <i class='fa-solid fa-plus w-4 text-center'></i> Nouvel article
                </a>

            <?php elseif ($role === 'admin'): ?>
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Blog</p>
                <a href='<?= path("article", "home") ?>'
                    class='<?= in_array($currentAction, ["home", "voir"]) && $currentController === "article" ? $active : $normal ?>'>
                    <i class='fa-solid fa-eye w-4 text-center'></i> Voir les articles
                </a>

                <p class='px-4 pt-4 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Tableau de bord</p>
                <a href='<?= path("utilisateur", "dashboard") ?>'
                    class='<?= in_array($currentAction, ['dashboard', 'index']) ? $active : $normal ?>'>
                    <i class='fa-solid fa-gauge w-4 text-center'></i> Vue d'ensemble
                </a>

                <p class='px-4 pt-4 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Gestion</p>
                <a href='<?= path("article", "listeAdmin") ?>'
                    class='<?= $currentAction === "listeAdmin" ? $active : $normal ?>'>
                    <i class='fa-solid fa-newspaper w-4 text-center'></i> Articles
                </a>
                <a href='<?= path("utilisateur", "listeAuteurs") ?>'
                    class='<?= $currentAction === "listeAuteurs" ? $active : $normal ?>'>
                    <i class='fa-solid fa-users w-4 text-center'></i> Auteurs
                </a>
                <a href='<?= path("categorie", "liste") ?>'
                    class='<?= $currentAction === "liste" && $currentController === "categorie" ? $active : $normal ?>'>
                    <i class='fa-solid fa-tags w-4 text-center'></i> Catégories
                </a>
                <a href='<?= path("utilisateur", "addAdmin") ?>'
                    class='<?= $currentAction === "addAdmin" ? $active : $normal ?>'>
                    <i class='fa-solid fa-user-shield w-4 text-center'></i> Ajouter un admin
                </a>
                <a href='<?= path("utilisateur", "listeAdmins") ?>'
                    class='<?= $currentAction === "listeAdmins" ? $active : $normal ?>'>
                    <i class='fa-solid fa-user-shield w-4 text-center'></i> Administrateurs
                </a>
                <a href='<?= path("commentaire", "signalements") ?>'
                    class='<?= $currentAction === "signalements" ? $active : $normal ?>'>
                    <i class='fa-solid fa-comment-slash w-4 text-center'></i> Signalements
                </a>
                <a href='<?= path("utilisateur", "listeNewsletters") ?>'
                    class='<?= $currentAction === "listeNewsletters" ? $active : $normal ?>'>
                    <i class='fa-solid fa-envelope w-4 text-center'></i> Newsletter
                </a>

            <?php elseif ($role === 'lecteur'): ?>
                <p class='px-4 pt-2 pb-1 text-xs text-indigo-400 uppercase font-semibold tracking-wider'>Blog</p>
                <a href='<?= path("article", "home") ?>'
                    class='<?= $currentAction === "home" ? $active : $normal ?>'>
                    <i class='fa-solid fa-newspaper w-4 text-center'></i> Articles publiés
                </a>
            <?php endif; ?>
        </nav>

        <!-- Déconnexion -->
        <div class='px-3 py-4 border-t border-indigo-700'>
            <a href='<?= path("auth", "logout") ?>'
                class='flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-indigo-200 hover:bg-red-600 hover:text-white transition-colors duration-150'>
                <i class='fa-solid fa-right-from-bracket w-4 text-center'></i> Déconnexion
            </a>
        </div>
    </aside>

    <!-- Zone droite -->
    <div class='flex-1 flex flex-col overflow-hidden min-w-0'>

        <!-- Header -->
        <header class='bg-[#1A237E] px-4 py-3 flex items-center justify-between flex-shrink-0 rounded-[10px] mx-3 mt-1'>
            <div class='flex items-center gap-3'>
                <!-- Bouton hamburger mobile -->
                <button onclick="ouvrirSidebar()"
                        class='lg:hidden text-white hover:text-indigo-200 transition'>
                    <i class='fa-solid fa-bars text-xl'></i>
                </button>

                <!-- Recherche -->
                <div class='relative hidden sm:block' id='search-wrapper'>
                    <i class='fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm'></i>
                    <input type='text'
                           id='search-global'
                           placeholder='Rechercher...'
                           autocomplete='off'
                           class='w-48 md:w-72 pl-9 pr-4 py-2 text-sm bg-white border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 transition'>
                    <div id='search-results'
                         class='hidden absolute top-full mt-1 left-0 w-full bg-white rounded-lg shadow-lg z-50 max-h-80 overflow-y-auto text-sm border border-gray-100'>
                    </div>
                </div>
            </div>

            <!-- Droite : cloche + avatar -->
            <div class='flex items-center gap-3'>
                <!-- Cloche de notifications -->
                <div class='relative' id='notif-wrapper'>
                    <button id='notif-btn'
                            onclick='toggleNotifs(event)'
                            aria-label='Notifications'
                            class='relative text-white hover:text-indigo-200 transition p-1 rounded-lg'>
                        <i class='fa-solid fa-bell text-lg'></i>
                        <span id='notif-badge'
                              class='hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center shadow'></span>
                    </button>

                    <!-- Dropdown des notifications -->
                    <div id='notif-panel'
                         class='hidden absolute right-0 mt-2 w-[min(92vw,380px)] bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden z-50'>
                        <!-- En-tête -->
                        <div class='flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50'>
                            <p class='text-sm font-bold text-gray-800'>Notifications</p>
                            <button onclick='marquerToutLu()'
                                    class='text-xs font-medium text-indigo-600 hover:text-indigo-800 transition'>
                                <i class='fa-solid fa-check-double mr-1'></i>Tout lire
                            </button>
                        </div>
                        <!-- Onglets par espace -->
                        <div id='notif-tabs' class='flex gap-1 px-3 py-2 border-b border-gray-100 overflow-x-auto'></div>
                        <!-- Liste -->
                        <div id='notif-list' class='max-h-[340px] overflow-y-auto'></div>
                    </div>
                </div>

                <div class='flex items-center gap-2'>
                    <div class='w-9 h-9 rounded-full bg-white text-[#1A237E] flex items-center justify-center text-sm font-bold uppercase'>
                        <?= mb_substr($_SESSION['user']['prenom'] ?? '', 0, 1) . mb_substr($_SESSION['user']['nom'] ?? '', 0, 1) ?>
                    </div>
                    <span class='hidden md:block text-sm font-medium text-white'>
                        <?= htmlspecialchars(($_SESSION['user']['prenom'] ?? '') . ' ' . ($_SESSION['user']['nom'] ?? '')) ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Contenu -->
        <main class='flex-1 overflow-y-auto p-4 md:p-8 page-fade'>
            <?php /** @var string $content */ echo $content; ?>
        </main>
    </div>

<script>
function ouvrirSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
}

function fermerSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}

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

    if (!input) return;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = this.value.trim();
        if (q.length < 2) { results.classList.add('hidden'); return; }
        timer = setTimeout(function () { doSearch(q); }, 300);
    });

    document.addEventListener('click', function (e) {
        var wrapper = document.getElementById('search-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            results.classList.add('hidden');
        }
    });

    function doSearch(q) {
        fetch('<?= WEBROOT ?>article/search?q=' + encodeURIComponent(q))
            .then(function (r) { return r.json(); })
            .then(function (data) { render(data); });
    }

    function render(data) {
        var html = '';
        if (data.articles && data.articles.length) {
            html += '<div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-50">Articles</div>';
            data.articles.forEach(function (a) {
                html += '<a href="<?= WEBROOT ?>article/listeAdmin" class="flex items-center gap-2 px-3 py-2 hover:bg-indigo-50 text-gray-700 border-b border-gray-50">'
                      + '<i class="fa-solid fa-newspaper text-indigo-400 text-xs w-4 shrink-0"></i>'
                      + '<span class="truncate flex-1">' + esc(a.titre) + '</span>'
                      + '<span class="ml-auto text-xs text-gray-400 shrink-0">' + esc(a.statut) + '</span>'
                      + '</a>';
            });
        }
        if (data.auteurs && data.auteurs.length) {
            html += '<div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-50 border-t border-gray-100">Auteurs</div>';
            data.auteurs.forEach(function (u) {
                html += '<a href="<?= WEBROOT ?>utilisateur/listeAuteurs" class="flex items-center gap-2 px-3 py-2 hover:bg-indigo-50 text-gray-700 border-b border-gray-50">'
                      + '<i class="fa-solid fa-user text-indigo-400 text-xs w-4 shrink-0"></i>'
                      + '<span class="truncate">' + esc(u.prenom + ' ' + u.nom) + '</span>'
                      + '</a>';
            });
        }
        if (data.categories && data.categories.length) {
            html += '<div class="px-3 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider bg-gray-50 border-t border-gray-100">Catégories</div>';
            data.categories.forEach(function (c) {
                html += '<a href="<?= WEBROOT ?>categorie/liste" class="flex items-center gap-2 px-3 py-2 hover:bg-indigo-50 text-gray-700 border-b border-gray-50">'
                      + '<i class="fa-solid fa-tag text-indigo-400 text-xs w-4 shrink-0"></i>'
                      + '<span class="truncate">' + esc(c.libelle) + '</span>'
                      + '</a>';
            });
        }
        if (!html) html = '<div class="px-3 py-4 text-center text-gray-400">Aucun résultat</div>';
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

<script>
(function () {
    var WEBROOT = '<?= WEBROOT ?>';

    var wrapper = document.getElementById('notif-wrapper');
    var panel   = document.getElementById('notif-panel');
    var badge   = document.getElementById('notif-badge');
    var tabsEl  = document.getElementById('notif-tabs');
    var listEl  = document.getElementById('notif-list');

    if (!wrapper) return;

    var ESPACES = [
        { key: '',          label: 'Tous',        icon: 'fa-bell' },
        { key: 'article',   label: 'Articles',    icon: 'fa-newspaper' },
        { key: 'commentaire', label: 'Commentaires', icon: 'fa-comments' },
        { key: 'signalement', label: 'Signalements', icon: 'fa-flag' },
        { key: 'newsletter', label: 'Newsletter',  icon: 'fa-envelope' },
        { key: 'contact',    label: 'Contact',     icon: 'fa-envelope-open-text' },
        { key: 'utilisateur', label: 'Utilisateurs', icon: 'fa-users' }
    ];

    var espaceActif = '';
    var notifs = [];

    function toggleNotifs(ev) {
        ev.stopPropagation();
        var visible = !panel.classList.contains('hidden');
        if (!visible) chargerNotifs(espaceActif);
        panel.classList.toggle('hidden');
    }

    function fermerNotifs() {
        panel.classList.add('hidden');
    }

    function afficherBadge(n) {
        if (n > 0) {
            badge.textContent = n > 99 ? '99+' : n;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function chargerNotifs(espace) {
        fetch(WEBROOT + 'notificationjs/liste')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data || !data.success) return;
                notifs = data.notifs || [];
                afficherBadge(data.nonLues);
                rendererTabs();
                rendererListe(espace);
            })
            .catch(function () {});
    }

    function rendererTabs() {
        var html = '';
        ESPACES.forEach(function (sp) {
            var compteur = notifs.filter(function (n) {
                return n.espace === sp.key && !n.lu;
            }).length;
            if (sp.key === espaceActif) {
                html += '<button onclick="setEspace(\'' + sp.key + '\')" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white whitespace-nowrap transition">' + sp.label;
            } else {
                html += '<button onclick="setEspace(\'' + sp.key + '\')" class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 whitespace-nowrap transition">' + sp.label;
            }
            if (compteur > 0) html += ' <span class="ml-0.5 text-[10px]">(' + compteur + ')</span>';
            html += '</button>';
        });
        tabsEl.innerHTML = html;
    }

    function rendererListe(espace) {
        var filtrées = espace ? notifs.filter(function (n) { return n.espace === espace; }) : notifs;

        if (!filtrées.length) {
            listEl.innerHTML = '<div class="px-4 py-10 text-center text-gray-400 text-sm"><i class="fa-solid fa-bell-slash text-2xl mb-2 block opacity-40"></i>Aucune notification</div>';
            return;
        }
        var html = '';
        var lectureClique = false;
        filtrées.forEach(function (n) {
            var icone = iconeEspace(n.espace);
            var classe = n.lu ? 'opacity-60' : '';
            html += '<a href="' + (n.lien ? n.lien : '#') + '" onclick="marquerLuUne(\'' + n.espace + '\', event)" class="flex items-start gap-3 px-4 py-3 hover:bg-indigo-50 transition border-b border-gray-50 ' + classe + '">'
                  + '<span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0"><i class="fa-solid ' + icone + ' text-xs"></i></span>'
                  + '<span class="flex-1 min-w-0">'
                  +   '<span class="block text-sm text-gray-700 leading-snug">' + echapper(n.message) + '</span>'
                  +   '<span class="block text-[11px] text-gray-400 mt-0.5">' + formatDate(n.date_creation) + '</span>'
                  + '</span>'
                  + (n.lu ? '' : '<span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 mt-1.5"></span>')
                  + '</a>';
        });
        listEl.innerHTML = html;
    }

    function iconeEspace(espace) {
        var found = ESPACES.find(function (s) { return s.key === espace; });
        return found ? found.icon : 'fa-bell';
    }

    function echapper(str) {
        var div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        var d = new Date(dateStr);
        if (isNaN(d)) return '';
        return d.toLocaleDateString('fr-FR') + ' ' + d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }

    window.toggleNotifs   = toggleNotifs;
    window.fermerNotifs   = fermerNotifs;

    window.setEspace = function (key) {
        espaceActif = key;
        rendererTabs();
        rendererListe(key);
    };

    window.marquerToutLu = function () {
        fetch(WEBROOT + 'notificationjs/marquerLues', { method: 'POST' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.success) {
                    notifs.forEach(function (n) { n.lu = true; });
                    afficherBadge(0);
                    rendererTabs();
                    rendererListe(espaceActif);
                }
            });
    };

    window.marquerLuUne = function (espace, ev) {
        fetch(WEBROOT + 'notificationjs/marquerLues?espace=' + espace, { method: 'POST' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data && data.success) {
                    notifs.forEach(function (n) { if (n.espace === espace) n.lu = true; });
                    afficherBadge(data.nonLues);
                }
            });
    };

    document.addEventListener('click', function (e) {
        if (wrapper && !wrapper.contains(e.target)) fermerNotifs();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') fermerNotifs();
    });

    // Chargement initial du badge (sans ouvrir)
    fetch(WEBROOT + 'notificationjs/liste')
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data && data.success) afficherBadge(data.nonLues);
        })
        .catch(function () {});
})();
</script>
</body>
</html>