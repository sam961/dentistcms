<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Dentist;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index()
    {
        $dentists = Dentist::paginate(15);

        return view('dentists.index', compact('dentists'));
    }

    public function create()
    {
        return view('dentists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:dentists',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|max:50|unique:dentists',
            'specialization' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'qualifications' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'working_hours_start' => 'required|date_format:H:i',
            'working_hours_end' => 'required|date_format:H:i',
            'working_days' => 'required|array',
        ]);

        Dentist::create($validated);

        return redirect()->route('dentists.index')->with('success', 'Dentist added successfully.');
    }

    public function show(Dentist $dentist)
    {
        $dentist->load(['appointments.patient']);

        return view('dentists.show', compact('dentist'));
    }

    public function edit(Dentist $dentist)
    {
        return view('dentists.edit', compact('dentist'));
    }

    public function update(Request $request, Dentist $dentist)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:dentists,email,'.$dentist->id,
            'phone' => 'sometimes|required|string|max:20',
            'license_number' => 'sometimes|required|string|max:50|unique:dentists,license_number,'.$dentist->id,
            'specialization' => 'sometimes|required|string|max:255',
            'years_of_experience' => 'sometimes|required|integer|min:0',
            'qualifications' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:active,inactive',
            'working_hours_start' => 'sometimes|required|date_format:H:i',
            'working_hours_end' => 'sometimes|required|date_format:H:i',
            'working_days' => 'sometimes|required|array',
        ]);

        $dentist->update($validated);

        return redirect()->route('dentists.show', $dentist)->with('success', 'Dentist updated successfully.');
    }

    public function destroy(Dentist $dentist)
    {
        $dentist->delete();

        return redirect()->route('dentists.index')->with('success', 'Dentist removed successfully.');
    }
}
