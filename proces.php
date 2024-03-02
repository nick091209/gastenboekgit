<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gastenboek</title>
        <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
        </head>
        </html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    echo "<script>
          swal({
            title: 'Error!',
            text: 'je moet inloggen om een bericht te sturen',
            icon: 'error',
            button: 'login'
          }).then(function() {
            window.location.href = 'login.php';
          });
        </script>";
    exit();
}

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $bericht = $_POST["bericht"];
    $photo = $_FILES["photo"]["name"];

    if (!empty($photo)) {
        $targetDir = "uploads/";
        $fileName = uniqid() . '_' . basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["photo"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, je file is niet geupload.";
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                echo "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . "is geupload.";

                $sql = "INSERT INTO berichten (naam, bericht, photo) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $bericht, $fileName);

                if ($stmt->execute()) {
                    header("Location: berichten.php");
                    
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $sql = "INSERT INTO berichten (naam, bericht) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $bericht);
        if ($stmt->execute()) {
            header("Location: berichten.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
