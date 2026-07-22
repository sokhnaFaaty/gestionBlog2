<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — Gestion Blogs</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

  <!-- Image de fond plein écran -->
  <div class="absolute inset-0 bg-cover bg-center"
       style="background-image: url('/uploads/auth.png');"></div>

  <!-- Voile dégradé aux couleurs de la marque (lisibilité) -->
  <div class="absolute inset-0 bg-gradient-to-br from-[#1A237E]/85 via-[#1A237E]/60 to-black/70"></div>

  <!-- Contenu (carte translucide) au-dessus du fond -->
  <div class="relative z-10 w-full flex items-center justify-center">
    <?php /** @var string $content */ echo $content; ?>
  </div>

</body>
</html>