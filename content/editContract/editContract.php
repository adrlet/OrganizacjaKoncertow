<?php
  include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_SESSION['employee']))
  {
    if(isset($_POST['saveContractPlace']))
    {
      $saveContractPlace = (int)htmlspecialchars($_POST['saveContractPlace']);
      $price = (float)htmlspecialchars($_POST['contractPrice']);
      $contract_place = new contract_place($cfg_mainLink, $saveContractPlace);
      $result = $contract_place->update($price);

      if($result == 0)
      {
        $_SESSION['editContractResponse'] = 'Edytowano kontrakt '.$saveContractPlace;
        header('Location: /test/index.php?contracts_page=1');
      }
      else
      {
        $_SESSION['editContractResponse'] = 'Nie udało się zedytować kontraktu '.$saveContractPlace.' '.$result;
        header('Location: /test/index.php?editContractPlace='.$saveContractPlace);
      }
    }
    elseif(isset($_POST['saveContractService']))
    {
      $saveContractService = (int)htmlspecialchars($_POST['saveContractService']);
      $price = (float)htmlspecialchars($_POST['contractPrice']);
      $contract_service = new contract_service($cfg_mainLink, $saveContractService);
      $result = $contract_service->update($price);

      if($result == 0)
      {
        $_SESSION['editContractResponse'] = 'Edytowano kontrakt '.$saveContractService;
        header('Location: /test/index.php?contracts_page=1');
      }
      else
      {
        $_SESSION['editContractResponse'] = 'Nie udało się zedytować kontraktu '.$saveContractService.' '.$result;
        header('Location: /test/index.php?editContractService='.$saveContractService);
      }
    }
    elseif(isset($_POST['saveContractPerformer']))
    {
      $saveContractPerformer = (int)htmlspecialchars($_POST['saveContractPerformer']);
      $price = (float)htmlspecialchars($_POST['contractPrice']);
      $contract_performer = new contract_performer($cfg_mainLink, $saveContractPerformer);
      $result = $contract_performer->update($price);

      if($result == 0)
      {
        $_SESSION['editContractResponse'] = 'Edytowano kontrakt '.$saveContractPerformer;
        header('Location: /test/index.php?contracts_page=1');
      }
      else
      {
        $_SESSION['editContractResponse'] = 'Nie udało się zedytować kontraktu '.$saveContractPerformer.' '.$result;
        header('Location: /test/index.php?editContractPerformer='.$saveContractPerformer);
      }
    }
  }

?>