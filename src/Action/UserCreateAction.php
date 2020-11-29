<?php

namespace App\Action;

use App\Domain\User\Data\UserCreatorData;
use App\Domain\User\Service\UserCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @OA\Post(
 *     tags={"user"},
 *     path="/users/",
 *     operationId="putUsers",
 *     @OA\Response(
 *      response="200",
 *      description="create user"
 *     ),
 *     @OA\RequestBody(
 *         description="user body",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(ref="#/components/schemas/userCreator")
 *         )
 *     )
  * )
 */
final class UserCreateAction extends Action
{
    /**
     * @var UserCreator
     */
    private $userCreator;

    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();

        $userCreatorData = new UserCreatorData($data);

        // Invoke the Domain with inputs and retain the result
        $userId = $this->userCreator->createUser($userCreatorData);

        // Transform the result into the JSON representation
        $result = [
            'user_id' => $userId
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
