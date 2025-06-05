// resources/js/components/ProjectForm.tsx
import React, { useState, useEffect } from 'react';
import { Project } from '../api/projectApi'; // Import interface Project

interface ProjectFormProps {
    initialData?: Project; // Data awal jika mode edit
    onSubmit: (data: Partial<Project>) => void; // Fungsi saat form disubmit
    onCancel?: () => void; // Fungsi saat tombol batal diklik
    buttonText?: string;   // Teks tombol submit
}

const ProjectForm: React.FC<ProjectFormProps> = ({ initialData, onSubmit, onCancel, buttonText = 'Simpan Proyek' }) => {
    const [name, setName] = useState(initialData?.name || '');
    const [description, setDescription] = useState(initialData?.description || '');
    const [status, setStatus] = useState<Project['status']>(initialData?.status || 'ongoing');
    // Pastikan format tanggal "YYYY-MM-DD" untuk input type="date"
    const [startDate, setStartDate] = useState(initialData?.start_date ? initialData.start_date.split('T')[0] : '');
    const [endDate, setEndDate] = useState(initialData?.end_date ? initialData.end_date.split('T')[0] : '');

    // Mengisi form saat initialData berubah (misal saat beralih dari mode "add" ke "edit")
    useEffect(() => {
        if (initialData) {
            setName(initialData.name);
            setDescription(initialData.description || ''); // Handle null description
            setStatus(initialData.status);
            // Pastikan properti ada sebelum mengaksesnya dan format untuk input date
            setStartDate(initialData.start_date ? initialData.start_date.split('T')[0] : '');
            setEndDate(initialData.end_date ? initialData.end_date.split('T')[0] : '');
        } else {
            // Reset form jika initialData kosong (misal saat membuka form add baru)
            setName('');
            setDescription('');
            setStatus('ongoing');
            setStartDate('');
            setEndDate('');
        }
    }, [initialData]); // Dependency array: efek akan berjalan ulang jika initialData berubah

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        onSubmit({
            name,
            description,
            status,
            start_date: startDate || null, // Kirim null jika string kosong
            end_date: endDate || null,     // Kirim null jika string kosong
        });
    };

    return (
        <form onSubmit={handleSubmit} className="bg-white p-6 rounded-lg shadow-md space-y-4">
            <div>
                <label htmlFor="name" className="block text-sm font-medium text-gray-700">Nama Proyek</label>
                <input
                    type="text"
                    id="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    required
                />
            </div>
            <div>
                <label htmlFor="description" className="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea
                    id="description"
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    rows={3}
                    className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                ></textarea>
            </div>
            <div>
                <label htmlFor="status" className="block text-sm font-medium text-gray-700">Status</label>
                <select
                    id="status"
                    value={status}
                    onChange={(e) => setStatus(e.target.value as Project['status'])}
                    className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="on hold">On Hold</option>
                </select>
            </div>
            <div>
                <label htmlFor="start_date" className="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input
                    type="date"
                    id="start_date"
                    value={startDate}
                    onChange={(e) => setStartDate(e.target.value)}
                    className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
            </div>
            <div>
                <label htmlFor="end_date" className="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input
                    type="date"
                    id="end_date"
                    value={endDate}
                    onChange={(e) => setEndDate(e.target.value)}
                    className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
            </div>
            <div className="flex justify-end space-x-3">
                <button
                    type="submit"
                    className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    {buttonText}
                </button>
                {onCancel && (
                    <button
                        type="button"
                        onClick={onCancel}
                        className="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Batal
                    </button>
                )}
            </div>
        </form>
    );
};

export default ProjectForm;
