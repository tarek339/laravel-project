<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Trailer;
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

    /**
     * Assign a driver to the truck.
     */
    public function assignDriver(Request $request, Truck $truck)
    {
        try {
            $validated = $request->validate([
                'driver_id' => 'required|exists:drivers,id',
            ]);

            $driver = Driver::findOrFail($validated['driver_id'])
                ->where('company_id', $truck->company_id)
                ->firstOrFail();

            $driver->update(['assigned_to' => $truck->license_plate]);
            $truck->update(['assigned_to_driver' => $driver->first_name.' '.$driver->last_name]);

            return response()->json(['message' => 'Driver assigned to truck successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('trucks.index')->with('error', __('Error assigning driver to truck: ').$e->getMessage());
        }
    }

    /**
     * Assign a trailer to the truck.
     */
    public function assignTrailer(Request $request, Truck $truck)
    {
        try {
            $validated = $request->validate([
                'trailer_id' => 'required|exists:trailers,id',
            ]);

            $trailer = Trailer::findOrFail($validated['trailer_id'])
                ->where('company_id', $truck->company_id)
                ->firstOrFail();

            $trailer->update(['assigned_to' => $truck->license_plate]);
            $truck->update(['assigned_to_trailer' => $trailer->license_plate]);

            return response()->json(['message' => 'Trailer assigned to truck successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('trucks.index')->with('error', __('Error assigning trailer to truck: ').$e->getMessage());
        }
    }
}
