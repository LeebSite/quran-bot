<?php
header('Content-Type: application/json');

// Ambil input dari user
$data = json_decode(file_get_contents("php://input"), true);
$message = strtolower(trim($data['message'] ?? ''));

// Format yang valid: al-baqarah:2
if (preg_match('/([a-z\-]+):([0-9]+)/', $message, $matches)) {
    $suratName = $matches[1];
    $ayat = intval($matches[2]);

    // Pemetaan surat ke ID
    $daftarSurat = [
        "al-fatihah" => 1,
        "al-baqarah" => 2,
        "ali-imran" => 3,
        "an-nisa" => 4,
        "al-maidah" => 5,
        "al-anam" => 6,
        "al-araf" => 7,
        "al-anfal" => 8,
        "at-taubah" => 9,
        "yunus" => 10,
        "hud" => 11,
        "yusuf" => 12,
        // Tambahkan lebih banyak sesuai kebutuhan
    ];

    if (isset($daftarSurat[$suratName])) {
        $idSurat = $daftarSurat[$suratName];
        $apiUrl = "https://equran.id/api/v2/surat/$idSurat";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            echo json_encode(['reply' => '❌ Gagal menghubungi API.']);
            exit;
        }

        $json = json_decode($response, true);
        $foundAyat = null;

        foreach ($json['data']['ayat'] as $item) {
            if ($item['nomor'] == $ayat) {
                $foundAyat = $item;
                break;
            }
        }

        if ($foundAyat) {
            $namaSurat = $json['data']['nama_latin'];
            $text = "<b>$namaSurat : {$foundAyat['nomor']}</b><br><i>{$foundAyat['teks_arab']}</i><br>{$foundAyat['teks_indonesia']}";
            echo json_encode(['reply' => $text]);
        } else {
            echo json_encode(['reply' => '❌ Ayat tidak ditemukan.']);
        }
    } else {
        echo json_encode(['reply' => '❌ Nama surat tidak dikenali.']);
    }
} else {
    echo json_encode(['reply' => '❗ Format salah. Gunakan format: al-baqarah:2']);
}
