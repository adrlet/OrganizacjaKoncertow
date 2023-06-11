<head>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<hr>
<p class="font-weight-bold mx-sm-2">Lista usług</p> 
<?php

  $page = (int)htmlspecialchars($_GET['services_page']);
  if(empty($page))
    $page = 1;

  if(isset($_SESSION['employee']) && $_SESSION['employee'] == 'Dział Analityczny')
  {
    $servicesCount = service::countServices($cfg_mainLink);
    $numeracjaStron = numeracjaStron($servicesCount, $page, 10, 'index.php?services_page');
    $services = service::loadServices($cfg_mainLink, $page);

    echo '<table class="table table-striped mx-sm-2 mb-1">
    <tr><td><b>id Usługi</b></td><td><b>Firma</b></td><td><b>Typ usługi</b></td><td><b>Koszt najmu</b></td><td><b>Liczba osób</b></td>';
    
    if(is_array($services))
    {
      for($i = 0; $i < count($services); $i++)
      {
        $serviceInfo = $services[$i]->read();
        echo '<tr>
        <td>'.$serviceInfo['service_id'].'</td>
        <td>'.$serviceInfo['company_name'].'</td>
        <td>'.$serviceInfo['service_type'].'</td>
        <td>'.$serviceInfo['service_price'].'</td>
        <td>'.$serviceInfo['seats_number'].'</td>
        </tr>';
      }
    }
    echo '</table>';
  }
  echo $numeracjaStron;

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>