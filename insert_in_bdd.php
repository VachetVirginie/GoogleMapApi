
<?php
 header('Content-type: text/html; charset=ISO-8859-1');
 if(isset($_POST['lat']) && isset($_POST['lng'])){
  $lat = addslashes($_POST['lat']);
  $lng = addslashes($_POST['lng']);
  $adr = addslashes($_POST['adr']);
  $db = mysql_connect(SERVER, USER, PASSWORD);
  $select = mysql_select_db(DATABASE, $db);
  mysql_query('INSERT INTO ma_table (lat,lng,adresse)
               VALUES ("'.$lat.'","'.$lng.'","'.$adr.'")');
  echo 'Vos coordonnées ont bien été insérées en base de données.';
 }else
   echo 'Problème rencontré dans les valeurs passées en paramètres';
?>