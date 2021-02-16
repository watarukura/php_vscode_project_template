<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserUpdaterData;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use DomainException;
use Exception;

/**
 * Repository.
 */
class UserUpdaterRepositoryDdb
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
     * Update user row.
     *
     * @param UserUpdaterData $userUpdaterData
     *
     * @return UserUpdaterData The user data
     *
     * @throws Exception
     */
    public function updateUser(UserUpdaterData $userUpdaterData): UserUpdaterData
    {
        $statement = 'UPDATE users ';
        $parameters = [];
        $marshaler = new Marshaler();
        foreach ($userUpdaterData->jsonSerialize() as $k => $v) {
            if ($k === 'id') {
                // TODO: Omit Partition Key
                continue;
            }
            if (is_null($v)) {
                continue;
            }
            $statement .= sprintf('SET %s=? ', $k);
            $parameters[] = $marshaler->marshalValue($v);
        }
        $statement .= ' WHERE id = ? RETURNING ALL NEW *';
        $parameters[] = [
            'N' => $userUpdaterData->id
        ];

        try {
            $result = $this->client->executeStatement([
                'Parameters' => $parameters,
                'Statement'  => $statement
            ]);
            $items = $result->get('Items') ?? [];
            if (!$items) {
                throw new DomainException(sprintf('User not found: %s', $userUpdaterData->id));
            } else {
                $result_item = json_decode(
                    $marshaler->unmarshalJson($items[0]),
                    true
                );
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return new UserUpdaterData($result_item);
    }
}
