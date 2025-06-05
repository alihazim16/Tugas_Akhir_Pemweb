<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Import trait HasRoles dari Spatie
use Tymon\JWTAuth\Contracts\JWTSubject; // Import kontrak JWTSubject dari Tymon JWTAuth
use App\Models\Comment; // Import model Comment untuk relasi
use App\Models\Project; // Import model Project untuk relasi
use App\Models\Task;    // Import model Task untuk relasi

class User extends Authenticatable implements JWTSubject // Implementasikan JWTSubject untuk JWT Auth
{
    use HasFactory, Notifiable, HasRoles; // Gunakan HasRoles trait untuk Spatie Permission

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi (misalnya saat diubah ke JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Mengubah timestamp verifikasi email menjadi objek datetime
    ];

    /**
     * JWT - Dapatkan pengidentifikasi yang akan disimpan dalam klaim subjek JWT.
     * Metode ini diperlukan saat mengimplementasikan Tymon\JWTAuth\Contracts\JWTSubject.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengembalikan primary key dari user
    }

    /**
     * JWT - Kembalikan array key-value yang berisi klaim kustom apa pun yang akan ditambahkan ke JWT.
     * Metode ini diperlukan saat mengimplementasikan Tymon\JWTAuth\Contracts\JWTSubject.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return []; // Untuk saat ini, tidak ada klaim kustom tambahan
    }

    /**
     * Relasi: Dapatkan proyek yang dibuat oleh user ini.
     * Relasi One-to-Many.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Relasi: Dapatkan tugas yang ditugaskan kepada user ini.
     * Relasi One-to-Many.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Relasi: Dapatkan komentar yang dibuat oleh user ini.
     * Relasi One-to-Many.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
