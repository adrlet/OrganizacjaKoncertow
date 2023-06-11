<?php

// SQL Table Requirements
define("MAX_LEN_performers_nickname", 50);
define("MAX_LEN_performers_music_genre", 50);

// constructor Exceptions
define('ERROR_performers_construct_entry_notexist', 'Record doesn\'t exists.');
define('ERROR_performers_construct_link_isnull', 'Pased link to db is null');

// create Error Codes
define("ERROR_performers_create_nickname_toolong", 1);
define("ERROR_performers_create_music_genre_toolong", 2);

define("ERROR_performers_create_nickname_empty", 4);
define("ERROR_performers_create_music_genre_empty", 5);
define("ERROR_performers_create_price_empty", 6);
  
define("ERROR_performers_create_failed", 13);
define('ERROR_performers_create_link_isnull', 14);

define("ERROR_performers_create_performers_price_notfloat", 17);

// read Error Codes
define("ERROR_performers_read_id_notexist", 16);

// update Error Codes
define("ERROR_performers_update_nickname_toolong", 1);
define("ERROR_performers_update_music_genre_toolong", 2);

define("ERROR_performers_update_price_notfloat", 5);

define("ERROR_performers_update_noentry", 13);
define("ERROR_performers_update_id_notexist", 14);
define("ERROR_performers_update_no_change", 15);

// delete Error Codes
define("ERROR_performers_delete_id_notexist", 14);
define("ERROR_performers_delete_entry_notexist", 15);

class performers
{
  private int $id_performer;
  private string $nickname;
  private string $music_genre;
  private float $price;

  private mysqli $link;

  public function __construct(mysqli $link, $id)
  {
    if(is_null($link))
      throw new RuntimeException('client::__construct $link is null'.
      '\r\n'.ERROR_performers_construct_entry_notexist);

    $query = 'SELECT * FROM performers WHERE id_performer=? LIMIT 1';
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows == 0)
    {
      $stmt->close();
      throw new RuntimeException('client::__construct $stmt->fetch() for query: "'.$query.
      '" id_client='.$id.'\r\n'.ERROR_performers_construct_link_isnull);
    }
    $stmt->bind_result($id_performer, $nickname, $music_genre, $price);
    $result = $stmt->fetch();
    $stmt->close();

    $this->id_performer = $id_performer;
    $this->nickname = $nickname;
    $this->music_genre = $music_genre;
    $this->price = $price;

    $this->link = $link;
  }

  public static function create(mysqli $link, $nickname, $music_genre, $price)
  {
    if(is_null($link))
      return ERROR_performers_create_link_isnull;

    if(empty($nickname) && is_null($nickname))
      return ERROR_performers_create_nickname_empty;
    elseif(empty($music_genre) && is_null($music_genre))
      return ERROR_performers_create_music_genre_empty;
    elseif(empty($price) && is_null($price))
      return ERROR_performers_create_price_empty;
    
    if(!is_int($price))
      return  ERROR_performers_create_pricer_notint;

    if(strlen($nickname) > MAX_LEN_performers_nickname)
      return ERROR_performers_create_nickname_toolong;
    elseif(strlen($music_genre) > MAX_LEN_performers_music_genre)
      return ERROR_performers_create_music_genre_toolong;

    $price = ceil($price*100.0)/100.0;
      
    $query = 'INSERT INTO performers(nickname, music_genre, price) VALUES(?, ?, ?);';
    $stmt = $link->prepare($query);
    $stmt->bind_param('ssd', $nickname, $music_genre, $price);
    $stmt->execute();
    if($stmt->affected_rows < 1)
    {
      $stmt->close();
      return ERROR_performers_create_failed;
    }
    $stmt->close();

    return 0;
  }

  public function read()
  {
    if($this->id_performer == 0)
      return ERROR_performers_read_id_notexist;

    $readArray['id_performer'] = $this->id_performer; 
    $readArray['nickname'] = $this->nickname;
    $readArray['music_genre'] = $this->music_genre;
    $readArray['price'] = $this->price; 

    return $readArray;
  }

  public function update($nickname, $music_genre, $price) : int
  {
    if($this->id_performer == 0)
      return ERROR_performers_update_id_notexist;

    if(strlen($nickname) > MAX_LEN_performers_nickname)
      return ERROR_performers_update_nickname_toolong;
    elseif(strlen($music_genre) > MAX_LEN_performers_music_genre)
      return ERROR_performers_update_music_genre_toolong;

    if(!is_numeric($price))
      return ERROR_performers_update_price_notfloat;

    $price = ceil($price*100.0)/100.0;

    $query = ('UPDATE performers SET ');
    $params1 = '';
    $a_params = array();
    $a_params[] = & $params1;

    if(!empty($nickname) || !is_null($nickname))
    {
      $query .= 'nickname=?, ';
      $params1 .= 's';
      $a_params[] = & $nickname;
    }

    if(!empty($music_genre) || !is_null($music_genre))
    {
      $query .= 'music_genre=?, ';
      $params1 .= 's';
      $a_params[] = & $music_genre;
    }

    if(!empty($price) || !is_null($price))
    {
      $query .= 'price=?, ';
      $params1 .= 'd';
      $a_params[] = & $price;
    }

    if(empty($query))
      return ERROR_contract_performer_update_noentry;

    $query = substr($query, 0, strlen($query)-2);
    $query .= ' WHERE id_performer=? LIMIT 1;';
    $params1 .= 'i';

    $stmt = $this->link->prepare($query);
    $id_performer = $this->id_performer;
    $a_params[] = & $id_performer;

    call_user_func_array(array($stmt, 'bind_param'), $a_params);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_performers_update_no_change;
    }
    $stmt->close();

    return 0;
  }

  public function delete() : int
  {
    if($this->id_performer == 0)
      return ERROR_performers_delete_id_notexist;

    $query = 'DELETE FROM performers WHERE id_performer=? LIMIT 1;';
    $stmt = $this->link->prepare($query);
    $id = $this->id_performer;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if($stmt->affected_rows == 0)
    {
      $stmt->close();
      return ERROR_performers_delete_entry_notexist;
    }
    $stmt->close();

    return 0;
  }

  public static function countPerformers(mysqli $link)
  {
    if(is_null($link))
      return 0;

    $query = 'SELECT COUNT(*) FROM performers;';
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

  public static function loadPerformers(mysqli $link, $page)
  {
    if($page == 0)
      return 0;

    $performers = array();
    $performers_id = array();
    $lower_limit = ($page-1)*10;
    $upper_limit = 10;

    $query = 'SELECT id_performer FROM performers LIMIT ? , ?;';
    $stmt = $link->prepare($query);
    $stmt->bind_param('ii', $lower_limit, $upper_limit);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id_performer);
    while($stmt->fetch())
      $performers_id[] = $id_performer;

    if(count($performers_id) == 0)
      return 0;

    for($i = 0; $i < count($performers_id); $i++)
      $performers[] = new performers($link, $performers_id[$i]);

    return $performers;
  }
}

//include($_SERVER['DOCUMENT_ROOT'].'/test/cfg/cfg.php');
//echo performers::create($cfg_mainLink, 'x', 'x', 100);

?>