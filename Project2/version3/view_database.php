<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Database</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Database Content</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'vendor/autoload.php';

                use Aws\DynamoDb\DynamoDbClient;
                use Aws\Exception\AwsException;

                $dynamoDb = new DynamoDbClient([
                    'region'      => 'us-east-1',
                    'version'     => 'latest',
                    'credentials' => [
                        'key'    => getenv('AWS_ACCESS_KEY_ID'),
                        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
                    ]
                ]);

                try {
                    $result = $dynamoDb->scan([
                        'TableName' => 'GuestList',
                    ]);
                    foreach ($result['Items'] as $item) {
                        $name = isset($item['Name']['S']) ? $item['Name']['S'] : '';
                        $email = isset($item['Email']['S']) ? $item['Email']['S'] : '';
                        $country = isset($item['Country']['S']) ? $item['Country']['S'] : '';

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($name) . '</td>';
                        echo '<td>' . htmlspecialchars($email) . '</td>';
                        echo '<td>' . htmlspecialchars($country) . '</td>';
                        echo '</tr>';
                    }
                } catch (AwsException $e) {
                    echo '<p>Error retrieving data: ' . $e->getMessage() . '</p>';
                }
                ?>
            </tbody>
        </table>
        <button onclick="location.href='welcome.html'" style="margin-top: 10px;">Back</button>
    </div>
</body>
</html>
