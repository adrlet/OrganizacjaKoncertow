<?php
  include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_SESSION['employee']))
  {
    if(isset($_POST['show_plan']))
    {
      $pokazPlan = (int)htmlspecialchars($_POST['show_plan']);
      header('Location: /test/index.php?concert_plan='.$pokazPlan);
    }
    else
    {
      if(isset($_POST['edit_contract_place']))
      {
        $edit_contract_place = (int)htmlspecialchars($_POST['edit_contract_place']);
        header('Location: /test/index.php?editContractPlace='.$edit_contract_place);
      }
      elseif(isset($_POST['edit_contract_service']))
      {
        $edit_contract_service = (int)htmlspecialchars($_POST['edit_contract_service']);
        header('Location: /test/index.php?editContractService='.$edit_contract_service);
      }
      elseif(isset($_POST['edit_contract_performer']))
      {
        $edit_contract_performer = (int)htmlspecialchars($_POST['edit_contract_performer']);
        header('Location: /test/index.php?editContractPerformer='.$edit_contract_performer);
      }
    }

    if(isset($_POST['delete_contract_place']))
    {
      $delete_contract_place = (int)htmlspecialchars($_POST['delete_contract_place']);
      $contract_place = new contract_place($cfg_mainLink, $delete_contract_place);
      $result = $contract_place->delete();
      
      if($result == 0)
        $_SESSION['listContractsResponse'] = 'Usunięto kontrakt '.$delete_contract_place;
      else
        $_SESSION['listContractsResponse'] = 'Nie udało się usunąć kontraktu '.$delete_contract_place;

      header('Location: '.$_SERVER['HTTP_REFERER']);
    }
    elseif(isset($_POST['delete_contract_service']))
    {
      $delete_contract_service = (int)htmlspecialchars($_POST['delete_contract_service']);
      $contract_service = new contract_service($cfg_mainLink, $delete_contract_service);
      $result = $contract_service->delete();
      
      if($result == 0)
        $_SESSION['listContractsResponse'] = 'Usunięto kontrakt '.$delete_contract_service;
      else
        $_SESSION['listContractsResponse'] = 'Nie udało się usunąć kontraktu '.$delete_contract_service;

      header('Location: '.$_SERVER['HTTP_REFERER']);
    }
    elseif(isset($_POST['delete_contract_performer']))
    {
      $delete_contract_performer = (int)htmlspecialchars($_POST['delete_contract_performer']);
      $contract_performer = new contract_performer($cfg_mainLink, $delete_contract_performer);
      $result = $contract_performer->delete();
      
      if($result == 0)
        $_SESSION['listContractsResponse'] = 'Usunięto kontrakt '.$delete_contract_performer;
      else
        $_SESSION['listContractsResponse'] = 'Nie udało się usunąć kontraktu '.$delete_contract_performer;

      header('Location: '.$_SERVER['HTTP_REFERER']);
    }

    if(isset($_POST['newContract']))
      header('Location: /test/index.php?newContract');
  }
?>