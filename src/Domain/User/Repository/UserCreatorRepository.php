<?php

namespace App\Domain\User\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

/**
 * Repository.
 */
class UserCreatorRepository
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
     * Insert user row.
     *
     * @param array<string,string> $user The user
     *
     * @return int The new ID
     * @throws Exception
     */
    public function insertUser(array $user): int
    {
        $row = [
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
        ];

        $this->connection->insert('users', $row);

        return (int)$this->connection->lastInsertId();
    }
}
