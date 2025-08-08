<?php
include './../fonction/functions.php';

header('Content-Type: application/json');

if (isset($_GET['name'])) {
    $restos = getRestaurant_nom($_GET['name']);
    if ($restos !== null) {
        echo json_encode($restos);
    } else {
        echo json_encode("no resto trouver"); // Retourne un tableau vide si aucun menu n'est trouvé
    }
} else {
    echo json_encode(['error' => 'ID du restaurant non spécifié']);
}
?>
