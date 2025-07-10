<?php
include '../components/config.php';

$uploadDir = "../uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $course = $con->real_escape_string($_POST['course']);
    $email = $con->real_escape_string($_POST['email']);
    $password = $con->real_escape_string(md5($_POST['password']));
    $role = 'user'; 

    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $fileType = $_FILES['profile']['type'];

        if (in_array($fileType, $allowedTypes)) {
            // Generate unique file name to avoid overwrite
            $ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
            $uniqueName = uniqid('profile_', true) . '.' . $ext;
            $targetPath = $uploadDir . $uniqueName;

            if (move_uploaded_file($_FILES['profile']['tmp_name'], $targetPath)) {
                $dbProfilePath = '../uploads/' . $uniqueName;

                $sql = "INSERT INTO users (name, course, profile, email, password, role)
                        VALUES ('$name', '$course', '$dbProfilePath', '$email', '$password', '$role')";

                if ($con->query($sql) === TRUE) {
                    echo "User registered successfully.";
                    header('location: ../index.php');
                } else {
                    echo "Database Error: " . $con->error;
                }
            } else {
                echo "Failed to upload profile image.";
            }
        } else {
            echo "Invalid image format. Allowed: JPG, JPEG, PNG, GIF.";
        }
    } else {
        echo "No profile image uploaded or file error.";
    }
}

$con->close();
?>
