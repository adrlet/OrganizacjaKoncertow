<?php
  if(isset($_SESSION['listContractsResponse']))
  {
    echo $_SESSION['listContractsResponse'];
    unset($_SESSION['listContractsResponse']);
  }
?>