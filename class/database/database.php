<?php

    // connect Exceptions
    define("ERROR_database_connect_noconnection", 'Couldn\'t connect');

    class database
    {
        private string $hostname;
        private string $dbname;

        public function __construct(string $host, string $db)
        {
            $this->hostname = $host;
            $this->dbname = $db;
        }

        public function connect(string $user, string $pass) : mysqli
        {
            $mysqli = new mysqli($this->hostname, $user, $pass, $this->dbname);
            if ($mysqli->connect_errno) {
                throw new RuntimeException('database::connect new mysqli for parameters: '.$this->hostname.' '.$user.
                ' '.$pass.' '.$this->dbname.'\r\n'.ERROR_database_connect_noconnection.'\r\n'.$mysqli->connect_error);
            }
            return $mysqli;
        }

        public function getInfo() : string
        {
            return 'Database: '.$this->dbname.' at: '.$this->hostname;
        }
    }

?>