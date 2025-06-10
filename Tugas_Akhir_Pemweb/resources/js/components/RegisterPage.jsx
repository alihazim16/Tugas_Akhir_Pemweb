import React, { useState } from 'react';
import { Link } from 'react-router-dom';

function RegisterPage() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');

  const handleRegister = (e) => {
    e.preventDefault();
    // Lakukan request ke endpoint register di sini
    alert(
      `Register clicked!\nNama: ${name}\nEmail: ${email}\nPassword: ${password}\nKonfirmasi: ${passwordConfirmation}`
    );
  };

  return (
    <div style={{ maxWidth: 400, margin: 'auto', padding: 20 }}>
      <h2>Register</h2>
      <form onSubmit={handleRegister}>
        <div>
          <label>Nama</label><br />
          <input
            type="text"
            value={name}
            onChange={e => setName(e.target.value)}
            required
            style={{ width: '100%', marginBottom: 10 }}
          />
        </div>
        <div>
          <label>Email</label><br />
          <input
            type="email"
            value={email}
            onChange={e => setEmail(e.target.value)}
            required
            style={{ width: '100%', marginBottom: 10 }}
          />
        </div>
        <div>
          <label>Password</label><br />
          <input
            type="password"
            value={password}
            onChange={e => setPassword(e.target.value)}
            required
            style={{ width: '100%', marginBottom: 10 }}
          />
        </div>
        <div>
          <label>Konfirmasi Password</label><br />
          <input
            type="password"
            value={passwordConfirmation}
            onChange={e => setPasswordConfirmation(e.target.value)}
            required
            style={{ width: '100%', marginBottom: 10 }}
          />
        </div>
        <button type="submit" style={{ width: '100%' }}>Register</button>
      </form>
      <div style={{ marginTop: 16, textAlign: 'center' }}>
        <span>Sudah punya akun?</span>
        <br />
        <Link to="/login">
          <button type="button" style={{ marginTop: 8 }}>Login</button>
        </Link>
      </div>
    </div>
  );
}

export default RegisterPage;