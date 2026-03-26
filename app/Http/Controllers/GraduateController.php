<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGraduateRequest;
use App\Http\Requests\UpdateGraduateRequest;
use App\Models\Career;
use App\Models\Graduate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GraduateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $query = Graduate::with('career');

        // Search by name or carnet
        if (request()->filled('search')) {
            $search = "%{request('search')}%";
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhere('carnet', 'like', $search);
            });
        }

        // Filter by graduation year
        if (request()->filled('graduation_year')) {
            $query->where('graduation_year', request('graduation_year'));
        }

        // Filter by career
        if (request()->filled('career_id')) {
            $query->where('career_id', request('career_id'));
        }

        // Filter by gender
        if (request()->filled('gender')) {
            $query->where('gender', request('gender'));
        }

        $graduates = $query->paginate(10)->withQueryString();
        $careers = Career::all();

        return view('graduates.index', compact('graduates', 'careers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $careers = Career::all();

        return view('graduates.create', compact('careers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGraduateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos/graduates', 'public');
            $data['photo_path'] = $path;
        }

        Graduate::create($data);

        return redirect()->route('graduates.index')
            ->with('success', 'Graduado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Graduate $graduate): View
    {
        return view('graduates.show', compact('graduate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Graduate $graduate): View
    {
        $careers = Career::all();

        return view('graduates.edit', compact('graduate', 'careers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGraduateRequest $request, Graduate $graduate): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($graduate->photo_path) {
                Storage::disk('public')->delete($graduate->photo_path);
            }

            $path = $request->file('photo')->store('photos/graduates', 'public');
            $data['photo_path'] = $path;
        }

        $graduate->update($data);

        return redirect()->route('graduates.show', $graduate)
            ->with('success', 'Graduado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Graduate $graduate): RedirectResponse
    {
        // Delete photo if exists
        if ($graduate->photo_path) {
            Storage::disk('public')->delete($graduate->photo_path);
        }

        $graduate->delete();

        return redirect()->route('graduates.index')
            ->with('success', 'Graduado eliminado exitosamente.');
    }
}
