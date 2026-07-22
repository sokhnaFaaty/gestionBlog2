<!-- Hero -->
<div class="bg-[#1A237E] text-white py-14 px-6 rounded-2xl mb-12 text-center shadow-lg">
    <span class="inline-block px-3 py-1 rounded-full text-xs bg-white/10 border border-white/20 mb-4">On vous écoute</span>
    <h1 class="text-3xl sm:text-4xl font-bold mb-4">Nous contacter</h1>
    <p class="text-indigo-100 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
        Une question, une suggestion, un partenariat ? Écrivez-nous, on vous répond rapidement.
    </p>
</div>

<!-- 2 colonnes : coordonnées + formulaire -->
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-16">

    <!-- Coordonnées -->
    <div class="lg:col-span-2 space-y-4">
        <div class="w-full h-44 rounded-xl overflow-hidden shadow-sm border border-gray-200">
            <img src="/uploads/contact-illustration.jpg" alt="Contactez GES-BLOG" class="w-full h-full object-cover">
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-indigo-50 text-[#1A237E] flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-envelope"></i></div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Email</p>
                <p class="text-sm text-gray-700 font-medium">contact@ges-blog.fr</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-indigo-50 text-[#1A237E] flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-phone"></i></div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Téléphone</p>
                <p class="text-sm text-gray-700 font-medium">+221 77 000 00 00</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-lg bg-indigo-50 text-[#1A237E] flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-location-dot"></i></div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Adresse</p>
                <p class="text-sm text-gray-700 font-medium">Dakar, Sénégal</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-3">Suivez-nous</p>
            <div class="flex gap-3">
                <a href="#" class="w-9 h-9 rounded-full bg-[#1A237E] hover:bg-[#141A5F] text-white flex items-center justify-center transition"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" class="w-9 h-9 rounded-full bg-[#1A237E] hover:bg-[#141A5F] text-white flex items-center justify-center transition"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="w-9 h-9 rounded-full bg-[#1A237E] hover:bg-[#141A5F] text-white flex items-center justify-center transition"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="w-9 h-9 rounded-full bg-[#1A237E] hover:bg-[#141A5F] text-white flex items-center justify-center transition"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Envoyez-nous un message</h2>
            <p class="text-sm text-gray-500 mb-6">On vous répond sous 48h.</p>

            <?php if (!empty($success)): ?>
                <div class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-6">
                    <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                    <p class="text-sm text-green-700"><?= htmlspecialchars($success) ?></p>
                </div>
            <?php endif; ?>

            <form action="<?= path('page', 'contact') ?>" method="POST" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <input type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" placeholder="Votre nom"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <span class="text-red-600 text-xs"><?= $errors['nom'] ?? '' ?></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="text" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="vous@exemple.fr"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <span class="text-red-600 text-xs"><?= $errors['email'] ?? '' ?></span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                    <input type="text" name="sujet" value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>" placeholder="Objet de votre message"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <span class="text-red-600 text-xs"><?= $errors['sujet'] ?? '' ?></span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="5" placeholder="Écrivez votre message ici..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition resize-none"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    <span class="text-red-600 text-xs"><?= $errors['message'] ?? '' ?></span>
                </div>
                <input type="hidden" name="controller" value="page">
                <input type="hidden" name="action" value="contact">
                <button type="submit" name="btn_contact"
                        class="w-full py-2.5 bg-[#1A237E] text-white text-sm font-medium rounded-lg hover:bg-[#141A5F] focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
                    <i class="fa-solid fa-paper-plane mr-2"></i>Envoyer le message
                </button>
            </form>
        </div>
    </div>
</div>

<!-- FAQ accordéon -->
<div class="max-w-3xl mx-auto mb-8">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Questions fréquentes</h2>
    <p class="text-gray-500 text-sm text-center mb-8">Peut-être avez-vous déjà la réponse ici.</p>
    <div class="space-y-3">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <button type="button" onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-5 py-4 text-left">
                <span class="text-sm font-semibold text-gray-800">Comment devenir auteur sur GES-BLOG ?</span>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
            </button>
            <div class="hidden px-5 pb-4 text-sm text-gray-500 leading-relaxed">Créez un compte, puis contactez-nous via ce formulaire pour demander le rôle auteur. Notre équipe étudie votre demande.</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <button type="button" onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-5 py-4 text-left">
                <span class="text-sm font-semibold text-gray-800">Mes commentaires sont-ils modérés ?</span>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
            </button>
            <div class="hidden px-5 pb-4 text-sm text-gray-500 leading-relaxed">Oui, pour garder des échanges sains. Les commentaires signalés sont vérifiés par notre équipe.</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <button type="button" onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-5 py-4 text-left">
                <span class="text-sm font-semibold text-gray-800">L'inscription est-elle gratuite ?</span>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
            </button>
            <div class="hidden px-5 pb-4 text-sm text-gray-500 leading-relaxed">Totalement gratuite. Créez votre compte en quelques secondes et rejoignez la communauté.</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <button type="button" onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-5 py-4 text-left">
                <span class="text-sm font-semibold text-gray-800">Combien de temps pour une réponse ?</span>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform"></i>
            </button>
            <div class="hidden px-5 pb-4 text-sm text-gray-500 leading-relaxed">En général sous 48h ouvrées. Les messages urgents sont traités en priorité.</div>
        </div>
    </div>
</div>

<script>
function toggleFaq(btn) {
    var panel = btn.nextElementSibling;
    var icon  = btn.querySelector('i');
    panel.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
