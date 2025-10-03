<?php
include_once("db.php");

//  Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die(" Session expired. Please log in again.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $userId = (int)$_SESSION['user_id']; 

    //  Check if image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);

       
        $safeFileName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $fileName);
        $uniqueName = uniqid("img_", true) . "_" . $safeFileName;

        $uploadDir = 'uploads/';
        $destPath = $uploadDir . $uniqueName;

        //  Create uploads folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        //  Move the uploaded file
        if (move_uploaded_file($fileTmpPath, $destPath)) {

            $sql_post = "INSERT INTO post (Title, Img_link, Status, created_at, user_id) VALUES (?, ?, 1, NOW(), ?)";
            $stmt = $conn->prepare($sql_post);

            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            if (!$stmt->bind_param("ssi", $title, $destPath, $userId)) {
                die(" Bind failed: " . $stmt->error);
            }
            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                echo " Database insert failed: " . $stmt->error;
            }

        } else {
            echo " Error moving the uploaded file.";
        }

    } else {
        echo " No file uploaded or upload error.";
    }
}
?>