<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quran ChatBot</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .arabic-text {
      font-family: 'Amiri', 'Traditional Arabic', serif;
    }
    .chat-animation {
      animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .typing-indicator {
      animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
      0%, 50%, 100% { opacity: 1; }
      25%, 75% { opacity: 0.5; }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 text-white flex flex-col h-screen">

  <!-- Header -->
  <header class="p-6 text-center bg-gray-800/80 backdrop-blur-sm shadow-lg border-b border-blue-500/20">
    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-green-400 bg-clip-text text-transparent">
      🕌 Al-Quran ChatBot
    </h1>
    <p class="text-sm text-gray-300 mt-3 space-y-1">
      <span class="block">📖 Ayat: <span class="text-green-400 font-mono bg-gray-700/50 px-2 py-1 rounded">al-baqarah:255</span></span>
      <span class="block">📚 Tafsir: <span class="text-yellow-400 font-mono bg-gray-700/50 px-2 py-1 rounded">tafsir al-baqarah:255</span></span>
      <span class="text-xs text-gray-400 mt-2 block">⚡ Ketik "help" untuk panduan lengkap</span>
    </p>
  </header>

  <!-- Chat Area -->
  <main id="chat-box" class="flex-1 overflow-y-auto px-4 py-6 space-y-4 scrollbar-thin scrollbar-thumb-gray-600">
    <!-- Initial greeting akan dimuat via JavaScript -->
  </main>

  <!-- Input -->
  <footer class="bg-gray-800/90 backdrop-blur-sm p-4 border-t border-gray-700/50">
    <form id="chat-form" class="flex gap-3 max-w-4xl mx-auto">
      <input type="text" id="user-input" 
             placeholder="Ketik: assalamualaikum, help, al-baqarah:255, atau tafsir al-baqarah:255" 
             class="flex-1 px-4 py-3 rounded-lg bg-gray-700/80 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-gray-700 transition-all duration-200"
             required>
      <button type="submit" 
              id="send-btn"
              class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
        <span id="send-text">Kirim</span>
        <span id="loading-text" class="hidden">⏳</span>
      </button>
    </form>
    
    <!-- Quick actions -->
    <div class="flex flex-wrap gap-2 mt-3 justify-center">
      <button onclick="sendQuickMessage('assalamualaikum')" class="text-xs bg-green-600/20 hover:bg-green-600/40 text-green-300 px-3 py-1 rounded-full transition-colors">
        👋 Salam
      </button>
      <button onclick="sendQuickMessage('help')" class="text-xs bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 px-3 py-1 rounded-full transition-colors">
        ❓ Help
      </button>
      <button onclick="sendQuickMessage('al-fatihah:1')" class="text-xs bg-purple-600/20 hover:bg-purple-600/40 text-purple-300 px-3 py-1 rounded-full transition-colors">
        📖 Al-Fatihah
      </button>
      <button onclick="sendQuickMessage('tafsir al-baqarah:255')" class="text-xs bg-yellow-600/20 hover:bg-yellow-600/40 text-yellow-300 px-3 py-1 rounded-full transition-colors">
        📚 Ayat Kursi
      </button>
    </div>
  </footer>

  <!-- Script -->
<script>
  const form = document.getElementById('chat-form');
  const input = document.getElementById('user-input');
  const chatBox = document.getElementById('chat-box');
  const sendBtn = document.getElementById('send-btn');
  const sendText = document.getElementById('send-text');
  const loadingText = document.getElementById('loading-text');

  // Load initial greeting
  window.addEventListener('load', function() {
    const welcomeMsg = `
      <div class="text-center p-6 bg-gradient-to-r from-blue-600/20 to-green-600/20 rounded-xl border border-blue-500/30">
        <div class="text-2xl mb-3">🕌</div>
        <div class="text-lg font-semibold text-blue-300 mb-2">Selamat datang di Al-Quran ChatBot</div>
        <div class="text-sm text-gray-300 mb-4">Asisten digital untuk membaca Al-Quran dan memahami tafsirnya</div>
        <div class="text-xs text-gray-400">
          💡 Mulai dengan mengetik <span class="text-green-400 font-mono">"assalamualaikum"</span> atau langsung cari ayat
        </div>
      </div>
    `;
    addMessage(welcomeMsg, 'bot');
  });

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const userText = input.value.trim();
    if (!userText) return;

    await sendMessage(userText);
  });

  async function sendMessage(userText) {
    // Tampilkan pesan user
    addMessage(userText, 'user');
    input.value = '';

    // Update button state
    setLoading(true);

    // Tampilkan typing indicator
    const loadingId = addMessage(`
      <div class="typing-indicator flex items-center space-x-2">
        <div class="flex space-x-1">
          <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
          <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
          <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        </div>
        <span class="text-sm text-gray-400">Sedang mencari...</span>
      </div>
    `, 'bot', true);

    try {
      const res = await fetch('botman.php', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ message: userText })
      });

      // Hapus loading message
      removeMessage(loadingId);

      if (!res.ok) {
        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
      }

      const responseText = await res.text();
      
      if (!responseText || !responseText.trim()) {
        throw new Error('Server mengembalikan response kosong');
      }

      let data;
      try {
        data = JSON.parse(responseText);
      } catch (parseError) {
        throw new Error(`JSON parse error: ${parseError.message}`);
      }
      
      if (data.reply) {
        addMessage(data.reply, 'bot');
        
        // Show debug info in development
        if (data.debug && window.location.hostname === 'localhost') {
          console.log('Debug info:', data.debug);
        }
      } else {
        addMessage('❌ Response tidak valid dari server', 'bot');
      }

    } catch (err) {
      removeMessage(loadingId);
      
      let errorMsg = `❌ <b>Terjadi kesalahan:</b><br><small>${err.message}</small><br><br>💡 <b>Solusi:</b><br>• Periksa koneksi internet<br>• Coba lagi dalam beberapa saat<br>• Pastikan format penulisan benar`;
      addMessage(errorMsg, 'bot');
      console.error('Full error:', err);
    } finally {
      setLoading(false);
    }

    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function sendQuickMessage(message) {
    input.value = message;
    sendMessage(message);
  }

  function setLoading(isLoading) {
    if (isLoading) {
      sendBtn.disabled = true;
      sendBtn.classList.add('opacity-50', 'cursor-not-allowed');
      sendText.classList.add('hidden');
      loadingText.classList.remove('hidden');
    } else {
      sendBtn.disabled = false;
      sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
      sendText.classList.remove('hidden');
      loadingText.classList.add('hidden');
    }
  }

  function addMessage(content, sender, isTemporary = false) {
    const messageId = 'msg-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    const isUser = sender === 'user';
    
    const messageDiv = document.createElement('div');
    messageDiv.id = messageId;
    messageDiv.className = `chat-animation ${isUser ? 'text-right' : 'text-left'}`;
    
    const bubbleClass = isUser 
      ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg' 
      : 'bg-gray-700/80 backdrop-blur-sm text-white shadow-lg border border-gray-600/30';
    
    const maxWidth = isUser ? 'max-w-sm ml-auto' : 'max-w-4xl mr-auto';
    
    messageDiv.innerHTML = `
      <div class="${bubbleClass} ${maxWidth} inline-block p-4 rounded-2xl mb-2" style="word-wrap: break-word;">
        ${isUser ? `<div class="flex items-center gap-2 mb-1"><span class="text-xs opacity-75">Anda</span></div>` : ''}
        <div class="${isUser ? 'text-sm' : ''}">${content}</div>
        ${!isUser ? `<div class="text-xs text-gray-400 mt-2 opacity-60">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</div>` : ''}
      </div>
    `;
    
    chatBox.appendChild(messageDiv);
    
    // Smooth scroll to bottom
    setTimeout(() => {
      chatBox.scrollTo({
        top: chatBox.scrollHeight,
        behavior: 'smooth'
      });
    }, 100);
    
    return messageId;
  }

  function removeMessage(messageId) {
    const element = document.getElementById(messageId);
    if (element) {
      element.style.opacity = '0';
      element.style.transform = 'translateY(-10px)';
      setTimeout(() => element.remove(), 200);
    }
  }

  // Auto focus input and handle enter key
  input.focus();
  
  // Add some helpful keyboard shortcuts
  input.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.ctrlKey) {
      // Ctrl+Enter for quick send
      form.dispatchEvent(new Event('submit'));
    }
  });

  // Add placeholder rotation for better UX
  const placeholders = [
    "Ketik: assalamualaikum untuk memulai...",
    "Contoh: al-baqarah:255 untuk Ayat Kursi",
    "Contoh: tafsir al-fatihah:1 untuk tafsir",
    "Ketik: help untuk panduan lengkap",
    "Contoh: yasin:36 atau an-nas:1"
  ];
  
  let placeholderIndex = 0;
  setInterval(() => {
    placeholderIndex = (placeholderIndex + 1) % placeholders.length;
    input.placeholder = placeholders[placeholderIndex];
  }, 4000);

  // Add smooth scrolling behavior for chat
  chatBox.style.scrollBehavior = 'smooth';

  // Handle visibility change to refocus input
  document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
      input.focus();
    }
  });
</script>

</body>
</html>