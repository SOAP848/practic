<?php
namespace App\Core;


abstract class BaseModel
{
    protected static string $table = '';

    /**
     * Получить все записи
     */
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM `" . static::$table . "`");
    }


    public static function find(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM `" . static::$table . "` WHERE `id` = ?",
            [$id]
        );
    }


    public static function findBy(string $field, $value): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM `" . static::$table . "` WHERE `{$field}` = ?",
            [$value]
        );
    }

   
    public static function findAllBy(string $field, $value): array
    {
        return Database::fetchAll(
            "SELECT * FROM `" . static::$table . "` WHERE `{$field}` = ?",
            [$value]
        );
    }

   
    public static function create(array $data): int
    {
        $columns = implode('`, `', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        return Database::insert(
            "INSERT INTO `" . static::$table . "` (`{$columns}`) VALUES ({$placeholders})",
            array_values($data)
        );
    }
}