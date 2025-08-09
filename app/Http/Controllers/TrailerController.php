<?php

namespace App\Http\Controllers;

use App\Models\Trailer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrailerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('trailers/trailers-table', [
            'trailers' => Trailer::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'license_plate' => 'required|string|max:255',
            'identification_number' => 'required|string|max:255',
            'next_major_inspection' => 'required|date',
            'next_safety_inspection' => 'required|date',
            'additional_information' => 'nullable|string|max:1000',
        ]);

        Trailer::create($validated);

        return redirect()->route('trailers.index')->with('success', 'Trailer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trailer $trailer)
    {
        return Inertia::render('trailers/trailer-profile', [
            'trailer' => $trailer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trailer $trailer)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'license_plate' => 'required|string|max:255',
            'identification_number' => 'required|string|max:255',
            'next_major_inspection' => 'required|date',
            'next_safety_inspection' => 'required|date',
            'additional_information' => 'nullable|string|max:1000',
        ]);

        $trailer->update($validated);

        return redirect()->route('trailers.index')->with('success', 'Trailer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trailer $trailer)
    {
        $trailer->delete();

        return redirect()->route('trailers.index')->with('success', 'Trailer deleted successfully.');
    }

    /**
     * Bulk delete trailers.
     */
    public function destroyMultiple(Request $request)
    {
        $trailerIds = $request->input('trailer_ids', []);

        if (empty($trailerIds)) {
            return redirect()->route('trailers.index')->with('error', __('No trailers selected for deletion.'));
        }

        Trailer::whereIn('id', $trailerIds)->delete();

        return redirect()->route('trailers.index')->with('success', __('Selected trailers deleted successfully.'));
    }
}
