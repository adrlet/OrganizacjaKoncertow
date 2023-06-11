<?php
      if(isset($_SESSION['changeEmailResponse']))
      {
        echo $_SESSION['changeEmailResponse'];
        unset($_SESSION['changeEmailResponse']);
      }
    ?>