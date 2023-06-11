<form action="content/concert_form/concert_form.php" method="POST">
<?php

  $id_concert_form = (int)htmlspecialchars($_GET['concert_form']);
  if(!empty($id_concert_form))
  {
    $concert_form = new concert_form($cfg_mainLink, $id_concert_form);
    $concert_formInfo = $concert_form->read();

    if($concert_form->loadPlan() == 0)
    {
      $concert_form->initPlan();
      $plan = $concert_form->readPlan();

      echo '<h3>Formularz koncertu</h3>
      <table>
      <tr><td>Nazwa</td><td>'.$concert_formInfo['name'].'</td>
      <tr><td>Gatunek muzyki</td><td>'.$concert_formInfo['music_genre'].'</td>
      <tr><td>Liczba miejsc</td><td>'.$concert_formInfo['seats_number'].'</td>
      <tr><td>Budżet</td><td>'.$concert_formInfo['budget'].'</td>
      <tr><td>Miejscowość</td><td>'.$concert_formInfo['location'].'</td>
      <tr><td>Data</td><td>'.$concert_formInfo['date'].'</td>
      </table>
      <h3>Plan koncertu</h3>
      <table>';

      $placeKey = array('location', 'address', 'seats_number', 'rent_price');
      $placeTrans = array('Miasto', 'Gdzie', 'Liczba miejsc', 'Koszt wynajmu');
      echo '<h4>Miejsce</h4>';
      if(isset($plan['place']))
        foreach($placeKey as $x => $y)
          echo '<tr><td>'.$placeTrans[$x].'</td><td>'.$plan['place'][$y].'</tr>';

      $serviceKey = array('company_name', 'service_type', 'service_price', 'seats_number');
      $serviceTrans = array('Firma', 'Usługa', 'Koszt usługi', 'Liczba miejsc');
      echo '<h4>Usługi</h4>';
      if(isset($plan['service']))
        for($i = 0; $i < 4; $i++)
          if(isset($plan['service'][$i]))
            foreach($serviceKey as $x => $y)
              echo '<tr><td>'.$serviceTrans[$x].'</td><td>'.$plan['service'][$i][$y].'</tr>';

      $performerKey = array('nickname', 'music_genre', 'price');
      $performerTrans = array('Pseudonim', 'Gatunke muzyki', 'Cena artysty');
      echo '<h4>Wykonawcy</h4>';
      if(isset($plan['performer']))
        for($i = 0; $i < count($plan['performer']); $i++)
          foreach($performerKey as $x => $y)
            echo '<tr><td>'.$performerTrans[$x].'</td><td>'.$plan['performer'][$i][$y].'</tr>';
      
      echo '
      <tr><td>Wydatki</td><td>'.$plan['expenses'].'</td></tr>
      <tr><td>Status</td><td>'.$plan['status'].'</td></tr>
      </table>';

    }
    else
    {
      echo '
      <label for="planName"><b>Nazwa koncertu</b></label>
      <input type="text" placeholder="Wpisz nazwę koncertu" name="planName" value="'.$concert_formInfo['name'].'" required>

      <label for="music_genre"><b>Gatunek muzyki</b></label>
      <select name="music_genre">';
      
      $query = 'SELECT DISTINCT music_genre FROM performers;';
      $stmt = $cfg_mainLink->prepare($query);
      $stmt->execute();
      $stmt->bind_result($music_genre);
      while($stmt->fetch())
      {
        if($concert_formInfo['music_genre'] == $music_genre)
          echo '<option value="'.$music_genre.'" selected="selected">'.$music_genre.'</option>';
        else
          echo '<option value="'.$music_genre.'">'.$music_genre.'</option>';
      }
      $stmt->close();

      echo '
      </select>

      <label for="seats_number"><b>Liczba miejsc</b></label>
      <input type="text" placeholder="Wpisz liczbę miejsc" name="seats_number" value="'.$concert_formInfo['seats_number'].'" required>

      <label for="budget"><b>Budżet</b></label>
      <input type="text" placeholder="Określ dostępny budżet" name="budget" value="'.$concert_formInfo['budget'].'" required>

      <label for="location"><b>Miejscowość</b></label>
      <select name="location">';
      $query = 'SELECT DISTINCT location FROM places;';
      $stmt = $cfg_mainLink->prepare($query);
      $stmt->execute();
      $stmt->bind_result($location);
      while($stmt->fetch())
      {
        if($concert_formInfo['location'] == $location)
          echo '<option value="'.$location.'" selected="selected">'.$location.'</option>';
        else
          echo '<option value="'.$location.'">'.$location.'</option>';
      }
      $stmt->close();

      echo '
      </select>
      <label for="date"><b>Data</b></label>
      <input type="datetime-local" name="date" value="'.substr(str_replace(' ', 'T', $concert_formInfo['date']), 0, strlen($concert_formInfo['date'])-3).'" required>

      <input type="hidden" name="id_concert_form" value="'.$id_concert_form.'">

      <button type="submit" name="concert_form" value="generatePlan">Wygeneruj</button>
      <button type="submit" name="concert_form" value="save">Zapisz formularz</button>
      <button type="submit" name="concert_form" value="delete">Usuń formularz</button>';

      if(isset($_SESSION['concert_form_has_plan']))
        echo '<button type="submit" name="concert_form" value="send">Prześlij formularz</button>';

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
        <table>
        <h4>Miejsce koncertu</h4>';
        foreach($placeKey as $number => $key)
        {
          echo '<tr><td>'.$placeTrans[$number].'</td><td>'.$_SESSION['places_'.$key].'</td></tr>';
          unset($_SESSION['places_'.$key]);
        }
        echo'</table>
        <table>
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
        <table>
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
        <br/>Koszta: '.$expenses;

        unset($_SESSION['expenses']);
        unset($_SESSION['performers']);
        unset($_SESSION['concert_form_has_plan']);
      }
    }
  }

?>