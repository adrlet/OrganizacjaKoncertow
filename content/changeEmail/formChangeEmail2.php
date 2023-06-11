    <form action="content/changeEmail/changeEmail.php" method="post">
        <hr>

            <label for="email"><b>Wpisz nowy E-mail</b></label>
            <?php
                $klientInfo = $klient->read();
                echo '<input type="text" placeholder="Wpisz E-mail" name="email" id="email" value="'.$klientInfo['e_mail'].'" required>';
            ?>

            <label for="pass"><b>Wpisz hasło</b></label>
            <input type="password" placeholder="Wpisz hasło" name="pass" id="pass" required>

            <button type="submit" name="changeEmail" value="changeEmail" class="registerbtn">Zatwierdź E-mail</button>
        <hr>
        
    </form>