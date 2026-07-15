<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Speciality;

class SpecialityController extends BaseController
{
    /**
     * Получить все специальности (для слайдера)
     */
    public function all(array $params = []): void
    {
        $specialities = Speciality::all();
        $this->success($specialities);
    }
}