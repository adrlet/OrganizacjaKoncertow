<?php
  $webTitle = 'tworzkoncerty.pl';

  include('cfg/cfg.php');
  include('function/listPages.php');
  include('cfg/clientHandler.php');

  $index_mode = 0;
  $pageTitle = 'Strona główna';
  if(isset($_SESSION['id_client']) || isset($_SESSION['employee']))
  {
    if(isset($_SESSION['id_client']))
    {
      if(isset($_GET['me']))
      {
        $index_mode = 3;
        $pageTitle = 'Twoje konto';
      }
      elseif(isset($_GET['changeInfo']))
      {
        $index_mode = 4;
        $pageTitle = 'Zmień dane';
      }
      elseif(isset($_GET['changeEmail']))
      {
        $index_mode = 5;
        $pageTitle = 'Zmień E-mail';
      }
      elseif(isset($_GET['changePass']))
      {
        $index_mode = 6;
        $pageTitle = 'Zmień Hasło';
      }
      elseif(isset($_GET['deleteMe']))
      {
        $index_mode = 7;
        $pageTitle = 'Usuń konto';
      }
      elseif(isset($_GET['newConcert_form']))
      {
        $index_mode = 9;
        $pageTitle = 'Utwórz formularz koncertu';
      }
    }
    else if(isset($_SESSION['employee']))
    {
      if(isset($_GET['contracts_page']))
      {
        $index_mode = 11;
        $pageTitle = 'Kontrakty';
      }
      elseif(isset($_GET['editContractService']) || isset($_GET['editContractPlace']) || isset($_GET['editContractPerformer']))
      {
        $index_mode = 12;
        $pageTitle = 'Edytuj kontrakt';
      }
      elseif(isset($_GET['concert_plans_page']))
      {
        $index_mode = 13;
        $pageTitle = 'Plany koncertów';
      }
      elseif(isset($_GET['concert_plan']))
      {
        $index_mode = 14;
        $pageTitle = 'Plan koncertu';
      }
      elseif(isset($_GET['newConcert_plan']))
      {
        $index_mode = 15;
        $pageTitle = 'Utwórz plan koncertu';
      }
      elseif(isset($_GET['newContract']))
      {
        $index_mode = 16;
        $pageTitle = 'Utwórz nowy kontrakt';
      }
      elseif(isset($_GET['places_page']))
      {
        $index_mode = 17;
        $pageTitle = 'Dostępne miejsca';
      }
      elseif(isset($_GET['services_page']))
      {
        $index_mode = 18;
        $pageTitle = 'Dostępne Usługi';
      }
      elseif(isset($_GET['performers_page']))
      {
        $index_mode = 19;
        $pageTitle = 'Dostępni Wykonawcy';
      }
    }
    if(isset($_GET['concert_forms_page']))
    {
      $index_mode = 8;
      $pageTitle = 'Twoje formularze koncertów';
    }
    elseif(isset($_GET['concert_form']))
    {
      $index_mode = 10;
      $pageTitle = 'Formularz koncertu';
    }
  }
  else
  {
    if(isset($_GET['signIn']))
    {
      $index_mode = 1;
      $pageTitle = 'Logowanie';
    }
    elseif(isset($_GET['register']))
    {
      $index_mode = 2;
      $pageTitle = 'Rejestracja';
    }
  }

?>

<!DOCTYPE html>
<html lang="pl">
  <head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="utf-8">
    <link rel="stylesheet" href="registerStyle.css">
    <title>
    <?php
      echo $pageTitle;
    ?>
    </title>
<style>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #e6e6e6;
}

li {
  float: left;
}

li a {
  display: block;
  color: #1a53ff;
  text-align: center;
  padding: 16px;
  text-decoration: none;
}

li a:hover {
  background-color: #3366ff;
  color: white;
  text-decoration: none;
}

</style>
  </head>
  
  <body>

    <!-- Start main bar -->
    <ul>
      <?php
        if(isset($_SESSION['id_client']))
        {
          $klientInfo = $klient->read();
          echo '<li>Zalogowany jako</li>
          <li>klient <a href="index.php?me">'.$klientInfo['firstName'].' '.$klientInfo['surname'].'</a></li>
          <li>
            <form action="content/signOut/signOut.php" method="POST">
              <button type="submit" class="btn btn-primary btn-sm" name="signOut" value="signOut">Wyloguj się</button>
            </form>
          </li>';
        }
        elseif(isset($_SESSION['employee']))
        {
          echo '<li>Zalogowany jako</li>
          <li>Pracownik '.$_SESSION['employee'].'</li>
          <li>
            <form action="content/signOut/signOut.php" method="POST">
              <button type="submit" class="btn btn-primary btn-sm" name="signOut" value="signOut">Wyloguj się</button>
            </form>
          </li>';
        }
        else
          echo '<li>Nie jesteś zalogowany</li>
          <li><a href="index.php?signIn">Zaloguj się</a></li>
          <li>Nie masz konta?</li>
          <li><a href="index.php?register">Zarejestruj się</a></li>';
      ?>
    </ul>
    <h1>
      <?php
        echo '<p class="text-center"><a href="index.php">'.$webTitle.'</a></p>';
      ?>
    </h1>
    <h2>
      <?php
        echo '<p class="text-center">'.$pageTitle.'</p>';
      ?>
    </h2>
    <ul class="nav justify-content-center">
      <?php
        if(isset($_SESSION['id_client']))
          echo '<li><a href="index.php?concert_forms_page=1">Zleć zorganizowanie koncertu</a></li>';
        elseif(isset($_SESSION['employee']))
        {
          if($_SESSION['employee'] == 'Dział Analityczny')
          {
            echo '<li><a href="index.php?concert_plans_page=1">Przeglądaj plany koncertów</a></li>
            <li><a href="index.php?concert_forms_page=1">Przeglądaj formularze</a></li>
            <li><a href="index.php?contracts_page=1">Przeglądaj kontrakty</a></li>
            <li><a href="index.php?places_page=1">Przeglądaj miejsca</a></li>
            <li><a href="index.php?services_page=1">Przeglądaj usługi</a></li>
            <li><a href="index.php?performers_page=1">Przeglądaj wykonawców</a></li>';
          }
        }
      ?>
      <li><a href="index.php?concerts_page=1">Wyszukaj koncert</a></li>
    </ul>
    <!-- End main bar -->

    <!-- Start response -->
    <?php
      if(isset($_SESSION['id_client']) || isset($_SESSION['employee']))
      {
        if(isset($_SESSION['id_client']))
        {
          if(isset($_GET['me']))
          {
            include('content/changeInfo/handlerChangeInfo.php');
            include('content/changeEmail/handlerChangeEmail.php');
            include('content/changePass/handlerChangePass.php');
          }
          elseif(isset($_GET['changeInfo']))
            include('content/changeInfo/handlerChangeInfo.php');
          elseif(isset($_GET['changeEmail']))
            include('content/changeEmail/handlerChangeEmail.php');
          elseif(isset($_GET['changePass']))
          include('content/changePass/handlerChangePass.php');
          include('content/newConcert_form/handlerNewConcert_form.php');
        }
        include('content/concert_form/handlerConcert_form.php');
        include('content/listConcert_form/handlerListConcert_form.php');
        include('content/listContracts/handlerListContract.php');
        include('content/editContract/handlerEditContract.php');
        include('content/listConcert_plan/handlerListConcert_plan.php');
        include('content/concert_plan/handlerConcert_plan.php');
        include('content/newConcert_plan/handlerNewConcert_plan.php');
        include('content/newContract/handlerNewContract.php');
      }
      else
      {
        include('content/signOut/handlerSignOut.php');
        include('content/register/handlerRegister.php');
        include('content/deleteMe/handlerDeleteMe.php');
      }
      include('content/signIn/handlerSignIn.php');
    ?>
    <!-- End response -->

    <!-- Start content -->
    <?php
      $index_content = '';
      $index_content_php = '';

      switch($index_mode)
      {
        case 1:
          $index_content = 'content/signIn/formSignIn.html';
          break;

        case 2:
          $index_content = 'content/register/formRegister.html';
          break;

        case 3:
          $index_content_php = 'content/me/meInfo.php';
          break;

        case 4:
          $index_content_php = 'content/changeInfo/formChangeInfo.php';
          break;

        case 5:
          $index_content_php = 'content/changeEmail/formChangeEmail.php';
          break;

        case 6:
          $index_content = 'content/changePass/formChangePass.html';
          break;

        case 7:
          $index_content = 'content/deleteMe/formDeleteMe.html';
          break;

        case 8:
          $index_content_php = 'content/listConcert_form/listConcert_form.php';
          break;

        case 9:
          $index_content_php = 'content/newConcert_form/formNewConcert_form.php';
          break;

        case 10:
          $index_content_php = 'content/concert_form/formConcert_form.php';
          break;

        case 11:
          $index_content_php = 'content/listContracts/listContracts.php';
          break;

        case 12:
          $index_content_php = 'content/editContract/formEditContract.php';
          break;

        case 13:
          $index_content_php = 'content/listConcert_plan/listConcert_plan.php';
          break;

        case 14:
          $index_content_php = 'content/concert_plan/formConcert_plan.php';
          break;

        case 15:
          $index_content_php = 'content/newConcert_plan/formNewConcert_plan.php';
          break;

        case 16:
          $index_content_php = 'content/newContract/formNewContract.php';
          break;

        case 17:
          $index_content_php = 'content/listPlaces/listPlaces.php';
          break;

        case 18:
          $index_content_php = 'content/listServices/listServices.php';
          break;

        case 19:
          $index_content_php = 'content/listPerformers/listPerformers.php';
          break;

        default:
      } 

      if(!empty($index_content))
        readfile($index_content);
      if(!empty($index_content_php))
        include($index_content_php);
    ?>
    <!-- End content -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>