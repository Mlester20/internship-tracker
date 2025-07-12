<?php
session_start();
include '../components/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// CREATE: Time In
if (isset($_POST['time_in'])) {
    $date = date('Y-m-d');
    $time_in = date('Y-m-d H:i:s');
    $stmt = $con->prepare("INSERT INTO time_out (user_id, date, time_in) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $date, $time_in);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Time In recorded!</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to record Time In.</div>';
    }
    $stmt->close();
}

// UPDATE: Time Out
if (isset($_POST['time_out'])) {
    $id = intval($_POST['record_id']);
    $time_out = date('Y-m-d H:i:s');
    $stmt = $con->prepare("UPDATE time_out SET time_out=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sii", $time_out, $id, $user_id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Time Out recorded!</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to record Time Out.</div>';
    }
    $stmt->close();
}

// DELETE: Remove a record
if (isset($_POST['delete'])) {
    $id = intval($_POST['record_id']);
    $stmt = $con->prepare("DELETE FROM time_out WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Record deleted!</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to delete record.</div>';
    }
    $stmt->close();
}

// READ: Fetch all records for this user
$records = [];
$stmt = $con->prepare("SELECT * FROM time_out WHERE user_id=? ORDER BY date DESC, time_in DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time in | <?php include '../components/title.php'; ?> </title>
    <link rel="stylesheet" href="../styles/custom.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">    
</head>
<body>

    <?php include '../components/header.php'; ?>

    <div class="container mt-4">
        <h3 class="card-title text-muted text-center">Time In/Out</h3>
        <?php if ($message) echo $message; ?>

        <!-- Time In Button -->
        <form method="POST" class="mb-3">
            <div class="text-end">
                <button type="submit" name="time_in" class="btn btn-success">
                    <i class="fa fa-sign-in-alt"></i> Time In
                </button>
            </div>
        </form>

        <!-- Records Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr><td colspan="4" class="text-center">No records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($records as $rec): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rec['date']); ?></td>
                            <td><?php echo htmlspecialchars($rec['time_in']); ?></td>
                            <td>
                                <?php if ($rec['time_out']): ?>
                                    <?php echo htmlspecialchars($rec['time_out']); ?>
                                <?php else: ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="record_id" value="<?php echo $rec['id']; ?>">
                                        <button type="submit" name="time_out" class="btn btn-warning btn-sm">
                                            <i class="fa fa-sign-out-alt"></i> Time Out
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="record_id" value="<?php echo $rec['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?');">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- external js scripts -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>