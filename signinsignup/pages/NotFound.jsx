import React from 'react';

const NotFound = () => (
  <div className="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-gray-100 to-blue-200">
    <div className="bg-white shadow-xl rounded-xl p-8 w-full max-w-md flex flex-col items-center">
      <img src='https://cdn-icons-png.flaticon.com/512/2748/2748558.png' alt='404' className='w-24 h-24 mb-4' />
      <h1 className="text-4xl font-bold text-blue-700 mb-2">404</h1>
      <p className="text-lg text-gray-600 mb-2">Page Not Found</p>
      <a href="/login" className="text-blue-600 font-semibold hover:underline">Go to Login</a>
    </div>
  </div>
);

export default NotFound;
