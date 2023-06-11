<?php

  // SQL Table Requirements
  define("MAX_LEN_concert_plan_status", 50);

  // generatePlan Error Codes
  define("ERROR_concert_plan_generatePlan_performerfail", 1);

  define("ERROR_concert_plan_generatePlan_music_genre_empty", 5);
  define("ERROR_concert_plan_generatePlan_seats_number_empty", 6);
  define("ERROR_concert_plan_generatePlan_budget_empty", 7);
  define("ERROR_concert_plan_generatePlan_location_empty", 8);
  define("ERROR_concert_plan_generatePlan_date_empty", 9);

  define("ERROR_concert_plan_generatePlan_date_wrong", 12);

  define('ERROR_concert_plan_generatePlan_link_isnull', 14);

  define("ERROR_concert_plan_generatePlan_seats_number_notint", 16);
  define("ERROR_concert_plan_generatePlan_budget_notfloat", 17);

    // create Error Codes
    define("ERROR_concert_plan_create_status_toolong", 1);
  
    define("ERROR_concert_plan_create_termin_start_empty", 4);
    define("ERROR_concert_plan_create_termin_end_empty", 5);
    define("ERROR_concert_plan_create_seats_number_empty", 6);
    define("ERROR_concert_plan_create_expenses_empty", 7);
    define("ERROR_concert_plan_create_status_empty", 8);
  
    define("ERROR_concert_plan_create_foreign_key_empty", 9);
    define("ERROR_concert_plan_create_foreign_key_notexist", 10);
  
    define("ERROR_concert_plan_create_termin_start_wrong", 11);
    define("ERROR_concert_plan_create_termin_end_wrong", 12);

    define("ERROR_concert_plan_create_failed", 13);
    define('ERROR_concert_plan_create_link_isnull', 14);
  
    define("ERROR_concert_plan_create_foreign_key_notint", 15);
    define("ERROR_concert_plan_create_seats_number_notint", 16);
    define("ERROR_concert_plan_create_expenses_notfloat", 17);

  // update Error Codes
  define("ERROR_concert_plan_update_status_toolong", 1);

  define("ERROR_concert_plan_update_seats_number_notint", 4);
  define("ERROR_concert_form_update_expenses_notfloat", 5);

  define("ERROR_concert_plan_update_noentry", 13);
  define("ERROR_concert_plan_update_id_notexist", 14);
  define("ERROR_concert_plan_update_no_change", 15);

  define("ERROR_concert_plan_update_termin_start_wrong", 11);
  define("ERROR_concert_plan_update_termin_end_wrong", 12);

  // delete Error Codes
  define("ERROR_concert_plan_delete_id_notexist", 14);
  define("ERROR_concert_plan_delete_entry_notexist", 15);
  
  include($_SERVER['DOCUMENT_ROOT'].'/test/class/contract_place/contract_place.php');
  include($_SERVER['DOCUMENT_ROOT'].'/test/class/contract_service/contract_service.php');
  include($_SERVER['DOCUMENT_ROOT'].'/test/class/contract_performer/contract_performer.php');

  class concert_plan
  {
    private int $id_concert_plan;
    private int $id_concert_form;
    private string $termin_start;
    private string $termin_end;
    private int $seats_number;
    private float $expenses;
    private string $status;

    private mysqli $link;

    private contract_place $miejsce;
    private array $usługi;
    private array $wykonawcy;

    public function __construct(mysqli $link, $id)
    {
      if(is_null($link))
        throw new RuntimeException('concert_form::__construct $link is null'.
        '\r\n'.ERROR_concert_form_construct_link_isnull);

      $query = 'SELECT termin_start, termin_end, seats_number, expenses, status, id_concert_form   
                FROM concert_plan WHERE id_concert_plan=? LIMIT 1;';
      $stmt = $link->prepare($query);
      $stmt->bind_param('i', $id);
      $stmt->execute(); 
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        throw new RuntimeException('concert_form::__construct $stmt->fetch() for query: "'.$query.
        '" id_concert_form='.$id.'\r\n'.ERROR_concert_form_construct_entry_notexist);
      }
      $stmt->bind_result($termin_start, $termin_end, $seats_number, $expenses, $status, $id_concert_form);
      $result = $stmt->fetch();
      $stmt->close();

      $this->id_concert_plan = $id;
      $this->termin_start = $termin_start;
      $this->termin_end = $termin_end;
      $this->seats_number = $seats_number;
      $this->expenses = $expenses;
      $this->status = $status;
      $this->id_concert_form = $id_concert_form;

      $this->link = $link;
    }

    public static function generatePlan(mysqli $link, $music_genre, $seats_number, $budget, $location, $date)
    {
      if(is_null($link))
        return ERROR_concert_plan_generatePlan_link_isnull;

      if(empty($music_genre) && is_null($music_genre))
        return ERROR_concert_plan_generatePlan_music_genre_empty;
      elseif(empty($seats_number) && is_null($seats_number))
        return ERROR_concert_plan_generatePlan_seats_number_empty;
      elseif(empty($budget) && is_null($budget))
        return ERROR_concert_plan_generatePlan_budget_empty;
      elseif(empty($location) && is_null($location))
        return ERROR_concert_plan_generatePlan_location_empty;
      elseif(empty($date) && is_null($date))
        return ERROR_concert_plan_generatePlan_date_empty;

      if(!is_int($seats_number))
        return ERROR_concert_plan_generatePlan_seats_number_notint;
      elseif(!is_numeric($budget))
        return ERROR_concert_plan_generatePlan_budget_notfloat;

      $budget = ceil($budget*100.0)/100.0;

      $valid_date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
      if($valid_date == false)
        return ERROR_concert_plan_generatePlan_date_wrong;

      $planArray = array();
      $planArray['expenses'] = 0;

      $query = 'SELECT * from places WHERE id_place NOT IN (SELECT contract_place.id_place
      FROM contract_place, concert_plan, places 
      WHERE contract_place.id_concert_plan=concert_plan.id_concert_plan 
      and contract_place.id_place=places.id_place 
      and ((CAST(concert_plan.termin_start - INTERVAL 12 HOUR AS DATETIME) <= CAST(? AS DATETIME) 
      and CAST(concert_plan.termin_start + INTERVAL 12 HOUR AS DATETIME) >= CAST(? AS DATETIME)) 
      OR (CAST(concert_plan.termin_end - INTERVAL 12 HOUR AS DATETIME) <= CAST(? AS DATETIME) 
      AND CAST(concert_plan.termin_end + INTERVAL 12 HOUR AS DATETIME) >= CAST(? AS DATETIME)))) 
      and location = ? and participants_limit >= ? and rent_price <= ? ORDER BY rent_price LIMIT 1;';

      $stmt = $link->prepare($query);
      $stmt->bind_param('sssssid', $date, $date, $date, $date, $location, $seats_number, $budget);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return ERROR_concert_plan_generatePlan_performerfail;
      }
      $stmt->bind_result($id_place, $place_location, $address, $place_seats_number, $place_price);
      $stmt->fetch();
      $stmt->close();

      $planArray['places'] = array('id_place' => $id_place, 'location' => $place_location,
      'address' => $address, 'participants_limit' => $place_seats_number, 'rent_price' => $place_price);
      $budget -= $place_price;
      $planArray['expenses'] += $place_price;

      $serviceTypes = array('Ochrona', 'Obsługa', 'Oświetlenie', 'Nagłośnienie');
      foreach($serviceTypes as $service)
      {
        $query = 'SELECT * from service WHERE service_id NOT IN (SELECT service.service_id
        FROM concert_plan, contract_service, service 
        WHERE contract_service.id_concert_plan=concert_plan.id_concert_plan 
        and contract_service.id_service=service.service_id
        and ((CAST(concert_plan.termin_start - INTERVAL 12 HOUR AS DATETIME) <= CAST(? AS DATETIME) 
        and CAST(concert_plan.termin_start + INTERVAL 12 HOUR AS DATETIME) >= CAST(? AS DATETIME)) 
        OR (CAST(concert_plan.termin_end - INTERVAL 12 HOUR AS DATETIME) <= CAST(? AS DATETIME) 
        AND CAST(concert_plan.termin_end + INTERVAL 12 HOUR AS DATETIME) >= CAST(? AS DATETIME)))) 
        and service_type = ? and participants_limit >= ? and service_price <= ? 
        ORDER BY service_price LIMIT 1;';

        $stmt = $link->prepare($query);
        $stmt->bind_param('sssssid', $date, $date, $date, $date, $service, $seats_number, $budget);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0)
        {
          $stmt->close();
          return ERROR_concert_plan_generatePlan_performerfail;
        }
        $stmt->bind_result($id_service, $name, $service_type, $service_price, $serivce_seats_number);
        $stmt->fetch();
        $stmt->close();

        $planArray['service'.$service] = array('service_id' => $id_service, 'company_name' => $name,
        'service_type' => $service_type, 'service_price' => $service_price, 
        'participants_limit' => $serivce_seats_number);
        $budget -= $service_price;
        $planArray['expenses'] += $service_price;
      }

      $performers = 0;
      $try = True;
      $excludeArray = array();
      while($performers < 4)
      {
        $query = 'SELECT * from performers WHERE id_performer NOT IN (SELECT performers.id_performer
        FROM concert_plan, contract_performer, performers 
        WHERE contract_performer.id_concert_plan=concert_plan.id_concert_plan 
        and contract_performer.id_performer=performers.id_performer
        and ((CAST(concert_plan.termin_start - INTERVAL 12 HOUR AS DATETIME) <= CAST(? AS DATETIME) 
        and CAST(concert_plan.termin_start + INTERVAL 12 HOUR AS DATETIME) >= CAST(? AS DATETIME)) 
        OR (CAST(concert_plan.termin_end - INTERVAL 12 HOUR AS DATETIME) <= CAST(? AS DATETIME) 
        AND CAST(concert_plan.termin_end + INTERVAL 12 HOUR AS DATETIME) >= CAST(? AS DATETIME)))) 
        and music_genre = ? and price <= ? ';
        if(count($excludeArray))
        {
          $query .= 'and id_performer NOT IN (';
          foreach($excludeArray as $id)
            $query .= $id.',';
          $query = substr($query, 0, strlen($query)-1);
          $query .= ') ';
        }
        $query .= 'ORDER BY price LIMIT 1;';

        $stmt = $link->prepare($query);
        $stmt->bind_param('sssssd', $date, $date, $date, $date, $music_genre, $budget);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0)
        {
          $stmt->close();
          if($try)
          {
            $try = False;
            $excludeArray = array();
            continue;
          }
          else
            break;
        }
        $try = True; 

        $stmt->bind_result($id_performer, $nickname, $performer_music_genre, $performer_price);
        $stmt->fetch();
        $stmt->close();
        $excludeArray[] = $id_performer;
        
        $planArray['performer'.$performers] = array('id_performer' => $id_performer, 'nickname' => $nickname,
        'music_genre' => $performer_music_genre, 'price' => $performer_price);
        $planArray['expenses'] += $performer_price;
        $budget -= $performer_price;
        $performers++;
      }


      if($performers == 0)
        return ERROR_concert_plan_generatePlan_performerfail;

      $planArray['performers'] = $performers;

      return $planArray;
    }

    public function createOffer(array $plan)
    {
      $id_concert_plan = $this->id_concert_plan;
      $result = contract_place::create($this->link, $plan['places']['id_place'], $id_concert_plan, $plan['places']['rent_price']);
      if($result != 0)
        return $result;

      $serviceTypes = array('Ochrona', 'Obsługa', 'Oświetlenie', 'Nagłośnienie');
      foreach($serviceTypes as $serviceType)
        $result = contract_service::create($this->link, $plan['service'.$serviceType]['service_id'], $id_concert_plan, $plan['service'.$serviceType]['service_price']);
      if($result != 0)
        return $result;
      
      for($i = 0; $i < $plan['performers']; $i++)
        $result = contract_performer::create($this->link, $plan['performer'.$i]['id_performer'], $id_concert_plan, $plan['performer'.$i]['price']);
      if($result != 0)
        return $result;

      return 0;
    }

    public static function create(mysqli $link, $termin_start, $id, $termin_end, $seats_number, $expenses, $status)
    {
      if(is_null($link))
        return ERROR_concert_plan_create_link_isnull;

      if(empty($id) || is_null($id))
        return ERROR_concert_plan_create_foreign_key_empty;
      if(empty($termin_start) && is_null($termin_start))
        return ERROR_concert_plan_create_termin_start_empty;
      elseif(empty($termin_end) && is_null($termin_end))
        return ERROR_concert_plan_create_termin_end_empty;
      elseif(empty($seats_number) && is_null($seats_number))
        return ERROR_concert_plan_create_seats_number_empty;
      //elseif(empty($expenses) && is_null($expenses))
        //return ERROR_concert_plan_create_expenses_empty;
      elseif(empty($status) && is_null($status))
        return ERROR_concert_plan_create_status_empty;
                
      if(strlen($status) > MAX_LEN_concert_plan_status)
        return ERROR_concert_plan_create_status_toolong;
     
      if(!is_int($id))
        return  ERROR_concert_plan_create_foreign_key_notint;
      elseif(!is_int($seats_number))
        return ERROR_concert_plan_create_seats_number_notint;
      elseif(!is_numeric($expenses))
        return ERROR_concert_plan_create_expenses_notfloat;

      $expenses = ceil($expenses*100.0)/100.0;

      $valid_date = DateTime::createFromFormat('Y-m-d H:i:s', $termin_start);
      if($valid_date == false)
        return ERROR_concert_plan_create_termin_start_wrong;

      $valid_date = DateTime::createFromFormat('Y-m-d H:i:s', $termin_end);
      if($valid_date == false)
          return ERROR_concert_plan_create_termin_end_wrong;

      $query = 'SELECT id_concert_form FROM concert_form WHERE id_concert_form=? LIMIT 1';
      $stmt = $link->prepare($query);
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return ERROR_concert_plan_create_foreign_key_notexist;
      }
      $stmt->close();
      
      $query = 'INSERT INTO concert_plan(termin_start, id_concert_form, termin_end, seats_number, expenses, status) 
      VALUES(?, ?, ?, ?, ?, ?);';
      $stmt = $link->prepare($query);
      $stmt->bind_param('sisids', $termin_start, $id, $termin_end, $seats_number, $expenses, $status);
      $stmt->execute();
      if($stmt->affected_rows < 1)
      {
        $stmt->close();
        return ERROR_concert_plan_create_failed;
      }
      $stmt->close();

      return 0;
    }

    public function read() : array
    {
      if($this->id_concert_plan == 0)
        return ERROR_concert_form_read_id_notexist;

      $readArray['id_concert_plan'] = $this->id_concert_plan;
      $readArray['termin_start'] = $this->termin_start; 
      $readArray['termin_end'] = $this->termin_end;
      $readArray['seats_number'] = $this->seats_number; 
      $readArray['expenses'] = $this->expenses; 
      $readArray['status'] = $this->status; 
      $readArray['id_concert_form'] = $this->id_concert_form; 

      return $readArray;
    }

    public function update($termin_start, $termin_end, $seats_number, $expenses, $status) : int
    {
      if($this->id_concert_plan == 0)
        return ERROR_concert_plan_update_id_notexist;

      if(strlen($status) > MAX_LEN_concert_plan_status)
        return ERROR_concert_plan_update_status_toolong;

      if(!is_int($seats_number))
        return ERROR_concert_plan_update_seats_number_notint;
      elseif(!is_numeric($expenses))
        return ERROR_concert_form_update_expenses_notfloat;

      $expenses = ceil($expenses*100.0)/100.0;

      $valid_date = DateTime::createFromFormat('Y-m-d H:i:s', $termin_start);
      if($valid_date == false && !empty($termin_start))
        return ERROR_concert_plan_update_termin_start_wrong;

      $valid_date = DateTime::createFromFormat('Y-m-d H:i:s', $termin_end);
      if($valid_date == false && !empty($termin_end))
        return ERROR_concert_plan_update_termin_end_wrong;

      $query = ('UPDATE concert_plan SET ');
      $params1 = '';
      $a_params = array();
      $a_params[] = & $params1;

      if(!empty($termin_start) && !is_null($termin_start))
      {
        $query .= 'termin_start=?, ';
        $params1 .= 's';
        $a_params[] = & $termin_start;
      }

      if(!empty($termin_end) && !is_null($termin_end))
      {
        $query .= 'termin_end=?, ';
        $params1 .= 's';
        $a_params[] = & $termin_end;
      }

      if(!empty($seats_number) || !is_null($seats_number))
      {
        $query .= 'seats_number=?, ';
        $params1 .= 'i';
        $a_params[] = & $seats_number;
      }

      if(!empty($expenses) || !is_null($expenses))
      {
        $query .= 'expenses=?, ';
        $params1 .= 'd';
        $a_params[] = & $expenses;
      }

      if(!empty($status) || !is_null($status))
      {
        $query .= 'status=?, ';
        $params1 .= 's';
        $a_params[] = & $status;
      }

      if(empty($query))
        return ERROR_concert_plan_update_noentry;

      $query = substr($query, 0, strlen($query)-2);
      $query .= ' WHERE id_concert_plan=? LIMIT 1;';
      $params1 .= 'i';

      $stmt = $this->link->prepare($query);
      $id_concert_plan = $this->id_concert_plan;
      $a_params[] = & $id_concert_plan;

      call_user_func_array(array($stmt, 'bind_param'), $a_params);
      $stmt->execute();
      if($stmt->affected_rows == 0)
      {
        $stmt->close();
        return ERROR_concert_plan_update_no_change;
      }
      $stmt->close();

      return 0;
    }

    public function delete() : int
    {
      if($this->id_concert_plan == 0)
        return ERROR_concert_plan_delete_id_notexist;

      $query = 'DELETE FROM concert_plan WHERE id_concert_plan=? LIMIT 1;';
      $stmt = $this->link->prepare($query);
      $id = $this->id_concert_plan;
      $stmt->bind_param('i', $id);
      $stmt->execute();
      if($stmt->affected_rows == 0 || !empty($stmt->error))
      {
        $stmt->close();
        return ERROR_concert_plan_delete_entry_notexist;
      }
      $stmt->close();

      return 0;
    }

    public static function countConcert_plan(mysqli $link) : int
    {
      if(is_null($link))
        return 0;

      $query = 'SELECT COUNT(*) FROM concert_plan LIMIT 1;';
      $stmt = $link->prepare($query);
      $stmt->execute(); 
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return 0;
      }

      $stmt->bind_result($count);
      $result = $stmt->fetch();
      $stmt->close();

      return $count;
    }

    public static function loadPlans(mysqli $link, $page, $elems)
    {
      if($page == 0)
        return 0;

      $plans = array();
      $plans_id = array();
      $lower_limit = ($page-1)*$elems;
      $upper_limit = $elems;

      $query = 'SELECT id_concert_plan FROM concert_plan LIMIT ? , ?;';
      $stmt = $link->prepare($query);
      $stmt->bind_param('ii', $lower_limit, $upper_limit);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($id_concert_plan);
      while($stmt->fetch())
        $plans_id[] = $id_concert_plan;

      if(count($plans_id) == 0)
        return 0;

      for($i = 0; $i < count($plans_id); $i++)
        $plans[] = new concert_plan($link, $plans_id[$i]);

      return $plans;
    }

    public function loadContracts()
    {
      $id_concert_plan = $this->id_concert_plan;

      $query = 'SELECT id_contract_place FROM contract_place WHERE id_concert_plan=? LIMIT 1;';
      $stmt = $this->link->prepare($query);
      $stmt->bind_param('i', $id_concert_plan);
      $stmt->execute();
      $stmt->bind_result($id_contract_place);
      $stmt->fetch();
      $stmt->close();

      try {
        $this->miejsce = new contract_place($this->link, $id_contract_place);
      } catch(RuntimeException $e) {

      }

      $services = array();
      $query = 'SELECT id_contract_service FROM contract_service WHERE id_concert_plan=? LIMIT 4;';
      $stmt = $this->link->prepare($query);
      $stmt->bind_param('i', $id_concert_plan);
      $stmt->execute();
      $stmt->bind_result($id_contract_service);
      while($stmt->fetch())
        $services[] = $id_contract_service;
      $stmt->close();

      foreach($services as $service)
        $this->uslugi[] = new contract_service($this->link, $service);

      $performers = array();
      $query = 'SELECT id_contract_performer FROM contract_performer WHERE id_concert_plan=?;';
      $stmt = $this->link->prepare($query);
      $stmt->bind_param('i', $id_concert_plan);
      $stmt->execute();
      $stmt->bind_result($id_contract_performer);
      while($stmt->fetch())
        $performers[] = $id_contract_performer;
      $stmt->close();

      foreach($performers as $performer)
        $this->wykonawcy[] = new contract_performer($this->link, $performer);

      return 0;
    }

    public function prepareContracts()
    {

      if(isset($this->miejsce))
        $this->miejsce->loadPlace();

      if(isset($this->uslugi) && is_array($this->uslugi))
        for($i = 0; $i < 4; $i++)
          if(isset($this->uslugi[$i]))
            $this->uslugi[$i]->loadService();

      if(isset($this->wykonawcy) && is_array($this->wykonawcy))
        for($i = 0; $i < count($this->wykonawcy); $i++)
          $this->wykonawcy[$i]->loadPerformer();
      
      return 1;
    }

    public function readPlan()
    {
      $plan = array();
      if(isset($this->miejsce))
        $plan['place'] = $this->miejsce->readPlace();
      
      if(isset($this->uslugi) && is_array($this->uslugi))
        for($i = 0; $i < 4; $i++)
          if(isset($this->uslugi[$i]))
            $plan['service'][$i] = $this->uslugi[$i]->readService();

      if(isset($this->wykonawcy) && is_array($this->wykonawcy))
        for($i = 0; $i < count($this->wykonawcy); $i++)
          $plan['performer'][$i] = $this->wykonawcy[$i]->readPerformer();

      $plan['expenses'] = $this->calcExpenses();
      $plan['status'] = $this->status;

      return $plan;
    }

    public function calcExpenses()
    {
      $expenses = 0.0;
      if(isset($this->miejsce))
      {
        $place = $this->miejsce->read();
        $expenses = $place['price'];
      }

      if(isset($this->uslugi) && is_array($this->uslugi))
      {
        for($i = 0; $i < 4; $i++)
        {
          if(isset($this->uslugi[$i]))
          {
            $service = $this->uslugi[$i]->read();
            $expenses += $service['price'];
          }
        }
      }

      if(isset($this->wykonawcy) && is_array($this->wykonawcy))
      {
        for($i = 0; $i < count($this->wykonawcy); $i++)
        {
          $performer = $this->wykonawcy[$i]->read();
          $expenses += $performer['price'];
        }
      }

      $this->expenses = $expenses;
      return $expenses;
    }
  }

  
  //include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');
  //$concert_plan = new concert_plan($cfg_mainLink, 14);
  //echo $concert_plan->update('', '', 0, 0, 'Realizowany');
  /*$serviceTypes = array('Ochrona', 'Obsługa', 'Oświetlenie', 'Nagłośnienie');
  $result = concert_plan::generatePlan($cfg_mainLink, 'Pop', 500, 100000.0, 'Olsztyn', '2022-01-22 15:00:00');
  if(is_numeric($result))
    echo $result;
  elseif(is_array($result))
  {
    echo 'place:<br/>';
    foreach($result['places'] as $y => $x)
      echo $y.'=>'.$x.'<br/>';
    echo 'services:<br/>';
    foreach($serviceTypes as $service)
    {
      foreach($result['service'.$service] as $y => $x)
        echo $y.'=>'.$x.'<br/>';
    }
    for($i = 0; $i < $result['performers']; $i++)
    {
      foreach($result['performer'.$i] as $y => $x)
        echo $y.'=>'.$x.'<br/>';
    }
    echo $result['expenses'].'<br/>';
  }

  echo concert_plan::create($cfg_mainLink, '2022-01-22 15:00:00', 4, '2022-01-22 19:00:00', 100, 100, 'Przesłano');
  */
?>