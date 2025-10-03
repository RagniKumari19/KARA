<?php
include_once("db.php");

if (!isset($_SESSION["user_id"])) {
  header("Location:login.php");
  exit;
}
$current_user_id = $_SESSION['user_id'];
// $currUser = "SELECT Username FROM user WHERE Sno = [user_id]";
$sql = "SELECT * FROM user WHERE Sno !=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$users = $stmt->get_result();


$sql_user = "SELECT Username FROM user WHERE Sno = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $current_user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$logged_in_user = $result_user->fetch_assoc();
//for posts
$sql3 = "SELECT * FROM post WHERE user_id =? ORDER BY created_at DESC";

$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $current_user_id);
$stmt3->execute();
$my_posts = $stmt3->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./output.css">
</head>

<body class="bg-blue-900 ">
  <div>
    <div class="flex items-center gap-3 bg-blue-600">
      <?php
      while ($u = $users->fetch_assoc()) {
        $hasPhoto = !empty($u['profile_Img']);
        echo '
      <div class="flex flex-col items-center">
        <div class="w-20 h-20 border-2 border-b-blue-400 rounded-2xl overflow-hidden">
          <a href="profile.php?Sno=' . $u["Sno"] . '">
            <img src="uploads/' . ($hasPhoto ? $u['profile_Img'] : 'default.png') . '" alt="' . '" class="w-full h-full object-cover">
          </a>
        </div>';

        if ($hasPhoto) {
          echo '<p class="text-white mt-2 text-sm text-center">' . $u['Username'] . '</p>';
        }

        echo '</div>';
      }
      ?>
    </div>
  </div>
</body>

</html>