<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<hr>
<?php

  $page = (int)htmlspecialchars($_GET['contracts_page']);
  if(empty($page))
    $page = 1;

  if(isset($_SESSION['employee']) && $_SESSION['employee'] == 'Dział Analityczny')
  {
    $concert_planCount = concert_plan::countConcert_plan($cfg_mainLink);
    $numeracjaStron = numeracjaStron($concert_planCount, $page, 2, 'index.php?contracts_page');
    $concert_plans = concert_plan::loadPlans($cfg_mainLink, $page, 2);

    echo '<p class="font-weight-bold mx-sm-2">Lista kontraktów</p> 
	<form action="content/listContracts/list_contract.php" method="POST">
    <table class="table table-striped mx-sm-2 mb-1">
    <tr><td><b>Plan</b></td><td><b>Do czego</b></td><td><b>Miejscowość/Typ/Gatunek</b></td><td><b>Adres/Nazwa/Pseudonim</b></td><td><b>Cena</b></td>
    <td><b>Edytuj</b></td><td><b>Usuń</b></td><td><b>Wyświetl plan</b></td></tr>';
    
    if(is_array($concert_plans))
    {
      for($i = 0; $i < count($concert_plans); $i++)
      {
        $concert_plans[$i]->loadContracts();
        $concert_plans[$i]->prepareContracts();
        $plan = $concert_plans[$i]->readPlan();
        $planInfo = $concert_plans[$i]->read();
        
        if(isset($plan['place']))
          echo '<tr>
          <td>'.$planInfo['id_concert_plan'].'</td>
          <td>Miejsce</td>
          <td>'.$plan['place']['location'].'</td>
          <td>'.$plan['place']['address'].'</td>
          <td>'.$plan['place']['rent_price'].'</td>
          <td><button type="submit" class="btn btn-primary btn-sm" name="edit_contract_place" value="'.$plan['place']['id_contract_place'].'">Edytuj</button></td>
          <td><button type="submit" class="btn btn-primary btn-sm" name="delete_contract_place" value="'.$plan['place']['id_contract_place'].'">Usuń</button></td>
          <td><button type="submit" class="btn btn-primary btn-sm" name="show_plan" value="'.$planInfo['id_concert_plan'].'">Wyświetl plan</button></td>
          </tr>';

        if(isset($plan['service']))
          for($j = 0; $j < count($plan['service']); $j++)
            echo '<tr>
            <td>'.$planInfo['id_concert_plan'].'</td>
            <td>Usługa</td>
            <td>'.$plan['service'][$j]['service_type'].'</td>
            <td>'.$plan['service'][$j]['company_name'].'</td>
            <td>'.$plan['service'][$j]['service_price'].'</td>
            <td><button type="submit" class="btn btn-primary btn-sm" name="edit_contract_service" value="'.$plan['service'][$j]['id_contract_service'].'">Edytuj</button></td>
            <td><button type="submit" class="btn btn-primary btn-sm" name="delete_contract_service" value="'.$plan['service'][$j]['id_contract_service'].'">Usuń</button></td>
            <td><button type="submit" class="btn btn-primary btn-sm" name="show_plan" value="'.$planInfo['id_concert_plan'].'">Wyświetl plan</button></td>
            </tr>';

        if(isset($plan['performer']))
          for($j = 0; $j < count($plan['performer']); $j++)
            echo '<tr>
            <td>'.$planInfo['id_concert_plan'].'</td>
            <td>Wykonawca</td>
            <td>'.$plan['performer'][$j]['music_genre'].'</td>
            <td>'.$plan['performer'][$j]['nickname'].'</td>
            <td>'.$plan['performer'][$j]['price'].'</td>
            <td><button type="submit" class="btn btn-primary btn-sm"  name="edit_contract_performer" value="'.$plan['performer'][$j]['id_contract_performer'].'">Edytuj</button></td>
            <td><button type="submit" class="btn btn-primary btn-sm" name="delete_contract_performer" value="'.$plan['performer'][$j]['id_contract_performer'].'">Usuń</button></td>
            <td><button type="submit" class="btn btn-primary btn-sm" name="show_plan" value="'.$planInfo['id_concert_plan'].'">Wyświetl plan</button></td>
            </tr>';
      }
    }

    echo ' <div class="mx-auto" style="width: 200px;"><div class="form-group col-md-2 mx-sm-2"> <button type="submit" class="btn btn-primary" name="newContract" value="">Nowy kontrakt</button></div>
    </div>
	</form>
    </table>';

    echo $numeracjaStron;
  }

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<hr>