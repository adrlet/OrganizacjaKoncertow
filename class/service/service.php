<?php

// SQL Table Requirements
define("MAX_LEN_service_company_name", 50);
define("MAX_LEN_service_service_type", 50);

// constructor Exceptions
define('ERROR_service_construct_entry_notexist', 'Record doesn\'t exists.');
define('ERROR_service_construct_link_isnull', 'Pased link to db is null');

// create Error Codes
define("ERROR_service_create_company_name_toolong", 1);
define("ERROR_service_create_service_type_toolong", 2);

define("ERROR_service_create_company_name_empty", 4);
define("ERROR_service_create_service_type_empty", 5);
define("ERROR_service_create_service_price_empty", 6);
define("ERROR_service_create_seats_number_empty", 7);
  
define("ERROR_service_create_failed", 13);
define('ERROR_service_create_link_isnull', 14);

define("ERROR_service_create_seats_number_notint", 16);
define("ERROR_service_create_service_price_notfloat", 17);

// read Error Codes
define("ERROR_service_read_id_notexist", 16);

// update Error Codes
define("ERROR_service_update_company_name_toolong", 1);
define("ERROR_service_update_service_type_toolong", 2);

define("ERROR_service_update_seats_number_notint", 5);
define("ERROR_service_update_service_price_notfloat", 6);

define("ERROR_service_update_noentry", 13);
define("ERROR_service_update_id_notexist", 14);
define("ERROR_service_update_no_change", 15);

// delete Error Codes
define("ERROR_service_delete_id_notexist", 14);
define("ERROR_service_delete_entry_notexist", 15);

class service
{
  private int $service_id;
  private string $company_name;
  private string $service_type;
  private float $service_price;
  private int $seats_number;

  private mysqli $link;

  public function __construct(mysqli $link, $id)
  {
    if(is_null($link))
      throw new RuntimeException('client::__construct $link is null'.
      '\r\n'.ERROR_service_construct_entry_notexist);

    $query = 'SELECT * FROM service WHERE service_id=? LIMIT 1';
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows == 0)
    {
      $stmt->close();
      throw new RuntimeException('client::__construct $stmt->fetch() for query: "'.$query.
      '" id_client='.$id.'\r\n'.ERROR_service_construct_link_isnull);
    }
    $stmt->bind_result($service_id, $company_name, $service_type, $service_price, $seats_number);
    $result = $stmt->fetch();
    $stmt->close();

    $this->service_id = $service_id;
    $this->company_name = $company_name;
    $this->service_type = $service_type;
    $this->service_price = $service_price;
    $this->seats_number = $seats_number;

    $this->link = $link;
  }

  public static function create(mysqli $link, $company_name, $service_type, $service_price, $seats_number)
  {
    if(is_null($link))
      return ERROR_service_create_link_isnull;

    if(empty($company_name) && is_null($company_name))
      return ERROR_service_create_company_name_empty;
    elseif(empty($service_type) && is_null($service_type))
      return ERROR_service_create_service_type_empty;
    elseif(empty($service_price) && is_null($service_price))
      return ERROR_service_create_service_price_empty;
    elseif(empty($seats_number) && is_null($seats_number))
      return ERROR_service_create_seats_number_empty;
    
    if(!is_int($seats_number))
      return  ERROR_service_create_seats_number_notint;
    elseif(!is_numeric($service_price))
      return ERROR_service_create_service_price_notfloat;

    if(strlen($company_name) > MAX_LEN_service_company_name)
      return ERROR_service_create_company_name_toolong;
    elseif(strlen($service_type) > MAX_LEN_service_service_type)
      return ERROR_service_create_service_type_toolong;

    $service_price = ceil($service_price*100.0)/100.0;
      
    $query = 'INSERT INTO service(company_name, service_type, service_price, participants_limit) VALUES(?, ?, ?, ?);';
    $stmt = $link->prepare($query);
    $stmt->bind_param('ssdi', $company_name, $service_type, $service_price, $seats_number);
    $stmt->execute();
    if($stmt->affected_rows < 1)
    {
      $stmt->close();
      return ERROR_service_create_failed;
    }
    $stmt->close();

    return 0;
  }

  public function read()
  {
    if($this->service_id == 0)
      return ERROR_service_read_id_notexist;

    $readArray['service_id'] = $this->service_id; 
    $readArray['company_name'] = $this->company_name;
    $readArray['service_type'] = $this->service_type;
    $readArray['service_price'] = $this->service_price; 
    $readArray['seats_number'] = $this->seats_number;

    return $readArray;
  }

  public function update($company_name, $service_type, $service_price, $seats_number) : int
  {
    if($this->service_id == 0)
      return ERROR_service_update_id_notexist;

    if(strlen($company_name) > MAX_LEN_service_company_name)
      return ERROR_service_update_company_name_toolong;
    elseif(strlen($service_type) > MAX_LEN_service_service_type)
      return ERROR_service_update_service_type_toolong;

    if(!is_int($seats_number))
      return ERROR_service_update_seats_number_notint;
    elseif(!is_numeric($service_price))
      return ERROR_service_update_service_price_notfloat;

    $service_price = ceil($service_price*100.0)/100.0;

    $query = ('UPDATE service SET ');
    $params1 = '';
    $a_params = array();
    $a_params[] = & $params1;

    if(!empty($company_name) || !is_null($company_name))
    {
      $query .= 'company_name=?, ';
      $params1 .= 's';
      $a_params[] = & $company_name;
    }

    if(!empty($service_type) || !is_null($service_type))
    {
      $query .= 'service_type=?, ';
      $params1 .= 's';
      $a_params[] = & $service_type;
    }

    if(!empty($service_price) || !is_null($service_price))
    {
      $query .= 'seats_number=?, ';
      $params1 .= 'd';
      $a_params[] = & $seats_number;
    }

    if(!empty($seats_number) || !is_null($seats_number))
    {
      $query .= 'participants_limit=?, ';
      $params1 .= 'i';
      $a_params[] = & $seats_number;
    }

    if(empty($query))
      return ERROR_service_update_noentry;

    $query = substr($query, 0, strlen($query)-2);
    $query .= ' WHERE service_id=? LIMIT 1;';
    $params1 .= 'i';

    $stmt = $this->link->prepare($query);
    $service_id = $this->service_id;
    $a_params[] = & $service_id;

    call_user_func_array(array($stmt, 'bind_param'), $a_params);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_service_update_no_change;
    }
    $stmt->close();

    return 0;
  }

  public function delete() : int
  {
    if($this->service_id == 0)
      return ERROR_service_delete_id_notexist;

    $query = 'DELETE FROM service WHERE service_id=? LIMIT 1;';
    $stmt = $this->link->prepare($query);
    $id = $this->service_id;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_service_delete_entry_notexist;
    }
    $stmt->close();

    return 0;
  }

  public static function countServices(mysqli $link)
  {
    if(is_null($link))
      return 0;

    $query = 'SELECT COUNT(*) FROM service;';
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

  public static function loadServices(mysqli $link, $page)
  {
    if($page == 0)
      return 0;

    $services = array();
    $services_id = array();
    $lower_limit = ($page-1)*10;
    $upper_limit = 10;

    $query = 'SELECT service_id FROM service LIMIT ? , ?;';
    $stmt = $link->prepare($query);
    $stmt->bind_param('ii', $lower_limit, $upper_limit);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($service_id);
    while($stmt->fetch())
      $services_id[] = $service_id;

    if(count($services_id) == 0)
      return 0;

    for($i = 0; $i < count($services_id); $i++)
      $services[] = new service($link, $services_id[$i]);

    return $services;
  }
}

//include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');
//echo service::create($cfg_mainLink, 'x', 'x', 10.0, 100);

?>