<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

$client = new DynamoDbClient([
    'region' => 'us-east-1',  // Adjust the region as needed
    'version' => 'latest' ,
    'credentials' => [
        'key'    => 'AKIAYS2NVKP5OW2AZHGF',
        'secret' => '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',
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
                    'Username' => ['S' => $username]
                ]
            ]);

            if (isset($result['Item'])) {
                $stored_password = $result['Item']['Password']['S'];  // Assuming the attribute name for password is 'Password'
                if ($password === $stored_password) {
                    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azubi Login Form</title>
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
        <h2>Login successful</h2>
        <div class="success-message">
            <p>Welcome, <strong>' . htmlspecialchars($username) . '</strong> to Azubi Africa program!</p>
        </div>
    </div>
</body>
</html>';
                } else {
                    showError($username);
                }
            } else {
                showError($username);
            }
        } catch (AwsException $e) {
            echo "Unable to login: " . $e->getMessage();
        }
    } else {
        showError($username);
    }
} else {
    showInvalidRequestMethod();
}

function showError($username) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azubi Login Form</title>
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
        <p style="color: red;">Invalid username or password.</p>
        <form action="login.php" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="' . htmlspecialchars($username) . '" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>';
}

function showInvalidRequestMethod() {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azubi Login Form</title>
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
        <p style="color: red;">Invalid request method.</p>
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
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>';
}
?>
