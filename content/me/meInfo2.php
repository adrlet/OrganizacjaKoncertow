    <hr>
    <table>
    <?php
        $klientInfo = $klient->read();
        echo '
        <tr><td>Imię: </td><td>'.$klientInfo['firstName'].'</td></tr>
        <tr><td>Nazwisko: </td><td>'.$klientInfo['surname'].'</td></tr>
        <tr><td>E-mail: </td><td>'.$klientInfo['e_mail'].'</td></tr>
        <tr><td>Telefon: </td><td>'.$klientInfo['telephone'].'</td></tr>
        <tr><td>Adres: </td><td>'.$klientInfo['address'].'</td></tr>
        ';
    ?>
    </table>
    <form action="index.php" method="GET">
      <button type="submit" name="changeInfo" value="" class="registerbtn">Zmień dane</button>
      <button type="submit" name="changeEmail" value="" class="registerbtn">Zmień email</button>
      <button type="submit" name="changePass" value="" class="registerbtn">Zmień hasło</button>
      <button type="submit" name="deleteMe" value="" class="registerbtn">Usuń konto</button>
    </form>
    <hr>