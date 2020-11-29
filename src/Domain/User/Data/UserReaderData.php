<?php

namespace App\Domain\User\Data;

use JsonSerializable;

/**
 * @OA\Schema(
 *     title="UserReader",
 *     description="A simple user model."
 * )
 */
final class UserReaderData implements JsonSerializable
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

    public function jsonSerialize()
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
