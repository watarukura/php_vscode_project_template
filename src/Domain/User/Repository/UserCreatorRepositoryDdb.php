<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserCreatorData;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use DomainException;
use Exception;

/**
 * Repository.
 */
class UserCreatorRepositoryDdb
{
    /**
     * @var DynamoDbClient
     */
    private $client;

    /**
     * Constructor.
     *
     * @param DynamoDbClient $client
     */
    public function __construct(DynamoDbClient $client)
    {
        $this->client = $client;
    }

    /**
     * Insert user row.
     *
     * @param UserCreatorData $user The user
     *
     * @return int The new ID
     */
    public function insertUser(UserCreatorData $user): int
    {
        $id = $this->incrementUserId();

        $marshaler = new Marshaler();
        $item = $marshaler->marshalJson((string)json_encode([
            'id'         => $id,
            'username'   => $user->username,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
        ]));

        $this->client->putItem([
            'TableName' => 'users',
            'Item'      => $item,
        ]);

        return $id;
    }

    private function incrementUserId(): int
    {
        $marshaler = new Marshaler();
        $key = $marshaler->marshalJson((string)json_encode([
            'id' => 'users'
        ]));
        $eav = $marshaler->marshalJson((string)json_encode([
            ':increment' => 1
        ]));
        $ean = [
            '#c' => 'counter'
        ];
        $params = [
            'TableName'                 => 'counter',
            'Key'                       => $key,
            'UpdateExpression'          => 'ADD #c :increment',
            'ExpressionAttributeValues' => $eav,
            'ExpressionAttributeNames'  => $ean,
            'ReturnValues'              => 'UPDATED_NEW'
        ];
        try {
            $result = $this->client->updateItem($params);
            return $result['Attributes']['counter']['N'];
        } catch (DynamoDbException $exception) {
            throw $exception;
        }
    }
}
