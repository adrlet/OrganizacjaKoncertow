<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<hr>
<p class="font-weight-bold mx-sm-2">Dane koncertu</p>
<form action="content/newConcert_form/newConcert_form.php" method="POST">
<?php

  if(!isset($_SESSION['concert_form_name']))
  {
    $_SESSION['concert_form_name'] = '';
    $_SESSION['concert_form_music_genre'] = '';
    $_SESSION['concert_form_seats_number'] = '';
    $_SESSION['concert_form_budget'] = '';
    $_SESSION['concert_form_location'] = '';
    $_SESSION['concert_form_date'] = '';
  }

  echo '
  <div class="form-row col-md-4">
  <label for="planName"><b>Nazwa koncertu</b></label>
  <input class="form-control" type="text" placeholder="Wpisz nazwę koncertu" name="planName" value="'.$_SESSION['concert_form_name'].'" required>
  </div>
  <div class="form-row col-md-4">
  <label for="music_genre"><b>Gatunek muzyki</b></label>
  <select class="form-control" name="music_genre">
  </div>';
  
  $query = 'SELECT DISTINCT music_genre FROM performers;';
  $stmt = $cfg_mainLink->prepare($query);
  $stmt->execute();
  $stmt->bind_result($music_genre);
  while($stmt->fetch())
  {
    if($_SESSION['concert_form_music_genre'] == $music_genre)
      echo '<option value="'.$music_genre.'" selected="selected">'.$music_genre.'</option>';
    else
      echo '<option value="'.$music_genre.'">'.$music_genre.'</option>';
  }
  $stmt->close();

  echo '<div class="form-row col-md-4">
  </select>
 
  <label for="seats_number"><b>Liczba miejsc</b></label>
  <input class="form-control" type="text" placeholder="Wpisz liczbę miejsc" name="seats_number" value="'.$_SESSION['concert_form_seats_number'].'" required>
  </div>
  
  <div class="form-row col-md-4">
  <label for="budget"><b>Budżet</b></label>
  <input class="form-control" type="text" placeholder="Określ dostępny budżet" name="budget" value="'.$_SESSION['concert_form_budget'].'" required>

  <label for="location"><b>Miejscowość</b></label>
  <select class="form-control" name="location"></div>';
  $query = 'SELECT DISTINCT location FROM places;';
  $stmt = $cfg_mainLink->prepare($query);
  $stmt->execute();
  $stmt->bind_result($location);
  while($stmt->fetch())
  {
    if($_SESSION['concert_form_location'] == $location)
      echo '<option value="'.$location.'" selected="selected">'.$location.'</option>';
    else
      echo '<option value="'.$location.'">'.$location.'</option>';
  }
  $stmt->close();

  echo '
  <div class="form-group ol-md-2 mx-sm-2">
  </select>
  <label for="date"><b>Data</b></label>
  <input class="form-control" type="datetime-local" name="date" value="'.$_SESSION['concert_form_date'].'" required>
	</div>
	<div class="form-group ol-md-2 mx-sm-2">
  <button type="submit" class="btn btn-primary" name="newConcert_form" value="generatePlan">Wygeneruj</button>
  <button type="submit" class="btn btn-primary" name="newConcert_form" value="save">Zapisz formularz</button></div>';
  if(isset($_SESSION['concert_form_has_plan']))
    echo '<div class="form-group ol-md-2 mx-sm-2"><button type="submit" class="btn btn-primary" name="newConcert_form" value="send">Prześlij formularz</button></div>';

  unset($_SESSION['concert_form_name']);
  unset($_SESSION['concert_form_music_genre']);
  unset($_SESSION['concert_form_seats_number']);
  unset($_SESSION['concert_form_budget']);
  unset($_SESSION['concert_form_location']);
  //echo $_SESSION['concert_form_date'];
  unset($_SESSION['concert_form_date']);
?>
</form>
<?php

  if(isset($_SESSION['performers']))
  {
    $placeKey = array('location', 'address', 'participants_limit', 'rent_price');
    $placeTrans = array('Miasto', 'Gdzie', 'Liczba miejsc', 'Koszt wynajmu');
    $serviceTypes = array('Ochrona', 'Obsługa', 'Oświetlenie', 'Nagłośnienie');
    $serviceKey = array('company_name', 'service_type', 'service_price');
    $serviceTrans = array('Firma', 'Usługa', 'Koszt usługi');
    $performerKey = array('nickname', 'music_genre', 'price');
    $performerTrans = array('Pseudonim', 'Gatunke muzyki', 'Cena artysty');
    $performers = $_SESSION['performers'];
    $expenses = $_SESSION['expenses'];

    echo '<h3>Propozycja planu koncertu</h3>
    <table class="table table-striped mx-sm-2 mb-1">
    <h4>Miejsce koncertu</h4>';
    foreach($placeKey as $number => $key)
    {
      echo '<tr><td>'.$placeTrans[$number].'</td><td>'.$_SESSION['places_'.$key].'</td></tr>';
      unset($_SESSION['places_'.$key]);
    }
    echo'</table>
    <table class="table table-striped mx-sm-2 mb-1">
    <h4>Dopasowane usługi</h4>';
    foreach($serviceTypes as $serviceType)
    {
      foreach($serviceKey as $number => $key)
      {
        echo '<tr><td>'.$serviceTrans[$number].'</td><td>'.$_SESSION['service_'.$serviceType.'_'.$key].'</td></tr>';
        unset($_SESSION['service_'.$serviceType.'_'.$key]);
      }
    }
    echo '</table>
    <table class="table table-striped mx-sm-2 mb-1">
    <h4>Dobrani wykonawcy</h4>';
    for($i = 0; $i < $performers; $i++)
    {
      foreach($performerKey as $number => $key)
      {
        echo '<tr><td>'.$performerTrans[$number].'</td><td>'.$_SESSION['performer_'.$i.'_'.$key].'</td></tr>';
        unset($_SESSION['performer_'.$i.'_'.$key]);
      }
    }
    echo '</table>
    <br/><b>Koszta: '.$expenses;

    unset($_SESSION['expenses']);
    unset($_SESSION['performers']);
    unset($_SESSION['concert_form_has_plan']);
  }

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<hr>
</head>