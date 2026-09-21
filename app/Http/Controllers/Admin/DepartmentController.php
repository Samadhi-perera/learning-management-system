<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::with('faculty')->withCount('courses')->latest()->get();
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.departments.index', compact('departments', 'faculties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }
}
