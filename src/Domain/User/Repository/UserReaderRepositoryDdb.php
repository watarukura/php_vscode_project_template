<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserReaderData;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use DomainException;
use Exception;

/**
 * Repository.
 */
class UserReaderRepositoryDdb
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
     * Get user by the given user id.
     *
     * @param int $userId The user id
     *
     * @return UserReaderData The user data
     *
     * @throws DomainException
     * @throws Exception
     */
    public function getUserById(int $userId): UserReaderData
    {
        try {
            $marshaler = new Marshaler();
            $key = $marshaler->marshalJson((string)json_encode([
                'id' => $userId
            ]));
            $row = $this->client->getItem([
                'TableName'      => 'users',
                'Key'            => $key,
            ]);
            if (!$row->get('Item')) {
                throw new DomainException(sprintf('User not found: %s', $userId));
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        // Map array to data object
        $user = new UserReaderData();
        $user->id = (int)$row['Item']['id']['N'];
        $user->username = (string)$row['Item']['username']['S'];
        $user->first_name = (string)$row['Item']['first_name']['S'];
        $user->last_name = (string)$row['Item']['last_name']['S'];
        $user->email = (string)$row['Item']['email']['S'];

        return $user;
    }
}
