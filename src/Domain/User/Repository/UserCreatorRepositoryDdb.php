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

    /**
     * @return int
     * @throws Exception
     */
    private function incrementUserId(): int
    {
        $marshaler = new Marshaler();
        $statement = 'UPDATE counter '
                   . 'SET counter = counter + ? '
                   . 'WHERE id = ? RETURNING ALL NEW *;';
        $parameters = [
            [
                'N' => 1
            ],
            [
                'S' => 'users'
            ]
        ];

        try {
            $result = $this->client->executeStatement([
                'Parameters' => $parameters,
                'Statement'  => $statement
            ]);
            $items = $result->get('Items') ?? [];
            if (!$items) {
                throw new DomainException(sprintf('Counter not found: %s', 'users'));
            } else {
                $result_item = json_decode(
                    $marshaler->unmarshalJson($items[0]),
                    true
                );
                return $result_item['counter'];
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
