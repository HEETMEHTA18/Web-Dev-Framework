import React from 'react';

const MessageBubble = ({ message, isUser }) => (
  <div className={`flex ${isUser ? 'justify-end' : 'justify-start'} mb-2`}>
    <div className={`max-w-[70%] px-4 py-2 rounded-lg text-sm shadow ${isUser ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'}`}>
      {message}
    </div>
  </div>
);

export default MessageBubble;
