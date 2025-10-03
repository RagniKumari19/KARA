<?php
include_once("db.php");
if (!isset($_SESSION["user_id"])) {
  header("Location:login.php");
  exit;
}
$current_user_id = $_SESSION['user_id'];
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



// Fetch profile user data
$sql = "SELECT * FROM user WHERE Sno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$profile_user = $stmt->get_result()->fetch_assoc();

if (!$profile_user) {
  echo "User not found.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo ucwords($logged_in_user['Username']); ?>'s Profile</title>
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="./output.css">
</head>




<body class="bg-blue-950">
  <div class="bg-blue-900 flex gap-4 p-18 h-20 justify-between" style="">
    <!-- Each child div below will be treated as a horizontal box -->

    <!-- Sidebar / Menu -->
    <label>
      <div class=" p-4">
        <input type="checkbox">
        <div class="toggle">
          <span class="top_line common"></span>
          <span class="middle_line common"></span>
          <span class="bottom_line common"></span>
        </div>
        <div class="slide">
          <div>
             <img src="uploads/<?= $profile_user['profile_Img'] ?? 'default.png' ?>" alt="<?= $profile_user['Username'] ?>" class="w-6 h-4 object-cover">

          </div>
          <ul>
            <!-- <li><a href="profile_dp.php"><i class="fas fa-user"></i>profile</a></li> -->
            <li><a href="post.php"><i class="fas fa-tv"></i>Post</a></li>
            <!-- <li><a href="#"><i class="fas fa-settings"></i>Settings</a></li> -->
            <li><a href="logout.php"><i class="fas fa-error"></i>logout</a></li>
          </ul>
        </div>
      </div>
    </label>
    <!-- Welcome Message -->
    <div class=" p-4 top-5 flex items-center justify-center">
      <h4 id="greet" class="text-white " style="color: white; font-family:Georgia, 'Times New Roman', Times, serif; margin-left:30px;">Welcome <?php echo ucwords($logged_in_user['Username']); ?></h4>
    </div>

    <!-- Profile Icons + Arrow -->
    <div class="flex items-center gap-3">
      <?php
      $count = 0;
      while ($u = $users->fetch_assoc()) {
        echo '
    <div class="w-10 h-10 border-2 border-amber-100 rounded-full overflow-hidden">
      <a href="profile.php?Sno=' . $u["Sno"] . '">'  . '<img src="uploads/' . ($u['profile_Img'] ?? 'default.png') . '" alt="' . $u["Username"] . '" class="w-full h-full object-cover"></a>
    </div>
  ';

        $count++;
        if ($count >= 3) {
          break;
        }
      }
      ?>

      <a href="profilepages.php" class="text-blue-200 text-3xl font-bold hover:text-red-700 transition">
        &gt;
      </a>
    </div>
  </div>
  <!-- posts -->
  <div class="bg-blue-950 ">
    <div class="flex flex-wrap justify-center  ">

      <section class="blog_container flex flex-wrap ">
        <?php
        while ($p = $my_posts->fetch_assoc()) {
          echo "
            <div class='card' style='width: 18rem; margin-left:10px;'>
                <img class='card-img-top' src='" . $p["Img_link"] . "' alt='Card image cap' style='height: 200px; margin:10px'>
                <div class='card-body'>
                    <h5 class='card-title' style='color: aliceblue;'>" . $p["Title"] . "</h5>

                </div>
            </div>
        ";
        }
        ?>
        <?php if ($my_posts->num_rows === 0) {
          echo "<p class='text-white text-lg, flex justify-center align'>You haven't posted anything yet.</p>";
        } ?>

      </section>
    </div>

  </div>


</body>


</html>