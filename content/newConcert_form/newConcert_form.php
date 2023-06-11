<?php

include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_POST['newConcert_form']) && isset($_SESSION['id_client']))
  {
    $planName = htmlspecialchars($_POST['planName']);
    $music_genre = htmlspecialchars($_POST['music_genre']);
    $seats_number = (int)htmlspecialchars($_POST['seats_number']);
    $budget = (float)htmlspecialchars($_POST['budget']);
    $location = htmlspecialchars($_POST['location']);
    $date = str_replace('T', ' ', htmlspecialchars($_POST['date'])).':00';
    //$date = htmlspecialchars($_POST['date']);
    $newConcert_form = htmlspecialchars($_POST['newConcert_form']);

    if($newConcert_form == 'save')
    {
      $klientInfo = $klient->read();
      $result = concert_form::create($cfg_mainLink, $planName, $klientInfo['id_client'], $music_genre, $seats_number,
      $budget, $location, $date);

      switch($result)
      {
        case ERROR_concert_form_create_link_isnull:
          $_SESSION['newConcert_formResponse'] = 'x1';
          break;
        case ERROR_concert_form_create_foreign_key_empty:
          $_SESSION['newConcert_formResponse'] = 'x2';
          break;
        case ERROR_concert_form_create_name_empty:
          $_SESSION['newConcert_formResponse'] = 'Wpisz nazwę koncertu';
          break;
        case ERROR_concert_form_create_seats_number_empty:
          $_SESSION['newConcert_formResponse'] = 'Wpisz liczbę wymaganych miejsc';
          break;
        case ERROR_concert_form_create_budget_empty:
          $_SESSION['newConcert_formResponse'] = 'Określ zakładany budżet';
          break;
        case ERROR_concert_form_create_location_empty:
          $_SESSION['newConcert_formResponse'] = 'Określ miejscowość koncertu';
          break;
        case ERROR_concert_form_create_date_empty:
          $_SESSION['newConcert_formResponse'] = 'Określ termin koncertu';
          break;
        case ERROR_concert_form_create_seats_number_notint:
          $_SESSION['newConcert_formResponse'] = 'Liczba miejsc musi być liczbą całkowitą';
          break;
        case ERROR_concert_form_create_budget_notfloat:
          $_SESSION['newConcert_formResponse'] = 'Budżet musi być liczbą';
          break;
        case ERROR_concert_form_create_date_wrong:
          $_SESSION['newConcert_formResponse'] = 'Data musi być w formacie YYYY-MM-DD HH-mm-ss';
          break;
        case ERROR_concert_form_create_foreign_key_notexist:
          $_SESSION['newConcert_formResponse'] = 'x3';
          break;
        case ERROR_concert_form_create_failed:
          $_SESSION['newConcert_formResponse'] = 'x4';
          break;
        case 0:
          $_SESSION['newConcert_formResponse'] = 'Zapisano formularz';
          break;
        default:
          $_SESSION['newConcert_formResponse'] = 'Zachowaj umiar z ilością znaków :)';
      }

      if($result != 0)
      {
        $_SESSION['concert_form_name'] = $planName;
        $_SESSION['concert_form_music_genre'] = $music_genre;
        $_SESSION['concert_form_seats_number'] = $seats_number;
        $_SESSION['concert_form_budget'] = $budget;
        $_SESSION['concert_form_location'] = $location;
        $_SESSION['concert_form_date'] = substr(str_replace(' ', 'T', $date), 0, strlen($date-3));
        
        header('Location: /test/index.php?newConcert_form');
      }
      else
        header('Location: /test/index.php?concert_forms_page');
    }
    else if($newConcert_form == 'generatePlan')
    {
      $_SESSION['concert_form_name'] = $planName;
      $_SESSION['concert_form_music_genre'] = $music_genre;
      $_SESSION['concert_form_seats_number'] = $seats_number;
      $_SESSION['concert_form_budget'] = $budget;
      $_SESSION['concert_form_location'] = $location;
      $_SESSION['concert_form_date'] = substr(str_replace(' ', 'T', $date), 0, strlen($date)-3);

      $result = concert_plan::generatePlan($cfg_mainLink, $music_genre, $seats_number, $budget, $location, $date);
      if(!is_array($result))
      {
        switch($result)
        {
          case ERROR_concert_plan_generatePlan_link_isnull:
            $_SESSION['newConcert_formResponse'] = 'x1';
            break;
          case ERROR_concert_plan_generatePlan_performerfail:
            $_SESSION['newConcert_formResponse'] = 'Nie udało się wygenerować planu dla tak dobranych kryteriów';
            break;
          case ERROR_concert_plan_generatePlan_seats_number_empty:
            $_SESSION['newConcert_formResponse'] = 'Wpisz liczbę wymaganych miejsc';
            break;
          case ERROR_concert_plan_generatePlan_budget_empty:
            $_SESSION['newConcert_formResponse'] = 'Określ zakładany budżet';
            break;
          case ERROR_concert_plan_generatePlan_location_empty:
            $_SESSION['newConcert_formResponse'] = 'Określ miejscowość koncertu';
            break;
          case ERROR_concert_plan_generatePlan_date_empty:
            $_SESSION['newConcert_formResponse'] = 'Określ termin koncertu';
            break;
          case ERROR_concert_plan_generatePlan_seats_number_notint:
            $_SESSION['newConcert_formResponse'] = 'Liczba miejsc musi być liczbą całkowitą';
            break;
          case ERROR_concert_plan_generatePlan_budget_notfloat:
            $_SESSION['newConcert_formResponse'] = 'Budżet musi być liczbą';
            break;
          case ERROR_concert_plan_generatePlan_date_wrong:
            $_SESSION['newConcert_formResponse'] = 'Data musi być w formacie YYYY-MM-DD HH-mm-ss '.$date;
            break;
          default:
            $_SESSION['newConcert_formResponse'] = 'Zachowaj umiar z ilością znaków :)';
        }
      }
      else
      {
        $_SESSION['concert_form_has_plan'] = 'yes';
        $_SESSION['newConcert_formResponse'] = 'Wygenerowano plan koncertu';
        $_SESSION['expenses'] = $result['expenses'];
        foreach($result['places'] as $x => $y)
          $_SESSION['places_'.$x] = $y;

        $serviceTypes = array('Ochrona', 'Obsługa', 'Oświetlenie', 'Nagłośnienie');
        foreach($serviceTypes as $service)
          foreach($result['service'.$service] as $x => $y)
            $_SESSION['service_'.$service.'_'.$x] = $y;

        $_SESSION['performers'] = $result['performers'];
        for($i = 0; $i < $result['performers']; $i++)
          foreach($result['performer'.$i] as $x => $y)
            $_SESSION['performer_'.$i.'_'.$x] = $y;
      }

      header('Location: /test/index.php?newConcert_form');
    }
    elseif($newConcert_form == 'send')
    {
      $klientInfo = $klient->read();
      $result = concert_form::create($cfg_mainLink, $planName, $klientInfo['id_client'], $music_genre, $seats_number,
      $budget, $location, $date);

      switch($result)
      {
        case ERROR_concert_form_create_link_isnull:
          $_SESSION['newConcert_formResponse'] = 'x1';
          break;
        case ERROR_concert_form_create_foreign_key_empty:
          $_SESSION['newConcert_formResponse'] = 'x2';
          break;
        case ERROR_concert_form_create_name_empty:
          $_SESSION['newConcert_formResponse'] = 'Wpisz nazwę koncertu';
          break;
        case ERROR_concert_form_create_seats_number_empty:
          $_SESSION['newConcert_formResponse'] = 'Wpisz liczbę wymaganych miejsc';
          break;
        case ERROR_concert_form_create_budget_empty:
          $_SESSION['newConcert_formResponse'] = 'Określ zakładany budżet';
          break;
        case ERROR_concert_form_create_location_empty:
          $_SESSION['newConcert_formResponse'] = 'Określ miejscowość koncertu';
          break;
        case ERROR_concert_form_create_date_empty:
          $_SESSION['newConcert_formResponse'] = 'Określ termin koncertu';
          break;
        case ERROR_concert_form_create_seats_number_notint:
          $_SESSION['newConcert_formResponse'] = 'Liczba miejsc musi być liczbą całkowitą';
          break;
        case ERROR_concert_form_create_budget_notfloat:
          $_SESSION['newConcert_formResponse'] = 'Budżet musi być liczbą';
          break;
        case ERROR_concert_form_create_date_wrong:
          $_SESSION['newConcert_formResponse'] = 'Data musi być w formacie YYYY-MM-DD HH-mm-ss';
          break;
        case ERROR_concert_form_create_foreign_key_notexist:
          $_SESSION['newConcert_formResponse'] = 'x3';
          break;
        case ERROR_concert_form_create_failed:
          $_SESSION['newConcert_formResponse'] = 'x4';
          break;
        default:
          $_SESSION['newConcert_formResponse'] = 'Zachowaj umiar z ilością znaków :)';
      }

      if($result == 0)
      {

        $result = concert_plan::generatePlan($cfg_mainLink, $music_genre, $seats_number, $budget, $location, $date);

        if(!is_array($result))
        {
          switch($result)
          {
            case ERROR_concert_plan_generatePlan_performerfail:
              $_SESSION['newConcert_formResponse'] = 'Nie udało się wygenerować planu dla tak dobranych kryteriów';
              break;
            default:
              $_SESSION['newConcert_formResponse'] = 'Zachowaj umiar z ilością znaków :)';
          }
        }
        else
        {
          $id_client = $klientInfo['id_client'];

          $query = 'SELECT id_concert_form FROM concert_form WHERE id_client=? ORDER BY id_concert_form DESC LIMIT 1;';
          $stmt = $cfg_mainLink->prepare($query);
          $stmt->bind_param('i', $id_client);
          $stmt->execute();
          $stmt->bind_result($id_concert_form);
          $stmt->fetch();
          $stmt->close();

          $concert_form = new concert_form($cfg_mainLink, $id_concert_form);

          $date_end = DateTime::createFromFormat('Y-m-d H:i:s', $date);
          $date_end->modify('+'.$result['performers'].' hours');
          $termin_end = $date_end->format('Y-m-d H:i:s');

          $result2 = concert_plan::create($cfg_mainLink, $date, $id_concert_form, $termin_end, $seats_number, $result['expenses'], 'Przesłano');
          
          if($result2 == 0)
          {
            $result2 = $concert_form->loadPlan();
            if($result2 == 0)
              $concert_form->sendForm($result);
          
            $_SESSION['newConcert_formResponse'] = 'Przesłano formularz';
            header('Location: /test/index.php?concert_forms_page');
          }
        }
      }
      else
        header('Location: /test/index.php?newConcert_form');
    }
    else
      $_SESSION['newConcert_formResponse'] = 'Co to za czarowanie...';
  }

?>