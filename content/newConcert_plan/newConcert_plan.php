<?php

  include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_SESSION['employee']))
  {
    if(isset($_POST['save_concert_plan']))
    {
      $id_concert_form = (int)htmlspecialchars($_POST['id_concert_form']);
      $termin_start = str_replace('T', ' ', htmlspecialchars($_POST['termin_start'])).':00';
      $termin_end = str_replace('T', ' ', htmlspecialchars($_POST['termin_end'])).':00';
      $seats_number = (int)htmlspecialchars($_POST['seats_number']);
      $status = htmlspecialchars($_POST['status']);
      
      $concert_form = new concert_form($cfg_mainLink, $id_concert_form);
      
      if($concert_form->loadPlan() != 0)
      {
        $result = concert_plan::create($cfg_mainLink, $termin_start, $id_concert_form, $termin_end, $seats_number, 0.0, $status);

        if($result == 0)
        {
          $_SESSION['newConcert_planResponse'] = 'Utworzono plan';
          header('Location: /test/index.php?concert_plans_page=1');
        }
        else
        {
          $_SESSION['newConcert_planResponse'] = 'Nie udało się utworzyć planu';
          header('Location: /test/index.php?newConcert_plan');
        }
      }
      else
      {
        $_SESSION['newConcert_planResponse'] = 'Plan już istnieje dla wskazanego formularza';
        header('Location: /test/index.php?newConcert_plan');
      }
    }
  }

?>