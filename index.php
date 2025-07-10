<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Signup | <?php include 'components/title.php'; ?> </title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
    
   
    <div class="container my-5 flex flex-items-center">
        <form action="controllers/auth.php" method="POST" enctype="multipart/form-data">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
    </div>

    <!-- js scripts -->
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>