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
            $result = $this->client->executeStatement([
                'Parameters' => [
                    [
                        'N' => $userId
                    ]
                ],
                'Statement'  => 'SELECT * FROM users WHERE id = ?'
            ]);
            $items = $result->get('Items') ?? [];
            if (!$items) {
                throw new DomainException(sprintf('User not found: %s', $userId));
            } else {
                $marshaler = new Marshaler();
                $result_item = json_decode(
                    $marshaler->unmarshalJson($items[0]),
                    true
                );
            }
        } catch (Exception $exception) {
            throw $exception;
        }


        // Map array to data object
        $user = new UserReaderData();
        $user->id = $result_item['id'];
        $user->username = $result_item['user_name'];
        $user->first_name = $result_item['first_name'];
        $user->last_name = $result_item['last_name'];
        $user->email = $result_item['email'];

        return $user;
    }
}
