<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Graduate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GraduateStatisticsController extends Controller
{
    public function byYear(Request $request): JsonResponse
    {
        $query = Graduate::query()
            ->select('graduation_year')
            ->selectRaw('count(*) as total')
            ->groupBy('graduation_year')
            ->orderBy('graduation_year');

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        $data = $query->get();

        return response()->json([
            'labels' => $data->pluck('graduation_year')->map(fn ($year) => (string) $year)->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ]);
    }

    public function byGender(Request $request): JsonResponse
    {
        $query = Graduate::query()
            ->select('gender')
            ->selectRaw('count(*) as total')
            ->groupBy('gender');

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        $data = $query->get();

        $genderLabels = [
            'male' => 'Masculino',
            'female' => 'Femenino',
        ];

        return response()->json([
            'labels' => $data->pluck('gender')->map(fn ($gender) => $genderLabels[$gender] ?? $gender)->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ]);
    }

    public function byCareer(Request $request): JsonResponse
    {
        $query = Graduate::query()
            ->join('careers', 'graduates.career_id', '=', 'careers.id')
            ->select('careers.name')
            ->selectRaw('count(graduates.id) as total')
            ->groupBy('careers.id', 'careers.name')
            ->orderBy('total', 'desc');

        if ($request->filled('graduation_year')) {
            $query->where('graduates.graduation_year', $request->graduation_year);
        }

        if ($request->filled('gender')) {
            $query->where('graduates.gender', $request->gender);
        }

        $data = $query->get();

        return response()->json([
            'labels' => $data->pluck('name')->toArray(),
            'data' => $data->pluck('total')->toArray(),
        ]);
    }
}
