<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacultyController extends Controller
{
    public function index(): View
    {
        $faculties = Faculty::withCount('departments')->with('departments')->latest()->get();
        return view('admin.faculties.index', compact('faculties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:faculties,code',
            'description' => 'nullable|string',
        ]);

        Faculty::create($validated);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty created successfully.');
    }

    public function update(Request $request, Faculty $faculty): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:faculties,code,' . $faculty->id,
            'description' => 'nullable|string',
        ]);

        $faculty->update($validated);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty): RedirectResponse
    {
        $faculty->delete();
        return redirect()->route('admin.faculties.index')->with('success', 'Faculty removed successfully.');
    }
}
