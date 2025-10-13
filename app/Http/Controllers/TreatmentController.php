<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::paginate(15);

        return view('treatments.index', compact('treatments'));
    }

    public function create()
    {
        return view('treatments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'requires_followup' => 'boolean',
            'is_active' => 'boolean',
        ]);

        Treatment::create($validated);

        return redirect()->route('treatments.index')->with('success', 'Treatment created successfully.');
    }

    public function show(Treatment $treatment)
    {
        return view('treatments.show', compact('treatment'));
    }

    public function edit(Treatment $treatment)
    {
        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category' => 'sometimes|required|string|max:100',
            'price' => 'sometimes|required|numeric|min:0',
            'duration' => 'sometimes|required|integer|min:1',
            'requires_followup' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $treatment->update($validated);

        return redirect()->route('treatments.show', $treatment)->with('success', 'Treatment updated successfully.');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();

        return redirect()->route('treatments.index')->with('success', 'Treatment deleted successfully.');
    }
}
