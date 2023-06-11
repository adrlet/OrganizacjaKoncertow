<?php

    include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

    if(isset($_POST['changeEmail']) && isset($_SESSION['id_client']))
    {
        $e_mail = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars($_POST['pass']);
        $changeEmail = htmlspecialchars($_POST['changeEmail']);

        $checkPass = $klient->checkPass($pass);
        $e_mail_valid = filter_var($e_mail, FILTER_VALIDATE_EMAIL);
        $uniqueEmail = client::uniqueMail($cfg_mainLink, $e_mail);
        
        if($changeEmail != 'changeEmail')
            $_SESSION['changeEmailResponse'] = 'Co to za czarowanie...';
        elseif($checkPass != 0)
        {
            switch($checkPass)
            {
                case ERROR_client_checkPass_wrong:
                    $_SESSION['changeEmailResponse'] = 'Podane hasło jest nieprawidłowe';
                    break;
                case ERROR_client_checkPass_id_notexist:
                    $klient->signOut();
                    break;
                default:
            }
        }
        elseif(!$e_mail_valid)
            $_SESSION['changeEmailResponse'] = 'Nowy E-mail nie jest właściwy';
        elseif($uniqueEmail == ERROR_client_uniqueMail_e_mail_alreadyused)
            $_SESSION['changeEmailResponse'] = 'Podany E-mail jest już w użyciu';
        else
        {
            $result = $klient->update('', '', $e_mail, '', '', '');

            switch($result)
            {
                case ERROR_client_read_id_notexist:
                    $klient->signOut();
                /*case ERROR_client_update_no_change: // Sprawdzane już przy uniqueEmail
                    $_SESSION['changeEmailResponse'] = 'Dokonaj zmiany w danych';
                    break;*/
                case 0:
                    $_SESSION['changeEmailResponse'] = 'Zaktualizowano E-mail';
                    break;
                default:
                    $_SESSION['changeEmailResponse'] = 'Zachowaj umiar z ilością znaków :)';
            }
        }

        if(isset($result) && $result == 0)
            header("Location: /test/index.php?me");
        else
            header("Location: /test/index.php?changeEmail");
        
    }

?>