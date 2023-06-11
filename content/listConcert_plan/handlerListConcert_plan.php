<?php
  if(isset($_SESSION['listConcert_planResponse']))
  {
    echo $_SESSION['listConcert_planResponse'];
    unset($_SESSION['listConcert_planResponse']);
  }
?>