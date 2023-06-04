<?php

$dbname = 'small';
$host = 'localhost';
$port = 3307;
$dsn = "mysql:dbname={$dbname};host={$host};port={$port}";
$username = 'root';
$password = '';

$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$database = new PDO($dsn, $username, $password, $options);