<?php

    interface Authorization
    {
        public static function create(mysqli $link, $firstName, $surname, $e_mail, $telephone, $address, $pass) : int;
        public static function uniqueMail(mysqli $link, $e_mail) : int;
        public static function signIn(mysqli $link, string $login, string $pass) : int;
        public function signOut() : int;
        public static function resetPass(mysqli $link, $e_mail, $webTitle) : int;
        public function checkPass($pass) : int;
    }
?>