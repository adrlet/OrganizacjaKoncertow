<?php
      if(isset($_SESSION['concert_formResponse']))
      {
        echo $_SESSION['concert_formResponse'];
        unset($_SESSION['concert_formResponse']);
      }
?>