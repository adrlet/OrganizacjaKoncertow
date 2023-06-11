<?php
  
  // constructor Exceptions
  define('ERROR_contract_performer_construct_entry_notexist', 'Record doesn\'t exists.');
  define('ERROR_contract_performer_construct_link_isnull', 'Pased link to db is null');

  // create Error codes
  define("ERROR_contract_performer_create_price_empty", 4);
  
  define("ERROR_contract_performer_create_foreign_key_empty", 9);
  define("ERROR_contract_performer_create_foreign_key_notexist", 10);

  define("ERROR_contract_performer_create_failed", 13);
  define('ERROR_contract_performer_create_link_isnull', 14);
  
  define("ERROR_contract_performer_create_foreign_key_notint", 15);
  define("ERROR_contract_performer_create_price_notfloat", 17);

// read Error Codes
define("ERROR_contract_performer_read_id_notexist", 16);

// update Error Codes
define("ERROR_contract_performer_update_price_notfloat", 5);

define("ERROR_contract_performer_update_noentry", 13);
define("ERROR_contract_performer_update_id_notexist", 14);
define("ERROR_contract_performer_update_no_change", 15);

// delete Error Codes
define("ERROR_contract_performer_delete_id_notexist", 14);
define("ERROR_contract_performer_delete_entry_notexist", 15);

include($_SERVER['DOCUMENT_ROOT'].'/test/class/performers/performers.php');

class contract_performer
{
  private int $id_contract_performer;
  private float $price;
  private int $id_performer;
  private int $id_concert_plan;

  private mysqli $link;

  private performers $wykonawca;

  public function __construct(mysqli $link, int $id)
  {
    if(is_null($link))
      throw new RuntimeException('client::__construct $link is null'.
      '\r\n'.ERROR_contract_performer_construct_entry_notexist);

    $query = 'SELECT id_contract_performer, price, id_performer, id_concert_plan FROM contract_performer WHERE id_contract_performer=? LIMIT 1;';
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute(); 
    $stmt->store_result();
    if($stmt->num_rows == 0)
    {
      $stmt->close();
      throw new RuntimeException('client::__construct $stmt->fetch() for query: "'.$query.
      '" id_client='.$id.'\r\n'.ERROR_contract_performer_construct_link_isnull);
    }
    $stmt->bind_result($id_contract_performer, $price, $id_performer, $id_concert_plan);
    $result = $stmt->fetch();
    $stmt->close();

    $this->id_contract_performer = $id_contract_performer;
    $this->price = $price;
    $this->id_performer = $id_performer;
    $this->id_concert_plan = $id_concert_plan;

    $this->link = $link;
  }

  public static function create(mysqli $link, $id_performer, $id_concert_plan, $price)
  {
    if(is_null($link))
        return ERROR_contract_performer_create_link_isnull;

      if(empty($id_performer) || is_null($id_performer))
        return ERROR_contract_performer_create_foreign_key_empty;
      elseif(empty($id_concert_plan) && is_null($id_concert_plan))
        return ERROR_contract_performer_create_foreign_key_empty;

      elseif(empty($price) && is_null($price))
        return ERROR_contract_performer_create_price_empty;
     
      if(!is_int($id_performer))
        return  ERROR_contract_performer_create_foreign_key_notint;
      elseif(!is_int($id_concert_plan))
        return  ERROR_contract_performer_create_foreign_key_notint;
      elseif(!is_numeric($price))
        return ERROR_contract_performer_create_price_notfloat;

      $price = ceil($price*100.0)/100.0;

      $query = 'SELECT id_performer FROM performers WHERE id_performer=? LIMIT 1';
      $stmt = $link->prepare($query);
      $stmt->bind_param('i', $id_performer);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows == 0)
      {
        $stmt->close();
        return ERROR_contract_performer_create_foreign_key_notexist;
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
        return ERROR_contract_performer_create_foreign_key_notexist;
      }
      $stmt->close();
      
      $query = 'INSERT INTO contract_performer(price, id_performer, id_concert_plan) 
      VALUES(?, ?, ?);';
      $stmt = $link->prepare($query);
      $stmt->bind_param('dii', $price, $id_performer, $id_concert_plan);
      $stmt->execute();
      if($stmt->affected_rows < 1)
      {
        $stmt->close();
        return ERROR_contract_performer_create_failed;
      }
      $stmt->close();

      return 0;
  }

  public function read()
  {
    if($this->id_contract_performer == 0)
      return ERROR_contract_performer_read_id_notexist;

    $readArray['id_contract_performer'] = $this->id_contract_performer; 
    $readArray['price'] = $this->price;
    $readArray['id_performer'] = $this->id_performer;
    $readArray['id_concert_plan'] = $this->id_concert_plan;

    return $readArray;
  }

  public function update($price) : int
  {
    if($this->id_contract_performer == 0)
      return ERROR_concert_plan_update_id_notexist;

    if(!is_numeric($price))
      return ERROR_contract_performer_update_price_notfloat;

    $price = ceil($price*100.0)/100.0;

    $query = 'UPDATE contract_performer SET ';
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
      return ERROR_contract_performer_update_noentry;

    $query = substr($query, 0, strlen($query)-2);
    $query .= ' WHERE id_contract_performer=? LIMIT 1;';
    $params1 .= 'i';

    $stmt = $this->link->prepare($query);
    $id_contract_performer = $this->id_contract_performer;
    $a_params[] = & $id_contract_performer;

    call_user_func_array(array($stmt, 'bind_param'), $a_params);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_contract_performer_update_no_change;
    }
    $stmt->close();

    return 0;
  }

  public function delete() : int
  {
    if($this->id_contract_performer == 0)
      return ERROR_contract_performer_delete_id_notexist;

    $query = 'DELETE FROM contract_performer WHERE id_contract_performer=? LIMIT 1;';
    $stmt = $this->link->prepare($query);
    $id = $this->id_contract_performer;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_contract_performer_delete_entry_notexist;
    }
    $stmt->close();

    return 0;
  }

  public function loadPerformer()
  {
    $this->wykonawca = new performers($this->link, $this->id_performer);
  }

  public function readPerformer()
  {
    $performer = $this->wykonawca->read();
    $performer['price'] = $this->price;
    $performer['id_contract_performer'] = $this->id_contract_performer;

    return $performer;
  }
}

  //include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');
  //echo contract_performer::create($cfg_mainLink, 1, 1, 10.0);
  //$dichodrei = new contract_performer($cfg_mainLink, 1);

?>