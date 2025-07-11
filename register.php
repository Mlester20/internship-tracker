<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | <?php include 'components/title.php'; ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
    
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 600px;">
            <h3 class="text-center mb-4 text-muted">Create an Account</h3>
            
            <form action="controllers/register.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="course" class="form-label">Course</label>
                        <input type="text" name="course" class="form-control" id="course" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>

                    <div class="cold-md-6 mb-3">
                        <label for="Total Hours" class="form-label">Internship Total Hours</label>
                        <input type="text" name="total_hours" class="form-control" id="total_hours" required>                        
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="profile" class="form-label">Profile Picture</label>
                        <input type="file" name="profile" class="form-control" id="profile" accept="image/*" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Already have an account? <a href="index.php">Login</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>
