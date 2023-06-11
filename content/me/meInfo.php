<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">  
	<hr>
	<p class="font-weight-bold mx-sm-2">Dane klienta:</p>
    <table class="table table-sm mx-sm-2 mb-1">
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
    <form action="index.php" method="GET" class="form-inline">
	<div class="form-group mx-sm-2 mb-2">
      <button type="submit" name="changeInfo" value="" class="btn btn-primary">Zmień dane</button>
	</div>
	<div class="form-group mx-sm-2 mb-2">
      <button type="submit" name="changeEmail" value="" class="btn btn-primary">Zmień email</button>
	</div>
	<div class="form-group mx-sm-2 mb-2">
      <button type="submit" name="changePass" value="" class="btn btn-primary">Zmień hasło</button>
	</div>
	<div class="form-group mx-sm-2 mb-2">
      <button type="submit" name="deleteMe" value="" class="btn btn-primary">Usuń konto</button>
	</div>
    </form>
    <hr>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>