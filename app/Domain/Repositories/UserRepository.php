<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Models\User;

class UserRepository
{
    public function firstOrCreate(int $userId): User
    {
        return User::firstOrCreate(['user_id' => $userId]);
    }

    public function updateById(int $id, array $data): void
    {
        if (empty($data)) {
            return;
        }

        User::query()
            ->where('id', $id)
            ->update($data);
    }

    public function getById(int $id): ?User
    {
        return User::where('user_id', $id)->first();
    }

    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createUser(array $data): void
    {
        User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name'],
        ]);;
    }

    public function deleteUserById(int $id): void
    {
        User::where('id', $id)->delete();
    }
}
