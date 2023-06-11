<?php

define("ERROR_employee_signIn_loginorpass_is_wrong", 1);
define("ERROR_employee_signOut_alreadysignedout", 1);

class employee
{
  public static function signIn(mysqli $link, string $login, string $pass) : int
  {
    $query = 'SELECT rola FROM pracownicy WHERE login=? and pass=? LIMIT 1';
    $stmt = $link->prepare($query);
    $stmt->bind_param('ss', $login, $pass);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows == 0)
    {
      $stmt->close();
      return ERROR_employee_signIn_loginorpass_is_wrong;
    }
    $stmt->bind_result($rola);
    $stmt->fetch();
    $stmt->close();

    $_SESSION['employee'] = $rola;

    return 0;
  }

  public static function signOut() : int
  {
    if(isset($_SESSION['employee']))
    {
      unset($_SESSION['employee']);
      return 0;
    }
    return ERROR_employee_signOut_alreadysignedout;
  }

}

?>