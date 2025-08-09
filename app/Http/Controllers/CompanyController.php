<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('companies/companies-table', [
            'companies' => Company::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'authorization_number' => 'nullable|string|max:50',
            'authorization_number_expiry_date' => 'nullable|date',
        ]);

        Company::create($data);

        return redirect()->route('companies.index')->with('success', __('Company created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return Inertia::render('companies/company-profile', [
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'authorization_number' => 'nullable|string|max:50',
            'authorization_number_expiry_date' => 'nullable|date',
        ]);

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', __('Company updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')->with('success', __('Company deleted successfully.'));
    }


    /**
     * Bulk delete companies.
     */
    public function destroyMultiple(Request $request)
    {
        $companyIds = $request->input('company_ids', []);

        if (empty($companyIds)) {
            return redirect()->route('companies.index')->with('error', __('No companies selected for deletion.'));
        }

        Company::whereIn('id', $companyIds)->delete();

        return redirect()->route('companies.index')->with('success', __('Selected companies deleted successfully.'));
    }
}
