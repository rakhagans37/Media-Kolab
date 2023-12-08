<?php
/* Koneksi database dengan metode PDO */
function getConnection()
{
    $server = 'localhost';
    $host = 3306;
    $username = 'kolab';
    $password = 'Kol@b123x';
    $dbName =  'db_medkolab';

    return new PDO("mysql:host=$server;dbname=$dbName", $username, $password);
}
