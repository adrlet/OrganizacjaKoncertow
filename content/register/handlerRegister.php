<?php
      if(isset($_SESSION['registerResponse']))
      {
        echo $_SESSION['registerResponse'];
        unset($_SESSION['registerResponse']);
      }
?>