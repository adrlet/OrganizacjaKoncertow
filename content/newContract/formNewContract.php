<head>	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<hr>
<p class="font-weight-bold mx-sm-2">Dane kontraktu</p>
<form action="content/newContract/newContract.php" method="POST">

<?php

if(!isset($_SESSION['contract_id_concert_plan']))
{
  $_SESSION['contract_id_concert_plan'] = '';
  $_SESSION['contract_id_foreign_key'] = '';
  $_SESSION['contract_id_price'] = '';
  $_SESSION['concert_plan_type'] = '';
}

echo '<table class="table table-striped mx-sm-2 mb-1">
  <tr><td>Id planu</td>
  <td><input type="text" name="id_concert_plan" value="'.$_SESSION['contract_id_concert_plan'].'" required></td></tr>
  <tr><td>Id kontraktownego</td>
  <td><input type="text" name="id_foreign_key" value="'.$_SESSION['contract_id_foreign_key'].'" required></td></tr>
  <tr><td>Cena</td>
  <td><input type="text"" name="price" value="'.$_SESSION['contract_id_price'].'" required></td></tr>
  <tr><td>Typ kontraktowanego</td>
  <td><select name="type" id="type">
  <option value="place">Miejsce</option>
  <option value="service">Usługa</option>
  <option value="performer">Wykonawca</option>
  </td></tr>
  </table>
  <div class="form-group col-md-2 ">
  <button type="submit" class="btn btn-primary" name="save_concert_plan" value="save">Zapisz</button></div>'; 

unset($_SESSION['contract_id_concert_plan']);
unset($_SESSION['contract_id_foreign_key']);
unset($_SESSION['contract_id_price']);
unset($_SESSION['concert_plan_type']);

?>

</form>
<hr>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>	