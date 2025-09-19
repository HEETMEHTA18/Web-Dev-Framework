import React, { useState } from 'react';
import MessageBubble from './MessageBubble';
import ChatInput from './ChatInput';


const OPENAI_API_KEY = "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";

const ChatWindow = () => {
  const [messages, setMessages] = useState([
    { text: 'Hello! How can I help you today?', isUser: false },
  ]);
  const [loading, setLoading] = useState(false);

  const handleSend = async (msg) => {
    setMessages(prev => [...prev, { text: msg, isUser: true }]);
    setLoading(true);
    try {
      const res = await fetch('http://localhost:5000/api/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          messages: [
            ...messages.map(m => ({ role: m.isUser ? 'user' : 'assistant', content: m.text })),
            { role: 'user', content: msg },
          ],
        }),
      });
      const data = await res.json();
      const reply = data.reply || 'Sorry, I could not get a response.';
      setMessages(m => [...m, { text: reply, isUser: false }]);
    } catch (err) {
      setMessages(m => [...m, { text: 'Error: ' + err.message, isUser: false }]);
    }
    setLoading(false);
  };

  return (
    <div className="flex flex-col h-full">
      <div className="flex-1 overflow-y-auto p-6 bg-gray-50">
        {messages.map((m, i) => (
          <MessageBubble key={i} message={m.text} isUser={m.isUser} />
        ))}
        {loading && <MessageBubble message="Thinking..." isUser={false} />}
      </div>
      <ChatInput onSend={handleSend} />
    </div>
  );
};

export default ChatWindow;
