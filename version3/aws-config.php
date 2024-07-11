<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

$client = new DynamoDbClient([
    'region'  => 'us-west-2',
    'version' => 'latest',
    'credentials' => [
        'key'    => 'acess-key',

        'secret' =>'secret-key' ,
    ],
]);

$tableName = 'Users';
?>
