<?php
  
  // constructor Exceptions
  define('ERROR_contract_service_construct_entry_notexist', 'Record doesn\'t exists.');
  define('ERROR_contract_service_construct_link_isnull', 'Pased link to db is null');

  // create Error Codes

  define("ERROR_contract_service_create_price_empty", 4);
  
  define("ERROR_contract_service_create_foreign_key_empty", 9);
  define("ERROR_contract_service_create_foreign_key_notexist", 10);

  define("ERROR_contract_service_create_failed", 13);
  define('ERROR_contract_service_create_link_isnull', 14);
  
  define("ERROR_contract_service_create_foreign_key_notint", 15);
  define("ERROR_contract_service_create_price_notfloat", 17);

  // read Error Codes
  define("ERROR_contract_service_read_id_notexist", 16);

    // update Error Codes
define("ERROR_contract_service_update_price_notfloat", 5);

define("ERROR_contract_service_update_noentry", 13);
define("ERROR_contract_service_update_id_notexist", 14);
define("ERROR_contract_service_update_no_change", 15);

// delete Error Codes
define("ERROR_contract_service_delete_id_notexist", 14);
define("ERROR_contract_service_delete_entry_notexist", 15);

  include($_SERVER['DOCUMENT_ROOT'].'/test/class/service/service.php');

class contract_service
{
  private int $id_contract_service;
  private float $price;
  private int $id_service;
  private int $id_concert_plan;

  private mysqli $link;

  private service $usluga;

  public function __construct(mysqli $link, int $id)
  {
    if(is_null($link))
      throw new RuntimeException('client::__construct $link is null'.
      '\r\n'.ERROR_contract_service_construct_entry_notexist);

    $query = 'SELECT id_contract_service, price, id_service, id_concert_plan FROM contract_service WHERE id_contract_service=? LIMIT 1;';
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute(); 
    $stmt->store_result();
    if($stmt->num_rows == 0)
    {
      $stmt->close();
      throw new RuntimeException('client::__construct $stmt->fetch() for query: "'.$query.
      '" id_client='.$id.'\r\n'.ERROR_contract_service_construct_link_isnull);
    }
    $stmt->bind_result($id_contract_service, $price, $id_service, $id_concert_plan);
    $result = $stmt->fetch();
    $stmt->close();

    $this->id_contract_service = $id_contract_service;
    $this->price = $price;
    $this->id_service = $id_service;
    $this->id_concert_plan = $id_concert_plan;

    $this->link = $link;
  }

  public static function create(mysqli $link, $id_service, $id_concert_plan, $price)
  {
    if(is_null($link))
        return ERROR_contract_service_create_link_isnull;

      if(empty($id_service) || is_null($id_service))
        return ERROR_contract_service_create_foreign_key_empty;
      elseif(empty($id_concert_plan) && is_null($id_concert_plan))
        return ERROR_contract_service_create_foreign_key_empty;

      elseif(empty($price) && is_null($price))
        return ERROR_contract_service_create_price_empty;
     
      if(!is_int($id_service))
        return  ERROR_contract_service_create_foreign_key_notint;
      elseif(!is_int($id_concert_plan))
        return  ERROR_contract_service_create_foreign_key_notint;
      elseif(!is_numeric($price))
        return ERROR_contract_service_create_price_notfloat;

      $price = ceil($price*100.0)/100.0;

      $query = 'SELECT service_id FROM service WHERE service_id=? LIMIT 1';
      $stmt = $link->prepare($query);
      $stmt->bind_param('i', $id_service);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return ERROR_contract_service_create_foreign_key_notexist;
      }
      $stmt->close();

      $query = 'SELECT id_concert_plan FROM concert_plan WHERE id_concert_plan=? LIMIT 1';
      $stmt = $link->prepare($query);
      $stmt->bind_param('i', $id_concert_plan);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return ERROR_contract_service_create_foreign_key_notexist;
      }
      $stmt->close();
      
      $query = 'INSERT INTO contract_service(price, id_service, id_concert_plan) 
      VALUES(?, ?, ?);';
      $stmt = $link->prepare($query);
      $stmt->bind_param('dii', $price, $id_service, $id_concert_plan);
      $stmt->execute();
      if($stmt->affected_rows < 1)
      {
        $stmt->close();
        return ERROR_contract_service_create_failed;
      }
      $stmt->close();

      return 0;
  }

  public function read()
  {
    if($this->id_contract_service == 0)
      return ERROR_contract_service_read_id_notexist;

    $readArray['id_contract_service'] = $this->id_contract_service; 
    $readArray['price'] = $this->price;
    $readArray['id_service'] = $this->id_service; 
    $readArray['id_concert_plan'] = $this->id_concert_plan;

    return $readArray;
  }

  public function update($price) : int
  {
    if($this->id_contract_service == 0)
      return ERROR_contract_service_update_id_notexist;

    if(!is_numeric($price))
      return ERROR_contract_service_update_price_notfloat;

    $price = ceil($price*100.0)/100.0;

    $query = 'UPDATE contract_service SET ';
    $params1 = '';
    $a_params = array();
    $a_params[] = & $params1;

    if(!empty($price) || !is_null($price))
    {
      $query .= 'price=?, ';
      $params1 .= 'd';
      $a_params[] = & $price;
    }

    if(empty($query))
      return ERROR_contract_service_update_noentry;

    $query = substr($query, 0, strlen($query)-2);
    $query .= ' WHERE id_contract_service=? LIMIT 1;';
    $params1 .= 'i';

    $stmt = $this->link->prepare($query);
    $id_contract_service = $this->id_contract_service;
    $a_params[] = & $id_contract_service;

    call_user_func_array(array($stmt, 'bind_param'), $a_params);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_contract_service_update_no_change;
    }
    $stmt->close();

    return 0;
  }

  public function delete() : int
  {
    if($this->id_contract_service == 0)
      return ERROR_contract_service_delete_id_notexist;

    $query = 'DELETE FROM contract_service WHERE id_contract_service=? LIMIT 1;';
    $stmt = $this->link->prepare($query);
    $id = $this->id_contract_service;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_contract_service_delete_entry_notexist;
    }
    $stmt->close();

    return 0;
  }

  public function loadService()
  {
    $this->usluga = new service($this->link, $this->id_service);
  }

  public function readService()
  {
    $service = $this->usluga->read();
    $service['service_price'] = $this->price;
    $service['id_contract_service'] = $this->id_contract_service;

    return $service;
  }
}

  //include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');
  //echo contract_service::create($cfg_mainLink, 1, 1, 10.0);
  //$dichodwa = new contract_service($cfg_mainLink, 1);

?>