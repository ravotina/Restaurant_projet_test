<?php
include './../fonction/functions.php';

// Récupérer tous les restaurants
$restaurants = getAllRestaurants();

// Convertir en JSON
$restaurants_json = json_encode($restaurants);

// Renvoyer en tant que réponse JSON
header('Content-Type: application/json');
echo $restaurants_json;
?>
