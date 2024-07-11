<?php
require 'aws-config.php';
use Aws\DynamoDb\Exception\DynamoDbException;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $fullName = htmlspecialchars($_POST['fullname']);
    $age = htmlspecialchars($_POST['age']);
    $phone = htmlspecialchars($_POST['phone']);
    $picture = $_FILES['picture'];

    $pictureData = file_get_contents($picture['tmp_name']);
    $pictureBase64 = base64_encode($pictureData);

    try {
        $client->putItem([
            'TableName' => $tableName,
            'Item' => [
                'username' => ['S' => $username],
                'password' => ['S' => $password],
                'fullName' => ['S' => $fullName],
                'age' => ['N' => $age],
                'phone' => ['S' => $phone],
                'picture' => ['S' => $pictureBase64]
            ]
        ]);
        echo "User added successfully.";
    } catch (DynamoDbException $e) {
        echo 'Unable to add item: ' . $e->getMessage();
    }
} else {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azubi Sign Up Form</title>
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
        <form action="guestlist.php" method="post" enctype="multipart/form-data">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div>
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div>
                <label for="picture">Picture:</label>
                <input type="file" id="picture" name="picture" accept="image/*" required>
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
