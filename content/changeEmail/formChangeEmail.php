<head>	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    
<hr>	
	<p class="font-weight-bold mx-sm-2">Zmień e-mail</p>
	<form action="content/changeEmail/changeEmail.php" method="post" class="form-inline">
			<div class="form-group mx-sm-2 mb-2">
            <label for="email" class="sr-only"><b>Wpisz nowy E-mail</b></label>
            <?php
                $klientInfo = $klient->read();
                echo '<input type="text" class="form-control" placeholder="Wpisz E-mail" name="email" id="email" value="'.$klientInfo['e_mail'].'" required>';
            ?>
			</div>
			<div class="form-group mx-sm-2 mb-2">
            <label for="pass" class="sr-only"><b>Wpisz hasło</b></label>
            <input type="password" class="form-control" placeholder="Wpisz hasło" name="pass" id="pass" required>
			</div>
			<div class="form-group mx-sm-2 mb-2">
            <button type="submit" name="changeEmail" value="changeEmail" class="btn btn-primary">Zatwierdź E-mail</button>
			</div>
        <hr>
        
    </form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<hr>
</head>
