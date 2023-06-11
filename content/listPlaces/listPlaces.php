<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<hr>
<p class="font-weight-bold mx-sm-2">Lista miejsc</p> 
<?php

  $page = (int)htmlspecialchars($_GET['places_page']);
  if(empty($page))
    $page = 1;

  if(isset($_SESSION['employee']) && $_SESSION['employee'] == 'Dział Analityczny')
  {
    $placesCount = places::countPlaces($cfg_mainLink);
    $numeracjaStron = numeracjaStron($placesCount, $page, 10, 'index.php?places_page');
    $places = places::loadPlaces($cfg_mainLink, $page);

    echo '<table class="table table-striped mx-sm-2 mb-1">
    <tr><td><b>id Miejsca</b></td><td><b>Miejscowość</b></td><td><b>Adres</b></td><td><b>Liczba osób</b></td><td><b>Cena wynajem</b></td>';
    
    if(is_array($places))
    {
      for($i = 0; $i < count($places); $i++)
      {
        $placeInfo = $places[$i]->read();
        echo '<tr>
        <td>'.$placeInfo['id_place'].'</td>
        <td>'.$placeInfo['location'].'</td>
        <td>'.$placeInfo['address'].'</td>
        <td>'.$placeInfo['seats_number'].'</td>
        <td>'.$placeInfo['rent_price'].'</td>
        </tr>';
      }
    }
    echo '</table>';
  }
  echo $numeracjaStron;

?>
<hr>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>