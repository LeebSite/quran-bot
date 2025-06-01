<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jadwal Shalat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="src/icon-quran-bot.png" />
  <style>
    body {
      font-family: 'Nunito', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

  <!-- Header -->
  <header class="bg-gray-800 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
      <h1 class="text-2xl font-bold text-blue-400">Jadwal Shalat</h1>
      <nav>
        <a href="index.php" class="text-sm text-gray-300 hover:text-white transition">Home</a>
      </nav>
    </div>
  </header>

  <!-- Main -->
  <main class="flex-grow px-6 py-10">
    <div class="max-w-2xl mx-auto text-center">
      <h2 class="text-3xl font-bold mb-4">Cek Jadwal Shalat</h2>
      <form method="GET" class="mb-6">
        <input
          type="text"
          name="kota"
          placeholder="Masukkan nama kota (misal: kediri)"
          class="w-full px-4 py-2 rounded text-black focus:outline-none"
          value="<?= isset($_GET['kota']) ? htmlspecialchars($_GET['kota']) : '' ?>"
        />
        <button
          type="submit"
          class="mt-4 bg-green-500 hover:bg-green-600 px-6 py-2 text-white rounded"
        >
          Cari Jadwal
        </button>
      </form>

      <?php
      if (isset($_GET['kota']) && !empty($_GET['kota'])) {
        $keyword = urlencode($_GET['kota']);
        $searchUrl = "https://api.myquran.com/v2/sholat/kota/cari/$keyword";
        $searchData = json_decode(file_get_contents($searchUrl), true);

        if ($searchData && $searchData['status'] && count($searchData['data']) > 0) {
          $kotaId = $searchData['data'][0]['id'];
          $lokasi = $searchData['data'][0]['lokasi'];

          $date = date('Y-m-d');
          $jadwalUrl = "https://api.myquran.com/v2/sholat/jadwal/$kotaId/$date";
          $jadwalData = json_decode(file_get_contents($jadwalUrl), true);

          if ($jadwalData && $jadwalData['status']) {
            $jadwal = $jadwalData['data']['jadwal'];
            echo "<div class='bg-gray-800 p-6 rounded shadow-md text-left'>";
            echo "<h3 class='text-xl font-semibold mb-2 text-blue-300'>Jadwal Shalat untuk {$lokasi}</h3>";
            echo "<p class='text-sm text-gray-400 mb-4'>Tanggal: {$jadwal['tanggal']}</p>";
            echo "<ul class='space-y-2'>";
            foreach (['imsak', 'subuh', 'terbit', 'dhuha', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $waktu) {
              echo "<li class='flex justify-between border-b border-gray-700 py-1'>
                      <span class='capitalize'>" . ucfirst($waktu) . "</span>
                      <span>{$jadwal[$waktu]}</span>
                    </li>";
            }
            echo "</ul></div>";
          } else {
            echo "<p class='text-red-400 mt-4'>Gagal memuat jadwal shalat.</p>";
          }
        } else {
          echo "<p class='text-yellow-400 mt-4'>Kota tidak ditemukan. Coba nama lain.</p>";
        }
      }
      ?>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-center p-4 text-sm text-gray-400">
    &copy; <?= date('Y'); ?> Quran ChatBot. All rights reserved.
  </footer>

</body>
</html>
