<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserReaderData;
use App\Domain\User\Repository\UserReaderRepository;
use App\Exception\ValidationException;
use Doctrine\DBAL\Exception;

/**
 * Service.
 */
final class UserReader
{
    /**
     * @var UserReaderRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param UserReaderRepository $repository The repository
     */
    public function __construct(UserReaderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a user by the given user id.
     *
     * @param int $userId The user id
     *
     * @return UserReaderData The user data
     * @throws Exception
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
