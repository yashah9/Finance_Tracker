<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbname1";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists in the database
    $check_existing_email = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_existing_email);

    if ($result->num_rows > 0) {
        echo "Email already exists. Please use a different email.";
    } else {
        // Insert data into the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        $insert_user_query = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";

        if ($conn->query($insert_user_query) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $insert_user_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
