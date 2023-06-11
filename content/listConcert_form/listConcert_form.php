<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<hr>
<p class="font-weight-bold mx-sm-2">Zbiór formularzów koncertu</p> 
  <table class="table table-striped mx-sm-2 mb-1">
    <tr>
      <td><b>id</b></td>
      <td><b>nazwa</b></td>
      <td><b>gatunek muzyki</b></td>
      <td><b>budżet</b></td></td>
      <td><b>wyświetl</b></td>
    </tr>
    <?php
        $page = (int)htmlspecialchars($_GET['concert_forms_page']);
        if(empty($page))
          $page = 1;

        if(isset($_SESSION['employee']) && $_SESSION['employee'] == 'Dział Analityczny')
        {
          echo '<form action="content/listConcert_form/listform.php" method="POST">';
          $concert_formCount = concert_form::countConcert_form($cfg_mainLink, 0);
          $numeracjaStron = numeracjaStron($concert_formCount, $page, 10, 'index.php?concert_forms_page');
          $concert_forms = concert_form::loadForms($cfg_mainLink, $page);

          if(is_array($concert_forms))
          {
            for($i = 0; $i < count($concert_forms); $i++)
            {
              $formInfo = $concert_forms[$i]->read();
              echo '<tr><td>'.$formInfo['id_concert_form'].'</td><td>'.$formInfo['name'].'</td><td>'.
              $formInfo['music_genre'].'</td><td>'.$formInfo['budget'].'</td>
              <td>
              <button type="submit" class="btn btn-primary btn-sm" name="concert_form" value="'.$formInfo['id_concert_form'].'">Wyświetl</button>
              <button type="submit" class="btn btn-primary btn-sm" name="delete" value="'.$formInfo['id_concert_form'].'">Usuń</button>
              </td>
              </tr>';
            }
          }
        }
        else
        {
          echo '<form action="index.php" method="GET">';
          $klientForms = $klient->countConcert_form();
          $numeracjaStron = numeracjaStron($klientForms, $page, 10, 'index.php?concert_forms_page');
          $loaded_forms = $klient->loadForms($page);
          if($loaded_forms > 0)
          {
            $formArray = $klient->readForms(0);
            
            foreach($formArray as $form)
              echo '<tr><td>'.$form['id_concert_form'].'</td><td>'.$form['name'].'</td><td>'.
              $form['music_genre'].'</td><td>'.$form['budget'].'</td>
              <td>
              <button  class="btn btn-primary btn-sm" type="submit" name="concert_form" value="'.$form['id_concert_form'].'">Wyświetl</button>
              </td>
              </tr>';
          }
      }
      echo '</form>';
    ?>
  </table>
  <?php
    if(!isset($_SESSION['employee']))
      echo '<form action="index.php" method="GET">
		<div class="mx-auto" style="width: 200px;">
        <button type="submit" class="btn btn-primary" name="newConcert_form" value="">Utwórz nowy formularz</button>
		</div>
        </form>';
  ?>
<hr>

<?php
  echo $numeracjaStron;
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>