<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Quran ChatBot</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex flex-col h-screen">

  <!-- Header -->
  <header class="p-6 text-center bg-gray-800 shadow-md">
    <h1 class="text-3xl font-bold text-blue-400">Quran ChatBot</h1>
    <p class="text-sm text-gray-300 mt-1">Tanyakan ayat dengan format: <span class="text-green-400">al-baqarah:2</span></p>
  </header>

  <!-- Chat Area -->
  <main id="chat-box" class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
    <!-- Chat akan tampil di sini -->
  </main>

  <!-- Input -->
  <footer class="bg-gray-800 p-4">
    <form id="chat-form" class="flex gap-2">
      <input type="text" id="user-input" placeholder="Ketik nama surat:ayat (contoh: al-baqarah:2)" 
             class="flex-1 px-4 py-2 rounded bg-gray-700 text-white focus:outline-none"
             required>
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Kirim</button>
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
    chatBox.innerHTML += `
      <div class="text-right">
        <div class="bg-blue-600 inline-block p-3 rounded-xl mb-1 max-w-lg">${userText}</div>
      </div>
    `;
    input.value = '';

    try {
      const res = await fetch('botman.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: userText })
      });

      const data = await res.json();

      chatBox.innerHTML += `
        <div class="text-left">
          <div class="bg-gray-700 inline-block p-3 rounded-xl max-w-lg">${data.reply}</div>
        </div>
      `;
    } catch (err) {
      chatBox.innerHTML += `
        <div class="text-left text-red-400">
          <i>Gagal memuat balasan: ${err.message}</i>
        </div>
      `;
    }

    chatBox.scrollTop = chatBox.scrollHeight;
  });
</script>
</body>
</html>
