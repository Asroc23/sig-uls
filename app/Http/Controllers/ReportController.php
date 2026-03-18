<?php

namespace App\Http\Controllers;

use App\Mail\GraduateNotification;
use App\Models\Graduate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    /**
     * Generate a PDF report of graduates with optional filters
     */
    public function downloadGraduatesPdf(Request $request): Response
    {
        $query = Graduate::query()
            ->with('career')
            ->orderBy('graduation_year', 'desc')
            ->orderBy('last_name');

        // Apply filters
        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $graduates = $query->get();

        $filters = [
            'year' => $request->graduation_year ?? 'Todos',
            'career' => $request->career_id ? \App\Models\Career::find($request->career_id)?->name ?? 'N/A' : 'Todas',
            'gender' => match ($request->gender) {
                'male' => 'Masculino',
                'female' => 'Femenino',
                default => 'Todos',
            },
        ];

        $pdf = Pdf::loadView('reports.graduates-pdf', [
            'graduates' => $graduates,
            'filters' => $filters,
            'count' => $graduates->count(),
        ]);

        return $pdf->download('reporte-graduados-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Show the email form for sending reports to graduates
     */
    public function emailForm(Request $request)
    {
        $careers = \App\Models\Career::orderBy('name')->get();

        return view('reports.email-form', [
            'careers' => $careers,
            'filters' => [
                'graduation_year' => $request->graduation_year,
                'career_id' => $request->career_id,
                'gender' => $request->gender,
            ],
        ]);
    }

    /**
     * Get count of graduates matching filters
     */
    public function getGraduatesCount(Request $request): JsonResponse
    {
        $query = Graduate::query();

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        return response()->json([
            'count' => $query->count(),
        ]);
    }

    /**
     * Send email to filtered graduates
     */
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'graduation_year' => 'nullable|integer|min:2000|max:2100',
            'career_id' => 'nullable|exists:careers,id',
            'gender' => 'nullable|in:male,female',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $query = Graduate::query();

        if ($validated['graduation_year'] ?? null) {
            $query->where('graduation_year', $validated['graduation_year']);
        }

        if ($validated['career_id'] ?? null) {
            $query->where('career_id', $validated['career_id']);
        }

        if ($validated['gender'] ?? null) {
            $query->where('gender', $validated['gender']);
        }

        $graduates = $query->get();

        if ($graduates->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No hay graduados que coincidan con los filtros especificados.');
        }

        // Send emails
        $successCount = 0;
        $failureCount = 0;

        foreach ($graduates as $graduate) {
            try {
                Mail::to($graduate->email)->send(
                    new GraduateNotification(
                        $graduate,
                        $validated['subject'],
                        $validated['message'],
                    )
                );
                $successCount++;
            } catch (\Exception $e) {
                Log::error("Failed to send email to {$graduate->email}: " . $e->getMessage());
                $failureCount++;
            }
        }

        $message = "Emails enviados exitosamente: {$successCount}";
        if ($failureCount > 0) {
            $message .= " (Fallos: {$failureCount})";
        }

        return redirect()->route('dashboard')
            ->with('success', $message);
    }
}
