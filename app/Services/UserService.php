<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class UserService
{
    private Collection $users;

    public function __construct()
    {
        $this->users = collect();
    }

    public function getAllUsers(): Collection
    {
        return $this->users->values();
    }

    public function getUserById(string $id): User
    {
        $user = $this->users->get($id);

        if (!$user) {
            throw new RuntimeException('User not found', 404);
        }

        return $user;
    }

    public function createUser(array $data): User
    {
        $id = Str::uuid()->toString();
        $user = new User($id, $data['name'], $data['email'], $data['age']);
        $this->users->put($id, $user);
        return $user;
    }

    public function updateUser(string $id, array $data): User
    {
        $user = $this->getUserById($id);

        $user->name = $data['name'] ?? $user->name;
        $user->email = $data['email'] ?? $user->email;
        $user->age = $data['age'] ?? $user->age;

        $this->users->put($id, $user);
        return $user;
    }

    public function deleteUser(string $id): void
    {
        $this->getUserById($id);
        $this->users->forget($id);
    }
}