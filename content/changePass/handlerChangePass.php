<?php
      if(isset($_SESSION['changePassResponse']))
      {
        echo $_SESSION['changePassResponse'];
        unset($_SESSION['changePassResponse']);
      }
    ?>