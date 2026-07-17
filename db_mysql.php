<?php

$host = "127.0.0.1";
$user = "root";
$password = "";
$port = 3306;

// Connect MySQL Server
$conn = new mysqli($host, $user, $password, "", $port);

if ($conn->connect_error) {
    die("MySQL Connection Failed : " . $conn->connect_error);
}

// Create Database
$sql = "CREATE DATABASE IF NOT EXISTS login_project";

if (!$conn->query($sql)) {
    die("Database Error : " . $conn->error);
}

// Select Database
$conn->select_db("login_project");

// Create users_auth Table
$table = "
CREATE TABLE IF NOT EXISTS users_auth(

    id INT AUTO_INCREMENT PRIMARY KEY,

    email VARCHAR(255) UNIQUE NOT NULL,

    password VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

)";

if (!$conn->query($table)) {
    die("Table Error : " . $conn->error);
}

$conn->set_charset("utf8mb4");

?>