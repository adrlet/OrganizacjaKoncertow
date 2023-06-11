<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <form action="content/changeInfo/changeInfo.php" method="post">
      <hr>
	  <p class="font-weight-bold mx-sm-2">Zmień dane</p>
        <?php
          $klientInfo = $klient->read();
          echo
          '<div class="form-row">
		  <div class="form-group col-md-2 mx-sm-2">
		  <label for="firstName" class="sr-only"><b>Imię</b></label>
          <input type="text" class="form-control" placeholder="Wpisz imię" name="firstName" value="'.$klientInfo['firstName'].'" required></div>
			
		  <div class="form-group col-md-2">
          <label for="surname" class="sr-only"><b>Nazwisko</b></label>
            <input type="text" class="form-control" placeholder="Wpisz nazwisko" name="surname" value="'.$klientInfo['surname'].'" required></div></div>
		  
		  <div class="form-row">
		  <div class="form-group col-md-2 mx-sm-2">
          <label for="telephone" class="sr-only"><b>Numer telefonu</b></label>
          <input type="text" class="form-control" placeholder="Wpisz numer telefonu" name="telephone" value="'.$klientInfo['telephone'].'"></div>
		  
		  <div class="form-group col-md-2">
          <label for="address" class="sr-only"><b>Adres</b></label>
          <input type="text" class="form-control" placeholder="Wpisz adres zamieszkania" name="address" id="address" value="'.$klientInfo['address'].'"></div></div>
          ';
        ?>
        <p class="font-weight-bold mx-sm-2"><label for="pass">Potwierdź zmianę hasłem</label></p>
		<div class="form-row">
		<div class="form-group col-md-2 mx-sm-2">
        <input type="password" class="form-control " placeholder="Wpisz hasło" name="pass" required>
		</div>
		<div class="form-group mx-sm-2 mb-2">
        <button type="submit" name="changeInfo" value="changeInfo" class="btn btn-primary">Zatwierdź dane</button>
		</div></div>
        <hr>
    </form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>