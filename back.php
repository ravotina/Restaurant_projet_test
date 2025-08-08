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
        flex: 2; /* Ajustez cette valeur pour définir la largeur de la carte */
    }
    #les_criteres {
        flex: 1; /* Ajustez cette valeur pour définir la largeur des critères */
        padding: 20px;
        background-color: #f4f4f4;
        box-shadow: -2px 0 5px rgba(0,0,0,0.1);
        overflow-y: auto; /* Permettre le défilement si le contenu dépasse la hauteur de l'écran */
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

        .form-field {
        margin: 20px 0;
    }

    .form-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-field input, .form-field textarea {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-field button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .form-field button:hover {
        background-color: #45a049;
    }

</style>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function initialize() {
    // Options de la carte
    var mapOptions = {
        center: new google.maps.LatLng(-18.91162816867355, 47.502579458391914),
        zoom: 12
    };
    // Création de la carte
    var carte = new google.maps.Map(document.getElementById("carteId"), mapOptions);

    function loadRestaurantsFromBackend(carte) {
    fetch('./traitement/get_restaurant.php')
        .then(response => response.json())
        .then(restaurants => {
            addRestaurantOption(restaurants);
            restaurants.forEach(restaurant => {
                var location = new google.maps.LatLng(parseFloat(restaurant.lat), parseFloat(restaurant.lng));

                var image = {
                    url: './uploads/' + restaurant.image_name,
                    scaledSize: new google.maps.Size(50, 50)
                };

                var marker = new google.maps.Marker({
                    position: location,
                    map: carte,
                    icon: image,
                    label: {
                        text: restaurant.name,
                        color: "red",
                        fontWeight: "bold",
                        labelOrigin: new google.maps.Point(25, 10)
                    }
                });

                // Fonction pour récupérer et afficher les menus d'un restaurant dans l'infowindow
                function fetchAndOpenInfoWindow(marker, restaurant) {
                    fetch(`./traitement/get_menu_resto.php?id=${restaurant.id}`)
                        .then(response => response.json())
                        .then(data => {
                            var menuContent = data.menus.map(menu => `<p> ${menu.menu}</p>`).join('');
                            var content = `<div>
                                            <h3>${restaurant.name}</h3>
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

                // Appel de la fonction pour chaque marqueur
                fetchAndOpenInfoWindow(marker, restaurant);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des restaurants:', error);
        });
    }



    function addRestaurantOption(restaurant) {
        const selectElement = document.getElementById('restaurant');
        selectElement.innerHTML = ""; // Vider les options existantes

        restaurant.forEach(element => {
            const option = document.createElement('option');
            option.value = element.id;
            option.textContent = element.name;
            selectElement.appendChild(option);
        });
    }

        // Appeler la fonction avec les paramètres appropriés
    loadRestaurantsFromBackend(carte);

    google.maps.event.addListener(carte, 'click', function(event) {
        document.getElementById("lat").value = event.latLng.lat();
        document.getElementById("lng").value = event.latLng.lng();
    });


    document.getElementById("actualisation").addEventListener("click", function(event) {
            loadRestaurantsFromBackend(carte);
            
    });

}

// Chargement de la carte après le chargement de la page
google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>
    <div id="container">
        <div id="carteId"></div>
        <div id="les_criteres">
            <h2>Restaurant</h2>
        
        <h4>Ajouter un Reastaurant</h4>    
        <form id="add-restaurant-form">
            <div class="form-field">
                <label for="restaurant-name">Nom du restaurant</label>
                <input type="text" id="restaurant-name" required>
            </div>
            
            <div class="form-field">
                <label for="image">Image</label>
                <input type="file" id="image" accept="image/*" required>
            </div>
            
            <div class="form-field">
                <label for="lat">Latitude</label>
                <input type="text" id="lat" readonly>
            </div>
            
            <div class="form-field">
                <label for="lng">Longitude</label>
                <input type="text" id="lng" readonly>
            </div>
            
            <div class="form-field">
                <button type="submit">Ajouter le Restaurant</button>
            </div>
        </form>


        <h4>Ajouter un Menu</h4>
        <form id="add-menu-form">
            <div class="form-field">
                <label for="restaurant">Restaurant</label>
                <select id="restaurant" required>
                    <option value="">Sélectionnez un restaurant...</option> 
                </select>
            </div>
            <div class="form-field">
                <label for="menu">Menu</label>
                <input type="text" id="menu" placeholder="Entrez le nom du menu..." required>
            </div>
            <div class="form-field">
                <button type="submit" class="button">Ajouter Menu</button>
            </div>
        </form>

        <button id="actualisation" class="button">Actualiser</button>

    </div>


    <script>

        document.getElementById("add-restaurant-form").addEventListener("submit", function(event) {
            event.preventDefault();
            
            var name = document.getElementById("restaurant-name").value;
            var lat = document.getElementById("lat").value;
            var lng = document.getElementById("lng").value;
            var image = document.getElementById("image").files[0];
            
            var formData = new FormData();
            formData.append("name", name);
            formData.append("lat", lat);
            formData.append("lng", lng);
            formData.append("image", image);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./traitement/add_restaurant.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Restaurant ajouté:\nNom: " + name + "\nLatitude: " + lat + "\nLongitude: " + lng);
                   
                } else {
                    alert("Erreur lors de l'ajout du restaurant.");
                }
            };
            xhr.send(formData);
          
        });




        document.getElementById("add-menu-form").addEventListener("submit", function(event) {
            event.preventDefault();
            
            var restaurant = document.getElementById("restaurant").value;
            var menu = document.getElementById("menu").value;


            var formData = new FormData();
            formData.append("id", restaurant);
            formData.append("menu", menu);
            
            // Ici, vous pouvez ajouter la logique pour envoyer les données au serveur via AJAX ou une autre méthode

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./traitement/add_menu.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {

                    alert("Menu ajouté pour " + restaurant + ":\nMenu: " + menu);
                   
                } else {
                    alert("Erreur lors de l'ajout du restaurant.");
                }
            };
            xhr.send(formData);
            // Réinitialiser le formulaire après soumission
            document.getElementById("add-menu-form").reset();
        });


    </script>

</body>
</html>
