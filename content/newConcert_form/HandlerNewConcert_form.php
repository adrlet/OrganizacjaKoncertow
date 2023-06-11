<?php
      if(isset($_SESSION['newConcert_formResponse']))
      {
        echo $_SESSION['newConcert_formResponse'];
        unset($_SESSION['newConcert_formResponse']);
      }
?>