<?php

namespace App\Domain\User\Data;

use App\Exception\ValidationException;

/**
 * @OA\Schema(
 *     title="UserCreaotr",
 *     required={"username", "email"},
 *     description="A simple user creator model."
 * )
 */
final class UserCreatorData
{
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
     * UserCreatorData constructor.
     *
     * @param array<string,string> $userData
     */
    public function __construct(array $userData)
    {
        $this->validateNewUser($userData);

        $this->username = $userData['username'];
        $this->first_name = $userData['first_name'];
        $this->last_name = $userData['last_name'];
        $this->email = $userData['email'];
    }

    /**
     * Input validation.
     *
     * @param array<string,string> $data The form data
     *
     * @return void
     */
    private function validateNewUser(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (empty($data['username'])) {
            $errors['username'] = 'Input required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Invalid email address';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}
