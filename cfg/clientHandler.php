<?php
    include_once ($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');
    include($_SERVER['DOCUMENT_ROOT'].'/test/class/client/client.php');

    $klient = null;
    if(isset($_SESSION['id_client']))
    {
        try {
            $klient = new client($cfg_mainLink, $_SESSION['id_client']);
        } catch(RuntimeException $e)  {
            echo $e;
            unset($_SESSION['id_client']);
        }
    }


?>