    <form action="content/changeInfo/changeInfo.php" method="post">
      <hr>
        <?php
          $klientInfo = $klient->read();
          echo
          '<label for="firstName"><b>Imię</b></label>
          <input type="text" placeholder="Wpisz imię" name="firstName" value="'.$klientInfo['firstName'].'" required>

          <label for="surname"><b>Nazwisko</b></label>
            <input type="text" placeholder="Wpisz nazwisko" name="surname" value="'.$klientInfo['surname'].'" required>

          <label for="telephone"><b>Numer telefonu</b></label>
          <input type="text" placeholder="Wpisz numer telefonu" name="telephone" value="'.$klientInfo['telephone'].'">

          <label for="address"><b>Adres</b></label>
          <input type="text" placeholder="Wpisz adres zamieszkania" name="address" id="address" value="'.$klientInfo['address'].'">
          ';
        ?>
        <label for="pass"><b>Potwierdź zmianę hasłem</b></label>
        <input type="password" placeholder="Wpisz hasło" name="pass" required>

        <button type="submit" name="changeInfo" value="changeInfo" class="registerbtn">Zatwierdź dane</button>
        <hr>
    </form>