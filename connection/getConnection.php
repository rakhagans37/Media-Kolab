<?php
function getConnection()
{
    $server = 'localhost';
    $host = 3306;
    $username = 'root';
    $password = '';
    $dbName =  'MediaKolab';

    return new PDO("mysql:host=$server;dbname=$dbName", $username, $password);
}
