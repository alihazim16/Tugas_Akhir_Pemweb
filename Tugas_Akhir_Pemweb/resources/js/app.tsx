// resources/js/app.tsx (Contoh, sesuaikan dengan setup Vite/React/Routing-mu)
import './bootstrap';
import '../css/app.css';

import React from 'react';
import ReactDOM from 'react-dom/client';
import ProjectsPage from './pages/ProjectsPage'; // <-- Import ProjectsPage

// Ini contoh sederhana untuk render langsung
// Untuk aplikasi yang lebih besar, gunakan React Router atau Inertia.js
if (document.getElementById('app')) {
    ReactDOM.createRoot(document.getElementById('app')!).render(
        <React.StrictMode>
            <ProjectsPage /> {/* Render halaman proyek */}
        </React.StrictMode>
    );
}