<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\StaticContent;

class StaticController extends BaseController
{
    /**
     * Получить статический контент по секции
     */
    public function get(array $params = []): void
    {
        $section = $params['section'] ?? '';

        if (empty($section)) {
            $this->error('Section parameter is required');
            return;
        }

        $content = StaticContent::getBySection($section);

        if (!$content) {
            $this->error('Content not found for section: ' . $section, 404);
            return;
        }

        $this->success($content);
    }
}