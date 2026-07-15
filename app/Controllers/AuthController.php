<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;

class AuthController extends BaseController
{
    /**
     * Авторизация (Sign In)
     */
    public function signin(array $params = []): void
    {
        $data = $this->getJsonInput();

        $email    = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->error('Email and password are required');
            return;
        }

        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['password'])) {
            $this->error('Invalid email or password', 401);
            return;
        }

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name']  = $user['fullname'];

        $this->success([
            'id'    => $user['id'],
            'email' => $user['email'],
            'name'  => $user['fullname'],
        ], 'Sign in successful');
    }

    /**
     * Регистрация (Sign Up)
     */
    public function signup(array $params = []): void
    {
        $data = $this->getJsonInput();

        $fullname       = trim($data['fullname'] ?? '');
        $email          = trim($data['email'] ?? '');
        $password       = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';
        $phone          = trim($data['phone'] ?? '');

        // Валидация
        if (empty($fullname) || empty($email) || empty($password)) {
            $this->error('Full name, email and password are required');
            return;
        }

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match');
            return;
        }

        if (strlen($password) < 6) {
            $this->error('Password must be at least 6 characters');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email format');
            return;
        }

        // Проверка, существует ли пользователь
        if (User::findByEmail($email)) {
            $this->error('User with this email already exists');
            return;
        }

        // Создание пользователя
        $userId = User::createUser([
            'fullname' => $fullname,
            'email'    => $email,
            'password' => $password,
            'phone'    => $phone,
        ]);

        $_SESSION['user_id']    = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name']  = $fullname;

        $this->success([
            'id'    => $userId,
            'email' => $email,
            'name'  => $fullname,
        ], 'Registration successful');
    }

    /**
     * Проверка авторизации
     */
    public function check(array $params = []): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->success([
                'id'    => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'name'  => $_SESSION['user_name'],
            ], 'Authorized');
        } else {
            $this->error('Not authorized', 401);
        }
    }

    /**
     * Выход
     */
    public function logout(array $params = []): void
    {
        session_destroy();
        $this->success(null, 'Logged out');
    }
}