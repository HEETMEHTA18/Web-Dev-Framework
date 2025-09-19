
import React from 'react';
import LogoutButton from './LogoutButton';



const Sidebar = ({ setIsAuthenticated }) => (
  <aside className="w-64 h-full bg-gray-900 text-white flex flex-col p-4">
    <h2 className="text-xl font-bold mb-6">ChatGPT</h2>
    <nav className="flex-1 space-y-2">
      <button className="w-full text-left px-3 py-2 rounded bg-gray-800 hover:bg-gray-700">New Chat</button>
      {/* Add chat history here */}
    </nav>
    <LogoutButton setIsAuthenticated={setIsAuthenticated} />
    <div className="mt-auto text-xs text-gray-400">Your App © 2025</div>
  </aside>
);

export default Sidebar;
