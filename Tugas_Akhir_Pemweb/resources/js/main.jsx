// ...existing code...
import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';

// Pastikan ada elemen dengan id="root" di HTML kamu
const root = document.getElementById('root');

if (root) {
  ReactDOM.createRoot(root).render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
}