<?php

namespace App\Action;

use App\Domain\User\Data\UserUpdaterData;
use App\Domain\User\Service\UserUpdater;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @OA\Post(
 *     tags={"user"},
 *     path="/users/{id}",
 *     operationId="updateUsers",
 *     @OA\Response(
 *      response="200",
 *      description="update user"
 *     ),
 *     @OA\RequestBody(
 *         description="user body",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/userUpdater")
 *         )
 *     )
 * )
 */
final class UserUpdateAction extends Action
{
    /**
     * @var UserUpdater
     */
    private $userUpdater;

    public function __construct(UserUpdater $userUpdater)
    {
        $this->userUpdater = $userUpdater;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array<string,string|int>   $args
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        // Collect input from the HTTP request
        $userId = (int)$args['id'];

        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();
        $data['id'] = $userId;

        $userUpdaterData = new UserUpdaterData($data);

        // Invoke the Domain with inputs and retain the result
        $userData = $this->userUpdater->updateUser($userUpdaterData);

        // Transform the result into the JSON representation
        $result = [
            'user_id'    => $userData->id,
            'username'   => $userData->username,
            'first_name' => $userData->first_name,
            'last_name'  => $userData->last_name,
            'email'      => $userData->email,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
