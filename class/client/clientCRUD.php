<?php

    interface CRUD
    {
        public static function create(mysqli $link, $firstName, $surname, $e_mail, $telephone, $address, $pass) : int;
        public function read() : array;
        public function update($firstName, $surname, $e_mail, $telephone, $address, $pass) : int;
        public function delete() : int;
    }

?>