<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('doctors')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Département créé avec succès.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Département mis à jour avec succès.');
    }

    public function destroy(Department $department)
    {
        if ($department->doctors()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce département car il contient des médecins.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Département supprimé avec succès.');
    }
}
