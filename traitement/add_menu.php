<?php
include './../fonction/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['id']) && isset($_POST['menu'])) {

        $id = $_POST['id'];
        $menu = $_POST['menu'];

        // Insérer le restaurant dans la base de données après le déplacement réussi du fichier
        insertMenu($id , $menu);
        echo json_encode(["message" => "Restaurant ajouté avec succès"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Erreur lors du téléchargement de l'image"]);
    }
}
?>
