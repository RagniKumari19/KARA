<?php
// session_start();
include_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

 
    $sql = "SELECT Sno, Username, Password FROM user WHERE Username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die(" Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

 
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();


        if ($row['Password'] === $password) {
            $_SESSION['user_id'] = $row['Sno'];
            header("Location: index.php");
            exit;
        } else {
            echo " Incorrect password.";
        }
    } else {
        echo " User not found.";
    }
}
?>