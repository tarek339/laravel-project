<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('trucks/trucks-table', [
            'trucks' => Truck::all(),
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
                'next_tachograph_inspection' => 'required|date',
                'additional_information' => 'nullable|string|max:1000',
                'assigned_to_trailer' => 'nullable|string|max:255',
                'assigned_to_driver' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            Truck::create($validated);

            return redirect()->route('trucks.index')->with('success', 'Truck created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('trucks.index')->with('error', __('Error creating truck: ').$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Truck $truck)
    {
        return Inertia::render('trucks/truck-profile', [
            'truck' => $truck,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Truck $truck)
    {
        try {
            $validated = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'license_plate' => 'required|string|max:255',
                'identification_number' => 'required|string|max:255',
                'next_major_inspection' => 'required|date',
                'next_safety_inspection' => 'required|date',
                'next_tachograph_inspection' => 'required|date',
                'additional_information' => 'nullable|string|max:1000',
                'assigned_to_trailer' => 'nullable|string|max:255',
                'assigned_to_driver' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            $truck->update($validated);

            return redirect()->route('trucks.index')->with('success', 'Truck updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('trucks.index')->with('error', __('Error updating truck: ').$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Truck $truck)
    {
        try {
            $truck->delete();

            return redirect()->route('trucks.index')->with('success', 'Truck deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('trucks.index')->with('error', __('Error deleting truck: ').$e->getMessage());
        }
    }

    /**
     * Bulk delete trucks.
     */
    public function destroyMultiple(Request $request)
    {
        try {
            $truckIds = $request->input('truck_ids', []);

            if (empty($truckIds)) {
                return redirect()->route('trucks.index')->with('error', __('No trucks selected for deletion.'));
            }

            Truck::whereIn('id', $truckIds)->delete();

            return redirect()->route('trucks.index')->with('success', __('Selected trucks deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('trucks.index')->with('error', __('Error deleting trucks: ').$e->getMessage());
        }
    }
}
