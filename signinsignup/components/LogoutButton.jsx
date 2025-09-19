import React from 'react';
import { useNavigate } from 'react-router-dom';

const LogoutButton = ({ setIsAuthenticated }) => {
  const navigate = useNavigate();

  const handleLogout = () => {
    // Clear any auth tokens or user data here if needed
    setIsAuthenticated(false);
    navigate('/login');
  };

  return (
    <button
      onClick={handleLogout}
      className="w-full mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
    >
      Logout
    </button>
  );
};

export default LogoutButton;
