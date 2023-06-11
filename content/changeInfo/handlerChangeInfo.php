<?php
  if(isset($_SESSION['changeInfoResponse']))
  {
    echo $_SESSION['changeInfoResponse'];
    unset($_SESSION['changeInfoResponse']);
  }
?>
