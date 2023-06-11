<?php
      if(isset($_SESSION['newConcert_planResponse']))
      {
        echo $_SESSION['newConcert_planResponse'];
        unset($_SESSION['newConcert_planResponse']);
      }
?>