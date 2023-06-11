<?php

include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');

    if(isset($_POST['register']) && !isset($_SESSION['id_client']))
    {
        $register = htmlspecialchars($_POST['register']);
        $firstName = htmlspecialchars($_POST['firstName']);
        $surname = htmlspecialchars($_POST['surname']);
        $e_mail = htmlspecialchars($_POST['e_mail']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $address = htmlspecialchars($_POST['address']);
        $pass = htmlspecialchars($_POST['pass']);
        $pass2 = htmlspecialchars($_POST['passRepeat']);

        $pass_valid = preg_match('@[A-Z]@', $pass);
        $pass_valid = ($pass_valid && preg_match('@[a-z]@', $pass));
        $pass_valid = ($pass_valid && preg_match('@[0-9]@', $pass));
        $pass_valid = ($pass_valid && preg_match('@[^\w]@', $pass));
        $pass_valid = ($pass_valid && (strlen($pass) >= 8));
        $pass_valid = ($pass_valid && (strlen($pass) <= 24));

        $e_mail_valid = filter_var($e_mail, FILTER_VALIDATE_EMAIL);

        $uniqueMail = client::uniqueMail($cfg_mainLink, $e_mail);

        if($register != 'register')
            $_SESSION['registerResponse'] = 'Co to za czarowanie...';
        elseif(!$pass_valid)
            $_SESSION['registerResponse'] = 'Hasło powinno składać się co najmniej z 8 i co najwyżej 24 znaków, conajmniej jednej litery wielkiej, litery małej, cyfry i znaku specjalnego';
        elseif($pass != $pass2)
            $_SESSION['registerResponse'] = 'Powtórzone hasło nie zgadza się z pierwszym wpisem hasła.';
        elseif(!$e_mail_valid)
            $_SESSION['registerResponse'] = 'Wprowadzony E-mail nie jest właściwy';
        elseif($uniqueMail != 0)
        {
            switch($uniqueMail)
            {
                case ERROR_client_uniqueMail_e_mail_alreadyused:
                    $_SESSION['registerResponse'] = 'Podany E-mail jest już w użyciu';
                    break;
                /* case ERROR_client_uniqueMail_e_mail_empty: // Warunek sprawdzany już przy weryfikacji e-maila
                    $_SESSION['registerResponse'] = 'Nie podano E-maila'; */
                    break;
                default:
            }
        }
        else
        {
            $result = client::create($cfg_mainLink, $firstName, $surname, $e_mail, $telephone, $address, $pass);

            switch($result)
            {
                case ERROR_client_create_firstName_empty:
                    $_SESSION['registerResponse'] = 'Pole z imieniem nie może być puste';
                    break;
                case ERROR_client_create_surname_empty:
                    $_SESSION['registerResponse'] = 'Pole z nazwiskiem nie może być puste';
                    break;
                /* case ERROR_client_create_e_mail_empty: // Warunek sprawdzany już przy weryfikacji e-maila
                    $_SESSION['registerResponse'] = 'Pole z adresem E-mail nie może być puste';
                    break;*/
                /*case ERROR_client_create_pass_empty: // Warunek sprawdzany już przy weryfikacji hasła
                    $_SESSION['registerResponse'] = 'Pole z hasłem nie może być puste';
                    break;*/
                case ERROR_client_create_failed:
                    $_SESSION['registerResponse'] = 'Nie można utworzyć konta w tej chwili, spróbój za jakiś czas';
                    break;
                case 0:
                    $_SESSION['registerResponse'] = 'Utworzono konto dla użytkownika '.$firstName.' '.$surname.' na E-mailu '.$e_mail;
                    break;
                default:
                    $_SESSION['registerResponse'] = 'Zachowaj umiar z ilością znaków :)';
            }
        }
        if(isset($result) && $result == 0)
            header('Location: /test/index.php');
        else
            header('Location: /test/index.php?register');
    }
?>