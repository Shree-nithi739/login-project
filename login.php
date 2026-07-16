<?php

session_start();

require_once "config/db_mysql.php";
require_once "config/db_mongo.php";
require_once "config/redis.php";

// Allow only POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request");
}

// Get Login Data
$email = trim($_POST["email"]);
$password = $_POST["password"];

// Validate
if (empty($email) || empty($password)) {
    die("Email and Password are required.");
}

// Check Email in MySQL
$stmt = $conn->prepare(
    "SELECT id, email, password FROM users_auth WHERE email=?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid Email or Password.");
}

$user = $result->fetch_assoc();

// Verify Password
if (!password_verify($password, $user["password"])) {
    die("Invalid Email or Password.");
}

// Fetch User Details From MongoDB
$profile = $collection->findOne([
    "email" => $email
]);

if (!$profile) {
    die("User profile not found in MongoDB.");
}

// Generate Login Token
$token = bin2hex(random_bytes(32));

// Store Token In Redis (1 hour expiry)
$redis->setex(
    "token:" . $token,
    3600,
    $email
);

// Create PHP Session
$_SESSION["logged_in"] = true;
$_SESSION["email"] = $email;
$_SESSION["token"] = $token;
$_SESSION["username"] = $profile["username"];
$_SESSION["profile"] = $profile["profile"];

// Redirect
header("Location: dashboard.php");
exit;

?>