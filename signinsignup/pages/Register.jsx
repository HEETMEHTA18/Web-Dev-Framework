import React from 'react';
import RegisterForm from '../components/RegisterForm';
import { Link } from 'react-router-dom';

const Register = () => {
  // Handler for registration form submission
  const handleRegister = async (data) => {
    try {
      const res = await fetch('http://localhost:5000/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      });
      const result = await res.json();
      if (res.ok) {
        alert('Registration successful!');
        // Optionally redirect to login page here
      } else {
        alert(result.error || 'Registration failed');
      }
    } catch (err) {
      alert('Error: ' + err.message);
    }
  };

  return (
    <div className="flex items-center justify-center min-h-screen bg-gradient-to-br from-green-100 to-blue-200">
      <div className="bg-white shadow-xl rounded-xl p-8 w-full max-w-md flex flex-col items-center">
        <div className="mb-6 flex flex-col items-center">
          <img
            src="https://cdn-icons-png.flaticon.com/512/5087/5087579.png"
            alt="Logo"
            className="w-16 h-16 mb-2"
          />
          <h1 className="text-3xl font-bold text-green-700">Register</h1>
          <p className="text-gray-500 text-sm mt-1">
            Create your account to get started.
          </p>
        </div>
        <RegisterForm onRegister={handleRegister} />
        <p className="mt-6 text-sm text-gray-600">
          Already have an account?{' '}
          <Link to="/login" className="text-green-600 font-semibold hover:underline">
            Login
          </Link>
        </p>
      </div>
    </div>
  );
};

export default Register;
