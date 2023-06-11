<?php

include($_SERVER['DOCUMENT_ROOT'].'/test/class/database/database.php');

$cfg_mainDBName = 'SI';
$cfg_mainDBHost = '127.0.0.1';
$cfg_mainDB = null;
$cfg_mainLink = null;

$cfg_employeeDBname = 'si_pracownicy';
$cfg_employeeDBHost = '127.0.0.1';
$cfg_employeeDB = null;
$cfg_employeeLink = null;

session_start();

$cfg_mainDB = new database($cfg_mainDBHost, $cfg_mainDBName);
try {
    $cfg_mainLink = $cfg_mainDB->connect('root', '');
} catch(RuntimeException $e) {
    echo $e;
}

$cfg_employeeDB = new database($cfg_employeeDBHost, $cfg_employeeDBname);
try {
    $cfg_employeeLink = $cfg_employeeDB->connect('root', '');
} catch(RuntimeException $e) {
    echo $e;
}

?>