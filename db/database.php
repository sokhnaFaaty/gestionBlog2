<?php

function openConnexion() {
    $con = null;
    try {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
        $con = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
        
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $con;
    } catch (PDOException $e) {
        echo('Erreur : ' . $e->getMessage());
    }
}

function closeConnexion($con) {
    $con = null;
}

function executeSelect(string $sql, array $data = [], $one = false) {
    $result = null;
    $conn = openConnexion();
    $statement = $conn->prepare($sql);
    count($data) == 0 ? $statement->execute() : $statement->execute($data);
    $result = $one == true ? $statement->fetch() : $statement->fetchAll();
    closeConnexion($conn);
    return $result;
}

function executeUpdate(string $sql, array $data) {
    $conn = openConnexion();
    $statement = $conn->prepare($sql);
    $statement->execute($data);
    closeConnexion($conn);
}