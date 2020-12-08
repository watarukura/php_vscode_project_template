<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserCreatorData;
use App\Domain\User\Data\UserReaderData;
use App\Domain\User\Repository\UserReaderRepository;
use App\Domain\User\Repository\UserReaderRepositoryDdb;
use App\Exception\ValidationException;
use Doctrine\DBAL\Exception;

/**
 * Service.
 */
final class UserReader
{
    /**
     * @var UserReaderRepositoryDdb
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param UserReaderRepositoryDdb $repository The repository
     */
    public function __construct(UserReaderRepositoryDdb $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a user by the given user id.
     *
     * @param int $userId The user id
     *
     * @return UserReaderData The user data
     *
     * @throws ValidationException
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function getUserDetails(int $userId): UserReaderData
    {
        // Validation
        if (empty($userId)) {
            throw new ValidationException('User ID required');
        }

        return $this->repository->getUserById($userId);
    }
}
