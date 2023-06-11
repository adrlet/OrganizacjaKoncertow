<?php
      if(isset($_SESSION['deleteMeResponse']))
      {
        echo $_SESSION['deleteMeResponse'];
        unset($_SESSION['deleteMeResponse']);
      }
    ?>