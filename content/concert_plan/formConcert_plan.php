<form action="content/concert_plan/concert_plan.php" method="POST">
<?php

  $id_concert_plan = (int)htmlspecialchars($_GET['concert_plan']);
  if(!empty($id_concert_plan))
  {
    $concert_plan = new concert_plan($cfg_mainLink, $id_concert_plan);

      $concert_plan->loadContracts();
      $concert_plan->prepareContracts();
      $plan = $concert_plan->read();
      $planContract = $concert_plan->readPlan();
      $plan['expenses'] = $concert_plan->calcExpenses();

      echo '<h3>Plan koncertu</h3>
      <table>
      <tr><td>Termin start</td><td><input type="datetime-local"  name="termin_start" value="'.$plan['termin_start'].'" required></td></tr>
      <tr><td>Termin koniec</td><td><input type="datetime-local"  name="termin_end" value="'.$plan['termin_end'].'" required></td></tr>
      <tr><td>Liczba miejsc</td><td><input type="text"  name="seats_number" value="'.$plan['seats_number'].'" required></td></tr>
      <tr><td>Koszt</td><td>'.$plan['expenses'].'</td>
      <tr><td>Status</td><td><input type="text"  name="status" value="'.$plan['status'].'" required></td></tr>
      </table>
      
      <button type="submit" name="concert_form" value="'.$plan['id_concert_form'].'">Przejdź do formularza</button>';

      if(isset($planContract['place']))
        echo '<button type="submit" name="contract_place" value="'.$planContract['place']['id_contract_place'].'">Przejdź do kontraktu miejsca</button>';

      if(isset($planContract['service']) && is_array($planContract['service']))
        foreach($planContract['service'] as $service)
          echo '<button type="submit" name="contract_service" value="'.$service['id_contract_service'].'">
          Przejdź do kontraktu usługi '.$service['service_type'].'</button>';

      if(isset($planContract['performer']) && is_array($planContract['performer']))
        foreach($planContract['performer'] as $performer)
          echo '<button type="submit" name="contract_performer" value="'.$performer['id_contract_performer'].'">
          Przejdź do kontraktu wykonawcy '.$performer['nickname'].'</button>';
      
      echo '<button type="submit" name="concert_plan_save" value="'.$plan['id_concert_plan'].'">Zapisz</button>';
  }

?>