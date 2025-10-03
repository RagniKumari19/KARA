<?php

include_once("db.php");
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

// Get profile user ID from URL
$profile_user_id = $_GET['Sno'] ?? null;
if (!$profile_user_id) {
  echo "Invalid profile ID.";
  exit;
}

$sql = "SELECT * FROM user WHERE Sno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $profile_user_id);
$stmt->execute();
$profile_user = $stmt->get_result()->fetch_assoc();

if (!$profile_user) {
  echo "User not found.";
  exit;
}

// Fetch posts by this user
$sql_posts = "SELECT * FROM post WHERE user_id = ? ORDER BY created_at DESC";
$stmt2 = $conn->prepare($sql_posts);
$stmt2->bind_param("i", $profile_user_id);
$stmt2->execute();
$posts = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= ucwords($profile_user['Username']) ?>'s Profile</title>
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="./output.css">
  <style>

  </style>
</head>

<body class="bg-blue-950">


  <div class="bg-blue-900 flex gap-4 p-4 h-20 justify-between items-center" style="padding: 20px">
    <!-- Profile Icon -->
    <div class="w-10 h-10 border-2 border-amber-100 rounded-full overflow-hidden">

      <img src="uploads/<?= $profile_user['profile_Img'] ?? 'default.png' ?>" alt="<?= $profile_user['Username'] ?>" class="w-6 h-4 object-cover">
    </div>
    <div class="p-4">
      <h4 class="text-white text-4xl" style="color: white; font-family: Georgia, 'Times New Roman', Times, serif;"><?= ucwords($profile_user['Username']) ?></h4>
    </div>

    <!-- Chat Button -->

    <div style="background-color: yellow; padding:10px; width:55px" class="p-4 rounded-3xl">
      <a href="chat.php?Sno=<?= $profile_user_id ?>">
        <h2>Chat</h2>
      </a>
    </div>

  </div>

  <!-- Posts Section -->
  <div class="flex flex-wrap justify-center p-4">
    <?php while ($p = $posts->fetch_assoc()) {
      echo '
    <div class="card bg-white rounded shadow-lg m-4" style="width: 18rem;">
      <img class="card-img-top" src="' . $p['Img_link'] . '" alt="Post Image" style="margin-top:12px;height: 200px; object-fit: cover;">
      <div class="card-body p-4 bg-amber-100">
        <h5 class="card-title" style="color: aliceblue;">' . htmlspecialchars($p['Title']) . '</h5>
      </div>
    </div>';
    } ?>
  </div>

</body>

</html>