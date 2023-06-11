<?php
  if(isset($_SESSION['listConcert_formResponse']))
    {
        echo $_SESSION['listConcert_formResponse'];
        unset($_SESSION['listConcert_formResponse']);
    }
?>