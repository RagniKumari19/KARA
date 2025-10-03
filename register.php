<?php
include_once("db.php");

if (isset($_SESSION["user"])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
<style>
  body {
    background: linear-gradient(to right, #0d1b2a, #1b263b);
    font-family: 'Segoe UI', sans-serif;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  div {
    background-color: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
    width: 400px;
    backdrop-filter: blur(8px);
  }

  form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #cbd5e1;
  }

  form .input-group {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  form .input-group label {
    width: 120px;
    margin-right: 10px;
    text-align: left;
  }

  form .input-group input {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 6px;
    background-color: #1e2a3a;
    color: white;
  }

  input::placeholder {
    color: #a0aec0;
  }

  button {
    width: 100%;
    padding: 12px;
    background-color: #1f4e79;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
  }

  button:hover {
    background-color: #163d5c;
  }
</style>
</head>
<body>
  <div>
    <form action="register_check.php" method="post" enctype="multipart/form-data">
      <label for="username">Username</label>
      <input type="text" name="username" placeholder="username" required><br><br>

      <label for="email">Email</label>
      <input type="email" name="email" placeholder="email" required><br><br>

      <label for="password">Password</label>
      <input type="password" name="password" placeholder="password" required><br><br>

      <label for="profile_Img">Profile Photo</label>
      <input type="file" name="profile_Img" accept="image/*" required><br><br>

      <button type="submit">Register</button>
    </form>
  </div>
</body>
</html>
