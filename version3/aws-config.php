<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

$client = new DynamoDbClient([
    'region'  => 'us-west-2',
    'version' => 'latest',
    'credentials' => [
        'key'    => '',

        'secret' =>'' ,
    ],
]);

$tableName = 'Users';
?>
