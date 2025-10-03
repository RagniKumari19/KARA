<?php
include_once("db.php");

$username = $_POST["username"];
$email = $_POST["email"];
$password = md5($_POST["password"]); 

$profile_Img = $_FILES["profile_Img"]["name"];
$tmp_name = $_FILES["profile_Img"]["tmp_name"];
$target_dir = "uploads/";
$target_file = $target_dir . basename($profile_Img);

// Check if username already exists
$sql_check_username = "SELECT * FROM user WHERE Username = '$username'";
$result_username = mysqli_query($conn, $sql_check_username);
$count = mysqli_num_rows($result_username);

if ($count == 0) {
  // Move uploaded file
  if (move_uploaded_file($tmp_name, $target_file)) {
    // Insert user data
    $sql = "INSERT INTO user (Sno, Username, Email, Password, Status, profile_Img) 
            VALUES (NULL, '$username', '$email', '$password', '1', '$profile_Img')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
      header("Location: login.php");
      exit();
    } else {
      echo "Registration failed.";
    }
  } else {
    echo "Image upload failed.";
  }
} else {
  echo "Username already exists.";
}
?>