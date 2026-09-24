<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — Plume &amp; Clic</title>
  <meta name="csrf-token" content="<?= htmlspecialchars(csrfToken()) ?>">
  <script>
  (function () {
    var TOKEN = document.querySelector('meta[name="csrf-token"]').content;
    window.__csrfToken = TOKEN;

    window.injectCsrf = function (form) {
      if (!form || form.querySelector('input[name="csrf_token"]')) return;
      var inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = 'csrf_token';
      inp.value = TOKEN;
      form.appendChild(inp);
    };

    document.addEventListener('submit', function (e) {
      var f = e.target;
      if (f && f.tagName === 'FORM') window.injectCsrf(f);
    }, true);

    if (typeof window.fetch === 'function') {
      var fetchOrig = window.fetch;
      window.fetch = function (url, opts) {
        opts = opts || {};
        opts.headers = opts.headers || {};
        opts.headers['X-CSRF-Token'] = TOKEN;
        return fetchOrig.call(this, url, opts);
      };
    }
  })();
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-3 sm:p-4 relative overflow-hidden bg-[#1A237E]">

  <!-- Bouton retour à l'accueil (commun aux pages connexion / inscription) -->
  <a href="<?= path('article', 'home') ?>"
     class="absolute top-4 left-4 z-20 inline-flex items-center gap-2 px-4 py-2.5 bg-white/85 backdrop-blur-xl rounded-full border border-white/40 text-[#1A237E] text-sm font-semibold shadow-lg hover:bg-white transition"
     aria-label="Retour à l'accueil">
    <i class="fa-solid fa-house"></i>
    <span class="hidden sm:inline">Accueil</span>
  </a>

  <!-- Image de fond plein écran (légère, optionnelle sur mobile pour la perf) -->
  <div class="absolute inset-0 bg-cover bg-center"
       role="img" aria-label="Image de fond de la page de connexion"
       style="background-image:url('<?= WEBROOT ?>uploads/auth.png');"></div>

  <!-- Voile dégradé aux couleurs de la marque (lisibilité) -->
  <div class="absolute inset-0 bg-gradient-to-br from-[#1A237E]/90 via-[#1A237E]/70 to-black/80"></div>

  <!-- Contenu (carte translucide) au-dessus du fond -->
  <div class="relative z-10 w-full flex items-center justify-center my-4">
    <?php /** @var string $content */ echo $content; ?>
  </div>

</body>
</html>