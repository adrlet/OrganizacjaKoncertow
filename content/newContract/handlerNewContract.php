<?php
      if(isset($_SESSION['newContractResponse']))
      {
        echo $_SESSION['newContractResponse'];
        unset($_SESSION['newContractResponse']);
      }
?>