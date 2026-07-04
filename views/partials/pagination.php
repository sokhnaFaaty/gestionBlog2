<?php if ($totalPages > 1): ?>
<div class="flex items-center justify-center gap-2 mt-8">

    <!-- Bouton précédent -->
    <?php if ($page > 1): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
           class="px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
    <?php endif; ?>

    <!-- Numéros de pages -->
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
           class="px-3 py-2 rounded-lg border text-sm font-medium transition
           <?= $i === $page
               ? 'bg-indigo-600 text-white border-indigo-600'
               : 'border-gray-300 text-gray-600 hover:bg-gray-50' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <!-- Bouton suivant -->
    <?php if ($page < $totalPages): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
           class="px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    <?php endif; ?>

</div>
<?php endif; ?>