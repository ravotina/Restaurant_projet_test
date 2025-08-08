<?php
$host = 'localhost';
$db = 'restaurants';
$user = 'ravo';
$pass = 'ravo';
$charset = 'utf8mb4';

$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$menu = $_GET['menu'];
$distance = $_GET['distance'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];

$sql = "
SELECT r.id, r.name, r.lat, r.lng, r.image_name, m.menu
FROM restaurants r
JOIN menus m ON r.id = m.restaurant_id
WHERE ST_DWithin(
    ST_SetSRID(ST_MakePoint(r.lng, r.lat), 4326)::geography,
    ST_SetSRID(ST_MakePoint(:lng, :lat), 4326)::geography,
    :distance * 1000
) AND m.menu ILIKE '%' || :menu || '%';
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['lng' => $lng, 'lat' => $lat, 'distance' => $distance, 'menu' => $menu]);
$results = $stmt->fetchAll();

echo json_encode($results);
?>
