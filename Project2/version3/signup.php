<?php
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
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $country = trim(htmlspecialchars($_POST['country']));
    $password = trim(htmlspecialchars($_POST['password']));

    if (!empty($name) && !empty($email) && !empty($country) && !empty($password)) {
        try {
            // Check if email already exists
            $result = $client->getItem([
                'TableName' => 'GuestList',
                'Key' => [
                    'Email' => ['S' => $email]
                ]
            ]);

            if (isset($result['Item'])) {
                showError('Email already in use.');
            } else {
                // Add new user
                $client->putItem([
                    'TableName' => 'GuestList',
                    'Item' => [
                        'Email' => ['S' => $email],
                        'Name' => ['S' => $name],
                        'Country' => ['S' => $country],
                        'Password' => ['S' => $password]
                    ]
                ]);
                echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azubi Sign-Up Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333333;
        }
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign-Up successful</h2>
        <div class="success-message">
            <p>Welcome, <strong>' . htmlspecialchars($name) . '</strong>! Please <a href="index.html">login</a>.</p>
        </div>
    </div>
</body>
</html>';
            }
        } catch (AwsException $e) {
            echo "Unable to sign up: " . $e->getMessage();
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
    <title>Azubi Sign-Up Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333333;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="error-message">' . htmlspecialchars($message) . '</p>
        <form action="signup.php" method="post">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Sign Up</button>
            </div>
        </form>
    </div>
</body>
</html>';
}
?>
