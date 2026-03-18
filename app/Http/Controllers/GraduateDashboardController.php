<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\View\View;

class GraduateDashboardController extends Controller
{
    public function __invoke(): View
    {
        $careers = Career::query()->orderBy('name')->get();

        return view('graduates.dashboard', [
            'careers' => $careers,
        ]);
    }
}
