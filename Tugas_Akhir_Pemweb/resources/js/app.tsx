import React from 'react';
import { createRoot } from 'react-dom/client';

const App: React.FC = () => {
    return (
        <div className="flex items-center justify-center min-h-screen bg-gray-100">
            <div className="bg-white p-8 rounded shadow max-w-md w-full">
                <h1 className="text-2xl font-bold mb-4 text-center">Hello from React + TypeScript!</h1>
                <p className="text-center text-gray-600">
                    Edit <code>resources/js/app.tsx</code> to get started.
                </p>
            </div>
        </div>
    );
};

const container = document.getElementById('app');
if (container) {
    createRoot(container).render(<App />);
}