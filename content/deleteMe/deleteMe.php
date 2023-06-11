<?php

    include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

    if(isset($_POST['deleteMe']) && isset($_SESSION['id_client']))
    {
        $deleteMe = htmlspecialchars($_POST['deleteMe']);

        if($deleteMe != 'deleteMe')
            $_SESSION['deleteMeResponse'] = 'Co to za czarowanie...';
        else
        {
            $klientInfo = $klient->read();
            $result = $klient->update('', '', NULL, '', '', NULL);
            $klient->signOut();
            switch($result)
            {
                case 0:
                    $_SESSION['deleteMeResponse'] = 'Usunięto konto na E-mailu '.$klientInfo['e_mail'];
                    break;
                default:
                    $_SESSION['deleteMeResponse'] = 'Błąd kasowania';
            }
        }

        if(isset($result) && $result == 0)
            header("Location: /test/index.php");
        else
            header("Location: /test/index.php?deleteMe");
    }

?>