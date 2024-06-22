<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
 
    // For demonstration purposes, we'll use hardcoded credentials
    $valid_username = "ayobami";
    $valid_password = "ayobami82";
 
    if ($username == $valid_username && $password == $valid_password) {
        echo '<div style="text-align: center;">
                <h2>Login successful</h2>
                <h4>Welcome, ' . htmlspecialchars($username) . ' to Azubi Africa program!</h4>
              </div>';
    } else {
        echo '<p style="color: red; text-align: center;">Invalid username or password.</p>';
    }
} else {
    echo '<p style="color: red; text-align: center;">Invalid request method.</p>';
}
?>