Berikut adalah panduan langkah demi langkah tentang cara menghubungkan proyek web yang dihosted ke aplikasi seluler Anda, beserta modifikasi kode yang diperlukan untuk domain https://neoblue.gt.tc.

### 1. **Hosting Proyek Web Anda**

Pertama, Anda perlu mengunggah seluruh folder proyek `webneoblue` Anda (termasuk `api`, `config`, `vendor`, dll.) ke penyedia hosting web publik. Berikut adalah langkah-langkah umumnya:

1.  **Pilih Penyedia Hosting**: Pilih penyedia yang mendukung PHP dan MySQL (misalnya, Bluehost, Hostinger, SiteGround, atau hosting bersama/VPS lainnya). Untuk domain gt.tc, pastikan penyedia hosting mendukung subdomain custom atau gunakan hosting yang kompatibel dengan gt.tc (seperti InfinityFree atau 000webhost yang sering digunakan untuk subdomain gratis).
2.  **Unggah File Anda**: Gunakan klien FTP (seperti FileZilla) atau Manajer File penyedia hosting untuk mengunggah semua file dan folder dari direktori `c:\laragon\www\webneoblue` Anda ke direktori `public_html` (atau `www`, `htdocs`) di server hosting Anda.
3.  **Siapkan Database**:
    *   Buat database MySQL baru di panel kontrol penyedia hosting Anda (seperti cPanel).
    *   Buat pengguna database baru dan tetapkan ke database dengan hak penuh.
    *   Impor file `db/db_neoblue.sql` Anda ke database baru menggunakan alat seperti phpMyAdmin yang disediakan oleh host Anda.
4.  **Perbarui `config/koneksi.php`**:
    *   Buka file `config/koneksi.php` di server Anda dan perbarui detail koneksi database (nama host, nama pengguna, kata sandi, dan nama database) agar sesuai dengan yang baru saja Anda buat di penyedia hosting Anda.

    ```php
    <?php
    // config/koneksi.php

    // Sesuaikan dengan detail database hosting Anda
    $host = 'localhost'; // Biasanya 'localhost', tetapi periksa dokumentasi penyedia Anda
    $user = 'your_hosting_db_user'; // Nama pengguna database baru Anda
    $pass = 'your_hosting_db_password'; // Kata sandi database baru Anda
    $db   = 'your_hosting_db_name';   // Nama database baru Anda

    $koneksi = mysqli_connect($host, $user, $pass, $db);

    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
    ?>
    ```
5.  **Perbarui `config/smtp.php`**:
    *   Jika Anda menggunakan SMTP untuk mengirim email, Anda mungkin perlu memperbarui pengaturan SMTP di `config/smtp.php` agar sesuai dengan pengaturan server email penyedia hosting Anda.
6.  **Uji API Anda di Browser**:
    *   Setelah semuanya diunggah dan dikonfigurasi, Anda seharusnya dapat mengakses titik akhir API Anda di browser web. Misalnya, untuk domain https://neoblue.gt.tc, Anda seharusnya dapat mengunjungi `https://neoblue.gt.tc/api/api_artikel.php` dan melihat output JSON.

### 2. **Menghubungkan Aplikasi Seluler ke API yang Dihosting**

Sekarang, Anda perlu memperbarui aplikasi seluler untuk menunjuk ke titik akhir API yang baru Anda hosting.

1.  **Perbarui `ApiConfig.java`**:
    *   Buka proyek Android Anda dan navigasikan ke `mobile/app/src/main/java/com/example/neoblue/api/ApiConfig.java`.
    *   Ubah konstanta `BASE_URL` ke domain hosting baru Anda. Pastikan untuk menggunakan `https` jika hosting Anda memiliki sertifikat SSL (yang sangat disarankan).

    ```java
    package com.example.neoblue.api;

    public class ApiConfig {
        // Ganti URL ini dengan URL hosting Anda
        public static final String BASE_URL = "https://neoblue.gt.tc/api/"; // <-- SANGAT PENTING

        public static final int TIMEOUT = 30000; // 30 detik
    }
    ```

### 3. **Memperbarui Tautan Reset Kata Sandi**

Email reset kata sandi Anda mengirimkan tautan kepada pengguna. Tautan ini juga perlu diperbarui untuk menunjuk ke domain baru Anda.

1.  **Perbarui `api/api_forgot_password.php`**:
    *   Buka file `api/api_forgot_password.php` di server Anda.
    *   Skrip menggunakan `$_SERVER['HTTP_HOST']` untuk menghasilkan tautan, yang seharusnya secara otomatis menggunakan domain baru Anda. Namun, ada baiknya untuk memastikan tautan dibuat dengan benar dan menunjuk ke halaman `reset-password.php` berbasis web. Aplikasi seluler akan mencegat tautan ini jika dikonfigurasi untuk deep linking.

    ```php
    <?php
    // ... (kode lainnya)

    // Tautan harus dibuat dengan benar secara otomatis.
    // Pastikan jalurnya benar jika Anda telah mengubah struktur folder.
    $host = $_SERVER['HTTP_HOST']; // Ini sekarang akan menjadi 'neoblue.gt.tc'
    $path = dirname($_SERVER['PHP_SELF']); // Seharusnya /api

    // Buat tautan untuk menunjuk ke halaman reset web, yang akan dicegat oleh aplikasi.
    $link = "https://" . $host . str_replace('/api', '', $path) . "/reset-password.php?token=" . $token;

    // ... (kode lainnya)
    ?>
    ```

### 4. **Deep Linking (Android)**

Agar tautan reset kata sandi dapat membuka aplikasi Anda secara langsung, Anda perlu mengonfigurasi deep link di proyek Android Anda.

1.  **Perbarui `AndroidManifest.xml`**:
    *   Buka `mobile/app/src/main/AndroidManifest.xml`.
    *   Temukan tag `<activity>` untuk `ResetPasswordActivity`.
    *   Tambahkan `<intent-filter>` ke dalamnya, dengan menentukan skema URL, host, dan path yang harus direspons oleh aplikasi.

    ```xml
    <activity android:name=".ResetPasswordActivity">
        <intent-filter>
            <action android:name="android.intent.action.VIEW" />
            <category android:name="android.intent.category.DEFAULT" />
            <category android:name="android.intent.category.BROWSABLE" />
            <!-- PENTING: Ubah 'domainanda.com' menjadi domain Anda yang sebenarnya -->
            <data
                android:scheme="https"
                android:host="neoblue.gt.tc"
                android:path="/reset-password.php" />
        </intent-filter>
    </activity>
    ```

    **Cara kerjanya**: Ketika pengguna mengklik tautan seperti `https://neoblue.gt.tc/reset-password.php?token=...`, Android akan memeriksa apakah ada aplikasi yang terpasang yang terdaftar untuk menangani URL ini. Karena filter intent ini, aplikasi Anda akan diberi opsi untuk membuka tautan, dan itu akan meluncurkan `ResetPasswordActivity` secara langsung. Metode `getIntent().getData()` di `ResetPasswordActivity.java` kemudian akan mengurai token dari URL dengan benar.

### Ringkasan Perubahan

1.  **Proyek Web**:
    *   Hosting proyek `webneoblue` Anda di server publik.
    *   Perbarui `config/koneksi.php` dengan detail database hosting Anda.
    *   Pastikan `api/api_forgot_password.php` menghasilkan tautan reset yang benar menggunakan domain baru Anda.
2.  **Aplikasi Seluler**:
    *   Perbarui `ApiConfig.java` untuk mengarahkan `BASE_URL` ke URL hosting baru Anda (`https://neoblue.gt.tc/api/`).
    *   Perbarui `AndroidManifest.xml` untuk mengaktifkan deep linking untuk `ResetPasswordActivity`, menggunakan domain baru Anda sebagai host.

Setelah membuat perubahan ini, bangun kembali aplikasi Android Anda untuk membuat APK baru. Versi baru aplikasi ini akan berkomunikasi dengan server langsung Anda alih-alih mesin lokal Anda.

## Tambahan: Ringkasan Perubahan yang Perlu Dilakukan

Berikut ringkasan cepat dan checklist perubahan yang harus Anda lakukan saat memindahkan proyek `webneoblue` ke hosting publik dan menghubungkannya ke aplikasi mobile:

- **Unggah Proyek**: Upload seluruh folder `webneoblue` ke direktori publik host (`public_html`/`www`/`htdocs`).
- **Impor Database**: Buat database MySQL di hosting, buat user, lalu impor `db/db_neoblue.sql` melalui phpMyAdmin atau CLI.
- **Perbarui Koneksi DB**: Edit `config/koneksi.php` dan ganti nilai host/user/password/nama database sesuai kredensial hosting.
- **Perbarui SMTP**: Edit `config/smtp.php` agar sesuai dengan pengaturan SMTP penyedia hosting (host, port, user, pass, secure).
- **Verifikasi Tautan Reset**: Pastikan `api/api_forgot_password.php` membuat tautan reset menggunakan domain produksi (mis. `https://neoblue.gt.tc/reset-password.php?token=...`). Jika skrip memakai `$_SERVER['HTTP_HOST']`, verifikasi nilainya di server.
- **Perbarui BASE_URL Mobile**: Ubah `BASE_URL` di file `mobile/.../ApiConfig.java` menjadi `https://neoblue.gt.tc/api/` lalu rebuild aplikasi.
- **Konfigurasi Deep Link Android**: Tambahkan/ubah `<intent-filter>` untuk `ResetPasswordActivity` di `mobile/app/src/main/AndroidManifest.xml` sehingga menangani `https://neoblue.gt.tc/reset-password.php`.
- **Izin Upload**: Pastikan folder `uploads/` dan subfolder (mis. `bukti_pembayaran/`, `profile/`, `artikel/`) dapat ditulis oleh PHP di hosting.
- **SSL & DNS**: Pasang sertifikat SSL (Let's Encrypt atau provider) dan pastikan A/CNAME record `neoblue.gt.tc` mengarah ke server hosting.
- **Scheduler / Cron (opsional)**: Jika ada skrip berkala seperti `admin/pages/proses_cek_kadaluarsa.php`, buat cron job di hosting.
- **Uji Menyeluruh**: Tes endpoint API (mis. `https://neoblue.gt.tc/api/api_artikel.php`), proses login/registrasi, forgot password, upload file, dan fitur admin.

### Checklist Singkat (Praktis)

- **Domain & DNS**: Atur A/CNAME untuk `neoblue.gt.tc` ke server.
- **Upload File**: Transfer semua file `webneoblue` ke `public_html`.
- **Database**: Buat DB & import `db/db_neoblue.sql`.
- **Koneksi & SMTP**: Update `config/koneksi.php` dan `config/smtp.php`.
- **Mobile**: Update `ApiConfig.java` (`BASE_URL`) dan `AndroidManifest.xml` untuk deep link; rebuild APK.
- **Permissions**: Set folder `uploads/` writable.
- **SSL**: Aktifkan HTTPS dan verifikasi sertifikat.
- **Test**: Jalankan semua tes fungsional.

Jika Anda ingin, saya dapat langsung menambahkan contoh potongan kode untuk `config/koneksi.php`, `api/api_forgot_password.php`, atau contoh `AndroidManifest.xml` dan `ApiConfig.java` yang sudah disesuaikan untuk `https://neoblue.gt.tc`.
