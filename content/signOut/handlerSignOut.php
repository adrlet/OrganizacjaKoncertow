<?php
  if(isset($_SESSION['signOutResponse']))
    {
        echo $_SESSION['signOutResponse'];
        unset($_SESSION['signOutResponse']);
    }
?>