<?php

namespace App\Http\Controllers;

use App\Exports\GraduatesExport;
use App\Models\Career;
use App\Models\Graduate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Show the reports page with filtering options
     */
    public function index(Request $request): View
    {
        // Get careers for dropdown
        $careers = Career::orderBy('name')->get();

        // Build query based on filters
        $query = Graduate::query()->with('career');

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Order results
        $graduates = $query
            ->orderBy('graduation_year', 'desc')
            ->orderBy('last_name')
            ->get();

        // Prepare filters for display
        $filters = [
            'graduation_year' => $request->graduation_year,
            'career_id' => $request->career_id,
            'gender' => $request->gender,
        ];

        return view('reports.index', [
            'graduates' => $graduates,
            'careers' => $careers,
            'filters' => $filters,
            'totalResults' => $graduates->count(),
        ]);
    }

    /**
     * Download PDF with current filters applied
     */
    public function downloadPdf(Request $request): Response
    {
        // Build query based on filters
        $query = Graduate::query()->with('career');

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $graduates = $query
            ->orderBy('graduation_year', 'desc')
            ->orderBy('last_name')
            ->get();

        // Prepare filter descriptions for PDF
        $filters = [
            'year' => $request->graduation_year ?? 'Todos',
            'career' => $request->career_id ? Career::find($request->career_id)?->name ?? 'N/A' : 'Todas',
            'gender' => match ($request->gender) {
                'male' => 'Masculino',
                'female' => 'Femenino',
                default => 'Todos',
            },
        ];

        $pdf = Pdf::loadView('reports.pdf', [
            'graduates' => $graduates,
            'filters' => $filters,
            'count' => $graduates->count(),
        ]);

        return $pdf->download('reporte-graduados-'.now()->format('Y-m-d-His').'.pdf');
    }

    /**
     * Export graduates to Excel with current filters applied
     */
    public function exportExcel(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'year' => $request->graduation_year,
            'career_id' => $request->career_id,
            'gender' => $request->gender,
        ];

        return Excel::download(
            new GraduatesExport($filters),
            'graduados-'.now()->format('Y-m-d-His').'.xlsx'
        );
    }
}
