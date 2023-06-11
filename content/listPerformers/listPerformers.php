<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<hr>
<p class="font-weight-bold mx-sm-2">Lista wykonawców</p> 
<?php

  $page = (int)htmlspecialchars($_GET['performers_page']);
  if(empty($page))
    $page = 1;

  if(isset($_SESSION['employee']) && $_SESSION['employee'] == 'Dział Analityczny')
  {
    $performersCount = performers::countPerformers($cfg_mainLink);
    $numeracjaStron = numeracjaStron($performersCount, $page, 10, 'index.php?performers_page');
    $performers = performers::loadPerformers($cfg_mainLink, $page);

    echo '<table class="table table-striped mx-sm-2 mb-1">
    <tr><td><b>id Wykonawcy</b></td><td><b>Pseudonim</b></td><td><b>Gatunek muzyczny</b></td><td><b>Cena artysty</b></td></tr>';
    
    if(is_array($performers))
    {
      for($i = 0; $i < count($performers); $i++)
      {
        $performerInfo = $performers[$i]->read();
        echo '<tr>
        <td>'.$performerInfo['id_performer'].'</td>
        <td>'.$performerInfo['nickname'].'</td>
        <td>'.$performerInfo['music_genre'].'</td>
        <td>'.$performerInfo['price'].'</td>
        </tr>';
      }
    }
    echo '</table>';
  }
  echo $numeracjaStron;

?>
<hr>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>