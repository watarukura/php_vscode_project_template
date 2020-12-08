<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserCreatorData;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Domain\User\Repository\UserCreatorRepositoryDdb;
use App\Factory\LoggerFactory;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class UserCreator
{
    /**
     * @var UserCreatorRepositoryDdb
     */
    private $repository;


    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * The constructor.
     *
     * @param UserCreatorRepositoryDdb $repository The repository
     * @param LoggerFactory            $logger
     */
    public function __construct(UserCreatorRepositoryDdb $repository, LoggerFactory $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger
            ->addConsoleHandler()
            ->createInstance('user_creator');
    }

    /**
     * Create a new user.
     *
     * @param UserCreatorData $data The form data
     *
     * @return int The new user ID
     * @throws Exception
     */
    public function createUser(UserCreatorData $data): int
    {
        // Insert user
        $userId = $this->repository->insertUser($data);

        // Logging here: User created successfully
        $this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
    }
}
