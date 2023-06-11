<?php

    include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/clientHandler.php');
    include($_SERVER['DOCUMENT_ROOT'].'/test/class/employee/employee.php');

    if(isset($_POST['signIn']) && !isset($_SESSION['id_client']))
    {
        $login = htmlspecialchars($_POST['signIn']);
        $e_mail = htmlspecialchars($_POST['e_mail']);
        $pass = htmlspecialchars($_POST['pass']);

        $pass_valid = preg_match('@[A-Z]@', $pass);
        $pass_valid = ($pass_valid && preg_match('@[a-z]@', $pass));
        $pass_valid = ($pass_valid && preg_match('@[0-9]@', $pass));
        $pass_valid = ($pass_valid && preg_match('@[^\w]@', $pass));
        $pass_valid = ($pass_valid && (strlen($pass) >= 8));
        $pass_valid = ($pass_valid && (strlen($pass) <= 24));

        $e_mail_valid = filter_var($e_mail, FILTER_VALIDATE_EMAIL);
        $e_mail_employee = preg_match('/tworzkoncerty.pl/i', $e_mail);

        if($login != 'signIn')
            $_SESSION['signInResponse'] = 'Co to za czarowanie...';
        elseif(!$pass_valid || !$e_mail_valid)
            $_SESSION['signInResponse'] = 'Podany E-mail lub podane hasło jest nieprawidłowe';
        elseif($e_mail_employee == 1)
        {
            $result = employee::signIn($cfg_employeeLink, $e_mail, $pass);
            if($result == 0)
                $_SESSION['signInResponse'] = 'Zalogowano';
            else
                $_SESSION['signInResponse'] = 'Podany E-mail lub podane hasło jest nieprawidłowe'.$e_mail_employee;
        }
        else
        {
            $result = client::signIn($cfg_mainLink, $e_mail, $pass);

            switch($result)
            {
                case ERROR_client_signIn_pass_iswrong:
                    $_SESSION['signInResponse'] = 'Podany E-mail lub podane hasło jest nieprawidłowe';
                    break;
                case ERROR_client_signIn_login_iswrong:
                    $_SESSION['signInResponse'] = 'Podany E-mail lub podane hasło jest nieprawidłowe';
                    break;
                /*case ERROR_client_signIn_e_mail_empty: // Sprawdzany już przy weryfikacji e-maila
                    $_SESSION['loginResponse'] = 'Podaj E-mail';
                    break;*/
                /*case ERROR_client_signIn_pass_empty: // Sprawdzany już przy weryfikacji hasła
                    $_SESSION['loginResponse'] = 'Podaj hasło';
                    break; */
                case 0:
                    $_SESSION['signInResponse'] = 'Zalogowano';
                    break;
                default:
                    $_SESSION['signInResponse'] = 'Zachowaj umiar z ilością znaków :)';
            }
        }

        if(isset($result) && $result == 0)
            header('Location: /test/index.php');
        else
            header('Location: /test/index.php?signIn');
    }

?>