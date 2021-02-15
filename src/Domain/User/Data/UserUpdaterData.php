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
     * @var string|null
     * @OA\Property (type="string", example="johndoe")
     */
    public $username = null;

    /**
     * @var string|null
     * @OA\Property (type="string", example="John")
     */
    public $first_name = null;

    /**
     * @var string|null
     * @OA\Property (type="string", example="Doe")
     */
    public $last_name = null;

    /**
     * @var string|null
     * @OA\Property (type="string", example="johndoe@example.com")
     */
    public $email = null;

    /**
     * UserUpdaterData constructor.
     *
     * @param array<string,string|int> $args
     */
    public function __construct(array $args)
    {
        $this->id = (int)$args['id'];
        $this->username = $args['username'] ? strval($args['username']) : null;
        $this->first_name = $args['first_name'] ? strval($args['first_name']) : null;
        $this->last_name = $args['last_name'] ? strval($args['last_name']) : null;
        $this->email = $args['email'] ? strval($args['email']) : null;
    }

    /**
     * @return array<string, int|string|null>
     */
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
