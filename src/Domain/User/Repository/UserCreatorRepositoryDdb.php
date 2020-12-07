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
     * @var
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
        $marshaler = new Marshaler();

        $id = $this->incrementUserId();

        $row = [
            'id' => $id,
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];
        $item = $marshaler->marshalJson(json_encode($row));

        $this->client->putItem([
            'TableName' =>  'users',
            'Item' => $item,
        ]);

        return (int)$row['id'];
    }

    private function incrementUserId(): int
    {
        $marshaler = new Marshaler();
        $key = $marshaler->marshalJson(json_encode([
            'id' => 'users'
        ]));
        $eav = $marshaler->marshalJson(json_encode([
            ':increment' => 1
        ]));
        $ean = [
            '#c' => 'counter'
        ];
        $params = [
            'TableName' => 'counter',
            'Key' => $key,
            'UpdateExpression' => 'ADD #c :increment',
            'ExpressionAttributeValues' => $eav,
            'ExpressionAttributeNames' => $ean,
            'ReturnValues' => 'UPDATED_NEW'
        ];
        try {
            $result = $this->client->updateItem($params);
            return $result['Attributes']['counter']['N'];
        } catch (DynamoDbException $exception) {
            throw $exception;
        }
    }
}
