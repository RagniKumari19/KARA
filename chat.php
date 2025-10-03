<?php

include_once("db.php");
if (!isset($_SESSION['user_id'])) {
    die(" Session expired. Please log in again.");
}

$current_user_id = (int)$_SESSION['user_id'];
$chat_partner_id = isset($_GET['Sno']) ? (int)$_GET['Sno'] : 0;


//  Fetch logged-in user's name
$sql_me = "SELECT Username FROM user WHERE Sno = ?";
$stmt_me = $conn->prepare($sql_me);
$stmt_me->bind_param("i", $current_user_id);
$stmt_me->execute();
$result_me = $stmt_me->get_result();
$me = $result_me->fetch_assoc();

//  Fetch chat partner's name
$profile_user_id = $_GET['Sno'] ?? null;
if (!$profile_user_id) {
    echo "Invalid profile ID.";
    exit;
}

// Fetch profile user data
$sql = "SELECT * FROM user WHERE Sno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $profile_user_id);
$stmt->execute();
$profile_user = $stmt->get_result()->fetch_assoc();

//  Handle message sending
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== "") {
        $sql_send = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt_send = $conn->prepare($sql_send);
        $stmt_send->bind_param("iis", $current_user_id, $profile_user_id, $message);
        $stmt_send->execute();
    }
}

//  Fetch conversation
$sql_chat = "SELECT * FROM messages 
             WHERE (sender_id = ? AND receiver_id = ?) 
                OR (sender_id = ? AND receiver_id = ?) 
             ORDER BY send_at ASC";

$stmt_chat = $conn->prepare($sql_chat);
$stmt_chat->bind_param("iiii", $current_user_id, $profile_user_id, $profile_user_id, $current_user_id);
$stmt_chat->execute();
$chat = $stmt_chat->get_result(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
  <title>Chat with <?php echo htmlspecialchars($profile_user['Username']); ?></title>
  <style>
    body { font-family: Arial; background: #f0f0f0; padding: 20px; }
    .chat-box { max-width: 700px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
    .chat-container { display: flex; flex-direction: column; gap: 10px; }
    .message { padding: 10px; border-radius: 8px; max-width: 60%; position: relative; }
    .mine { background: #d1e7dd; align-self: flex-end; text-align: right; }
    .theirs { background: #f8d7da; align-self: flex-start; text-align: left; }
    .username { font-weight: bold; font-size: 0.9em; margin-bottom: 5px; display: block; }
    .timestamp { font-size: 0.8em; color: #555; margin-top: 5px; display: block; }
    .form-area { margin-top: 20px; display: flex; gap: 10px; }
    input[type="text"] { flex: 1; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
    input[type="submit"] { padding: 10px 20px; border: none; background: #007bff; color: white; border-radius: 5px; cursor: pointer; }
  </style>
  <script>
  function fetchMessages() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_messages.php?Sno=<?= $profile_user_id ?>", true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        document.querySelector(".chat-container").innerHTML = xhr.responseText;
      }
    };
    xhr.send();
  }

  function sendMessage(event) {
    event.preventDefault();

    const input = document.querySelector('input[name="message"]');
    const message = input.value.trim();
    if (message === "") return;

    // Show message instantly
    const container = document.querySelector(".chat-container");
    const msgHTML = `
      <div class="message mine">
        <span class="username"><?= htmlspecialchars($me['Username']) ?></span>
        <p>${message}</p>
        <span class="timestamp">Now</span>
      </div>
    `;
    container.insertAdjacentHTML("beforeend", msgHTML);
    container.scrollTop = container.scrollHeight;

    input.value = "";

    // Send to server
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "send_message.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        fetchMessages();
      }
    };
    xhr.send("receiver_id=<?= $profile_user_id ?>&message=" + encodeURIComponent(message));
  }

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", sendMessage);
    fetchMessages();
    setInterval(fetchMessages, 2000);
  });
</script>
</head>
<body>
  <div class="chat-box">
    <h2>Chat with <?php echo htmlspecialchars($profile_user['Username']); ?></h2>
    <div class="chat-container">
      <?php while ($msg = $chat->fetch_assoc()) {
        $isMine = $msg['sender_id'] == $current_user_id;
        $username = $isMine ? $me['Username'] : $profile_user['Username'];
        echo "<div class='message " . ($isMine ? "mine" : "theirs") . "'>";
        echo "<span class='username'>" . htmlspecialchars($username) . "</span>";
        echo "<p>" . htmlspecialchars($msg['message']) . "</p>";
    
        echo "</div>";
      } ?>
    </div>

    <form method="POST" class="form-area">
      <input type="text" name="message" placeholder="Type your message..." required>
      <input type="submit" value="Send">
    </form>
  </div>
</body>
</html>