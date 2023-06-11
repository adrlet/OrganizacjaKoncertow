<?php

  // SQL Table Requirements
  define("MAX_LEN_concert_form_name", 100);
  define("MAX_LEN_concert_form_music_genre", 50);
  define("MAX_LEN_concert_form_location", 50);

  // constructor Exceptions
  define('ERROR_concert_form_construct_entry_notexist', 'Record doesn\'t exists.');
  define('ERROR_concert_form_construct_link_isnull', 'Pased link to db is null');

  // create Error Codes
  define("ERROR_concert_form_create_name_toolong", 1);
  define("ERROR_concert_form_create_music_genre_toolong", 2);
  define("ERROR_concert_form_create_location_toolong", 3);

  define("ERROR_concert_form_create_name_empty", 4);
  define("ERROR_concert_form_create_music_genre_empty", 5);
  define("ERROR_concert_form_create_seats_number_empty", 6);
  define("ERROR_concert_form_create_budget_empty", 7);
  define("ERROR_concert_form_create_location_empty", 8);
  define("ERROR_concert_form_create_date_empty", 9);

  define("ERROR_concert_form_create_foreign_key_empty", 10);
  define("ERROR_concert_form_create_foreign_key_notexist", 11);

  define("ERROR_concert_form_create_date_wrong", 12);

  define("ERROR_concert_form_create_failed", 13);
  define('ERROR_concert_form_create_link_isnull', 14);

  define("ERROR_concert_form_create_foreign_key_notint", 15);
  define("ERROR_concert_form_create_seats_number_notint", 16);
  define("ERROR_concert_form_create_budget_notfloat", 17);

  // read Error Codes
  define("ERROR_concert_form_read_id_notexist", 16);

  // update Error Codes
  define("ERROR_concert_form_update_name_toolong", 1);
  define("ERROR_concert_form_update_music_genre_toolong", 2);
  define("ERROR_concert_form_update_location_toolong", 3);

  define("ERROR_concert_form_update_seats_number_notint", 4);
  define("ERROR_concert_form_update_budget_notfloat", 5);

  define("ERROR_concert_form_update_noentry", 13);
  define("ERROR_concert_form_update_id_notexist", 14);
  define("ERROR_concert_form_update_no_change", 15);

  define("ERROR_concert_form_update_date_wrong", 6);

  // delete Error Codes
  define("ERROR_concert_form_delete_id_notexist", 14);
  define("ERROR_concert_form_delete_entry_notexist", 15);

  include($_SERVER['DOCUMENT_ROOT'].'/test/class/concert_plan/concert_plan.php');

  class concert_form
  {
    private int $id_concert_form;
    private string $name;
    private string $music_genre;
    private int $seats_number;
    private float $budget;
    private string $location;
    private string $date;

    private mysqli $link;
    
    private concert_plan $plan;

    public function __construct(mysqli $link, $id)
    {
      if(is_null($link))
        throw new RuntimeException('concert_form::__construct $link is null'.
        '\r\n'.ERROR_concert_form_construct_link_isnull);

      $query = 'SELECT name, music_genre, seats_number, budget, location, date 
                FROM concert_form WHERE id_concert_form=? LIMIT 1;';
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
      $stmt->bind_result($name, $music_genre, $seats_number, $budget, $location, $date);
      $result = $stmt->fetch();
      $stmt->close();

      $this->id_concert_form = $id;
      $this->name = $name;
      $this->music_genre = $music_genre;
      $this->seats_number = $seats_number;
      $this->budget = $budget;
      $this->location = $location;
      $this->date = $date;

      $this->link = $link;
    }

    public static function countConcert_form(mysqli $link, $id) : int
    {
      if(is_null($link))
        return 0;

      $query = 'SELECT COUNT(*) FROM concert_form ';
      if($id > 0)
        $query .= 'WHERE id_client=? ';
      $query .= 'LIMIT 1;';
      $stmt = $link->prepare($query);
      if($id > 0)
        $stmt->bind_param('i', $id);
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

    public static function create(mysqli $link, $name, $id, $music_genre, $seats_number, $budget, $location, $date)
    {
      if(is_null($link))
        return ERROR_concert_form_create_link_isnull;

      if(empty($id) || is_null($id))
        return ERROR_concert_form_create_foreign_key_empty;
      if(empty($name) && is_null($name))
        return ERROR_concert_form_create_name_empty;
      elseif(empty($music_genre) && is_null($music_genre))
        return ERROR_concert_form_create_music_genre_empty;
      elseif(empty($seats_number) && is_null($seats_number))
        return ERROR_concert_form_create_seats_number_empty;
      elseif(empty($budget) && is_null($budget))
        return ERROR_concert_form_create_budget_empty;
      elseif(empty($location) && is_null($location))
        return ERROR_concert_form_create_location_empty;
      elseif(empty($date) && is_null($date))
        return ERROR_concert_form_create_date_empty;
                
      if(strlen($name) > MAX_LEN_concert_form_name)
        return ERROR_concert_form_create_name_toolong;
      elseif(strlen($music_genre) > MAX_LEN_concert_form_music_genre)
        return ERROR_concert_form_create_music_genre_toolong;
      elseif(strlen($location) > MAX_LEN_concert_form_location)
        return ERROR_concert_form_create_location_toolong;

      if(!is_int($id))
        return  ERROR_concert_form_create_foreign_key_notint;
      elseif(!is_int($seats_number))
        return ERROR_concert_form_create_seats_number_notint;
      elseif(!is_numeric($budget))
        return ERROR_concert_form_create_budget_notfloat;

      $budget = ceil($budget*100.0)/100.0;

      $valid_date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
      if($valid_date == false)
        return ERROR_concert_form_create_date_wrong;

      $query = 'SELECT id_client FROM clients WHERE id_client=? LIMIT 1';
      $stmt = $link->prepare($query);
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return ERROR_concert_form_create_foreign_key_notexist;
      }
      $stmt->close();
      
      $query = 'INSERT INTO concert_form(name, id_client, music_genre, seats_number, budget, location, date) 
      VALUES(?, ?, ?, ?, ?, ?, ?);';
      $stmt = $link->prepare($query);
      $stmt->bind_param('sisidss', $name, $id, $music_genre, $seats_number, $budget, $location, $date);
      $stmt->execute();
      if($stmt->affected_rows < 1)
      {
        $stmt->close();
        return ERROR_concert_form_create_failed;
      }
      $stmt->close();

      return 0;
    }

    public function read() : array
    {
      if($this->id_concert_form == 0)
        return ERROR_concert_form_read_id_notexist;

      $readArray['id_concert_form'] = $this->id_concert_form; 
      $readArray['name'] = $this->name;
      $readArray['music_genre'] = $this->music_genre; 
      $readArray['seats_number'] = $this->seats_number; 
      $readArray['budget'] = $this->budget; 
      $readArray['location'] = $this->location;
      $readArray['date'] = $this->date;

      return $readArray;
    }

    public function update($name, $music_genre, $seats_number, $budget, $location, $date) : int
    {
      if($this->id_concert_form == 0)
      return ERROR_concert_form_update_id_notexist;

      if(strlen($name) > MAX_LEN_concert_form_name)
        return ERROR_concert_form_create_name_toolong;
      elseif(strlen($music_genre) > MAX_LEN_concert_form_music_genre)
        return ERROR_concert_form_create_music_genre_toolong;
      elseif(strlen($location) > MAX_LEN_concert_form_location)
        return ERROR_concert_form_create_location_toolong;

      if(!is_int($seats_number))
        return ERROR_concert_form_update_seats_number_notint;
      elseif(!is_numeric($budget))
        return ERROR_concert_form_update_budget_notfloat;

      $budget = ceil($budget*100.0)/100.0;

      $valid_date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
      if($valid_date == false)
        return ERROR_concert_form_update_date_wrong;

      $query = ('UPDATE concert_form SET ');
      $params1 = '';
      $a_params = array();
      $a_params[] = & $params1;

      if(!empty($name) && !is_null($name))
      {
        $query .= 'name=?, ';
        $params1 .= 's';
        $a_params[] = & $name;
      }

      if(!empty($music_genre) && !is_null($music_genre))
      {
        $query .= 'music_genre=?, ';
        $params1 .= 's';
        $a_params[] = & $music_genre;
      }

      if(!empty($seats_number) || !is_null($seats_number))
      {
        $query .= 'seats_number=?, ';
        $params1 .= 'i';
        $a_params[] = & $seats_number;
      }

      if(!empty($budget) || !is_null($budget))
      {
        $query .= 'budget=?, ';
        $params1 .= 'd';
        $a_params[] = & $budget;
      }

      if(!empty($location) || !is_null($location))
      {
        $query .= 'location=?, ';
        $params1 .= 's';
        $a_params[] = & $location;
      }

      if(!empty($date) || !is_null($date))
      {
        $query .= 'date=?, ';
        $params1 .= 's';
        $a_params[] = & $date;
      }

      if(empty($query))
        return ERROR_concert_form_update_noentry;

      $query = substr($query, 0, strlen($query)-2);
      $query .= ' WHERE id_concert_form=? LIMIT 1;';
      $params1 .= 'i';

      $stmt = $this->link->prepare($query);
      $id_concert_form = $this->id_concert_form;
      $a_params[] = & $id_concert_form;

      call_user_func_array(array($stmt, 'bind_param'), $a_params);
      $stmt->execute();
      if($stmt->affected_rows == 0)
      {
        $stmt->close();
        return ERROR_concert_form_update_no_change;
      }
      $stmt->close();

      return 0;
    }

    public function delete() : int
    {
      if($this->id_concert_form == 0)
        return ERROR_concert_form_delete_id_notexist;

      $query = 'DELETE FROM concert_form WHERE id_concert_form=? LIMIT 1;';
      $stmt = $this->link->prepare($query);
      $id = $this->id_concert_form;
      $stmt->bind_param('i', $id);
      $stmt->execute();
      if($stmt->affected_rows == 0 || !empty($stmt->error))
      {
        $stmt->close();
        return ERROR_concert_form_delete_entry_notexist;
      }
      $stmt->close();

      return 0;
    }

    public static function loadForms(mysqli $link, $page)
    {
      if($page == 0)
        return 0;

      $forms = array();
      $forms_id = array();
      $lower_limit = ($page-1)*10;
      $upper_limit = 10;

      $query = 'SELECT id_concert_form FROM concert_form LIMIT ? , ?;';
      $stmt = $link->prepare($query);
      $stmt->bind_param('ii', $lower_limit, $upper_limit);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($id_concert_form);
      while($stmt->fetch())
        $forms_id[] = $id_concert_form;

      if(count($forms_id) == 0)
        return 0;

      for($i = 0; $i < count($forms_id); $i++)
        $forms[] = new concert_form($link, $forms_id[$i]);

      return $forms;
    }

    public function loadPlan()
    {
      $id_form = $this->id_concert_form;
      $query = 'SELECT id_concert_plan FROM concert_plan WHERE id_concert_form=? LIMIT 1';
      $stmt = $this->link->prepare($query);
      $stmt->bind_param('i', $id_form);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return 1;
      }
      $stmt->bind_result($id_plan);
      $stmt->fetch();
      $stmt->close();

      $this->plan = new concert_plan($this->link, $id_plan);

      return 0;
    }

    public function sendForm(array $plan)
    {
      if(isset($this->plan))
        return $this->plan->createOffer($plan);

      return 1;
    }

    public function initPlan()
    {
      $this->plan->loadContracts();
      $result = $this->plan->prepareContracts();

      return $result;
    }

    public function readPlan()
    {
      $plan = $this->plan->readPlan();

      return $plan;
    }
  } 

  /*include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');

  echo concert_form::countConcert_form($cfg_mainLink, 1).'<br/>';
  echo concert_form::create($cfg_mainLink, 'z', 16, 'z', 1, 1.0, 'z', '2022-01-22 12:55:00').'<br/>';
  $test = new concert_form($cfg_mainLink, 12);
  $concert_form_info = $test->read();
  foreach($concert_form_info as $x)
    echo $x.'<br/>';*/

?>