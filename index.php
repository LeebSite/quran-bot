<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quran ChatBot</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex flex-col h-screen">

  <!-- Header -->
  <header class="p-6 text-center bg-gray-800 shadow-md">
    <h1 class="text-3xl font-bold text-blue-400">🕌 Quran ChatBot</h1>
    <p class="text-sm text-gray-300 mt-2">
      <span class="block">📖 Untuk ayat: <span class="text-green-400 font-mono">al-baqarah:2</span></span>
      <span class="block">📚 Untuk tafsir: <span class="text-yellow-400 font-mono">tafsir al-baqarah:2</span></span>
      <span class="text-xs text-gray-400 mt-1 block">⚠️ Pastikan menggunakan tanda titik dua (:) bukan titik koma</span>
    </p>
  </header>

  <!-- Chat Area -->
  <main id="chat-box" class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
    <div class="text-left">
      <div class="bg-gray-700 inline-block p-3 rounded-xl max-w-lg">
        <div class="text-blue-300">🤖 Assalamu'alaikum!</div>
        <div class="mt-2 text-sm">Saya siap membantu Anda mencari ayat Al-Qur'an dan tafsirnya.</div>
      </div>
    </div>
  </main>

  <!-- Input -->
  <footer class="bg-gray-800 p-4">
    <form id="chat-form" class="flex gap-2">
      <input type="text" id="user-input" 
             placeholder="Ketik: al-baqarah:2 atau tafsir al-baqarah:2" 
             class="flex-1 px-4 py-2 rounded bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
             required>
      <button type="submit" 
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
        Kirim
      </button>
    </form>
  </footer>

  <!-- Script -->
<script>
  const form = document.getElementById('chat-form');
  const input = document.getElementById('user-input');
  const chatBox = document.getElementById('chat-box');

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const userText = input.value.trim();
    if (!userText) return;

    // Tampilkan pesan user
    addMessage(userText, 'user');
    input.value = '';

    // Tampilkan loading
    const loadingId = addMessage('🔄 Mencari...', 'bot', true);

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

      // Cek apakah response kosong
      const responseText = await res.text();

      if (!responseText || !responseText.trim()) {
        throw new Error('Server mengembalikan response kosong');
      }

      console.log('Raw response:', responseText); // Debug log

      let data;
      try {
        data = JSON.parse(responseText);
      } catch (parseError) {
        throw new Error(`JSON parse error: ${parseError.message}. Response: ${responseText.substring(0, 200)}`);
      }

      if (data.reply) {
        addMessage(data.reply, 'bot');

        // Tampilkan debug info jika ada (untuk development)
        if (data.debug && window.location.hostname === 'localhost') {
          console.log('Debug info:', data.debug);
        }
      } else {
        addMessage('❌ Response tidak valid dari server', 'bot');
        console.log('Invalid response:', data);
      }

    } catch (err) {
      removeMessage(loadingId);
      addMessage(`❌ Error: ${err.message}`, 'bot');
      console.error('Full error:', err);
    }

    chatBox.scrollTop = chatBox.scrollHeight;
  });

  function addMessage(content, sender, isTemporary = false) {
    const messageId = 'msg-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    const isUser  = sender === 'user';

    const messageDiv = document.createElement('div');
    messageDiv.id = messageId;
    messageDiv.className = isUser  ? 'text-right' : 'text-left';

    const bubbleClass = isUser  
      ? 'bg-blue-600 text-white' 
      : 'bg-gray-700 text-white';

    messageDiv.innerHTML = 
      `<div class="${bubbleClass} inline-block p-3 rounded-xl mb-1 max-w-lg" style="word-wrap: break-word;">
        ${content}
      </div>`;

    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;

    return messageId;
  }

  function removeMessage(messageId) {
    const element = document.getElementById(messageId);
    if (element) {
      element.remove();
    }
  }

  // Auto focus input
  input.focus();
</script>
</body>
</html>
