<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserReaderData;
use Doctrine\DBAL\Connection;
use DomainException;
use Exception;

/**
 * Repository.
 */
class UserReaderRepository
{
    /**
     * @var Connection The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param Connection $connection The database connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get user by the given user id.
     *
     * @param int $userId The user id
     *
     * @return mixed The user data
     * @throws \Doctrine\DBAL\Exception
     *
     * @throws DomainException
     */
    public function getUserById(int $userId): UserReaderData
    {
        $query = $this->connection->createQueryBuilder();
        try {
            $row = $query
                ->select('id', 'username', 'first_name', 'last_name', 'email')
                ->from('users')
                ->where('id = :id')
                ->setParameter('id', $userId)
                ->execute()
                ->fetchAssociative();
        } catch (Exception $exception) {
            throw $exception;
        }

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        // Map array to data object
        $user = new UserReaderData();
        $user->id = (int)$row['id'];
        $user->username = (string)$row['username'];
        $user->firstName = (string)$row['first_name'];
        $user->lastName = (string)$row['last_name'];
        $user->email = (string)$row['email'];

        return $user;
    }
}
