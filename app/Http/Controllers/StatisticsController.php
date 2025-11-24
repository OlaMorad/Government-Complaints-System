<?php

namespace App\Http\Controllers;

use App\Http\Services\StatisticsService;

class StatisticsController extends Controller
{
    public function __construct(protected StatisticsService $statistics) {}

    // إحصائيات الأدمن
    public function admin()
    {
        return $this->statistics->adminStats();
    }

    // إحصائيات الجهة الحكومية
    public function government()
    {
        return $this->statistics->governmentStats();
    }
}
