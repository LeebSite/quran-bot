<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quran ChatBot | Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="src/icon-quran-bot.png">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

  <!-- Header -->
  <header class="p-6 bg-gray-800 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold text-blue-400">Quran ChatBot</h1>
      <nav>
        <a href="chatbot.php" class="text-sm bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white transition">Masuk ke ChatBot</a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-grow flex items-center justify-center text-center px-4">
    <div class="max-w-2xl">
      <h2 class="text-4xl font-extrabold mb-4 text-white">Selamat Datang di Quran ChatBot</h2>
      <p class="text-gray-300 mb-8">
        Dapatkan jawaban langsung dari Al-Qur'an hanya dengan mengetikkan nama surat dan ayat. Contoh: <span class="text-green-400">al-baqarah:2</span>
      </p>
      <a href="chatbot.php" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded transition">
        Mulai Chat Sekarang
      </a>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-center p-4 text-sm text-gray-400">
    &copy; <?= date('Y'); ?> Quran ChatBot. All rights reserved.
  </footer>

</body>
</html>
