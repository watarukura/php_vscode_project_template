<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use Doctrine\DBAL\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @OA\Get(
 *   tags={"user"},
 *   path="/users/{id}",
 *   operationId="getUser",
 *   @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="User id",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Read single user",
 *     @OA\JsonContent(ref="#/components/schemas/UserReader")
 *   )
 * )
 */
final class UserReadAction extends Action
{
    /**
     * @var UserReader
     */
    private $userReader;

    /**
     * The constructor.
     *
     * @param UserReader $userReader The user reader
     */
    public function __construct(UserReader $userReader)
    {
        $this->userReader = $userReader;
    }

    /**
     * Invoke.
     *
     * @param ServerRequestInterface $request  The request
     * @param ResponseInterface      $response The response
     * @param array<string,string>   $args     The route arguments
     *
     * @return ResponseInterface The response
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        // Collect input from the HTTP request
        $userId = (int)$args['id'];

        // Invoke the Domain with inputs and retain the result
        $userData = $this->userReader->getUserDetails($userId);

        // Transform the result into the JSON representation
        $result = [
            'user_id' => $userData->id,
            'username' => $userData->username,
            'first_name' => $userData->first_name,
            'last_name' => $userData->last_name,
            'email' => $userData->email,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
