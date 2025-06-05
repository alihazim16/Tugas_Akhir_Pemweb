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
        // Middleware untuk permission, diterapkan di konstruktor
        // 'only' atau 'except' digunakan untuk menerapkan middleware hanya pada metode tertentu
        $this->middleware('permission:view dashboard')->only(['index', 'show']); // Semua user bisa lihat dashboard
        $this->middleware('permission:create project')->only('store');
        $this->middleware('permission:update project')->only('update');
        $this->middleware('permission:delete project')->only('destroy');
        $this->middleware('auth:api'); // Pastikan ini di-apply ke semua method yang butuh Auth API
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua proyek. Jika user bukan admin/manager, filter hanya proyek mereka.
        $user = Auth::user();
        if ($user->hasAnyRole(['admin', 'project manager'])) {
            $projects = Project::with('creator')->latest()->get();
        } else {
            $projects = Project::where('created_by', $user->id)->with('creator')->latest()->get();
        }
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|in:ongoing,completed,on hold', // Enum status
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        }

        // Tambahkan ID user yang membuat proyek
        $validatedData['created_by'] = Auth::id();

        $project = Project::create($validatedData);

        return response()->json($project, 201); // 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Cek permission: admin/manager bisa lihat semua, user biasa hanya bisa lihat proyek miliknya
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'project manager']) && $project->created_by !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke proyek ini.'], 403);
        }

        return response()->json($project->load('creator', 'tasks')); // Load creator dan tasks terkait
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Cek permission: admin/manager bisa update semua, user biasa hanya bisa update proyek miliknya
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'project manager']) && $project->created_by !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk mengupdate proyek ini.'], 403);
        }

        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'sometimes|nullable|in:ongoing,completed,on hold',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        }

        $project->update($validatedData);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Permission 'delete project' hanya untuk Admin. Ini sudah dijamin oleh middleware.
        // Tidak perlu cek created_by karena hanya admin yang boleh delete project.
        $project->delete();

        return response()->json(['message' => 'Proyek berhasil dihapus.'], 204); // 204 No Content
    }
}