<?php
require_once(ROOT . "/db/database.php");
function inscrireNwesletter(string $email){
    $sql = "INSERT INTO newsletter (email) VALUES (:email)";
        executeUpdate($sql, [':email' => $email]);
    return true; 


}
function findAllNewslettersEmails(){
 $sql = "SELECT email FROM newsletter ORDER BY date_inscription DESC";
    
    return executeSelect($sql);


}