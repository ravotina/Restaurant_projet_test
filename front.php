
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #container {
        display: flex;
        height: 100%;
    }
    #carteId {
        flex: 2; /* Adjust this value to set the map width */
    }
    #les_criteres {
        flex: 1; /* Adjust this value to set the criteria width */
        padding: 20px;
        background-color: #f4f4f4;
        box-shadow: -2px 0 5px rgba(0,0,0,0.1);
        overflow-y: auto; /* Allow scrolling if content exceeds screen height */
    }
    h2 {
        background-color: #a9e279;
        color: white;
        font-size: 2em;
        text-align: center;
        padding: 20px;
        margin: 0;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .search-field {
        margin: 20px 0;
    }
    .search-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .search-field input {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .search-field button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .search-field button:hover {
        background-color: #45a049;
    }
</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var carte;
var markers = [];

function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng(-18.91162816867355, 47.502579458391914),
        zoom: 12
    };
    carte = new google.maps.Map(document.getElementById("carteId"), mapOptions);
    
    function loadRestaurantsFromBackend(carte) {
        fetch('./traitement/get_restaurant.php')
            .then(response => response.json())
            .then(restaurants => {
                restaurants.forEach(restaurant => {
                    var location = new google.maps.LatLng(parseFloat(restaurant.lat), parseFloat(restaurant.lng));
                    var image = {
                        url: 'uploads/' + restaurant.image_name,
                        scaledSize: new google.maps.Size(50, 50)
                    };
                    var marker = new google.maps.Marker({
                        position: location,
                        map: carte,
                        //icon: image,
                        label: {
                            text: restaurant.name,
                            color: "black",
                            fontWeight: "bold",
                            labelOrigin: new google.maps.Point(25, 10)
                        }
                    });
                    markers.push(marker);

                    function fetchAndOpenInfoWindow(marker, restaurant) {
                        fetch(`./traitement/get_menu_resto.php?id=${restaurant.id}`)
                            .then(response => response.json())
                            .then(data => {
                                var menuContent = data.menus.map(menu => `<p> ${menu.menu}</p>`).join('');
                                var content = `<div>
                                                <h3>${restaurant.name}</h3>
                                                <img src="./uploads/${restaurant.image_name}" style="width:100px;height:auto;">  
                                                <h4>Liste des menus : </h4>
                                                ${menuContent}
                                              </div>`;
                                var infoWindow = new google.maps.InfoWindow({
                                    content: content
                                });
                                marker.addListener('click', function() {
                                    infoWindow.open(carte, marker);
                                });
                            })
                            .catch(error => {
                                console.error('Erreur lors de la récupération des menus:', error);
                            });
                    }
                    fetchAndOpenInfoWindow(marker, restaurant);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des restaurants:', error);
            });
    }

    loadRestaurantsFromBackend(carte);

    document.getElementById("actualisation").addEventListener("click", function(event) {
        loadRestaurantsFromBackend(carte);
    });

    google.maps.event.addListener(carte, 'click', function(event) {
        document.getElementById("lat").value = event.latLng.lat();
        document.getElementById("lng").value = event.latLng.lng();
    });
}

google.maps.event.addDomListener(window, 'load', initialize);

function searchByName() {
    var name = document.getElementById("search-name").value;
    alert("Recherche par nom: " + name);

    fetch(`./traitement/get_resto_nom.php?name=${encodeURIComponent(name)}`)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                clearMarkers();
                loadRestaurantsFromBackend_nom(carte, data);
            } else {
                console.error('Erreur:', data);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des restaurants:', error);
        });
}

function loadRestaurantsFromBackend_nom(carte, restaurants) {
    restaurants.forEach(restaurant => {
        var location = new google.maps.LatLng(parseFloat(restaurant.lat), parseFloat(restaurant.lng));
        var image = {
            url: 'uploads/' + restaurant.image_name,
            scaledSize: new google.maps.Size(50, 50)
        };
        var marker = new google.maps.Marker({
            position: location,
            map: carte,
            //icon: image,
            label: {
                text: restaurant.name,
                color: "black",
                fontWeight: "bold",
                labelOrigin: new google.maps.Point(25, 10)
            }
        });
        markers.push(marker);

        function fetchAndOpenInfoWindow(marker, restaurant) {
            fetch(`./traitement/get_menu_resto.php?id=${restaurant.id}`)
                .then(response => response.json())
                .then(data => {
                    var menuContent = data.menus.map(menu => `<p> ${menu.menu}</p>`).join('');
                    var content = `<div>
                                    <h3>${restaurant.name}</h3>
                                    <img src="./uploads/${restaurant.image_name}" style="width:100px;height:auto;">  
                                    <h4>Liste des menus : </h4>
                                    ${menuContent}
                                  </div>`;
                    var infoWindow = new google.maps.InfoWindow({
                        content: content
                    });
                    marker.addListener('click', function() {
                        infoWindow.open(carte, marker);
                    });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des menus:', error);
                });
        }
        fetchAndOpenInfoWindow(marker, restaurant);
    });
}

function searchByMenuAndDistance() {
    var menu = document.getElementById("search-menu").value;
    var distance = document.getElementById("distance").value;
    var lat = document.getElementById("lat").value;
    var lng = document.getElementById("lng").value;
    alert("Recherche par menu: " + menu + ", distance: " + distance + " km, latitude: " + lat + ", longitude: " + lng);

    fetch(`./traitement/search_by_menu_distance.php?menu=${encodeURIComponent(menu)}&distance=${encodeURIComponent(distance)}&lat=${encodeURIComponent(lat)}&lng=${encodeURIComponent(lng)}`)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                clearMarkers();
                loadRestaurantsFromBackend_nom(carte, data);
            } else {
                console.error('Erreur:', data);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des restaurants:', error);
        });
    // Add search by menu and distance logic here
}

function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}
</script>
</head>
<body>
    <div id="container">
        <div id="carteId"></div>
        <div id="les_criteres">
            <h2>Restaurant</h2>
            <div class="search-field">
                <label for="search-name">Recherche par nom</label>
                <input type="text" id="search-name" placeholder="Nom du restaurant">
                <button onclick="searchByName()">Rechercher</button>
            </div>
            <div class="search-field">
                <label for="search-menu">Recherche par menu</label>
                <input type="text" id="search-menu" placeholder="Menu">
            </div>
            <div class="search-field">
                <label for="distance">Distance (km)</label>
                <input type="number" id="distance" placeholder="Distance">
            </div>
            <div class="search-field">
                <label for="lat">Latitude</label>
                <input type="text" id="lat" readonly>
            </div>
            <div class="search-field">
                <label for="lng">Longitude</label>
                <input type="text" id="lng" readonly>
            </div>
            <button onclick="searchByMenuAndDistance()">Rechercher</button>   
            <button id="actualisation" class="button">Actualiser</button> 
        </div>
    </div>
</body>
</html>

