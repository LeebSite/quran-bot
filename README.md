# 🕌 Quran ChatBot

Quran ChatBot adalah aplikasi web sederhana berbasis PHP yang memungkinkan pengguna untuk berinteraksi dan mencari ayat-ayat dari Al-Qur'an melalui input sederhana seperti `al-baqarah:2`. Aplikasi ini juga menyediakan fitur tambahan seperti pencarian jadwal shalat berdasarkan nama kota.

## ✨ Fitur Utama

- 🎙️ **ChatBot Al-Qur'an**: Menjawab input ayat seperti `an-nas:1` langsung dari Al-Qur'an.
- 📅 **Jadwal Shalat**: Cari jadwal shalat harian berdasarkan nama kota.
- 🌙 **Tampilan Dark Mode Elegan** dengan TailwindCSS.
- ⚡ Ringan, cepat, dan dapat dijalankan di localhost tanpa framework berat.

## 🚀 Teknologi yang Digunakan

- PHP Native
- TailwindCSS (via CDN)
- API Al-Qur'an dan Jadwal Shalat dari [MyQuran API](https://api.myquran.com/)
- HTML5 & CSS3
- JSON (untuk parsing data)

## 📦 Struktur Proyek

```
📁 src/
    └── icon-quran-bot.png
📄 index.php           # Landing Page utama
📄 chatbot.php         # Halaman utama ChatBot Al-Qur'an
📄 jadwal.php          # Halaman pencarian dan tampilan jadwal shalat
📄 styles.css          # Optional tambahan styling
📄 README.md
```

## 🛠️ Cara Menjalankan

1. Clone repositori ini:
   ```bash
   git clone https://github.com/username/quran-chatbot.git
   cd quran-chatbot
   ```
2. Jalankan server lokal (contoh dengan PHP built-in):
   ```bash
   php -S localhost:8000
   ```
3. Buka di browser:
   ```
   http://localhost:8000/index.php
   ```

## 💡 Contoh Penggunaan

- Masukkan `al-fatihah:1` ke ChatBot untuk mendapatkan ayat.
- Cari `jakarta` di halaman jadwal shalat dan dapatkan waktu shalat hari ini.

## 📷 Tangkapan Layar

> Tambahkan screenshot halaman ChatBot dan Jadwal Shalat di sini

## 📚 API Referensi

- [API Jadwal Shalat - MyQuran](https://api.myquran.com/v2/sholat)
- [API Surah & Ayat](https://api.myquran.com/v1/alquran/)

## 🙏 Kontribusi

Pull request sangat disambut! Jika kamu memiliki ide, perbaikan bug, atau peningkatan desain, jangan ragu untuk fork dan PR.

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi MIT.

---

> Dibuat dengan niat baik agar mempermudah pembelajaran dan akses terhadap ayat-ayat Al-Qur'an dan jadwal ibadah shalat.
