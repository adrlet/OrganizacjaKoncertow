<?php

    include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

    if(isset($_POST['changeInfo']) && isset($_SESSION['id_client']))
    {
        $firstName = htmlspecialchars($_POST['firstName']);
        $surname = htmlspecialchars($_POST['surname']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $address = htmlspecialchars($_POST['address']);
        $pass = htmlspecialchars($_POST['pass']);
        $changeInfo = htmlspecialchars($_POST['changeInfo']);

        if(empty($telephone))
            $telephone = NULL;
        if(empty($address))
            $address = NULL;

        $checkPass = $klient->checkPass($pass);
        
        if($changeInfo != 'changeInfo')
            $_SESSION['changeInfoResponse'] = 'Co to za czarowanie...';
        elseif($checkPass != 0)
        {
            switch($checkPass)
            {
                case ERROR_client_checkPass_wrong:
                    $_SESSION['changeInfoResponse'] = 'Podane hasło jest nieprawidłowe';
                    break;
                case ERROR_client_checkPass_id_notexist:
                    $klient->signOut();
                    break;
                default:
            }
        }
        else
        {
            $result = $klient->update($firstName, $surname, '', $telephone, $address, '');

            switch($result)
            {
                case ERROR_client_read_id_notexist:
                    $klient->signOut();
                case ERROR_client_update_noentry:
                    $_SESSION['changeInfoResponse'] = 'Dokonaj zmiany w danych1';
                    break;
                case ERROR_client_update_no_change:
                    $_SESSION['changeInfoResponse'] = 'Dokonaj zmiany w danych2';
                    break;
                case 0:
                    $_SESSION['changeInfoResponse'] = 'Zaktualizowano dane klienta';
                    break;
                default:
                    $_SESSION['changeInfoResponse'] = 'Zachowaj umiar z ilością znaków :)';
            }
        }

        if(isset($result) && $result == 0)
            header("Location: /test/index.php?me");
        else
            header("Location: /test/index.php?changeInfo");
    }

?>