<?php

namespace App\Application\Services;

use App\Application\Exceptions\CreateTokenException;
use App\Application\Exceptions\InvalidCredentialsException;
use App\Application\Exceptions\UserAlreadyExistsException;
use App\Application\Exceptions\UserNotFoundException;
use App\Domain\Entities\Models\User;
use App\Domain\Repositories\UserRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    public function __construct(
        protected array                 $requestData,
        private readonly UserRepository $userRepository,
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function signup(): void
    {
        $user = $this->userRepository->getByEmail($this->requestData['email']);
        throw_if($user, new UserAlreadyExistsException($this->requestData['email']));

        $userData = [
            'email' => $this->requestData['email'],
            'password' => $this->requestData['password'],
            'name' => $this->requestData['name'],
        ];

        $this->userRepository->createUser($userData);
    }

    /**
     * @throws InvalidCredentialsException
     * @throws CreateTokenException
     */
    public function signin(): string
    {
        $credentials['password'] = $this->requestData['password'];
        $email = hash('sha256', $this->requestData['email']);
        $credentials['email'] = $email;

        if (!JWTAuth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        try {
            $user = JWTAuth::user();
            $customClaims = ['user_id' => $user->id, 'authenticated_true' => true];
            $token = JWTAuth::claims($customClaims)->fromUser($user);
        } catch (Throwable $e) {
            throw new CreateTokenException();
        }

        return $token;
    }

    public function signout(): void
    {
        JWTAuth::invalidate(request()->header('Authorization'));
    }

    /**
     * @throws Throwable
     */
    public function getUser(): User
    {
        $user = JWTAuth::authenticate(request()->header('Authorization'));
        throw_if(!$user, new UserNotFoundException());

        return $user;
    }

    public function updateUserName(): void
    {
        $user = JWTAuth::authenticate(request()->header('Authorization'));

        $this->userRepository->updateById($user->id, ['name' => $this->requestData['name']]);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function deleteUser(): void
    {
        $user = JWTAuth::authenticate(request()->header('Authorization'));

        if (!Hash::check($this->requestData['password'], $user->password)) {
            throw new InvalidCredentialsException('Password is incorrect');
        }

        $this->userRepository->deleteUserById($user->id);
    }
}
