<head>	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<hr>
<p class="font-weight-bold mx-sm-2">Dane planu koncertu</p>
<form action="content/newConcert_plan/newConcert_plan.php" method="POST">
<?php
  
  if(!isset($_SESSION['concert_plan_id_concert_form']))
  {
    $_SESSION['concert_plan_id_concert_form'] = '';
    $_SESSION['concert_plan_termin_start'] = '';
    $_SESSION['concert_plan_termin_end'] = '';
    $_SESSION['concert_plan_seats_number'] = '';
    $_SESSION['concert_plan_status'] = '';
  }
  
  echo '<table class="table table-striped mx-sm-2 mb-1">
  <tr><td>Id formularza</td>
  <td><input type="text" name="id_concert_form" value="'.$_SESSION['concert_plan_id_concert_form'].'" required></td></tr>
  <tr><td>Termin start</td>
  <td><input type="datetime-local" name="termin_start" value="'.$_SESSION['concert_plan_termin_start'].'" required></tr></tr>
  <tr><td>Termin koniec</td>
  <td><input type="datetime-local" name="termin_end" value="'.$_SESSION['concert_plan_termin_end'].'" required></tr></tr>
  <tr><td>Liczba miejsc</td>
  <td><input type="text" name="seats_number" value="'.$_SESSION['concert_plan_seats_number'].'" required></tr></tr>
  <tr><td>Status</td>
  <td><input type="text" name="status" value="'.$_SESSION['concert_plan_status'].'" required></tr></tr>
  </table>
  <div class="form-group col-md-2 ">
  <button type="submit" class="btn btn-primary" name="save_concert_plan" value="save">Zapisz</button></div>';
  

  
    unset($_SESSION['concert_plan_id_concert_form']);
    unset($_SESSION['concert_plan_termin_start']);
    unset($_SESSION['concert_plan_termin_end']);
    unset($_SESSION['concert_plan_seats_number']);
    unset($_SESSION['concert_plan_status']);
?>
</form>
<hr>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>	