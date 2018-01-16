<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDC9Bs205kUPID2bl3D_QUnirTrxe0t4xc"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/
jquery.min.js"></script>
<script type="text/javascript">
var geocoder = new google.maps.Geocoder();
var map;
var marker;
 
// Initialisation de la carte Google Map de départ
function initial() {
  var latlng = new google.maps.LatLng(41.87670244674532, 9.057442000000037);
  var Options = {
    zoom      : 9,
    center    : latlng,
    mapTypeId : google.maps.MapTypeId.ROADMAP
  }
  // Map est le conteneur HTML de la carte Google Map
  map = new google.maps.Map(document.getElementById('map'), Options);
 
  // Lors de l'évènement au click de la souris on fait appel à la fonction placerMarker(avec les coordonnées)
  google.maps.event.addListener(map, 'click', function(event) {
    placerMarker(event.latLng);
  });
 
  // Affiche les marqueurs disponible dans la BDD
//   <?php foreach ($check as $tab){ ?>
//     new google.maps.Marker({
//         position: new google.maps.LatLng(<?php echo $tab["latitude"] ?>, <?php echo $tab["longitude"] ?>),
//         map: map,
//         title: "<?php echo $tab["adresse"]; ?>"
//     });
//   <?php } ?>
// }
 
// Fonction qui est appeller lors du click pour placer un marqueur
function placerMarker(location) {
    if(marker){ // Si le marqueur existe
        marker.setPosition(location); // Change de position
        map.setCenter(location); // Puis on centre la map par rapport à la nouvelle position
    } else {
        marker = new google.maps.Marker({ // Création du marqueur
            position : location, // Ajout de sa nouvelle position
            map : map // Dans la map
        });
    }
    document.getElementById("latitude").value = location.lat(); // Enregistrement latitude dans l'input
    document.getElementById("longitude").value = location.lng(); // Enregistrement longitude dans l'input
 
    var coogps = new google.maps.LatLng(document.getElementById("latitude").value, document.getElementById("longitude").value); // Par rapport aux input de la latitude et longitude
    geocoder.geocode({"latLng": coogps}, function(data, status) { // Geocode les données GPS en Adresse Postale
            if (status == google.maps.GeocoderStatus.OK && data[0]) {
                  document.getElementById("adresse").value = data[0].formatted_address;
            } else {
                alert("Erreur: " + status);
            }
      });
}
 
function AjouterAdresse() {
    // Appel AJAX pour insertion en BDD en récupérant la latitude, longitude et l'adresse des inputs
    var sendAjax = $.ajax({
      type: "POST",
      url: 'caserne.php',
      data: 'latitude='+document.getElementById("latitude").value+'&longitude='+document.getElementById("longitude").value+'&adresse='+document.getElementById("adresse").value,
      success: Reponse
    });
 
    // Fonction qui renvois la réponse dans la div adaptée
    function Reponse(){
      $('#rep').get(0).innerHTML = sendAjax.responseText;
      }
}
// Lancement de la construction de la carte google map
google.maps.event.addDomListener(window, 'load', initial);
</script>
</head>
<body>
  <?php
  if (!empty($_POST)){ // Si un formulaire POST Est envoyé
    if (!empty($_POST['latitude']) && !empty($_POST['longitude']) && !empty($_POST['adresse'])){ // Vérifie si les champs sont vide ou pas
        $latitude = addslashes($_POST['latitude']);
        $longitude = addslashes($_POST['longitude']);
        $adresse = addslashes($_POST['adresse']);
        // Après définition des variables on insert dans la BDD avec PDO
        $sqlinsert = $connexion->exec("INSERT INTO `Map`(`idMap`, `latitude`, `longitude`, `adresse`) VALUES ('','".$latitude."', '".$longitude."', '".$adresse."')");
        echo 'Vos coordonnées ont bien été insérées en base de données.';
    } else {
        echo 'Vos coordonnées n\'ont pas été insérées dans la base de données.';
    }
  } ?>
 
  <div id="map" style="height:500px;width:45%"></div>
  <form>
    <input type="text" name="latitude" id="latitude">
    <input type="text" name="longitude" id="longitude">
    <input type="text" name="adresse" id="adresse">
    <input type="button" value="Ajouter" onclick="AjouterAdresse();">
  </form>
  <div id="rep"></div>
</body>
</html>
