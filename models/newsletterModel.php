<?php
require_once(ROOT . "/db/database.php");
function inscrireNewsletter(string $email){
    $sql = "INSERT INTO newsletter (email) VALUES (:email)";
        executeUpdate($sql, [':email' => $email]);
    return true; 


}
function findAllNewslettersEmails(){
 $sql = "SELECT email, date_inscription FROM newsletter ORDER BY date_inscription DESC";
    
    return executeSelect($sql);
}