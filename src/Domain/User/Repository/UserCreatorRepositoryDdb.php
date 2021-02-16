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
     * @throws Exception
     */
    public function insertUser(UserCreatorData $user): int
    {
        $id = $this->incrementUserId();

        try {
            $this->client->executeStatement([
                'Parameters' => [
                    [
                        'N' => $id,
                    ],
                    [
                        'S' => $user->username,
                    ],
                    [
                        'S' => $user->first_name,
                    ],
                    [
                        'S' => $user->last_name,
                    ],
                    [
                        'S' => $user->email
                    ]
                ],
                'Statement'  => "INSERT INTO users
                                 VALUE {
                                    'id': ?,
                                    'user_name': ?,
                                    'first_name': ?,
                                    'last_name': ?,
                                    'email': ?
                                 }"
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $id;
    }

    private function incrementUserId(): int
    {
        $marshaler = new Marshaler();
        $key = $marshaler->marshalItem([
            'id' => 'users'
        ]);
        $eav = $marshaler->marshalItem([
            ':increment' => 1
        ]);
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
            $attr = $result['Attributes'] ?? [];
            $counter = json_decode(
                $marshaler->unmarshalJson($attr),
                true
            );
            if ($counter) {
                return $counter['counter'];
            } else {
                throw new DomainException(sprintf('Counter not found: %s', 'users'));
            }
        } catch (DynamoDbException $exception) {
            throw $exception;
        }
    }
}
