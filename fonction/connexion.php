<?php

function getConnection() {
    $user = 'ravo';
    $pass = 'ravo';
    $dsn = 'pgsql:host=localhost;port=5432;dbname=restaurants';

    try {
        $dbh = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $dbh;
    } catch (PDOException $e) {
        die("Erreur ! : " . $e->getMessage());
    }
}

?>
