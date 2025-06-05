// resources/js/api/projectApi.ts

import axios from 'axios';

// Definisikan interface untuk model Project sesuai dengan struktur data dari Laravel API.
// Ini penting untuk type-checking yang kuat di TypeScript.
export interface Project {
    id: number;
    name: string;
    description: string | null; // Deskripsi bisa null
    status: 'ongoing' | 'completed' | 'on hold'; // Status menggunakan tipe literal string
    created_by: number; // ID user yang membuat proyek
    created_at: string; // Timestamp pembuatan
    updated_at: string; // Timestamp pembaruan
    creator?: { id: number, name: string }; // Relasi ke user creator (opsional, bisa di-load dengan 'with')
    start_date: string | null; // Tanggal mulai proyek, bisa null. Asumsi format string "YYYY-MM-DD" dari API.
    end_date: string | null;   // Tanggal selesai proyek, bisa null. Asumsi format string "YYYY-MM-DD" dari API.
}

// Buat instance Axios kustom untuk konfigurasi default.
// Ini membantu dalam mengatur base URL dan headers secara konsisten.
const api = axios.create({
    baseURL: '/api', // Base URL untuk semua endpoint API Laravel (misal: http://127.0.0.1:8000/api)
    headers: {
        'Accept': 'application/json', // Mengharapkan respons JSON
        'Content-Type': 'application/json', // Mengirim permintaan dalam format JSON
    },
});

// Tambahkan interceptor permintaan Axios.
// Interceptor ini akan dijalankan sebelum setiap permintaan dikirim.
// Tujuannya adalah untuk menyertakan token JWT dari localStorage ke header Authorization.
api.interceptors.request.use(config => {
    // Ambil token JWT dari localStorage.
    // Token ini seharusnya disimpan di localStorage setelah login berhasil (dari LoginController).
    const token = localStorage.getItem('jwt_token');

    // Jika token ada, tambahkan ke header Authorization sebagai Bearer token.
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config; // Kembalikan konfigurasi permintaan yang sudah dimodifikasi
}, error => {
    // Tangani error jika terjadi masalah sebelum permintaan dikirim (misal: token tidak valid)
    return Promise.reject(error);
});

// Tambahkan interceptor respons Axios.
// Interceptor ini akan dijalankan setelah setiap respons diterima.
// Berguna untuk menangani error autentikasi secara global (misal: token kadaluarsa).
api.interceptors.response.use(response => {
    return response; // Kembalikan respons jika sukses
}, error => {
    // Jika respons adalah error 401 (Unauthorized) dan bukan dari rute login/register
    // Ini menunjukkan token JWT mungkin kadaluarsa atau tidak valid.
    if (error.response && error.response.status === 401 && !error.config.url.includes('/auth/')) {
        // Hapus token yang kadaluarsa dari localStorage
        localStorage.removeItem('jwt_token');
        // Arahkan pengguna kembali ke halaman login
        // window.location.href = '/login'; // Uncomment jika ingin redirect otomatis
    }
    return Promise.reject(error); // Lempar error agar bisa ditangani di komponen yang memanggil
});

// --- Fungsi-fungsi CRUD untuk Proyek ---

/**
 * Mengambil daftar semua proyek dari API.
 * @returns Promise<AxiosResponse<Project[]>>
 */
export const getProjects = () => api.get<Project[]>('/projects');

/**
 * Membuat proyek baru melalui API.
 * @param data Data proyek baru (Partial<Project> karena ID, timestamps, dll. akan dibuat oleh backend)
 * @returns Promise<AxiosResponse<Project>>
 */
export const createProject = (data: Partial<Project>) => api.post<Project>('/projects', data);

/**
 * Mengupdate proyek yang sudah ada melalui API.
 * @param id ID proyek yang akan diupdate
 * @param data Data proyek yang akan diupdate (Partial<Project> karena hanya bagian tertentu yang diubah)
 * @returns Promise<AxiosResponse<Project>>
 */
export const updateProject = (id: number, data: Partial<Project>) => api.put<Project>(`/projects/${id}`, data);

/**
 * Menghapus proyek melalui API.
 * @param id ID proyek yang akan dihapus
 * @returns Promise<AxiosResponse<any>> (biasanya respons kosong atau pesan sukses)
 */
export const deleteProject = (id: number) => api.delete(`/projects/${id}`);

/**
 * Mengambil detail satu proyek berdasarkan ID melalui API.
 * @param id ID proyek yang akan diambil
 * @returns Promise<AxiosResponse<Project>>
 */
export const getProject = (id: number) => api.get<Project>(`/projects/${id}`);
