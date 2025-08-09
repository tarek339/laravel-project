<?php

namespace App\Http\Controllers;

use App\Models\Driver;
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
        ]);

        Driver::create($validated);

        return redirect()->route('drivers.index')->with('success', __('Driver created successfully.'));
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
        ]);

        $driver->update($validated);

        return redirect()->route('drivers.index')->with('success', __('Driver updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', __('Driver deleted successfully.'));
    }

    /**
     * Bulk delete drivers.
     */
    public function destroyMultiple(Request $request)
    {
        $driverIds = $request->input('driver_ids', []);

        if (empty($driverIds)) {
            return redirect()->route('drivers.index')->with('error', __('No drivers selected for deletion.'));
        }

        Driver::whereIn('id', $driverIds)->delete();

        return redirect()->route('drivers.index')->with('success', __('Selected drivers deleted successfully.'));
    }
}
