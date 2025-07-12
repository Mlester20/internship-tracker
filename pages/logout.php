<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #007bff 0%, #00c6ff 100%);
        }
        .logout-animation {
            text-align: center;
            color: #fff;
        }
        .logout-loader {
            position: relative;
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
        }
        .logout-loader .circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 6px solid rgba(255,255,255,0.2);
            border-top: 6px solid #fff;
            animation: spin 1.2s linear infinite;
        }
        .logout-loader .checkmark {
            position: absolute;
            top: 18px;
            left: 18px;
            width: 34px;
            height: 34px;
            opacity: 0;
            stroke: #28d17c;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            animation: checkmark 0.5s 1.2s forwards;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        @keyframes checkmark {
            to { opacity: 1; }
        }
        .logout-text {
            font-size: 1.5rem;
            font-weight: 500;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .logout-subtext {
            font-size: 1rem;
            opacity: 0.85;
        }
    </style>
</head>
<body>
    <div class="logout-animation">
        <div class="logout-loader">
            <div class="circle"></div>
            <svg class="checkmark" viewBox="0 0 34 34">
                <polyline points="8,18 15,25 26,12" />
            </svg>
        </div>
        <div class="logout-text">Logging out...</div>
        <div class="logout-subtext">Thank you for using the system!</div>
    </div>

    <script>
        // Show checkmark after 1.2s, then redirect after 2s
        setTimeout(() => {
            document.querySelector('.logout-text').textContent = 'Logged out!';
        }, 1200);
        setTimeout(() => {
            window.location.href = '../index.php';
        }, 2000);
    </script>