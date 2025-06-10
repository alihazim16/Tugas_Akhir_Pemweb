<?php


namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Tampilkan semua project milik user
    public function index()
    {
        $projects = Project::where('created_by', Auth::id())->get();
        return view('projects.index', compact('projects'));
    }

    // Form tambah project
    public function create()
    {
        return view('projects.create');
    }

    // Simpan project baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:ongoing,completed,on hold',
        ]);
        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);
        return redirect()->route('projects.index')->with('status', 'Project created!');
    }

    // Form edit project
    public function edit(Project $project)
    {
        if ($project->created_by !== Auth::id()) {
            abort(403);
        }
        return view('projects.edit', compact('project'));
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        if ($project->created_by !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:ongoing,completed,on hold',
        ]);
        $project->update($request->only('name', 'description', 'status'));
        return redirect()->route('projects.index')->with('status', 'Project updated!');
    }

    // Hapus project
    public function destroy(Project $project)
    {
        if ($project->created_by !== Auth::id()) {
            abort(403);
        }
        $project->delete();
        return redirect()->route('projects.index')->with('status', 'Project deleted!');
    }
}