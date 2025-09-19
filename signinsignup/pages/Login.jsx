
import React from 'react';
import LoginForm from '../components/LoginForm';
import { Link, useNavigate } from 'react-router-dom';



const Login = ({ setIsAuthenticated }) => {
  const navigate = useNavigate();
  return (
    <div className="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-100 to-blue-300">
      <div className="bg-white shadow-xl rounded-xl p-8 w-full max-w-md flex flex-col items-center">
        <div className="mb-6 flex flex-col items-center">
          <img src="https://cdn-icons-png.flaticon.com/512/5087/5087579.png" alt="Logo" className="w-16 h-16 mb-2" />
          <h1 className="text-3xl font-bold text-blue-700">Sign In</h1>
          <p className="text-gray-500 text-sm mt-1">Welcome back! Please login to your account.</p>
        </div>
        <LoginForm onLogin={async (data) => {
          try {
            const res = await fetch('http://localhost:5000/api/auth/login', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify(data),
            });
            const result = await res.json();
            if (res.ok) {
              setIsAuthenticated && setIsAuthenticated(true);
              navigate('/chat');
            } else {
              alert(result.error || 'Login failed');
            }
          } catch (err) {
            alert('Error: ' + err.message);
          }
        }} />
        <p className="mt-6 text-sm text-gray-600">
          Don't have an account?{' '}
          <Link to="/register" className="text-blue-600 font-semibold hover:underline">Register</Link>
        </p>
      </div>
    </div>
  );
};

export default Login;
