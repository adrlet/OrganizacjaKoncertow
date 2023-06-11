<?php
      if(isset($_SESSION['concert_planResponse']))
      {
        echo $_SESSION['concert_planResponse'];
        unset($_SESSION['concert_planResponse']);
      }
?>