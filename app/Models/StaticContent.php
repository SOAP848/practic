<?php
namespace App\Models;

use App\Core\BaseModel;

class StaticContent extends BaseModel
{
    protected static string $table = 'static';


    public static function getBySection(string $section): ?array
    {
        return self::findBy('section', $section);
    }
}