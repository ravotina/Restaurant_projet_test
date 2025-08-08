<?php
include './../fonction/functions.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $menu = getMenuByIdResto($_GET['id']);
    if ($menu !== null) {
        echo json_encode(['menus' => $menu]);
    } else {
        echo json_encode(['menus' => []]); // Retourne un tableau vide si aucun menu n'est trouvé
    }
} else {
    echo json_encode(['error' => 'ID du restaurant non spécifié']);
}
?>
