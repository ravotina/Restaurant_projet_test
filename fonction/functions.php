<?php
include 'connexion.php';

function insertRestaurant($name, $lat, $lng, $image_name) {
    try {
        $dbh = getConnection();
        $sql = 'INSERT INTO restaurants (name, lat, lng, image_name) VALUES (:name, :lat, :lng, :image_name)';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'lat' => $lat,
            'lng' => $lng,
            'image_name' => $image_name
        ]);
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion du restaurant ! : " . $e->getMessage());
    }
}

function getAllRestaurants() {
    try {
        $dbh = getConnection();
        $sql = 'SELECT * FROM restaurants';
        $stmt = $dbh->query($sql);
        $restaurants = $stmt->fetchAll();
        return $restaurants;
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des restaurants ! : " . $e->getMessage());
    }
}

function getRestaurant_nom($nom) {
    try {
        $dbh = getConnection();
        $sql = 'SELECT * FROM restaurants WHERE name LIKE :name';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['name' => '%' . $nom . '%']);
        $resto = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resto;
    } catch (PDOException $e) {
        die("Erreur lors de la récupération du restaurant ! : " . $e->getMessage());
    }
}



function insertMenu($id , $menu) {
    try {
        $dbh = getConnection();
        $sql = 'INSERT INTO menus (restaurant_id ,  menu) VALUES (:restaurant_id, :menu)';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'restaurant_id' => $id,
            'menu' => $menu,
        ]);
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion du restaurant ! : " . $e->getMessage());
    }
}


function getMenuByIdResto($id) {
    try {
        $dbh = getConnection();
        $sql = 'SELECT menu FROM menus WHERE restaurant_id = :restaurant_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['restaurant_id' => $id]);
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $menus;
    } catch (PDOException $e) {
        die("Erreur lors de la récupération du menu ! : " . $e->getMessage());
    }
}



?>


