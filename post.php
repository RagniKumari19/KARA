<?php
include_once("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
</head>
<body>
   <form action="upload.php" method="POST" enctype="multipart/form-data">
  <label for="image">Select a photo:</label>
  <input type="file" name="image" id="image" accept="image/*" required>
<br><br>
 <label for="title">Title:</label>
  <input type="text" name="title" id="title" required><br><br>
  <button type="submit">Upload</button>
</form>
</body>
</html>