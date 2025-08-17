<?php

namespace App\Http\Controllers;

use App\Models\Trailer;
use App\Models\Truck;
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
        try {
            $validated = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'license_plate' => 'required|string|max:255',
                'identification_number' => 'required|string|max:255',
                'next_major_inspection' => 'required|date',
                'next_safety_inspection' => 'required|date',
                'additional_information' => 'nullable|string|max:1000',
                'assigned_to' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            Trailer::create($validated);

            return redirect()->route('trailers.index')->with('success', 'Trailer created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error creating trailer: ').$e->getMessage());
        }
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
        try {
            $validated = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'license_plate' => 'required|string|max:255',
                'identification_number' => 'required|string|max:255',
                'next_major_inspection' => 'required|date',
                'next_safety_inspection' => 'required|date',
                'additional_information' => 'nullable|string|max:1000',
                'assigned_to' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            $trailer->update($validated);

            return redirect()->route('trailers.index')->with('success', 'Trailer updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error updating trailer: ').$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trailer $trailer)
    {
        try {
            $trailer->delete();

            return redirect()->route('trailers.index')->with('success', 'Trailer deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error deleting trailer: ').$e->getMessage());
        }
    }

    /**
     * Bulk delete trailers.
     */
    public function destroyMultiple(Request $request)
    {
        try {
            $trailerIds = $request->input('trailer_ids', []);

            if (empty($trailerIds)) {
                return redirect()->route('trailers.index')->with('error', __('No trailers selected for deletion.'));
            }

            Trailer::whereIn('id', $trailerIds)->delete();

            return redirect()->route('trailers.index')->with('success', __('Selected trailers deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error deleting trailers: ').$e->getMessage());
        }
    }

    /**
     * Assign a truck to the trailer from profile.
     */
    public function assignTruck(Request $request, Trailer $trailer)
    {
        try {
            $validated = $request->validate([
                'truck_id' => 'required|exists:trucks,id',
            ]);

            $truck = Truck::where('id', $validated['truck_id'])
                ->where('company_id', $trailer->company_id)
                ->firstOrFail();

            $truck->update(['assigned_to_trailer' => $trailer->license_plate]);
            $trailer->update(['assigned_to' => $truck->license_plate]);

            return response()->json(['message' => 'Truck assigned to trailer successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error assigning truck to trailer: ').$e->getMessage());
        }
    }

    /**
     * Assign a truck to the trailer from table.
     */
    public function assignTruckFromTable(Request $request)
    {
        try {
            $validated = $request->validate([
                'trailer_id' => 'required|exists:trailers,id',
                'truck_id' => 'required|exists:trucks,id',
            ]);

            $trailer = Trailer::findOrFail($validated['trailer_id']);
            $truck = Truck::findOrFail($validated['truck_id'])
                ->where('company_id', $trailer->company_id)
                ->firstOrFail();

            $truck->update(['assigned_to_trailer' => $trailer->license_plate]);
            $trailer->update(['assigned_to' => $truck->license_plate]);

            return response()->json(['message' => 'Truck assigned to trailer successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error assigning truck to trailer: ').$e->getMessage());
        }
    }

    /**
     * Set the trailer as active.
     */
    public function setActive(Request $request, Trailer $trailer)
    {
        try {
            $trailer->update(['is_active' => true]);

            return response()->json(['message' => 'Trailer set to active successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error setting trailer active: ').$e->getMessage());
        }
    }

    /**
     * Set the trailer as inactive.
     */
    public function setInactive(Request $request, Trailer $trailer)
    {
        try {
            $trailer->update(['is_active' => false]);

            return response()->json(['message' => 'Trailer set to inactive successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('trailers.index')->with('error', __('Error setting trailer inactive: ').$e->getMessage());
        }
    }
}
