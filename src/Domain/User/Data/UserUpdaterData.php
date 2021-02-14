<?php

namespace App\Domain\User\Data;

use JsonSerializable;

/**
 * @OA\Schema(
 *     title="UserUpdater",
 *     description="A simple user model."
 * )
 */
final class UserUpdaterData implements JsonSerializable
{
    /**
     * @var int
     * @OA\Property (type="integer", format="int64", readOnly=true, example=1)
     */
    public $id;

    /**
     * @var string
     * @OA\Property (type="string", example="johndoe")
     */
    public $username;

    /**
     * @var string
     * @OA\Property (type="string", example="John")
     */
    public $first_name;

    /**
     * @var string
     * @OA\Property (type="string", example="Doe")
     */
    public $last_name;

    /**
     * @var string
     * @OA\Property (type="string", example="johndoe@example.com")
     */
    public $email;

    /**
     * UserUpdaterData constructor.
     *
     * @param int   $userId
     * @param array $args
     */
    public function __construct(int $userId, array $args)
    {
        $this->id = $userId;
        $this->username = $args['username'] ?? null;
        $this->first_name = $args['first_name'] ?? null;
        $this->last_name = $args['last_name'] ?? null;
        $this->email = $args['email'] ?? null;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'username'   => $this->username,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
        ];
    }
}
