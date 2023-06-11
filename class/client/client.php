<?php

    include('clientCRUD.php');
    include('clientAuth.php');

    // SQL Table Requirements
    define("MAX_LEN_client_firstName", 50);
    define("MAX_LEN_client_surname", 50);
    define("MAX_LEN_client_e_mail", 50);
    define("MAX_LEN_client_telephone", 25);
    define("MAX_LEN_client_address", 50);
    define("MAX_LEN_client_pass", 100);

    // constructor Exceptions
    define('ERROR_client_construct_entry_notexist', 'Record doesn\'t exists.');
    define('ERROR_client_construct_link_isnull', 'Pased link to db is null');

    // create Error Codes
    define("ERROR_client_create_firstName_toolong", 1);
    define("ERROR_client_create_surname_toolong", 2);
    define("ERROR_client_create_e_mail_toolong", 3);
    define("ERROR_client_create_telephone_toolong", 4);
    define("ERROR_client_create_address_toolong", 5);
    define("ERROR_client_create_pass_toolong", 6);

    define("ERROR_client_create_firstName_empty", 7);
    define("ERROR_client_create_surname_empty", 8);
    define("ERROR_client_create_e_mail_empty", 9);
    define("ERROR_client_create_pass_empty", 10);

    define("ERROR_client_create_failed", 10);
    //define("ERROR_client_create_e_mail_alreadyused", 18);

    // read Error Codes
    define("ERROR_client_read_id_notexist", 16);

    // update Error Codes
    define("ERROR_client_update_firstName_toolong", 1);
    define("ERROR_client_update_surname_toolong", 2);
    define("ERROR_client_update_e_mail_toolong", 3);
    define("ERROR_client_update_telephone_toolong", 4);
    define("ERROR_client_update_address_toolong", 5);
    define("ERROR_client_update_pass_toolong", 6);

    define("ERROR_client_update_noentry", 13);
    define("ERROR_client_update_id_notexist", 14);
    define("ERROR_client_update_no_change", 15);

    // delete Error Codes
    define("ERROR_client_delete_id_notexist", 14);
    define("ERROR_client_delete_entry_notexist", 15);

    // signIn Error Codes
    define("ERROR_client_signIn_e_mail_toolong", 3);
    define("ERROR_client_signIn_pass_toolong", 6);

    define("ERROR_client_signIn_e_mail_empty", 9);
    define("ERROR_client_signIn_pass_empty", 10);

    define("ERROR_client_signIn_pass_iswrong", 11);
    define("ERROR_client_signIn_login_iswrong", 12);

    // signOut Error Codes
    define("ERROR_client_signOut_alreadysignedout", 16);

    // checkPass Error Codes
    define("ERROR_client_checkPass_id_notexist", 14);
    define("ERROR_client_checkPass_wrong", 17);

    // uniqueMail Error Codes
    define("ERROR_client_uniqueMail_e_mail_alreadyused", 18);
    define("ERROR_client_uniqueMail_e_mail_empty", 9);

    // resetPass Error Codes
    define("ERROR_client_resetpass_e_mail_empty", 9);
    define("ERROR_client_resetpass_e_mail_notexist", 10);

    // loadForm Error Codes
    define("ERROR_client_loadForm_id_notexist", 14);
    define("ERROR_client_loadForm_form_notexist", 15);

    include($_SERVER['DOCUMENT_ROOT'].'/test/class/concert_form/concert_form.php');

    class client implements CRUD, Authorization
    {
        private int $id_client;
        private string $firstName;
        private string $surname;
        private string $e_mail; // login
        private string $telephone;
        private string $address;
        private string $pass; // password

        private mysqli $link; // link to db containing record

        private array $forms;

        public function __construct(mysqli $link, int $id)
        {
            if(is_null($link))
                throw new RuntimeException('client::__construct $link is null'.
                '\r\n'.ERROR_client_construct_link_isnull);

            $query = 'SELECT * FROM clients WHERE id_client=? LIMIT 1;';
            $stmt = $link->prepare($query);
            $stmt->bind_param('i', $id);
            $stmt->execute(); 
            $stmt->store_result();
            if($stmt->num_rows == 0)
            {
                $stmt->close();
                throw new RuntimeException('client::__construct $stmt->fetch() for query: "'.$query.
                '" id_client='.$id.'\r\n'.ERROR_client_construct_entry_notexist);
            }
            $stmt->bind_result($id_client, $firstName, $surname, $e_mail, $telephone, $address, $pass);
            $result = $stmt->fetch();
            $stmt->close();

            $this->id_client = $id_client;
            $this->firstName = $firstName;
            $this->surname = $surname;
            $this->e_mail = is_null($e_mail) ? '' : $e_mail;
            $this->telephone = is_null($telephone) ? '' : $telephone;
            $this->address = is_null($address) ? '' : $address;
            $this->pass = is_null($pass) ? '' : $pass;

            $this->link = $link;
        }

        /*public function __destruct()
        {
            echo $this->firstName.' has been destroyed<br/>';
        }*/

        public static function create(mysqli $link, $firstName, $surname, $e_mail, $telephone, $address, $pass) : int
        {
            if(empty($e_mail))
                $e_mail = NULL;
            if(empty($pass))
                $pass = NULL;
            else
                $hash = password_hash($pass, PASSWORD_DEFAULT);

            if(empty($firstName) && is_null($firstName))
                return ERROR_client_create_firstName_empty;
            elseif(empty($surname) && is_null($surname))
                return ERROR_client_create_surname_empty;
            //elseif(empty($e_mail))
                //return ERROR_client_create_e_mail_empty;
            //elseif(empty($pass))
                //return ERROR_client_create_pass_empty;
                
            if(strlen($firstName) > MAX_LEN_client_firstName)
                return ERROR_client_create_firstName_toolong;
            elseif(strlen($surname) > MAX_LEN_client_surname)
                return ERROR_client_create_surname_toolong;
            elseif(strlen($e_mail) > MAX_LEN_client_e_mail)
                return ERROR_client_create_e_mail_toolong;
            elseif(strlen($telephone) > MAX_LEN_client_telephone)
                return ERROR_client_create_telephone_toolong;
            elseif(strlen($address) > MAX_LEN_client_address)
                return ERROR_client_create_address_toolong;
            elseif(strlen($pass) > MAX_LEN_client_pass)
                return ERROR_client_create_pass_toolong;

            $query = 'INSERT INTO clients(firstName, surname, e_mail, telephone, address, pass) VALUES(?, ?, ?, ?, ?, ?);';
            $stmt = $link->prepare($query);
            $stmt->bind_param('ssssss', $firstName, $surname, $e_mail, $telephone, $address, $hash);
            $stmt->execute();
            if($stmt->affected_rows == 0)
            {
                $stmt->close();
                return ERROR_client_create_failed;
            }
            $stmt->close();

            return 0;
        }

        public function read() : array
        {
            if($this->id_client == 0)
                return ERROR_client_read_id_notexist;

            $readArray['id_client'] = $this->id_client; 
            $readArray['firstName'] = $this->firstName;
            $readArray['surname'] = $this->surname; 
            $readArray['e_mail'] = $this->e_mail; 
            $readArray['telephone'] = $this->telephone; 
            $readArray['address'] = $this->address;
            $readArray['pass'] = $this->pass;

            return $readArray;
        }

        public function update($firstName, $surname, $e_mail, $telephone, $address, $pass) : int
        {
            if($this->id_client == 0)
                return ERROR_client_update_id_notexist;

            if(strlen($firstName) > MAX_LEN_client_firstName)
                return ERROR_client_update_firstName_toolong;
            elseif(strlen($surname) > MAX_LEN_client_surname)
                return ERROR_client_update_surname_toolong;
            elseif(strlen($e_mail) > MAX_LEN_client_e_mail)
                return ERROR_client_update_e_mail_toolong;
            elseif(strlen($telephone) > MAX_LEN_client_telephone)
                return ERROR_client_update_telephone_toolong;
            elseif(strlen($address) > MAX_LEN_client_address)
                return ERROR_client_update_address_toolong;
            elseif(strlen($pass) > MAX_LEN_client_pass)
                return ERROR_client_update_pass_toolong;

            $query = ('UPDATE clients SET ');
            $params1 = '';
            $a_params = array();
            $a_params[] = & $params1;

            if(!empty($firstName) && !is_null($firstName))
            {
                $query .= 'firstName=?, ';
                $params1 .= 's';
                $a_params[] = & $firstName;
            }

            if(!empty($surname) && !is_null($surname))
            {
                $query .= 'surname=?, ';
                $params1 .= 's';
                $a_params[] = & $surname;
            }

            if(!empty($e_mail) || is_null($e_mail))
            {
                $query .= 'e_mail=?, ';
                $params1 .= 's';
                $a_params[] = & $e_mail;
            }

            if(!empty($telephone) || is_null($telephone))
            {
                $query .= 'telephone=?, ';
                $params1 .= 's';
                $a_params[] = & $telephone;
            }

            if(!empty($address) || is_null($address))
            {
                $query .= 'address=?, ';
                $params1 .= 's';
                $a_params[] = & $address;
            }

            if(!empty($pass) || is_null($pass))
            {
                if(!is_null($pass))
                    $hash = password_hash($pass, PASSWORD_DEFAULT);
                else
                    $hash = NULL;
                $query .= 'pass=?, ';
                $params1 .= 's';
                $a_params[] = & $hash;
            }

            if(empty($query))
                return ERROR_client_update_noentry;

            $query = substr($query, 0, strlen($query)-2);
            $query .= ' WHERE id_client=? LIMIT 1;';
            $params1 .= 'i';

            $stmt = $this->link->prepare($query);
            $id_client = $this->id_client;
            $a_params[] = & $id_client;

            call_user_func_array(array($stmt, 'bind_param'), $a_params);
            $stmt->execute();
            if($stmt->affected_rows == 0)
            {
                $stmt->close();
                return ERROR_client_update_no_change;
            }
            $stmt->close();

            return 0;
        }

        public function delete() : int
        {
            if($this->id_client == 0)
                return ERROR_client_delete_id_notexist;

            $query = 'DELETE FROM clients WHERE id_client=? LIMIT 1;';
            $stmt = $this->link->prepare($query);
            $id = $this->id_client;
            $stmt->bind_param('i', $id);
            $stmt->execute();
            if($stmt->affected_rows == 0)
            {
                $stmt->close();
                return ERROR_client_delete_entry_notexist;
            }
            $stmt->close();

            return 0;
        }

        public static function signIn(mysqli $link, string $login, string $pass) : int
        {
            if(strlen($login) > MAX_LEN_client_e_mail)
                return ERROR_client_signIn_e_mail_toolong;
            if(strlen($pass) > MAX_LEN_client_pass)
                return ERROR_client_signIN_pass_toolong;

            if(empty($login) || is_null($login))
                return ERROR_client_signIn_e_mail_empty;
            if(empty($pass) || is_null($login))
                return ERROR_client_signIn_pass_empty;

            $query = 'SELECT id_client, pass FROM clients WHERE e_mail=? LIMIT 1;';
            $stmt = $link->prepare($query);
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $hash);
            $stmt->fetch();
            if($stmt->num_rows == 0)
            {
                $stmt->close();
                return ERROR_client_signIn_login_iswrong;
            }
            $stmt->close();

            if(password_verify($pass, $hash))
            {
                $_SESSION['id_client'] = $id;
                return 0;
            }
            
            return ERROR_client_signIn_pass_iswrong;
        }

        public function signOut() : int
        {
            if(isset($_SESSION['id_client']))
            {
                unset($_SESSION['id_client']);
                return 0;
            }
            return ERROR_client_signOut_alreadysignedout;
        }

        public function checkPass($pass) : int
        {
            if($this->id_client == 0)
                return ERROR_client_checkPass_id_notexist;
            
            if(password_verify($pass, $this->pass))
                return 0;
                    
            return ERROR_client_checkPass_wrong;
        }

        public static function uniqueMail(mysqli $link, $e_mail) : int
        {
            if(empty($e_mail) || is_null($e_mail))
                return ERROR_client_uniqueMail_e_mail_empty;

            $query = 'SELECT e_mail FROM clients WHERE e_mail=? LIMIT 1;';
            $stmt = $link->prepare($query);
            $stmt->bind_param('s', $e_mail);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            if($stmt->num_rows == 1)
            {
                $stmt->close();
                return ERROR_client_uniqueMail_e_mail_alreadyused;
            }
            $stmt->close();

            return 0;
        }

        public static function resetPass(mysqli $link, $e_mail, $webTitle) : int
        {
            $exist = uniqueMail($link, $e_mail);
            if($exist == ERROR_client_uniqueMail_e_mail_alreadyused)
            {
                static $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
                $keyspace_max = strlen($keyspace);

                do {
                    $pass = '';
                    $pass_len = random_int(8, 24);
                    for($i = 0; $i <= $pass_len; $i++)
                        $pass_len .= $keyspace[random_int(0, $keyspace_max)];

                    $pass_valid = preg_match('@[A-Z]@', $pass);
                    $pass_valid = ($pass_valid && preg_match('@[a-z]@', $pass));
                    $pass_valid = ($pass_valid && preg_match('@[0-9]@', $pass));
                    $pass_valid = ($pass_valid && preg_match('@[^\w]@', $pass));
                } while (!$pass_valid);

                $subject = 'Nowe hasło';
                $message = 'Wygenerowano nowe hasło dla twojego konta: '.$pass;
                $headers = 'From: '.substr($webTitle, 0, strlen($webTitle)-3).'@'.$webTitle.'\r\n'.
                           'Reply-To: '.substr($webTitle, 0, strlen($webTitle)-3).'@'.$webTitle.'\r\n'.
                           'X-Mailer: PHP/' . phpversion();

                mail($e_mail, $subject, $message, $headers);

                $query = 'SELECT id_client FROM clients WHERE e_mail=? LIMIT 1;';
                $stmt = $link->prepare($query);
                $stmt->bind_param('s', $e_mail);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id);
                $stmt->fetch();

                $temp = new client($link, $id);
                $temp->update('', '', '', '', '', $pass);
            }

            if($exist == 0)
                return ERROR_client_resetpass_e_mail_notexist;
            return ERROR_client_resetpass_e_mail_empty;
        }

        public function countConcert_form()
        {
            return concert_form::countConcert_form($this->link, $this->id_client);
        }

        public function loadForm($id_concert_form) : int
        {
            if($id_concert_form == 0)
                return ERROR_client_loadForm_id_notexist;

            try {
                $this->forms[] = new concert_form($this->link, $id_concert_form);
            } catch(RuntimeException $e) {
                return ERROR_client_loadForm_form_notexist;
            }

            return 0;
        }

        public function loadForms($page) : int
        {
            if($page == 0)
                return 0;

            $lower_limit = ($page-1)*10;
            $upper_limit = 10;
            $id_client = $this->id_client;
            $loaded = 0;
            $query = 'SELECT id_concert_form FROM concert_form WHERE id_client=? LIMIT ? , ?;';
            $stmt = $this->link->prepare($query);
            $stmt->bind_param('iii', $id_client, $lower_limit, $upper_limit);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id_concert_form);
            while($stmt->fetch())
            {
                $loaded++;
                try {
                    $this->forms[] = new concert_form($this->link, $id_concert_form);
                } catch(RuntimeException $e) {
                    $loaded--;
                }
            }

            return $loaded;
        }

        public function readForms($id)
        {
            if($id > 0)
            {
                if(isset($this->forms[$id]))
                    return $this->forms[$id]->read();
                return array();
            }
            
            $formsInfo = array();
            for($i = 0; $i < count($this->forms); $i++)
                $formsInfo[] = $this->forms[$i]->read();

            return $formsInfo;
        }
        
    }
    
?>