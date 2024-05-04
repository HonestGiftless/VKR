<?php

$servername = "127.0.0.1";
$username = "xxxvz";
$password = "23142003";
$dbname = "test_diplom";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Error ". $conn->connect_error);
}