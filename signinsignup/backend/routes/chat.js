const express = require('express');
const fetch = require('node-fetch');
require('dotenv').config();

const router = express.Router();


router.post('/', async (req, res) => {
  try {
    const { messages } = req.body;
    const openaiRes = await fetch('https://api.openai.com/v1/chat/completions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${process.env.OPENAI_API_KEY}`,
      },
      body: JSON.stringify({
        model: 'gpt-3.5-turbo',
        messages: [
          { role: 'system', content: 'You are a helpful assistant.' },
          ...messages,
        ],
      }),
    });
    const data = await openaiRes.json();
    res.json({ reply: data.choices?.[0]?.message?.content || 'No response.' });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

module.exports = router;
