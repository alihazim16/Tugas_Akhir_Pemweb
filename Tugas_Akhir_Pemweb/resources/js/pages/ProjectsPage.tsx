// resources/js/pages/ProjectsPage.tsx
import React, { useEffect, useState } from 'react';
import { getProjects, createProject, updateProject, deleteProject, Project } from '../api/projectApi';
import ProjectForm from '../components/ProjectForm'; // Import ProjectForm

const ProjectsPage: React.FC = () => {
    // Error TypeScript yang menunjuk ke baris ini (Line 6) seringkali menyesatkan.
    // Masalah sebenarnya kemungkinan ada di tempat lain dalam logika rendering atau setup lingkungan.
    const [projects, setProjects] = useState<Project[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [editingProject, setEditingProject] = useState<Project | null>(null);
    const [showAddForm, setShowAddForm] = useState(false);

    useEffect(() => {
        fetchProjects();
    }, []);

    const fetchProjects = async () => {
        setLoading(true);
        setError(null); // Reset error
        try {
            const response = await getProjects();
            setProjects(response.data);
        } catch (err: any) {
            if (err.response && err.response.status === 401) {
                // Redirect ke login jika token tidak valid atau sesi berakhir
                setError('Sesi Anda telah berakhir atau Anda tidak terautentikasi. Silakan login kembali.');
                // Contoh redirect ke halaman login
                // window.location.href = '/login'; 
            } else if (err.response && err.response.status === 403) {
                 setError('Anda tidak memiliki izin untuk melihat proyek.');
            } else {
                setError('Gagal memuat proyek. Pastikan API berjalan.');
                console.error('Error fetching projects:', err);
            }
        } finally {
            setLoading(false);
        }
    };

    const handleCreateSubmit = async (data: Partial<Project>) => {
        try {
            await createProject(data);
            setShowAddForm(false); // Sembunyikan form setelah berhasil
            await fetchProjects(); // Refresh daftar proyek
        } catch (err: any) {
            setError(err.response?.data?.message || err.response?.data?.errors ? JSON.stringify(err.response.data.errors) : 'Gagal membuat proyek.');
            console.error('Error creating project:', err);
        }
    };

    const handleUpdateSubmit = async (data: Partial<Project>) => {
        if (editingProject) {
            try {
                await updateProject(editingProject.id, data);
                setEditingProject(null); // Sembunyikan form edit
                await fetchProjects(); // Refresh daftar proyek
            } catch (err: any) {
                setError(err.response?.data?.message || err.response?.data?.errors ? JSON.stringify(err.response.data.errors) : 'Gagal mengupdate proyek.');
                console.error('Error updating project:', err);
            }
        }
    };

    const handleDelete = async (id: number) => {
        if (window.confirm('Apakah Anda yakin ingin menghapus proyek ini? Ini tidak bisa dibatalkan.')) {
            try {
                await deleteProject(id);
                await fetchProjects(); // Refresh daftar proyek
            } catch (err: any) {
                setError(err.response?.data?.message || 'Gagal menghapus proyek. Anda mungkin tidak memiliki izin.');
                console.error('Error deleting project:', err);
            }
        }
    };

    // Tampilan loading, error, atau daftar proyek
    if (loading) return <div className="text-center py-8 text-gray-600">Memuat proyek...</div>;
    if (error) return <div className="text-center py-8 text-red-600 font-bold">{error}</div>;

    return (
        <div className="container mx-auto p-4">
            <h1 className="text-3xl font-bold text-gray-800 mb-6">Manajemen Proyek</h1>

            {/* Tombol Tambah Proyek */}
            <button
                onClick={() => { setShowAddForm(!showAddForm); setEditingProject(null); }} // Reset edit form saat toggle add
                className="mb-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md transition-colors duration-200"
            >
                {showAddForm ? 'Sembunyikan Form Tambah' : 'Tambah Proyek Baru'}
            </button>

            {/* Form Tambah Proyek (Tampil jika showAddForm true) */}
            {showAddForm && (
                <div className="mb-8 p-6 bg-blue-50 rounded-lg shadow-inner">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-4">Buat Proyek Baru</h2>
                    <ProjectForm onSubmit={handleCreateSubmit} onCancel={() => setShowAddForm(false)} buttonText="Buat Proyek" />
                </div>
            )}

            {/* Daftar Proyek */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {projects.length === 0 ? (
                    <p className="col-span-full text-center text-gray-600 text-lg">Tidak ada proyek ditemukan.</p>
                ) : (
                    projects.map((project) => (
                        <div key={project.id} className="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform duration-200 hover:scale-[1.02]">
                            <div className="p-6">
                                {/* Jika proyek sedang diedit, tampilkan ProjectForm */}
                                {editingProject?.id === project.id ? (
                                    <ProjectForm
                                        initialData={project}
                                        onSubmit={handleUpdateSubmit}
                                        onCancel={() => setEditingProject(null)}
                                        buttonText="Update Proyek"
                                    />
                                ) : (
                                    <>
                                        <h3 className="text-xl font-semibold text-gray-900 mb-2">{project.name}</h3>
                                        <p className="text-gray-600 text-sm mb-4 line-clamp-3">{ project.description || 'Tidak ada deskripsi.' }</p>
                                        <div className="flex justify-between items-center text-sm text-gray-500 mb-2">
                                            <span>Status: <span className={`font-medium ${project.status === 'completed' ? 'text-green-600' : project.status === 'ongoing' ? 'text-blue-600' : 'text-yellow-600'}`}>{project.status}</span></span>
                                            <span>Dibuat oleh: <span className="font-medium text-gray-700">{ project.creator?.name || 'N/A' }</span></span>
                                        </div>
                                        <div className="text-sm text-gray-500">
                                            Mulai: <span className="font-medium">{ project.start_date || 'N/A' }</span>
                                            <span className="ml-4">Selesai: <span className="font-medium">{ project.end_date || 'N/A' }</span></span>
                                        </div>
                                        
                                        <div className="mt-4 flex space-x-3">
                                            <button
                                                onClick={() => setEditingProject(project)}
                                                className="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-1 px-3 rounded shadow-sm transition-colors duration-200"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                onClick={() => handleDelete(project.id)}
                                                className="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-3 rounded shadow-sm transition-colors duration-200"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
};

export default ProjectsPage;
