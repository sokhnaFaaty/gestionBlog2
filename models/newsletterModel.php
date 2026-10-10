<?php
require_once(ROOT . "/db/database.php");
function inscrireNewsletter(string $email){
    $sql = "INSERT INTO newsletter (email) VALUES (:email)";
        executeUpdate($sql, [':email' => $email]);

    require_once(ROOT . "/models/notificationModel.php");
    notifierAdmins(
        "newsletter",
        "Nouvel abonné à la newsletter : " . $email,
        path("utilisateur", "listeNewsletter")
    );
    return true; 


}
function findAllNewslettersEmails(){
 $sql = "SELECT email, date_inscription FROM newsletter ORDER BY date_inscription DESC";
    
    return executeSelect($sql);
}

function findNewslettersEmailsPagines(int $page = 1, int $parPage = 6): array {
    $offset = ($page - 1) * $parPage;
    $sql = "SELECT email, date_inscription FROM newsletter
            ORDER BY date_inscription DESC
            LIMIT :limit OFFSET :offset";
    return executeSelect($sql, ["limit" => $parPage, "offset" => $offset]);
}

function countAllNewsletters(): int {
    $result = executeSelect("SELECT COUNT(*) as total FROM newsletter", [], true);
    return (int)($result["total"] ?? 0);
}