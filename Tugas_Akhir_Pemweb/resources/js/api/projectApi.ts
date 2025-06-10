// resources/js/api/projectapi.ts

import axios, { AxiosResponse } from 'axios';

// Definisikan interface untuk model Project
export interface Project {
    id: number;
    name: string;
    description: string | null;
    status: 'ongoing' | 'completed' | 'on hold';
    created_by: number;
    created_at: string;
    updated_at: string;
    creator?: { id: number, name: string };
    start_date: string | null;
    end_date: string | null;
}

// Buat instance Axios kustom
const api = axios.create({
    baseURL: '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
});

// Interceptor request: tambahkan JWT ke header Authorization
api.interceptors.request.use(config => {
    const token = localStorage.getItem('jwt_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

// Interceptor response: handle error 401 (Unauthorized)
api.interceptors.response.use(response => response, error => {
    if (error.response && error.response.status === 401 && !error.config.url.includes('/auth/')) {
        localStorage.removeItem('jwt_token');
        // window.location.href = '/login'; // Uncomment jika ingin redirect otomatis
    }
    return Promise.reject(error);
});

// Fungsi CRUD Project

// Ambil semua proyek
export const getProjects = (): Promise<AxiosResponse<Project[]>> =>
    api.get<Project[]>('/projects');

// Ambil detail proyek berdasarkan ID
export const getProject = (id: number): Promise<AxiosResponse<Project>> =>
    api.get<Project>(`/projects/${id}`);

// Buat proyek baru
export const createProject = (data: Partial<Project>): Promise<AxiosResponse<Project>> =>
    api.post<Project>('/projects', data);

// Update proyek
export const updateProject = (id: number, data: Partial<Project>): Promise<AxiosResponse<Project>> =>
    api.put<Project>(`/projects/${id}`, data);

// Hapus proyek
export const deleteProject = (id: number): Promise<AxiosResponse<any>> =>
    api.delete(`/projects/${id}`);