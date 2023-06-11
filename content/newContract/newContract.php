<?php

include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_SESSION['employee']) && isset($_POST['save_concert_plan']))
  {
    $id_concert_plan = (int)htmlspecialchars($_POST['id_concert_plan']);
    $id_foreign_key = (int)htmlspecialchars($_POST['id_foreign_key']);
    $price = (float)htmlspecialchars($_POST['price']);
    $type = htmlspecialchars($_POST['type']);

    if($type == 'place')
      $result = contract_place::create($cfg_mainLink, $id_foreign_key, $id_concert_plan, $price);
    elseif($type == 'service')
      $result = contract_service::create($cfg_mainLink, $id_foreign_key, $id_concert_plan, $price);
    elseif($type == 'performer')
      $result = contract_performer::create($cfg_mainLink, $id_foreign_key, $id_concert_plan, $price);
    else
    {
      $_SESSION['newContractResponse'] = 'Nie czaruj';
      header('Location: /test/index.php?newContract');
    }

    if(isset($result))
    {
      if($result == 0)
      {
        $_SESSION['newContractResponse'] = 'Utworzono kontrakt';
        header('Location: /test/index.php?contracts_page=1');
      }
      else
      {
        $_SESSION['newContractResponse'] = 'Wkradł się błąd';
        header('Location: /test/index.php?newContract');
      }
    }
    
  }

?>