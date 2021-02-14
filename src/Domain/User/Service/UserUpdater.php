<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserUpdaterData;
use App\Domain\User\Repository\UserUpdaterRepositoryDdb;

/**
 * Service.
 */
final class UserUpdater
{
    /**
     * @var UserUpdaterRepositoryDdb
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param UserUpdaterRepositoryDdb $repository The repository
     */
    public function __construct(UserUpdaterRepositoryDdb $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a user by the given user id.
     *
     * @param UserUpdaterData $userUpdaterData
     *
     * @return UserUpdaterData The user data
     * @throws \Exception
     */
    public function updateUser(UserUpdaterData $userUpdaterData): UserUpdaterData
    {
        return $this->repository->updateUser($userUpdaterData);
    }
}
