<?php
namespace App\Models;

use App\Core\Database;
use App\Core\BaseModel;

class MenuItem extends BaseModel
{
    protected static string $table = 'menu';


    public static function getForMain(): array
    {
        return Database::fetchAll(
            "SELECT * FROM `" . static::$table . "` WHERE `on_main` = 1 LIMIT 21"
        );
    }


    public static function getByCategory(string $category): array
    {
        return Database::fetchAll(
            "SELECT * FROM `" . static::$table . "` WHERE `category` = ? AND `on_main` = 1",
            [$category]
        );
    }
}