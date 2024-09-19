<?php 
declare(strict_types=1);

function getUser($pdo, $login)   {

    $query = "SELECT * FROM users WHERE login = :login";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":login", $login);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $pdo =   null;
    $stmt = null;

    return  $result;
}