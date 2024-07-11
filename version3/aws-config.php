<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

$client = new DynamoDbClient([
    'region'  => 'us-west-2',
    'version' => 'latest',
    'credentials' => [
        'key'    => 'AKIAYS2NVKP5OW2AZHGF',

        'secret' => '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',
    ],
]);

$tableName = 'Users';
?>
