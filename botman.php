<?php
header('Content-Type: application/json');

// Baca pesan dari JSON body
$data = json_decode(file_get_contents("php://input"), true);
$message = strtolower(trim($data['message'] ?? ''));

// Cek format input "al-baqarah:2"
if (preg_match('/([a-z\-]+):([0-9]+)/', $message, $matches)) {
    $suratName = $matches[1];
    $ayat = $matches[2];

    // Daftar surat
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
        "yusuf" => 12
        // Tambahkan sesuai kebutuhan
    ];

    if (isset($daftarSurat[$suratName])) {
        $idSurat = $daftarSurat[$suratName];
        $apiUrl = "https://equran.id/api/surat/$idSurat/$ayat";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        if ($response === false) {
            echo json_encode(['reply' => '❌ Gagal menghubungi API (cURL).']);
            exit;
        }

        $json = json_decode($response, true);

        if ($json && isset($json['ayat'])) {
            $text = "<b>{$json['surat']['nama_latin']} : {$json['ayat']['nomor']}</b><br><i>{$json['ayat']['arab']}</i><br>{$json['ayat']['idn']}";
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
