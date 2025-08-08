<?php
include './../fonction/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $image_name = $_FILES['image']['name'];

    // Déplacer le fichier image vers un répertoire
    $target_dir = __DIR__ . "./../uploads/";
    $target_file = $target_dir . basename($image_name);
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Créer le répertoire s'il n'existe pas
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insérer le restaurant dans la base de données après le déplacement réussi du fichier
        insertRestaurant($name, $lat, $lng, $image_name);
        echo json_encode(["message" => "Restaurant ajouté avec succès"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Erreur lors du téléchargement de l'image"]);
    }
}
?>
