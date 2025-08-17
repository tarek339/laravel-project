<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Truck;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('drivers/drivers-table', [
            'drivers' => Driver::all(),
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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:drivers',
                'phone' => 'required|string|max:255',
                'license_number' => 'required|string|max:255',
                'license_expiry_date' => 'required|date',
                'driver_card_number' => 'required|string|max:255',
                'driver_card_expiry_date' => 'required|date',
                'driver_qualification_number' => 'required|string|max:255',
                'driver_qualification_expiry_date' => 'required|date',
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'additional_information' => 'nullable|string|max:1000',
                'assigned_to' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            Driver::create($validated);

            return redirect()->route('drivers.index')->with('success', __('Driver created successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('drivers.index')->with('error', __('Error creating driver: ').$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return Inertia::render('drivers/driver-profile', [
            'driver' => $driver,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        try {
            $validated = $request->validate([
                'company_id' => 'required|exists:companies,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:drivers,email,'.$driver->id,
                'phone' => 'required|string|max:255',
                'license_number' => 'required|string|max:255',
                'license_expiry_date' => 'required|date',
                'driver_card_number' => 'required|string|max:255',
                'driver_card_expiry_date' => 'required|date',
                'driver_qualification_number' => 'required|string|max:255',
                'driver_qualification_expiry_date' => 'required|date',
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'additional_information' => 'nullable|string|max:1000',
                'assigned_to' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            $driver->update($validated);

            return redirect()->route('drivers.index')->with('success', __('Driver updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('drivers.index')->with('error', __('Error updating driver: ').$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        try {
            $driver->delete();

            return redirect()->route('drivers.index')->with('success', __('Driver deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('drivers.index')->with('error', __('Error deleting driver: ').$e->getMessage());
        }
    }

    /**
     * Bulk delete drivers.
     */
    public function destroyMultiple(Request $request)
    {
        try {
            $driverIds = $request->input('driver_ids', []);

            if (empty($driverIds)) {
                return redirect()->route('drivers.index')->with('error', __('No drivers selected for deletion.'));
            }

            Driver::whereIn('id', $driverIds)->delete();

            return redirect()->route('drivers.index')->with('success', __('Selected drivers deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('drivers.index')->with('error', __('Error deleting drivers: ').$e->getMessage());
        }
    }

    /**
     * Assign a truck to the driver.
     */
    public function assignTruck(Request $request, Driver $driver)
    {
        try {
            $validated = $request->validate([
                'truck_id' => 'required|exists:trucks,id',
            ]);

            $truck = Truck::where('id', $validated['truck_id'])
                ->where('company_id', $driver->company_id)
                ->firstOrFail();

            $driver->update(['assigned_to' => $truck->license_plate]);
            $truck->update(['assigned_to_driver' => $driver->first_name.' '.$driver->last_name]);

            return response()->json(['message' => 'Truck assigned to driver successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Truck assignment failed: '.$e->getMessage()], 400);
        }
    }
}
