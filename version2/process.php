<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    
    // Process the data as needed
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password;
} else {
    echo "Invalid request method.";
}
?>

