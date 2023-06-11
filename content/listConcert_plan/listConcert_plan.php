<hr>
<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<p class="font-weight-bold mx-sm-2">Zarządzaj planami</p> 
  <table class="table table-striped mx-sm-2 mb-1">
    <tr>
      <td><b>id</b></td>
      <td><b>termin start</b></td>
      <td><b>termin koniec</b></td>
      <td><b>status</b></td></td>
	  <td><b>wyświetl</b></b></td>
    </tr>
    <?php
        $page = (int)htmlspecialchars($_GET['concert_plans_page']);
        if(empty($page))
          $page = 1;

        if(isset($_SESSION['employee']) && $_SESSION['employee'] == 'Dział Analityczny')
        {
          echo '<form action="content/listConcert_plan/listplan.php" method="POST">';
          $concert_planCount = concert_plan::countConcert_plan($cfg_mainLink, 0);
          $numeracjaStron = numeracjaStron($concert_planCount, $page, 10, 'index.php?concert_plans_page');
          $concert_plans = concert_plan::loadPlans($cfg_mainLink, $page, 10);

          if(is_array($concert_plans))
          {
            for($i = 0; $i < count($concert_plans); $i++)
            {
              $planInfo = $concert_plans[$i]->read();
              echo '<tr><td>'.$planInfo['id_concert_plan'].'</td><td>'.$planInfo['termin_start'].'</td><td>'.
              $planInfo['termin_end'].'</td><td>'.$planInfo['status'].'</td>
              <td>
              <button type="submit" name="concert_plan" class="btn btn-primary btn-sm" value="'.$planInfo['id_concert_plan'].'">Wyświetl</button>
              <button type="submit" name="delete" class="btn btn-primary btn-sm" value="'.$planInfo['id_concert_plan'].'">Usuń</button>
              </td>
              </tr>';
            }
          }
          echo '</form>';
          echo '<form action="index.php" method="GET">
		  <div class="mx-auto" style="width: 200px;"><div class="form-group col-md-2 mx-sm-2">
          <button type="submit" class="btn btn-primary" name="newConcert_plan" value="">Utwórz nowy plan</button>
		  </div></div>
          </form>';
        }
    ?>
  </table>
<hr>

<?php
  echo $numeracjaStron;
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>