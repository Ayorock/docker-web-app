<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;

// AWS SDK Configuration
$sdk = new Aws\Sdk([
    'region'   => 'us-east-1',
    'version'  => 'latest',
    'credentials' => [
        'key'    => '',
        'secret' => '',
    ]
]);

$dynamodb = $sdk->createDynamoDb();

// Collect form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
        
    // Handle file upload
    
    
    
    // Store data in DynamoDB
    try {
        $result = $dynamodb->putItem([
            'TableName' => 'GuestList',
            'Item' => [
                'Username' => ['S' => $username],
                'Password' => ['S' => $password],
                
            ]
        ]);
        echo "Successfully added guest to the list.";
    } catch (DynamoDbException $e) {
        echo "Unable to add guest to the list: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
