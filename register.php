<?php

session_start();

// Include MySQL & MongoDB Connection
require_once "config/db_mysql.php";
require_once "config/db_mongo.php";

// Check Form Submission
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request");
}

/* ===========================
   GET FORM VALUES
=========================== */

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$address = trim($_POST['address']);
$dob = $_POST['dob'];
$gender = $_POST['gender'];

/* ===========================
   VALIDATION
=========================== */

if (
    empty($username) ||
    empty($email) ||
    empty($phone) ||
    empty($password) ||
    empty($confirm_password) ||
    empty($address) ||
    empty($dob) ||
    empty($gender)
) {
    die("All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid Email Address.");
}

if ($password != $confirm_password) {
    die("Passwords do not match.");
}

/* ===========================
   PROFILE IMAGE UPLOAD
=========================== */

$profileName = "";

if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] == 0) {

    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $extension = pathinfo(
        $_FILES["profile"]["name"],
        PATHINFO_EXTENSION
    );

    $profileName = time() . "." . $extension;

    $target = $uploadDir . $profileName;

    move_uploaded_file(
        $_FILES["profile"]["tmp_name"],
        $target
    );
}

/* ===========================
   PASSWORD HASH
=========================== */

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

/* ===========================
   CHECK EMAIL EXISTS
=========================== */

$check = $conn->prepare(
    "SELECT id FROM users_auth WHERE email=?"
);

$check->bind_param("s", $email);

$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {

    die("Email already exists.");

}

/* ===========================
   INSERT INTO MYSQL
=========================== */

$stmt = $conn->prepare(
    "INSERT INTO users_auth(email,password)
     VALUES(?,?)"
);

$stmt->bind_param(
    "ss",
    $email,
    $hashedPassword
);

if (!$stmt->execute()) {

    die("MySQL Insert Failed : " . $stmt->error);

}

/* ===========================
   INSERT USER DETAILS INTO MONGODB
=========================== */

try {

    $collection->insertOne([

        "username" => $username,
        "email"    => $email,
        "phone"    => $phone,
        "address"  => $address,
        "dob"      => $dob,
        "gender"   => $gender,
        "profile"  => $profileName,
        "created_at" => date("Y-m-d H:i:s")

    ]);

} catch (Exception $e) {

    // Rollback MySQL insert if MongoDB insert fails
    $delete = $conn->prepare(
        "DELETE FROM users_auth WHERE email=?"
    );

    $delete->bind_param("s", $email);
    $delete->execute();

    die("MongoDB Insert Failed : " . $e->getMessage());

}

/* ===========================
   CLOSE MYSQL
=========================== */

$stmt->close();
$check->close();
$conn->close();

/* ===========================
   SUCCESS
=========================== */

echo "<script>
alert('Registration Successful!');
window.location='login.html';
</script>";

exit;

?>