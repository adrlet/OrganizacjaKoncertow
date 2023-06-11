<?php
  if(isset($_SESSION['editContractResponse']))
  {
    echo $_SESSION['editContractResponse'];
    unset($_SESSION['editContractResponse']);
  }
?>