<?php

  include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_SESSION['employee']))
  {
    if(isset($_POST['concert_form']))
    {
        $id_concert_form = (int)htmlspecialchars($_POST['concert_form']);
        header('Location: /test/index.php?concert_form='.$id_concert_form);
    }
    elseif(isset($_POST['contract_place']))
    {
        $id_contract_place = (int)htmlspecialchars($_POST['contract_place']);
        header('Location: /test/index.php?editContractPlace='.$id_contract_place);
    }
    elseif(isset($_POST['contract_service']))
    {
        $id_contract_service = (int)htmlspecialchars($_POST['contract_service']);
        header('Location: /test/index.php?editContractService='.$id_contract_service);
    }
    elseif(isset($_POST['contract_performer']))
    {
        $id_contract_performer = (int)htmlspecialchars($_POST['contract_performer']);
        header('Location: /test/index.php?editContractPerformer='.$id_contract_performer);
    }
    elseif(isset($_POST['concert_plan_save']))
    {
      $id_concert_plan = (int)htmlspecialchars($_POST['concert_plan_save']);
      $termin_start = str_replace('T', ' ', htmlspecialchars($_POST['termin_start'])).':00';
      $termin_end = str_replace('T', ' ', htmlspecialchars($_POST['termin_end'])).':00';
      $seats_number = (int)htmlspecialchars($_POST['seats_number']);
      $status = htmlspecialchars($_POST['status']);

      $concert_plan = new concert_plan($cfg_mainLink ,$id_concert_plan);
      $result = $concert_plan->update($termin_start, $termin_end, $seats_number, 0, $status);
      if($result == 0)
      {
        $_SESSION['concert_planResponse'] = 'Zaktualizowano plan '.$id_concert_plan;
        header('Location: /test/index.php?concert_plans_page=1');
      }
      else
      {
        $_SESSION['concert_planResponse'] = 'Nieudało się zaktualizować planu '.$id_concert_plan.' '.$result;
        header('Location: /test/index.php?concert_plan='.$id_concert_plan);
      }
    }
  }

?>