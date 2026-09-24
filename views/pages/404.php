<div class="max-w-2xl mx-auto text-center">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-16 sm:px-10">
        <div class="text-7xl sm:text-8xl font-bold text-[#1A237E] mb-2">404</div>
        <div class="w-14 h-14 mx-auto rounded-full bg-red-50 border border-red-100 flex items-center justify-center mb-6">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
        </div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
            <?= htmlspecialchars($message_404 ?? 'Page introuvable') ?>
        </h1>
        <p class="text-sm text-gray-500 mb-8 leading-relaxed">
            La page que vous cherchez n'existe pas ou a été déplacée.<br>
            Vous pouvez retourner à l'accueil pour continuer votre lecture.
        </p>
        <a href="<?= path('article', 'home') ?>"
           class="inline-flex items-center gap-2 px-6 py-3 bg-[#1A237E] text-white text-sm font-semibold rounded-lg hover:bg-[#141A5F] focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
            <i class="fa-solid fa-house"></i> Retour à l'accueil
        </a>
    </div>
</div>