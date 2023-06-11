<form action="content/editContract/editContract.php" method="POST">
<table>

<?php

  if(isset($_GET['editContractPlace']) && isset($_SESSION['employee']))
  {
    $editContractPlace = (int)htmlspecialchars($_GET['editContractPlace']);

    $contract_place = new contract_place($cfg_mainLink, $editContractPlace);
    $contract_placeInfo = $contract_place->read();

    echo 'Miejsce
    <tr><td>Id kontraktu</td><td>Id planu</td><td>Id miejsca</td><td>Cena</td><td>Zapisz</td></tr>
    <tr>
    <td>'.$contract_placeInfo['id_contract_place'].'</td>
    <td>'.$contract_placeInfo['id_concert_plan'].'</td>
    <td>'.$contract_placeInfo['id_place'].'</td>
    <td><input type="text" name="contractPrice" value="'.$contract_placeInfo['price'].'" required></td>
    <td><button type="submit" name="saveContractPlace" value="'.$editContractPlace.'">Zapisz</button></td>
    </tr>';

  }
  elseif(isset($_GET['editContractService']) && isset($_SESSION['employee']))
  {
    $editContractService = (int)htmlspecialchars($_GET['editContractService']);

    $contract_service = new contract_service($cfg_mainLink, $editContractService);
    $contract_serviceInfo = $contract_service->read();

    echo 'Usługa
    <tr><td>Id kontraktu</td><td>Id planu</td><td>Id miejsca</td><td>Cena</td></tr>
    <tr>
    <td>'.$contract_serviceInfo['id_contract_service'].'</td>
    <td>'.$contract_serviceInfo['id_concert_plan'].'</td>
    <td>'.$contract_serviceInfo['id_service'].'</td>
    <td><input type="text" name="contractPrice" value="'.$contract_serviceInfo['price'].'" required></td>
    <td><button type="submit" name="saveContractService" value="'.$editContractService.'">Zapisz</button></td>
    </tr>';

  }
  elseif(isset($_GET['editContractPerformer']) && isset($_SESSION['employee']))
  {
    $editContractPerformer = (int)htmlspecialchars($_GET['editContractPerformer']);

    $contract_performer = new contract_performer($cfg_mainLink, $editContractPerformer);
    $contract_performerInfo = $contract_performer->read();

    echo 'Wykonawca
    <tr><td>Id kontraktu</td><td>Id planu</td><td>Id miejsca</td><td>Cena</td></tr>
    <tr>
    <td>'.$contract_performerInfo['id_contract_performer'].'</td>
    <td>'.$contract_performerInfo['id_concert_plan'].'</td>
    <td>'.$contract_performerInfo['id_performer'].'</td>
    <td><input type="text" name="contractPrice" value="'.$contract_performerInfo['price'].'" required></td>
    <td><button type="submit" name="saveContractPerformer" value="'.$editContractPerformer.'">Zapisz</button></td>
    </tr>';

  }

?>

</table>
</form>