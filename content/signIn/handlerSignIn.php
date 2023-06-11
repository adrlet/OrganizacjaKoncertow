<?php
    if(isset($_SESSION['signInResponse']))
    {
      echo '<div class="container signin">'.
      $_SESSION['signInResponse'].
      '</div>';
      unset($_SESSION['signInResponse']);
    }
?>