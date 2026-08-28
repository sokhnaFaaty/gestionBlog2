<!-- Hero -->
<div class="bg-[#1A237E] text-white py-14 px-6 rounded-2xl mb-12 text-center shadow-lg">
    <span class="inline-block px-3 py-1 rounded-full text-xs bg-white/10 border border-white/20 mb-4">Notre histoire</span>
    <h1 class="text-3xl sm:text-4xl font-bold mb-4">À propos de GES-BLOG</h1>
    <p class="text-indigo-100 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
        La plateforme où nos auteurs partagent des articles de qualité et où la communauté échange, commente et grandit ensemble.
    </p>
</div>

<!-- Notre mission -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center mb-16">
    <div>
        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-100 mb-3">Notre mission</span>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Donner la parole aux auteurs, offrir la qualité aux lecteurs</h2>
        <p class="text-gray-600 text-sm leading-relaxed mb-4">
            GES-BLOG est né d'une idée simple : créer un espace où chacun peut publier, lire et échanger autour d'articles
            soigneusement rédigés. Nos auteurs proposent des contenus variés, notre équipe veille à leur qualité, et nos
            lecteurs enrichissent les discussions par leurs commentaires.
        </p>
        <p class="text-gray-600 text-sm leading-relaxed">
            Que tu sois là pour lire, écrire ou débattre, tu es au bon endroit.
        </p>
    </div>
    <div class="w-full h-64 sm:h-80 rounded-2xl overflow-hidden shadow-md border border-gray-200">
        <img src="<?= WEBROOT ?>uploads/1781403275_6a2e0e8b9ccf5.jpeg" alt="GES-BLOG" class="w-full h-full object-cover">
    </div>
</div>

<!-- Comment ça marche -->
<div class="mb-16">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Comment ça marche ?</h2>
    <p class="text-gray-500 text-sm text-center mb-8">Trois rôles, une communauté qui tourne.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 text-center hover:shadow-md transition">
            <div class="w-14 h-14 rounded-full bg-indigo-50 text-[#1A237E] flex items-center justify-center mx-auto mb-4 text-xl"><i class="fa-solid fa-pen-nib"></i></div>
            <h3 class="font-semibold text-gray-800 mb-2">Les auteurs publient</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Ils rédigent des articles, ajoutent une image de couverture et les soumettent à la validation.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 text-center hover:shadow-md transition">
            <div class="w-14 h-14 rounded-full bg-indigo-50 text-[#1A237E] flex items-center justify-center mx-auto mb-4 text-xl"><i class="fa-solid fa-shield-halved"></i></div>
            <h3 class="font-semibold text-gray-800 mb-2">L'équipe modère</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Chaque article est relu et validé avant publication pour garantir la qualité du contenu.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 text-center hover:shadow-md transition">
            <div class="w-14 h-14 rounded-full bg-indigo-50 text-[#1A237E] flex items-center justify-center mx-auto mb-4 text-xl"><i class="fa-solid fa-comments"></i></div>
            <h3 class="font-semibold text-gray-800 mb-2">Les lecteurs commentent</h3>
            <p class="text-sm text-gray-500 leading-relaxed">La communauté réagit, échange et fait vivre chaque publication.</p>
        </div>
    </div>
</div>

<!-- Compteurs dynamiques -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div>
            <p class="text-3xl font-bold text-[#1A237E]"><?= (int)$stats["articles"] ?></p>
            <p class="text-sm text-gray-500 mt-1">Articles publiés</p>
        </div>
        <div>
            <p class="text-3xl font-bold text-[#1A237E]"><?= (int)$stats["auteurs"] ?></p>
            <p class="text-sm text-gray-500 mt-1">Auteurs</p>
        </div>
        <div>
            <p class="text-3xl font-bold text-[#1A237E]"><?= (int)$stats["categories"] ?></p>
            <p class="text-sm text-gray-500 mt-1">Catégories</p>
        </div>
        <div>
            <p class="text-3xl font-bold text-[#1A237E]"><?= (int)$stats["commentaires"] ?></p>
            <p class="text-sm text-gray-500 mt-1">Commentaires</p>
        </div>
    </div>
</div>

<!-- Nos valeurs -->
<div class="mb-16">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">Nos valeurs</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <i class="fa-solid fa-award text-[#1A237E] text-2xl mb-3"></i>
            <h3 class="font-semibold text-gray-800 mb-2">Qualité éditoriale</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Des articles relus et validés, pour une lecture qui vaut le détour.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <i class="fa-solid fa-people-group text-[#1A237E] text-2xl mb-3"></i>
            <h3 class="font-semibold text-gray-800 mb-2">Communauté</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Un espace d'échange bienveillant entre lecteurs et auteurs.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <i class="fa-solid fa-heart text-[#1A237E] text-2xl mb-3"></i>
            <h3 class="font-semibold text-gray-800 mb-2">Modération bienveillante</h3>
            <p class="text-sm text-gray-500 leading-relaxed">On protège la communauté sans étouffer la liberté d'expression.</p>
        </div>
    </div>
</div>

<!-- CTA -->
<?php if (!isConnected()): ?>
<div class="bg-[#1A237E] text-white py-12 px-6 rounded-2xl text-center shadow-lg mb-4">
    <h2 class="text-2xl font-bold mb-3">Rejoignez la communauté</h2>
    <p class="text-indigo-100 text-sm mb-6 max-w-xl mx-auto">Créez votre compte pour commenter, échanger et, pourquoi pas, devenir auteur.</p>
    <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="<?= path('auth', 'register') ?>" class="px-6 py-2.5 bg-white text-[#1A237E] font-semibold rounded-lg hover:bg-indigo-50 transition text-sm">S'inscrire</a>
        <a href="<?= path('auth', 'login') ?>" class="px-6 py-2.5 border border-white text-white font-semibold rounded-lg hover:bg-[#141A5F] transition text-sm">Se connecter</a>
    </div>
</div>
<?php endif; ?>
