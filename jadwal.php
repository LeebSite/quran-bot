    <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Sholat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">
  <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4 text-center text-blue-600">Jadwal Sholat</h1>

    <form method="GET" class="mb-6 flex gap-2">
      <input type="text" name="kota" placeholder="Masukkan nama kota..." class="flex-grow border p-2 rounded" required>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Cari</button>
    </form>

    <?php
    if (isset($_GET['kota'])) {
        $kota = urlencode($_GET['kota']);

        // Step 1: Cari ID kota
        $cariKotaJson = file_get_contents("https://api.myquran.com/v2/sholat/kota/cari/$kota");
        $cariKota = json_decode($cariKotaJson, true);

        if ($cariKota && isset($cariKota['data'][0])) {
            $idKota = $cariKota['data'][0]['id'];
            $namaKota = $cariKota['data'][0]['lokasi'];

            // Step 2: Ambil Jadwal Sholat
            $today = date('Y-m-d');
            $jadwalJson = file_get_contents("https://api.myquran.com/v2/sholat/jadwal/$idKota/$today");
            $jadwal = json_decode($jadwalJson, true);

            if ($jadwal && isset($jadwal['data']['jadwal'])) {
                $data = $jadwal['data']['jadwal'];
                echo "<div class='text-center mb-4'><h2 class='text-lg font-semibold text-green-600'>Jadwal Sholat - $namaKota</h2><p class='text-sm text-gray-500'>Tanggal: {$data['tanggal']}</p></div>";

                echo "<ul class='space-y-2'>";
                foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $sholat) {
                    echo "<li class='flex justify-between border-b pb-1'>
                            <span class='capitalize'>$sholat</span>
                            <span>{$data[$sholat]}</span>
                          </li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='text-red-500'>Gagal mengambil jadwal sholat.</p>";
            }
        } else {
            echo "<p class='text-red-500'>Kota tidak ditemukan.</p>";
        }
    }
    ?>
  </div>
</body>
</html>
