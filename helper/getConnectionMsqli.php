<?php
/* Koneksi database dengan metode mysqli */
function getConnectionMysqli()
{
    $server = 'localhost';
    $host = 3306;
    $username = 'kolab';
    $password = 'Kol@b123x';
    $dbName =  'db_medkolab';

    return mysqli_connect($server, $username, $password, $dbName);
}
