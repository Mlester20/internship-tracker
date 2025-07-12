<?php
session_start();
include '../components/config.php';

//check if user is logged in
if(!isset($_SESSION['user_id'])){
    header('location: ../index.php');
    exit();
}

//store the session id into variable
$user_id = $_SESSION['user_id'];
$target_hours = 0;

// Get target hours from database
$stmt = $con->prepare('SELECT internship_hours FROM users WHERE user_id = ? LIMIT 1');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($target_hours);
$stmt->fetch();
$stmt->close();

// Calculate completed hours dynamically from time_out table
$completed_hours = 0;
$stmt = $con->prepare('SELECT time_in, time_out FROM time_out WHERE user_id = ? AND time_out IS NOT NULL');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $in = strtotime($row['time_in']);
    $out = strtotime($row['time_out']);
    if ($out > $in) {
        $completed_hours += round(($out - $in) / 3600, 2); // hours with 2 decimals
    }
}
$stmt->close();

$remaining_hours = max(0, $target_hours - $completed_hours);
$completion_percentage = $target_hours > 0 ? round(($completed_hours / $target_hours) * 100, 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Home | <?php include '../components/title.php'; ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    
    <?php include '../components/header.php'; ?>
    
<div class="container mt-5">
    <!-- Stats Cards Row -->
    <div class="row g-4 justify-content-center mb-4">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted"><i class="fas fa-target"></i> Targeted Hours</h6>
                    <p class="h3 fw-bold text-primary mb-0"><?php echo $target_hours; ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted"><i class="fas fa-check-circle"></i> Completed Hours</h6>
                    <p class="h3 fw-bold text-success mb-0"><?php echo $completed_hours; ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted"><i class="fas fa-clock"></i> Remaining Hours</h6>
                    <p class="h3 fw-bold text-warning mb-0"><?php echo $remaining_hours; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Charts Row -->
    <div class="row g-4">
        <!-- Bar Chart -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Progress Overview</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 350px;">
                        <canvas id="barChart"></canvas>
                    </div>
                    <div class="text-center mt-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="h6 text-muted mb-1">Completed</div>
                                <div class="h4 fw-bold text-primary"><?php echo $completed_hours; ?>h</div>
                            </div>
                            <div class="col-6">
                                <div class="h6 text-muted mb-1">Remaining</div>
                                <div class="h4 fw-bold text-black"><?php echo $remaining_hours; ?>h</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-lg-6 col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Completion Rate</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 280px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="text-center mt-3">
                        <div class="h6 text-muted">Overall Progress</div>
                        <div class="display-6 fw-bold text-primary"><?php echo $completion_percentage; ?>%</div>
                        <div class="mt-2">
                            <div class="progress" style="height: 15px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: <?php echo $completion_percentage; ?>%"
                                     aria-valuenow="<?php echo $completion_percentage; ?>"
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <?php if ($completion_percentage >= 100): ?>
                            <div class="mt-2">
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-trophy"></i> Completed!
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <script>
        // Bar Chart - Left Side
        const barCtx = document.getElementById('barChart').getContext('2d');
        const completedHours = <?php echo $completed_hours; ?>;
        const remainingHours = <?php echo $remaining_hours; ?>;
        const targetHours = <?php echo $target_hours; ?>;

        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Internship Hours'],
                datasets: [{
                    label: 'Completed Hours',
                    data: [completedHours],
                    backgroundColor: '#28a745',
                    borderColor: '#1e7e34',
                    borderWidth: 2
                }, {
                    label: 'Remaining Hours',
                    data: [remainingHours],
                    backgroundColor: '#ffc107',
                    borderColor: '#e0a800',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;
                                const percentage = targetHours > 0 ? ((value / targetHours) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' hours (' + percentage + '%)';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + 'h';
                            }
                        }
                    }
                }
            }
        });

        // Pie Chart - Right Side
        const pieCtx = document.getElementById('pieChart').getContext('2d');

        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed Hours', 'Remaining Hours'],
                datasets: [{
                    data: [completedHours, remainingHours],
                    backgroundColor: [
                        '#28a745', // Green for completed
                        '#ffc107'  // Yellow for remaining
                    ],
                    borderColor: [
                        '#1e7e34',
                        '#e0a800'
                    ],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const percentage = targetHours > 0 ? ((value / targetHours) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' hours (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%',
                elements: {
                    arc: {
                        borderWidth: 0
                    }
                }
            }
        });
    </script>

    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>