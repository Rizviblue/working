import React, { useState } from 'react';
import { LoginPage } from './components/LoginPage';
import { AdminDashboard } from './components/AdminDashboard';
import { AgentDashboard } from './components/AgentDashboard';
import { UserDashboard } from './components/UserDashboard';

export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'agent' | 'user';
  city?: string;
}

function App() {
  const [currentUser, setCurrentUser] = useState<User | null>(null);

  const handleLogin = (user: User) => {
    setCurrentUser(user);
  };

  const handleLogout = () => {
    setCurrentUser(null);
  };

  if (!currentUser) {
    return <LoginPage onLogin={handleLogin} />;
  }

  switch (currentUser.role) {
    case 'admin':
      return <AdminDashboard user={currentUser} onLogout={handleLogout} />;
    case 'agent':
      return <AgentDashboard user={currentUser} onLogout={handleLogout} />;
    case 'user':
      return <UserDashboard user={currentUser} onLogout={handleLogout} />;
    default:
      return <LoginPage onLogin={handleLogin} />;
  }
}

export default App;