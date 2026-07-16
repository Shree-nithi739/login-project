<?php

session_start();

require_once "config/db_mongo.php";
require_once "config/redis.php";

/* ===========================
   SESSION CHECK
=========================== */

if (!isset($_SESSION["logged_in"])) {

    header("Location: login.html");
    exit();

}

/* ===========================
   REDIS TOKEN CHECK
=========================== */

$token = $_SESSION["token"];

if (!$redis->exists("token:" . $token)) {

    session_destroy();

    header("Location: login.html");
    exit();

}

/* ===========================
   FETCH USER DETAILS
=========================== */

$email = $_SESSION["email"];

$user = $collection->findOne([

    "email" => $email

]);

if (!$user) {

    die("User Not Found");

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="dashboard">

<h1>Welcome <?php echo htmlspecialchars($user["username"]); ?></h1>

<div class="card">

<div>

<img
src="uploads/<?php echo htmlspecialchars($user["profile"]); ?>"
width="180">

</div>

<div class="details">

<h3>Name :
<?php echo htmlspecialchars($user["username"]); ?>
</h3>

<h3>Email :
<?php echo htmlspecialchars($user["email"]); ?>
</h3>

<h3>Phone :
<?php echo htmlspecialchars($user["phone"]); ?>
</h3>

<h3>Address :
<?php echo htmlspecialchars($user["address"]); ?>
</h3>

<h3>DOB :
<?php echo htmlspecialchars($user["dob"]); ?>
</h3>

<h3>Gender :
<?php echo htmlspecialchars($user["gender"]); ?>
</h3>

</div>

</div>

<div class="logout">

<a href="logout.php">

Logout

</a>

</div>

</div>

<script>

/* ===========================
   LOCAL STORAGE TOKEN
=========================== */

localStorage.setItem(
    "token",
    "<?php echo $_SESSION['token']; ?>"
);

</script>

</body>

</html>