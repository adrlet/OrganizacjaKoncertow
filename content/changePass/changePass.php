<?php

    include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

    if(isset($_POST['changePass']) && isset($_SESSION['id_client']))
    {
        $pass = htmlspecialchars($_POST['pass']);
        $newPass = htmlspecialchars($_POST['newPass']);
        $newPassRpt = htmlspecialchars($_POST['newPassRpt']);
        $changePass = htmlspecialchars($_POST['changePass']);

        $newPass_valid = preg_match('@[A-Z]@', $newPass);
        $newPass_valid = ($newPass_valid && preg_match('@[a-z]@', $newPass));
        $newPass_valid = ($newPass_valid && preg_match('@[0-9]@', $newPass));
        $newPass_valid = ($newPass_valid && preg_match('@[^\w]@', $newPass));
        $newPass_valid = ($newPass_valid && (strlen($newPass) >= 8));
        $newPass_valid = ($newPass_valid && (strlen($newPass) <= 24));

        $checkPass = $klient->checkPass($pass);
        
        if($changePass != 'changePass')
            $_SESSION['changePassResponse'] = 'Co to za czarowanie...';
        elseif($checkPass != 0)
        {
            switch($checkPass)
            {
                case ERROR_client_checkPass_wrong:
                    $_SESSION['changePassResponse'] = 'Podane hasło jest nieprawidłowe';
                    break;
                 case ERROR_client_checkPass_id_notexist:
                    $klient->signOut();
                    break;
                default:
            }
        }
        elseif(!$newPass_valid)
            $_SESSION['changePassResponse'] = 'Nowe hasło powinno składać się co najmniej z 8 i co najwyżej 24 znaków, conajmniej jednej litery wielkiej, litery małej, cyfry i znaku specjalnego';
        elseif($newPass != $newPassRpt)
            $_SESSION['changePassResponse'] = 'Powtórzone hasło nie zgadza się z nowym hasłem';
        elseif($klient->checkPass($newPass) == 0)
            $_SESSION['changePassResponse'] = 'Hasło nie różni się od starego hasła';
        else
        {
            $result = $klient->update('', '', '', '', '', $newPass);

            switch($result)
            {
                case ERROR_client_read_id_notexist:
                    $klient->signOut();
                case ERROR_client_update_noentry:
                    $_SESSION['changePassResponse'] = 'Dokonaj zmiany w danych';
                    break;
                case 0:
                    $_SESSION['changePassResponse'] = 'Zaktualizowano hasło';
                    break;
                default:
                    $_SESSION['changePassResponse'] = 'Zachowaj umiar z ilością znaków :)';
            }
        }

        if(isset($result) && $result == 0)
            header("Location: /test/index.php?me");
        else
            header("Location: /test/index.php?changePass");
        
    }

?>