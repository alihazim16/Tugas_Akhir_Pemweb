<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project; // Import Project model
use App\Models\Task;    // Import Task model

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil statistik sederhana untuk dashboard
        $totalProjects = Project::count();
        $myProjects = Project::where('created_by', $user->id)->count();
        // Baris-baris berikut membutuhkan kolom 'assigned_to' di tabel 'tasks'
        $totalTasks = Task::count();
        $myAssignedTasks = Task::where('assigned_to', $user->id)->count(); // <-- Baris 25 yang error

        $tasksToDo = Task::where('status', 'to_do')->count();
        $tasksInProgress = Task::where('status', 'in_progress')->count();
        $tasksDone = Task::where('status', 'done')->count();

        // Jika user adalah Project Manager atau Admin, tampilkan semua project/task
        if ($user->hasAnyRole(['admin', 'project manager'])) {
            $recentProjects = Project::latest()->limit(5)->get(); // 5 proyek terbaru
            $recentTasks = Task::latest()->limit(5)->get(); // 5 tugas terbaru
        } else {
            // Jika user biasa, tampilkan proyek/tugas yang relevan saja
            $recentProjects = Project::where('created_by', $user->id)->latest()->limit(5)->get();
            $recentTasks = Task::where('assigned_to', $user->id)->latest()->limit(5)->get();
        }

        // Mengembalikan view 'dashboard' dengan data statistik
        // Pastikan file ini ada di resources/views/dashboard.blade.php
        return view('dashboard', compact(
            'user',
            'totalProjects',
            'myProjects',
            'totalTasks',
            'myAssignedTasks',
            'tasksToDo',
            'tasksInProgress',
            'tasksDone',
            'recentProjects',
            'recentTasks'
        ));
    }
}
