<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Metode ini membuat tabel 'projects' dengan kolom-kolom yang diperlukan.
     */
    public function up(): void // Ubah dari 'up()' ke 'up(): void' untuk Laravel 9+
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment primary key
            $table->string('name'); // Nama proyek
            $table->text('description')->nullable(); // Deskripsi proyek, bisa kosong

            // Menggunakan enum untuk status proyek, lebih spesifik daripada string
            $table->enum('status', ['ongoing', 'completed', 'on hold'])->default('ongoing'); // Status proyek

            $table->date('start_date')->nullable(); // Tanggal mulai proyek, bisa kosong
            $table->date('end_date')->nullable();   // Tanggal selesai proyek, bisa kosong

            // Menggunakan foreignId() untuk foreign key, cara yang lebih modern dan aman
            // Mereferensikan kolom 'id' di tabel 'users'.
            // onDelete('cascade') berarti jika user dihapus, proyeknya juga terhapus.
            $table->foreignId('created_by')
                  ->constrained('users') // Mereferensikan tabel 'users'
                  ->onDelete('cascade'); // Jika user dihapus, proyeknya ikut dihapus

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Balikkan migrasi.
     * Metode ini menghapus tabel 'projects' jika migrasi di-rollback.
     */
    public function down(): void // Ubah dari 'down()' ke 'down(): void'
    {
        Schema::dropIfExists('projects');
    }
};
