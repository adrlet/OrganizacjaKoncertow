<?php

  include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  $ok = False;
  if(isset($_POST['concert_form']) && (isset($_SESSION['id_client']) || isset($_SESSION['employee'])))
  {
    $planName = htmlspecialchars($_POST['planName']);
    $music_genre = htmlspecialchars($_POST['music_genre']);
    $seats_number = (int)htmlspecialchars($_POST['seats_number']);
    $budget = (float)htmlspecialchars($_POST['budget']);
    $location = htmlspecialchars($_POST['location']);
    $date = str_replace('T', ' ', htmlspecialchars($_POST['date'])).':00';
    $button_concert_form = htmlspecialchars($_POST['concert_form']);
    $id_concert_form = (int)htmlspecialchars($_POST['id_concert_form']);

    //echo $button_concert_form;

    if(isset($_SESSION['id_client']) && $klient->loadForm($id_concert_form) == 0)
      $ok = True;
    elseif(isset($_SESSION['employee']))
      $ok = True;
  }


  if($ok)
  {
    $concert_form = new concert_form($cfg_mainLink, $id_concert_form);

    $result = $concert_form->update($planName, $music_genre, $seats_number, $budget, $location, $date);
    switch($result)
    {
      case ERROR_concert_form_update_seats_number_notint:
        $_SESSION['concert_formResponse'] = 'Liczba miejsc musi być liczbą całkowitą';
        break;
      case ERROR_concert_form_update_budget_notfloat:
        $_SESSION['concert_formResponse'] = 'Budżet musi być liczbą';
        break;
      case ERROR_concert_form_update_date_wrong:
        $_SESSION['concert_formResponse'] = 'Data musi być w formacie YYYY-MM-DD HH-mm-ss';
        break;
        break;
      case ERROR_concert_form_update_noentry:
        $_SESSION['concert_formResponse'] = 'x1';
        break;
      case ERROR_concert_form_update_id_notexist:
        $_SESSION['concert_formResponse'] = 'x2';
        break;
      case ERROR_concert_form_update_no_change:
        $_SESSION['concert_formResponse'] = 'Wprowadź jakieś zmiany';
        break;
      case 0:
          $_SESSION['concert_formResponse'] = 'Wprowadzono zmiany';
          break;
      default:
        $_SESSION['concert_formResponse'] = 'Zachowaj umiar z ilością znaków :)';
    }

    if($button_concert_form == 'save')
    {
      if($result != 0)
        header('Location: /test/index.php?concert_form='.$id_concert_form);
      else
        header('Location: /test/index.php?concert_forms_page');
    }
    else if($button_concert_form == 'generatePlan')
    {

      $result = concert_plan::generatePlan($cfg_mainLink, $music_genre, $seats_number, $budget, $location, $date);
      if(!is_array($result))
      {
        switch($result)
        {
          case ERROR_concert_plan_generatePlan_link_isnull:
            $_SESSION['concert_formResponse'] = 'x1';
            break;
          case ERROR_concert_plan_generatePlan_performerfail:
            $_SESSION['concert_formResponse'] = 'Nie udało się wygenerować planu dla tak dobranych kryteriów';
            break;
          case ERROR_concert_plan_generatePlan_seats_number_empty:
            $_SESSION['concert_formResponse'] = 'Wpisz liczbę wymaganych miejsc';
            break;
          case ERROR_concert_plan_generatePlan_budget_empty:
            $_SESSION['concert_formResponse'] = 'Określ zakładany budżet';
            break;
          case ERROR_concert_plan_generatePlan_location_empty:
            $_SESSION['concert_formResponse'] = 'Określ miejscowość koncertu';
            break;
          case ERROR_concert_plan_generatePlan_date_empty:
            $_SESSION['concert_formResponse'] = 'Określ termin koncertu';
            break;
          case ERROR_concert_plan_generatePlan_seats_number_notint:
            $_SESSION['concert_formResponse'] = 'Liczba miejsc musi być liczbą całkowitą';
            break;
          case ERROR_concert_plan_generatePlan_budget_notfloat:
            $_SESSION['concert_formResponse'] = 'Budżet musi być liczbą';
            break;
          case ERROR_concert_plan_generatePlan_date_wrong:
            $_SESSION['concert_formResponse'] = 'Data musi być w formacie YYYY-MM-DD HH-mm-ss '.$date;
            break;
          default:
            $_SESSION['concert_formResponse'] = 'Zachowaj umiar z ilością znaków :)';
        }
      }
      else
      {
        $_SESSION['concert_form_has_plan'] = 'yes';
        $_SESSION['concert_formResponse'] = 'Wygenerowano plan koncertu';
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

      header('Location: /test/index.php?concert_form='.$id_concert_form);
    }
    elseif($button_concert_form == 'send')
    {
        $result = concert_plan::generatePlan($cfg_mainLink, $music_genre, $seats_number, $budget, $location, $date);

        if(!is_array($result))
        {
          switch($result)
          {
            case ERROR_concert_plan_generatePlan_performerfail:
              $_SESSION['concert_formResponse'] = 'Nie udało się wygenerować planu dla tak dobranych kryteriów';
              break;
            default:
              $_SESSION['concert_formResponse'] = 'Zachowaj umiar z ilością znaków :)';
          }
        }
        else
        {
          $date_end = DateTime::createFromFormat('Y-m-d H:i:s', $date);
          $date_end->modify('+'.$result['performers'].' hours');
          $termin_end = $date_end->format('Y-m-d H:i:s');

          $result2 = concert_plan::create($cfg_mainLink, $date, $id_concert_form, $termin_end, $seats_number, $result['expenses'], 'Przesłano');
          
          if($result2 == 0)
          {
            $result2 = $concert_form->loadPlan();
            if($result2 == 0)
              $concert_form->sendForm($result);
          
            $_SESSION['concert_formResponse'] = 'Przesłano formularz';
            header('Location: /test/index.php?concert_forms_page');
          }
        }
    }
    elseif($button_concert_form == 'delete')
    {
      $result = $concert_form->delete();
      $_SESSION['concert_formResponse'] = 'Usunieto formularz';
      header('Location: /test/index.php?concert_forms_page');
    }
    else
    {
      $_SESSION['concert_formResponse'] = 'Co to za czarowanie...';
      header('Location: /test/index.php?concert_form='.$id_concert_form);
    }
  }

?>