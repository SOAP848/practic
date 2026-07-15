<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\MenuItem;

class MenuController extends BaseController
{
    /**
     * Получить все блюда для главной страницы
     */
    public function all(array $params = []): void
    {
        $items = MenuItem::getForMain();
        $this->success($items);
    }

    /**
     * Получить блюда по категории
     */
    public function byCategory(array $params = []): void
    {
        $category = $params['category'] ?? '';

        if (empty($category)) {
            $this->error('Category parameter is required');
            return;
        }

        $items = MenuItem::getByCategory($category);
        $this->success($items);
    }
}