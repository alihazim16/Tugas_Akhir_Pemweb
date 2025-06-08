<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Middleware permission (jika pakai spatie/permission)
        $this->middleware('permission:view dashboard')->only(['index', 'show']);
        $this->middleware('permission:create project')->only('store');
        $this->middleware('permission:update project')->only('update');
        $this->middleware('permission:delete project')->only('destroy');
        $this->middleware('auth:api');
    }

    // Tampilkan semua project
    public function index()
    {
        $user = Auth::user();
        if ($user->hasAnyRole(['admin', 'project manager'])) {
            $projects = Project::with(['creator', 'tasks'])->latest()->get();
        } else {
            $projects = Project::where('created_by', $user->id)->with(['creator', 'tasks'])->latest()->get();
        }
        return response()->json($projects);
    }

    // Simpan project baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:ongoing,completed,on hold',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validatedData['created_by'] = Auth::id();
        $project = Project::create($validatedData);

        return response()->json($project, 201);
    }

    // Tampilkan detail project
    public function show(Project $project)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'project manager']) && $project->created_by !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke proyek ini.'], 403);
        }
        return response()->json($project->load(['creator', 'tasks']));
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'project manager']) && $project->created_by !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk mengupdate proyek ini.'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|nullable|in:ongoing,completed,on hold',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validatedData);

        return response()->json($project);
    }

    // Hapus project
    public function destroy(Project $project)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'project manager']) && $project->created_by !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus proyek ini.'], 403);
        }

        $project->delete();
        return response()->json(['message' => 'Proyek berhasil dihapus.'], 200);
    }
}