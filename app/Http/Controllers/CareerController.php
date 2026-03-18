<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCareerRequest;
use App\Http\Requests\UpdateCareerRequest;
use App\Models\Career;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $careers = Career::withCount('graduates')->paginate(10);

        return view('careers.index', compact('careers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('careers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCareerRequest $request): RedirectResponse
    {
        Career::create($request->validated());

        return redirect()->route('careers.index')
            ->with('success', 'Carrera creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Career $career): View
    {
        $career->load('graduates');

        return view('careers.show', compact('career'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Career $career): View
    {
        return view('careers.edit', compact('career'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCareerRequest $request, Career $career): RedirectResponse
    {
        $career->update($request->validated());

        return redirect()->route('careers.show', $career)
            ->with('success', 'Carrera actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career): RedirectResponse
    {
        $career->delete();

        return redirect()->route('careers.index')
            ->with('success', 'Carrera eliminada exitosamente.');
    }
}
