<?php
$profileImg = 'default.png'; // fallback image
if (isset($_SESSION['user_id'])) {
    $stmt = $con->prepare('SELECT profile FROM users WHERE user_id = ? LIMIT 1');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($profile);
    if ($stmt->fetch() && $profile) {
        $profileImg = htmlspecialchars($profile);
    }
    $stmt->close();
}
?>

<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container-fluid">
        <img src="../images/final.png" alt="" class="me-3" style="height: 50px;">
        <a class="navbar-brand" id="navbarTitle" href="home.php">
            Internship Daily Time Records Tracker
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="home.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="dailyLogs.php">
                        <i class="fas fa-clock"></i> Daily Logs
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <!-- <img src="<?php echo $profileImg; ?>" alt="Profile" class="rounded-circle me-2" style="height:40px;width:40px;"> -->
                        <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>
                        
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><a class="dropdown-item" href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function updateNavbarTitle() {
        const navbarTitle = document.getElementById('navbarTitle');
        if (window.innerWidth <= 768) {
            navbarTitle.textContent = 'Internship Tracker'; 
        } else {
            navbarTitle.textContent = 'Internship Daily Time Records Tracker'; 
        }
    }
    updateNavbarTitle();

    window.addEventListener('resize', updateNavbarTitle);
</script>