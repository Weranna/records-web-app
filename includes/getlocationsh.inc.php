<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once 'config/config.php';
require_once 'models/locationmodel.inc.php';

try {

    require_once 'classes/dbh.inc.php';

    $db = new Dbh();
    $pdo = $db->getConnection(); 

    $locations = getLocations($pdo);

    echo json_encode(['locations' => $locations]);

} catch (PDOException $e) { 
    echo json_encode(["error"=> $e->getMessage()]);
}