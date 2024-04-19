<?php
require('connection.php');
session_start();

if (isset($_POST['login'])) {
   $emailUsername = mysqli_real_escape_string($con, $_POST['email_username']);
   $query = "SELECT * FROM `registered_users` WHERE `email`='$emailUsername' OR `name`='$emailUsername' ";
   $result = mysqli_query($con, $query);
 
   if ($result && mysqli_num_rows($result) == 1) {
     $resultFetch = mysqli_fetch_assoc($result);
 
     if ($resultFetch['password'] == $_POST['password']) {
       $_SESSION['logged_in'] = true;
       $_SESSION['name'] = $resultFetch['name'];
       header("location: index.php");
       exit;
     } else {
       echo "<script>alert('Incorrect Password.'); window.location.href='index.php';</script>";
       exit;
     }
   } else {
     echo "<script>alert('Cannot Find User.'); window.location.href='index.php';</script>";
     exit;
   }
}


if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];

    $userExistQuery = "SELECT * FROM `registered_users` WHERE `name`='$name' OR `email`='$email'";
    $result = mysqli_query($con, $userExistQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $resultFetch = mysqli_fetch_assoc($result);
        if ($resultFetch['name'] == $name) {
            echo "<script>alert('$name - User already taken'); window.location.href='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('$email - Email already taken'); window.location.href='index.php';</script>";
            exit;
        }
    } 
    else {
        $query = "INSERT INTO `registered_users` (`name`, `email`, `password`) VALUES ('$name','$email','$password')";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Registration Successful.'); window.location.href='index.php';</script>";
            exit;
        } else {
            die("Error: " . mysqli_error($con));
        }
    }
}
?>
