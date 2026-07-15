<?php
namespace App\Models;

use App\Core\BaseModel;

class User extends BaseModel
{
    protected static string $table = 'users';


    public static function findByEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }


    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

   
    public static function createUser(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return self::create($data);
    }
}