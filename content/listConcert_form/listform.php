<?php

  include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

  if(isset($_POST['concert_form']) && isset($_SESSION['employee']))
  {
    $concert_form = (int)htmlspecialchars($_POST['concert_form']);
    header('Location: /test/index.php?concert_form='.$concert_form);
  }

  if(isset($_POST['delete']) && isset($_SESSION['employee']))
  {
    $delete = (int)htmlspecialchars($_POST['delete']);

    $concert_form = new concert_form($cfg_mainLink, $delete);
    $result = $concert_form->delete();

    if($result == 0)
      $_SESSION['listConcert_formResponse'] = 'Usunięto formularz';
    else
      $_SESSION['listConcert_formResponse'] = 'Nie udało się usunąć formularza';

    header('Location: /test/index.php?concert_forms_page=1');
  }

?>