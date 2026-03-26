<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Event;
use App\Models\Graduate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::with('career')
            ->latest('event_date')
            ->paginate(12);

        return view('events.index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $careers = Career::orderBy('name')->get();

        return view('events.create', [
            'careers' => $careers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date_format:Y-m-d\TH:i',
            'career_id' => 'required|exists:careers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            ...$validated,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Evento creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load('career', 'graduates');

        // Get all graduates not already in this event
        $attachedIds = $event->graduates->pluck('id')->toArray();
        $availableGraduates = Graduate::whereNotIn('id', $attachedIds)
            ->orderBy('first_name')
            ->get();

        return view('events.show', [
            'event' => $event,
            'availableGraduates' => $availableGraduates,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        $careers = Career::orderBy('name')->get();

        return view('events.edit', [
            'event' => $event,
            'careers' => $careers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date_format:Y-m-d\TH:i',
            'career_id' => 'required|exists:careers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Evento actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Evento eliminado exitosamente');
    }

    /**
     * Search graduates for attendance (optimized autocomplete)
     */
    public function searchGraduates(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $graduates = Graduate::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('carnet', 'like', "%{$query}%");
        })
            ->limit(5)
            ->get(['id', 'first_name', 'last_name', 'carnet']);

        return response()->json($graduates);
    }

    /**
     * Attach graduate to event
     */
    public function attachGraduate(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'graduate_id' => 'required|exists:graduates,id',
        ]);

        $graduateId = $validated['graduate_id'];

        // Check if graduate is already attached
        if ($event->graduates()->where('graduate_id', $graduateId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'El graduado ya está registrado en este evento',
            ], 409);
        }

        // Attach the graduate
        $event->graduates()->attach($graduateId);

        return response()->json([
            'success' => true,
            'message' => 'Graduado agregado al evento',
        ]);
    }

    /**
     * Detach graduate from event
     */
    public function detachGraduate(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'graduate_id' => 'required|exists:graduates,id',
        ]);

        $event->graduates()->detach($validated['graduate_id']);

        return response()->json([
            'success' => true,
            'message' => 'Graduado removido del evento',
        ]);
    }

    /**
     * Download event PDF
     */
    public function downloadPdf(Event $event): Response
    {
        $event->load('career', 'graduates');

        $pdf = Pdf::loadView('events.pdf', [
            'event' => $event,
        ]);

        return $pdf->download('evento-'.$event->id.'-'.now()->format('Y-m-d-His').'.pdf');
    }

    /**
     * Get events by year for dashboard
     */
    public function getEventsByYear(): JsonResponse
    {
        $currentYear = now()->year;
        $data = [];

        for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
            $count = Event::whereYear('event_date', $year)->count();
            $data[] = [
                'year' => (string) $year,
                'count' => $count,
            ];
        }

        return response()->json(array_reverse($data));
    }

    /**
     * Get participants per event for dashboard
     */
    public function getParticipantsByEvent(): JsonResponse
    {
        $events = Event::with('graduates')
            ->latest('event_date')
            ->limit(10)
            ->get()
            ->map(function ($event) {
                return [
                    'name' => $event->name,
                    'count' => $event->graduates->count(),
                ];
            });

        return response()->json($events->values());
    }
}
