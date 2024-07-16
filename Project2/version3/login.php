<?php
session_start();
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

$client = new DynamoDbClient([
    'region' => 'us-east-1',
    'version' => 'latest',
    'credentials' => [
        'key'    => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
    ]
]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = trim(htmlspecialchars($_POST['password']));

    if (!empty($username) && !empty($password)) {
        try {
            $result = $client->getItem([
                'TableName' => 'GuestList',
                'Key' => [
                    'Email' => ['S' => $username]
                ]
            ]);

            if (isset($result['Item']) && $result['Item']['Password']['S'] === $password) {
                $_SESSION['username'] = $username;
                header('Location: welcome.html');
                exit;
            } else {
                showError('Invalid username or password.');
            }
        } catch (AwsException $e) {
            showError('Unable to login: ' . $e->getMessage());
        }
    } else {
        showError('All fields are required.');
    }
}

function showError($message) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azubi Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Azubi Login Form</h2>
        <p class="error-message">' . htmlspecialchars($message) . '</p>
        <form action="login.php" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
        </form>
        <button onclick="location.href=\'signup.html\'" style="margin-top: 10px;">Sign Up</button>
    </div>
</body>
</html>';
}
?>
