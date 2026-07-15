<?php
namespace App\Core;

use PDO;
use PDOException;


class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function init(array $config): void
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            die('Database not initialized. Call Database::init($config) first.');
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }


    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getInstance()->getPdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }


    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }


    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

   
    public static function insert(string $sql, array $params = []): int
    {
        self::query($sql, $params);
        return (int) self::getInstance()->getPdo()->lastInsertId();
    }
}