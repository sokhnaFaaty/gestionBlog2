<div class="max-w-6xl mx-auto my-8 px-4">
    <!-- En-tête de la page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-200">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Mes Articles</h2>
            <p class="text-sm text-gray-500 mt-1">Retrouvez la liste et le statut de vos publications.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="<?= path("article", "add") ?>"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <i class="fa-solid fa-plus mr-2"></i> Écrire un article
            </a>
        </div>
    </div>

    <!-- Tableau des articles (le <tbody> est rempli par le JS) -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs uppercase font-semibold">
                        <th class="px-6 py-4">Titre</th>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Date de création</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="liste-articles" class="divide-y divide-gray-100 text-sm text-gray-700">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i> Chargement...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination (remplie par le JS) -->
    <div id="pagination-articles" class="flex items-center justify-center gap-2 mt-8"></div>
</div>

<script>
    const tbody     = document.getElementById("liste-articles");
    const pagination = document.getElementById("pagination-articles");

    // Empêche l'injection de HTML depuis les données de la base
    function echapper(valeur) {
        const div = document.createElement("div");
        div.textContent = valeur ?? "";
        return div.innerHTML;
    }

    function formaterDate(date) {
        const d = new Date(date);
        if (isNaN(d)) return "";
        return d.toLocaleDateString("fr-FR") + " " +
               d.toLocaleTimeString("fr-FR", { hour: "2-digit", minute: "2-digit" });
    }

    function classeStatut(statut) {
        if (statut === "Publie") return "bg-green-50 text-green-700 border-green-100";
        if (statut === "Rejete") return "bg-red-50 text-red-700 border-red-100";
        return "bg-yellow-50 text-yellow-700 border-yellow-100";
    }

    function ligneArticle(article) {
        return `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-semibold text-gray-900">${echapper(article.titre)}</td>
                <td class="px-6 py-4">
                    <img src="/uploads/${echapper(article.image)}" alt="${echapper(article.titre)}"
                         class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                        ${echapper(article.categorie_nom)}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-500">${formaterDate(article.date_publication)}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border ${classeStatut(article.statut)}">
                        ${echapper(article.statut)}
                    </span>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="/article/edit?id=${article.id_article}"
                       class="text-indigo-600 hover:text-indigo-900 font-medium transition">Modifier</a>
                </td>
            </tr>`;
    }

    function messageVide(texte) {
        return `<tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">
                        <i class="fa-solid fa-folder-open mr-2"></i> ${texte}
                    </td>
                </tr>`;
    }

    function afficherPagination(page, totalPages) {
        pagination.innerHTML = "";
        if (totalPages <= 1) return;

        const bouton = (numero, libelle, actif) => {
            const style = actif
                ? "bg-indigo-600 text-white border-indigo-600"
                : "border-gray-300 text-gray-600 hover:bg-gray-50";
            const b = document.createElement("button");
            b.className = `px-3 py-2 rounded-lg border text-sm font-medium transition ${style}`;
            b.innerHTML = libelle;
            b.addEventListener("click", () => chargerArticles(numero));
            return b;
        };

        if (page > 1) pagination.appendChild(bouton(page - 1, '<i class="fa-solid fa-chevron-left"></i>', false));
        for (let i = 1; i <= totalPages; i++) pagination.appendChild(bouton(i, i, i === page));
        if (page < totalPages) pagination.appendChild(bouton(page + 1, '<i class="fa-solid fa-chevron-right"></i>', false));
    }

    function chargerArticles(page = 1) {
        fetch(`/articlejs/liste?page=${page}`)
            .then(response => {
                if (response.status === 401) {
                    window.location.href = "/auth/login";
                    return;
                }
                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                if (!data.articles.length) {
                    tbody.innerHTML = messageVide("Aucun article rédigé pour le moment.");
                    pagination.innerHTML = "";
                    return;
                }
                tbody.innerHTML = data.articles.map(ligneArticle).join("");
                afficherPagination(data.page, data.totalPages);
            })
            .catch(error => {
                console.error("Fetch failed:", error);
                tbody.innerHTML = messageVide("Impossible de charger les articles.");
            });
    }

    chargerArticles();
</script>
