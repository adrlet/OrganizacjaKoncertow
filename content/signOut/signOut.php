<?php

    include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');
    include($_SERVER['DOCUMENT_ROOT'].'/test/class/employee/employee.php');

    if(isset($_POST['signOut']) && (isset($_SESSION['id_client']) || isset($_SESSION['employee'])))
    {
        $signout = htmlspecialchars($_POST['signOut']);

        if($signout == 'signOut')
        {
            if(isset($_SESSION['id_client']))
                $klient->signOut();
            else
                employee::signOut();
            $_SESSION['signOutResponse'] = 'Wylogowano...';
        }
        else
            $_SESSION['signOutResponse'] = 'Co to za czarowanie...';

        if(isset($_SERVER['HTTP_REFERER']))
            header('Location: '.$_SERVER['HTTP_REFERER']);
    }

?>