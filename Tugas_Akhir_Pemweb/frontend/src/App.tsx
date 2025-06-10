import React, { useState } from "react";

const App: React.FC = () => {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [pesan, setPesan] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (username === "admin" && password === "admin") {
      setPesan("Login berhasil!");
    } else {
      setPesan("Username atau password salah.");
    }
  };

  return (
    <div style={{ minHeight: "100vh", display: "flex", alignItems: "center", justifyContent: "center", background: "#f3f4f6" }}>
      <form onSubmit={handleSubmit} style={{ background: "#fff", padding: 32, borderRadius: 8, boxShadow: "0 2px 8px rgba(0,0,0,0.1)", width: 320 }}>
        <h2 style={{ textAlign: "center", marginBottom: 24 }}>Login</h2>
        <div style={{ marginBottom: 16 }}>
          <label>Username:</label>
          <input
            type="text"
            value={username}
            onChange={e => setUsername(e.target.value)}
            required
            style={{ width: "100%", padding: 8, marginTop: 4 }}
          />
        </div>
        <div style={{ marginBottom: 24 }}>
          <label>Password:</label>
          <input
            type="password"
            value={password}
            onChange={e => setPassword(e.target.value)}
            required
            style={{ width: "100%", padding: 8, marginTop: 4 }}
          />
        </div>
        <button type="submit" style={{ width: "100%", padding: 10, background: "#2563eb", color: "#fff", border: "none", borderRadius: 4 }}>Login</button>
        {pesan && <p style={{ marginTop: 16, textAlign: "center" }}>{pesan}</p>}
      </form>
    </div>
  );
};

export default App;