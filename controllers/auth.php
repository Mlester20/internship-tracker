<?php
session_start();
include '../components/config.php';

    //function to check users credentials
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = trim($_POST['email']);
        $password = md5(trim($_POST['password']));

        //perform query
        $query = "SELECT user_id, name, course, role, profile, internship_hours FROM users WHERE email = ? AND password = ? ";
        $stmt = mysqli_prepare($con, $query);

        if($stmt){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
            mysqli_stmt_execute($stmt);
            //store data to result variable
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['course'] = $row['course'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['internship_hours'] = $row['internship_hours'];
                $_SESSION['profile'] = $user['profile'];
                
                //redirect based on role column
                if($row['role'] === 'admin'){
                    header('location: ../admin/home.php ');
                }else{  
                    header('location: ../pages/home.php');
                }
                exit();
            }else{
                echo "<script type='text/javascript'>alert('Invalid Username or Password!');
                document.location='../index.php'</script>";  
            }
            mysqli_stmt_close($stmt);
        }else{
            echo "<script>alert('Database error. Please try again later.');</script>";
        }
    }

?>