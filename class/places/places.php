<?php

// SQL Table Requirements
define("MAX_LEN_places_location", 50);
define("MAX_LEN_places_address", 100);

// constructor Exceptions
define('ERROR_places_construct_entry_notexist', 'Record doesn\'t exists.');
define('ERROR_places_construct_link_isnull', 'Pased link to db is null');

// create Error Codes
define("ERROR_places_create_location_toolong", 1);
define("ERROR_places_create_address_toolong", 2);

define("ERROR_places_create_location_empty", 4);
define("ERROR_places_create_addres_empty", 5);
define("ERROR_places_create_seats_number_empty", 6);
define("ERROR_places_create_rent_price_empty", 7);

define("ERROR_places_create_failed", 13);
define('ERROR_places_create_link_isnull', 14);

define("ERROR_places_create_seats_number_notint", 16);
define("ERROR_places_create_rent_price_notfloat", 17);

// read Error Codes
define("ERROR_places_read_id_notexist", 16);

// update Error Codes
define("ERROR_places_update_location_toolong", 1);
define("ERROR_places_update_address_toolong", 2);

define("ERROR_places_update_seats_number_notint", 5);
define("ERROR_places_update_rent_price_notfloat", 6);

define("ERROR_places_update_noentry", 13);
define("ERROR_places_update_id_notexist", 14);
define("ERROR_places_update_no_change", 15);

// delete Error Codes
define("ERROR_places_delete_id_notexist", 14);
define("ERROR_places_delete_entry_notexist", 15);

class places
{
  private int $id_place;
  private string $location;
  private string $address;
  private int $seats_number;
  private float $rent_price;

  private mysqli $link;

  public function __construct(mysqli $link, $id)
  {
    if(is_null($link))
      throw new RuntimeException('client::__construct $link is null'.
      '\r\n'.ERROR_places_construct_entry_notexist);

    $query = 'SELECT * FROM places WHERE id_place=? LIMIT 1';
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows == 0)
    {
      $stmt->close();
      throw new RuntimeException('client::__construct $stmt->fetch() for query: "'.$query.
      '" id_client='.$id.'\r\n'.ERROR_places_construct_link_isnull);
    }
    $stmt->bind_result($id_place, $location, $address, $seats_number, $rent_price);
    $result = $stmt->fetch();
    $stmt->close();

    $this->id_place = $id_place;
    $this->location = $location;
    $this->address = $address;
    $this->seats_number = $seats_number;
    $this->rent_price = $rent_price;

    $this->link = $link;
  }

  public static function create(mysqli $link, $location, $addres, $seats_number, $rent_price)
  {
    if(is_null($link))
      return ERROR_places_create_link_isnull;

    if(empty($location) && is_null($location))
      return ERROR_places_create_location_empty;
    elseif(empty($addres) && is_null($addres))
      return ERROR_places_create_addres_empty;
    elseif(empty($seats_number) && is_null($seats_number))
      return ERROR_places_create_seats_number_empty;
    elseif(empty($rent_price) && is_null($rent_price))
      return ERROR_places_create_rent_price_empty;
    
    if(!is_int($seats_number))
      return  ERROR_places_create_seats_number_notint;
    elseif(!is_numeric($rent_price))
      return ERROR_places_create_rent_price_notfloat;

    if(strlen($location) > MAX_LEN_places_location)
      return ERROR_places_create_location_toolong;
    elseif(strlen($addres) > MAX_LEN_places_address)
      return ERROR_places_create_address_toolong;

    $rent_price = ceil($rent_price*100.0)/100.0;
      
    $query = 'INSERT INTO places(location, address, participants_limit, rent_price) VALUES(?, ?, ?, ?);';
    $stmt = $link->prepare($query);
    $stmt->bind_param('ssid', $location, $addres, $seats_number, $rent_price);
    $stmt->execute();
    if($stmt->affected_rows < 1)
    {
      $stmt->close();
      return ERROR_places_create_failed;
    }
    $stmt->close();

    return 0;
  }

  public function read()
  {
    if($this->id_place == 0)
      return ERROR_places_read_id_notexist;

    $readArray['id_place'] = $this->id_place; 
    $readArray['location'] = $this->location;
    $readArray['address'] = $this->address;
    $readArray['seats_number'] = $this->seats_number; 
    $readArray['rent_price'] = $this->rent_price;

    return $readArray;
  }

  public function update($location, $address, $seats_number, $rent_price) : int
  {
    if($this->id_place == 0)
      return ERROR_places_update_id_notexist;

    if(strlen($location) > MAX_LEN_places_location)
      return ERROR_places_update_location_toolong;
    elseif(strlen($address) > MAX_LEN_places_address)
      return ERROR_places_update_address_toolong;

    if(!is_int($seats_number))
      return ERROR_places_update_seats_number_notint;
    elseif(!is_numeric($rent_price))
      return ERROR_places_update_rent_price_notfloat;

    $rent_price = ceil($rent_price*100.0)/100.0;

    $query = ('UPDATE places SET ');
    $params1 = '';
    $a_params = array();
    $a_params[] = & $params1;

    if(!empty($location) || !is_null($location))
    {
      $query .= 'location=?, ';
      $params1 .= 's';
      $a_params[] = & $location;
    }

    if(!empty($address) || !is_null($address))
    {
      $query .= 'address=?, ';
      $params1 .= 's';
      $a_params[] = & $address;
    }

    if(!empty($seats_number) || !is_null($seats_number))
    {
      $query .= 'participants_limit=?, ';
      $params1 .= 'i';
      $a_params[] = & $seats_number;
    }

    if(!empty($rent_price) || !is_null($rent_price))
    {
      $query .= 'rent_price=?, ';
      $params1 .= 'd';
      $a_params[] = & $rent_price;
    }

    if(empty($query))
      return ERROR_places_update_noentry;

    $query = substr($query, 0, strlen($query)-2);
    $query .= ' WHERE id_place=? LIMIT 1;';
    $params1 .= 'i';

    $stmt = $this->link->prepare($query);
    $id_place = $this->id_place;
    $a_params[] = & $id_place;

    call_user_func_array(array($stmt, 'bind_param'), $a_params);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_places_update_no_change;
    }
    $stmt->close();

    return 0;
  }

  public function delete() : int
  {
    if($this->id_place == 0)
      return ERROR_places_delete_id_notexist;

    $query = 'DELETE FROM places WHERE id_place=? LIMIT 1;';
    $stmt = $this->link->prepare($query);
    $id = $this->id_place;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_places_delete_entry_notexist;
    }
    $stmt->close();

    return 0;
  }

  public static function countPlaces(mysqli $link)
  {
    if(is_null($link))
      return 0;

    $query = 'SELECT COUNT(*) FROM places;';
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

  public static function loadPlaces(mysqli $link, $page)
  {
    if($page == 0)
      return 0;

    $places = array();
    $places_id = array();
    $lower_limit = ($page-1)*10;
    $upper_limit = 10;

    $query = 'SELECT id_place FROM places LIMIT ? , ?;';
    $stmt = $link->prepare($query);
    $stmt->bind_param('ii', $lower_limit, $upper_limit);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id_place);
    while($stmt->fetch())
      $places_id[] = $id_place;

    if(count($places_id) == 0)
      return 0;

    for($i = 0; $i < count($places_id); $i++)
      $places[] = new places($link, $places_id[$i]);

    return $places;
  }
  
}

//include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');
//echo places::create($cfg_mainLink, 'x', 'x', 100, 10.0);

?>