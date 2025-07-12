<?php
session_start();
include '../components/config.php';

//check if user is logged in
if(!isset($_SESSION['user_id'])){
    header('location: ../index.php ');
}

//store session into variable
$user_id = $_SESSION['user_id'];

//perform query
$query = "SELECT name, course, profile, email, internship_hours FROM users WHERE user_id = ? ";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$update_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    $new_course = trim($_POST['course']);
    $new_email = trim($_POST['email']);
    $new_internship_hours = intval($_POST['internship_hours']);
    $new_password = !empty($_POST['new_password']) ? md5($_POST['new_password']) : '';
    $confirm_password = !empty($_POST['confirm_password']) ? md5($_POST['confirm_password']) : '';
    $current_password = md5($_POST['current_password']);

    // Fetch current password hash
    $stmt = $con->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password_hash);
    $stmt->fetch();
    $stmt->close();

    // Verify current password using MD5
    if ($current_password !== $db_password_hash) {
        $update_msg = '<div class="alert alert-danger">Incorrect current password.</div>';
    } else {
        // If new password is set, check confirmation
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                $update_msg = '<div class="alert alert-danger">New passwords do not match.</div>';
            } else {
                $stmt = $con->prepare("UPDATE users SET name=?, course=?, email=?, internship_hours=?, password=? WHERE user_id=?");
                $stmt->bind_param("sssisi", $new_name, $new_course, $new_email, $new_internship_hours, $new_password, $user_id);
            }
        } else {
            $stmt = $con->prepare("UPDATE users SET name=?, course=?, email=?, internship_hours=? WHERE user_id=?");
            $stmt->bind_param("sssii", $new_name, $new_course, $new_email, $new_internship_hours, $user_id);
        }

        if ($stmt->execute()) {
            $update_msg = '<div class="alert alert-success">Profile updated successfully.</div>';
            // Refresh user data
            $query = "SELECT name, course, profile, email, internship_hours FROM users WHERE user_id = ? ";
            $stmt2 = $con->prepare($query);
            $stmt2->bind_param("i", $user_id);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $user = $result->fetch_assoc();
            $stmt2->close();
        } else {
            $update_msg = '<div class="alert alert-danger">Failed to update profile.</div>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | <?php include '../components/title.php'; ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

    <?php include '../components/header.php' ?>
    <div class="container mt-5">
        <h3 class="text-center text-muted">Update Profile</h3>
        <?php if (!empty($update_msg)) echo $update_msg; ?>
        <form method="POST" class="mt-4" autocomplete="off">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($user['course']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Internship Hours</label>
                    <input type="number" name="internship_hours" class="form-control" value="<?php echo htmlspecialchars($user['internship_hours']); ?>" min="0" required>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" name="new_password" class="form-control" autocomplete="new-password">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" autocomplete="new-password">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
                <div class="form-text">Enter your current password to confirm changes.</div>
            </div>

            <div class="text-end">
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>

    <!-- external js scripts -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>