<?php 
// Pastikan tidak ada output sebelum JSON
ob_clean();
ini_set('display_errors', 0);
error_reporting(0);

// Set headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Debug: log input
    $rawInput = file_get_contents("php://input");
    
    if (empty($rawInput)) {
        echo json_encode(['reply' => '❌ No input received', 'debug' => 'empty_input']);
        exit();
    }
    
    $data = json_decode($rawInput, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['reply' => '❌ JSON decode error: ' . json_last_error_msg(), 'debug' => $rawInput]);
        exit();
    }
    
    $message = isset($data['message']) ? strtolower(trim($data['message'])) : '';
    
    if (empty($message)) {
        echo json_encode(['reply' => '❌ Message is empty', 'debug' => $data]);
        exit();
    }

    // Daftar surat lengkap (114 surat)
    $daftarSurat = [
        "al-fatihah" => 1, "al-baqarah" => 2, "ali-imran" => 3, "an-nisa" => 4,
        "al-maidah" => 5, "al-an'am" => 6, "al-a'raf" => 7, "al-anfal" => 8,
        "at-taubah" => 9, "yunus" => 10, "hud" => 11, "yusuf" => 12,
        "ar-ra'd" => 13, "ibrahim" => 14, "al-hijr" => 15, "an-nahl" => 16,
        "al-isra" => 17, "al-kahf" => 18, "maryam" => 19, "ta-ha" => 20,
        "al-anbiya" => 21, "al-hajj" => 22, "al-mu'minun" => 23, "an-nur" => 24,
        "al-furqan" => 25, "asy-syu'ara" => 26, "an-naml" => 27, "al-qasas" => 28,
        "al-ankabut" => 29, "ar-rum" => 30, "luqman" => 31, "as-sajdah" => 32,
        "al-ahzab" => 33, "saba" => 34, "fatir" => 35, "yasin" => 36,
        "as-saffat" => 37, "sad" => 38, "az-zumar" => 39, "ghafir" => 40,
        "fussilat" => 41, "asy-syura" => 42, "az-zukhruf" => 43, "ad-dukhan" => 44,
        "al-jasiyah" => 45, "al-ahqaf" => 46, "muhammad" => 47, "al-fath" => 48,
        "al-hujurat" => 49, "qaf" => 50, "az-zariyat" => 51, "at-tur" => 52,
        "an-najm" => 53, "al-qamar" => 54, "ar-rahman" => 55, "al-waqi'ah" => 56,
        "al-hadid" => 57, "al-mujadilah" => 58, "al-hasyr" => 59, "al-mumtahanah" => 60,
        "as-saff" => 61, "al-jumu'ah" => 62, "al-munafiqun" => 63, "at-taghabun" => 64,
        "at-talaq" => 65, "at-tahrim" => 66, "al-mulk" => 67, "al-qalam" => 68,
        "al-haqqah" => 69, "al-ma'arij" => 70, "nuh" => 71, "al-jinn" => 72,
        "al-muzzammil" => 73, "al-muddassir" => 74, "al-qiyamah" => 75, "al-insan" => 76,
        "al-mursalat" => 77, "an-naba" => 78, "an-nazi'at" => 79, "abasa" => 80,
        "at-takwir" => 81, "al-infitar" => 82, "al-mutaffifin" => 83, "al-insyiqaq" => 84,
        "al-buruj" => 85, "at-tariq" => 86, "al-a'la" => 87, "al-gasyiyah" => 88,
        "al-fajr" => 89, "al-balad" => 90, "asy-syams" => 91, "al-lail" => 92,
        "ad-duha" => 93, "asy-syarh" => 94, "at-tin" => 95, "al-alaq" => 96,
        "al-qadr" => 97, "al-bayyinah" => 98, "az-zalzalah" => 99, "al-adiyat" => 100,
        "al-qari'ah" => 101, "at-takasur" => 102, "al-asr" => 103, "al-humazah" => 104,
        "al-fil" => 105, "quraisy" => 106, "al-ma'un" => 107, "al-kausar" => 108,
        "al-kafirun" => 109, "an-nasr" => 110, "al-lahab" => 111, "al-ikhlas" => 112,
        "al-falaq" => 113, "an-nas" => 114
    ];

    // Handle sapaan dan pesan umum
    if (in_array($message, ['assalamu\'alaikum', 'assalamualaikum', 'salam', 'halo', 'hai', 'hi'])) {
        $greetingText = "وعليكم السلام ورحمة الله وبركاته<br><br>";
        $greetingText .= "🤖 <b>Selamat datang di Quran ChatBot!</b><br><br>";
        $greetingText .= "Saya siap membantu Anda mencari ayat Al-Quran dan tafsirnya. Silakan gunakan format berikut:<br><br>";
        $greetingText .= "📖 <b>Untuk ayat:</b> <code>al-baqarah:2</code><br>";
        $greetingText .= "📚 <b>Untuk tafsir:</b> <code>tafsir al-baqarah:2</code><br><br>";
        $greetingText .= "<small>💡 Tips: Pastikan menggunakan tanda titik dua (:) dan nama surat dengan huruf kecil</small>";
        echo json_encode(['reply' => $greetingText]);
        exit();
    }

    if (in_array($message, ['help', 'bantuan', 'panduan'])) {
        $helpText = "📋 <b>Panduan Penggunaan Quran ChatBot</b><br><br>";
        $helpText .= "🔹 <b>Format Ayat:</b> <code>nama-surat:nomor-ayat</code><br>";
        $helpText .= "   Contoh: <code>al-baqarah:255</code><br><br>";
        $helpText .= "🔹 <b>Format Tafsir:</b> <code>tafsir nama-surat:nomor-ayat</code><br>";
        $helpText .= "   Contoh: <code>tafsir al-baqarah:255</code><br><br>";
        $helpText .= "📝 <b>Beberapa nama surat populer:</b><br>";
        $helpText .= "• al-fatihah, al-baqarah, ali-imran<br>";
        $helpText .= "• an-nisa, al-maidah, al-an'am<br>";
        $helpText .= "• yasin, ar-rahman, al-waqi'ah<br>";
        $helpText .= "• al-ikhlas, al-falaq, an-nas";
        echo json_encode(['reply' => $helpText]);
        exit();
    }

    // Cek format untuk ayat biasa: surat:ayat
    if (preg_match('/^([a-z\-\']+):([0-9]+)$/', $message, $matches)) {
        $suratName = $matches[1];
        $ayat = intval($matches[2]);
        
        if (!isset($daftarSurat[$suratName])) {
            echo json_encode(['reply' => "❌ Nama surat '<b>$suratName</b>' tidak dikenali.<br><br>💡 Contoh nama surat yang benar:<br>• al-baqarah:2<br>• ali-imran:3<br>• yasin:1<br><br>Ketik <code>help</code> untuk panduan lengkap."]);
            exit();
        }
        
        $idSurat = $daftarSurat[$suratName];
        $apiUrl = "https://equran.id/api/v2/surat/$idSurat";
        
        $response = getApiData($apiUrl);
        
        if (!$response) {
            echo json_encode(['reply' => '❌ Gagal menghubungi server Al-Quran. Silakan coba lagi.']);
            exit();
        }
        
        $json = json_decode($response, true);
        
        if (!$json || !isset($json['data']['ayat'])) {
            echo json_encode(['reply' => '❌ Data tidak valid dari server']);
            exit();
        }
        
        // Cari ayat - perbaikan utama di sini
        $ayatData = null;
        $totalAyat = count($json['data']['ayat']);
        
        foreach ($json['data']['ayat'] as $item) {
            // Cek field nomorAyat (sesuai dengan contoh JSON yang diberikan)
            if (isset($item['nomorAyat']) && $item['nomorAyat'] == $ayat) {
                $ayatData = $item;
                break;
            }
        }
        
        if ($ayatData) {
            // Gunakan field yang benar sesuai API
            $namaSurat = $json['data']['namaLatin'] ?? 'Unknown';  // Perbaikan: gunakan namaLatin
            $namaSuratArab = $json['data']['nama'] ?? '';
            $nomorAyat = $ayatData['nomorAyat'] ?? $ayat;
            $teksArab = $ayatData['teksArab'] ?? 'النص العربي غير متوفر';  // Perbaikan: gunakan teksArab
            $teksIndonesia = $ayatData['teksIndonesia'] ?? 'Terjemahan tidak tersedia';  // Perbaikan: gunakan teksIndonesia
            
            $text = "📖 <b>$namaSurat ($namaSuratArab) : $nomorAyat</b><br><br>";
            $text .= "<div style='font-size: 20px; text-align: right; margin: 15px 0; font-family: \"Amiri\", \"Traditional Arabic\", serif; line-height: 1.8; padding: 10px; background: rgba(59, 130, 246, 0.1); border-radius: 8px;'>$teksArab</div>";
            $text .= "<div style='margin: 15px 0; line-height: 1.6; font-size: 15px; padding: 10px 0;'><i>\"$teksIndonesia\"</i></div>";
            $text .= "<hr style='margin: 15px 0; border: none; border-top: 1px solid #374151;'>";
            $text .= "<small style='color: #9CA3AF;'>💡 Untuk melihat tafsir ayat ini, ketik: <code>tafsir $suratName:$ayat</code></small>";
            
            echo json_encode(['reply' => $text]);
            exit();
        } else {
            echo json_encode(['reply' => "❌ Ayat <b>$ayat</b> tidak ditemukan di surat <b>$suratName</b>.<br><br>📋 Surat ini memiliki <b>$totalAyat ayat</b>. Silakan pilih nomor ayat antara 1-$totalAyat."]);
            exit();
        }
    }
    // Cek format untuk tafsir: tafsir surat:ayat
    elseif (preg_match('/^tafsir ([a-z\-\']+)[\:\-]([0-9]+)$/', $message, $matches)) {
        $suratName = $matches[1];
        $ayat = intval($matches[2]);
        
        if (!isset($daftarSurat[$suratName])) {
            echo json_encode(['reply' => "❌ Nama surat '<b>$suratName</b>' tidak dikenali untuk tafsir.<br><br>💡 Contoh: <code>tafsir al-baqarah:255</code>"]);
            exit();
        }
        
        $idSurat = $daftarSurat[$suratName];
        
        // Ambil data ayat terlebih dahulu
        $ayatUrl = "https://equran.id/api/v2/surat/$idSurat";
        $ayatResponse = getApiData($ayatUrl);
        
        // Ambil data tafsir
        $tafsirUrl = "https://equran.id/api/v2/tafsir/$idSurat";
        $tafsirResponse = getApiData($tafsirUrl);
        
        if (!$ayatResponse || !$tafsirResponse) {
            echo json_encode(['reply' => '❌ Gagal menghubungi server untuk data ayat atau tafsir']);
            exit();
        }
        
        $ayatJson = json_decode($ayatResponse, true);
        $tafsirJson = json_decode($tafsirResponse, true);
        
        if (!$ayatJson || !$tafsirJson || !isset($ayatJson['data']['ayat']) || !isset($tafsirJson['data']['tafsir'])) {
            echo json_encode(['reply' => '❌ Data ayat atau tafsir tidak valid']);
            exit();
        }
        
        // Cari ayat dengan field yang benar
        $ayatData = null;
        foreach ($ayatJson['data']['ayat'] as $item) {
            if (isset($item['nomorAyat']) && $item['nomorAyat'] == $ayat) {  // Gunakan nomorAyat
                $ayatData = $item;
                break;
            }
        }
        
        // Cari tafsir
        $tafsirData = null;
        foreach ($tafsirJson['data']['tafsir'] as $item) {
            if (isset($item['ayat']) && $item['ayat'] == $ayat) {
                $tafsirData = $item;
                break;
            }
        }
        
        if ($ayatData && $tafsirData) {
            $namaSurat = $ayatJson['data']['namaLatin'] ?? 'Unknown';  // Gunakan namaLatin
            $namaSuratArab = $ayatJson['data']['nama'] ?? '';
            $nomorAyat = $ayatData['nomorAyat'] ?? $ayat;
            $teksArab = $ayatData['teksArab'] ?? 'النص العربي غير متوفر';  // Gunakan teksArab
            $teksIndonesia = $ayatData['teksIndonesia'] ?? 'Terjemahan tidak tersedia';  // Gunakan teksIndonesia
            $teksTafsir = $tafsirData['teks'] ?? 'Tafsir tidak tersedia';
            
            $text = "📚 <b>Tafsir $namaSurat ($namaSuratArab) : $nomorAyat</b><br><br>";
            
            // Tampilkan ayat terlebih dahulu
            $text .= "<div style='background: rgba(34, 197, 94, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px;'>";
            $text .= "<div style='font-size: 18px; text-align: right; margin-bottom: 10px; font-family: \"Amiri\", \"Traditional Arabic\", serif; line-height: 1.8;'>$teksArab</div>";
            $text .= "<div style='font-style: italic; font-size: 14px; color: #10B981;'>\"$teksIndonesia\"</div>";
            $text .= "</div>";
            
            // Tampilkan tafsir
            $text .= "<div style='background: rgba(59, 130, 246, 0.05); padding: 15px; border-radius: 8px;'>";
            $text .= "<h4 style='color: #3B82F6; margin-bottom: 10px;'>📖 Tafsir:</h4>";
            $text .= "<div style='text-align: justify; line-height: 1.7; font-size: 14px;'>$teksTafsir</div>";
            $text .= "</div>";
            
            echo json_encode(['reply' => $text]);
            exit();
        } else {
            $totalTafsir = count($tafsirJson['data']['tafsir']);
            echo json_encode(['reply' => "❌ Tafsir untuk ayat <b>$ayat</b> tidak ditemukan di surat <b>$suratName</b>.<br><br>📋 Tersedia tafsir untuk <b>$totalTafsir ayat</b> dalam surat ini."]);
            exit();
        }
    }
    else {
        $helpText = "❗ Format pesan tidak dikenali.<br><br>";
        $helpText .= "<b>💬 Gunakan format berikut:</b><br>";
        $helpText .= "📖 Untuk ayat: <code>al-baqarah:255</code><br>";
        $helpText .= "📚 Untuk tafsir: <code>tafsir al-baqarah:255</code><br><br>";
        $helpText .= "<b>🗣️ Atau coba perintah:</b><br>";
        $helpText .= "• <code>help</code> - Panduan lengkap<br>";
        $helpText .= "• <code>assalamualaikum</code> - Sapaan<br><br>";
        $helpText .= "<small>💡 Pesan Anda: \"<i>$message</i>\"</small>";
        echo json_encode(['reply' => $helpText]);
    }

} catch (Exception $e) {
    echo json_encode(['reply' => '❌ Terjadi kesalahan sistem. Silakan coba lagi.', 'debug' => $e->getMessage()]);
}

function getApiData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; QuranBot/2.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response === false || $httpCode !== 200) {
        return false;
    }
    
    return $response;
}

// Pastikan output bersih
ob_end_clean();
?>