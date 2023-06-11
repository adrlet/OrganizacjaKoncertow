<?php

  include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_POST['concert_plan']) && isset($_SESSION['employee']))
  {
    $concert_plan = (int)htmlspecialchars($_POST['concert_plan']);
    header('Location: /test/index.php?concert_plan='.$concert_plan);
  }

  if(isset($_POST['delete']) && isset($_SESSION['employee']))
  {
    $delete = (int)htmlspecialchars($_POST['delete']);

    $concert_plan = new concert_plan($cfg_mainLink, $delete);
    $result = $concert_plan->delete();

    if($result == 0)
      $_SESSION['listConcert_planResponse'] = 'Usunięto plan';
    else
      $_SESSION['listConcert_planResponse'] = 'Nie udało się usunąć planu';

    header('Location: /test/index.php?concert_plans_page=1');
  }

?>