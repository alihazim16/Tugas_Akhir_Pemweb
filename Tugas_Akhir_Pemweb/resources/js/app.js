import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import LoginPage from './components/LoginPage';
import RegisterPage from './components/RegisterPage';

function App() {
  return (
    <Router>
      <Routes>
        {/* Halaman login */}
        <Route path="/login" element={<LoginPage />} />
        {/* Halaman register */}
        <Route path="/register" element={<RegisterPage />} />
        {/* Route default: redirect ke login */}
        <Route path="/" element={<Navigate to="/login" replace />} />
        {/* Route tidak dikenal: redirect ke login */}
        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    </Router>
  );
}

export default App;