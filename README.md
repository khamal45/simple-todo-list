

## Prasyarat

Sebelum memulai instalasi, pastikan Anda telah memenuhi prasyarat berikut:

-   PHP versi 8.1 atau lebih tinggi
-   Composer terinstal di sistem Anda
-   Database (MySQL, PostgreSQL, SQLite, dll.) telah disiapkan
-   Ekstensi PHP berikut telah diaktifkan:
    curl,
    fileinfo,
    gd,
    mbstring,
    openssl,
    pdo_mysql,
    zip,

## Langkah-langkah Instalasi

1. **Clone Repository**
   Buka terminal dan jalankan perintah berikut untuk meng-clone repository:
    ```bash
    git clone https://github.com/khamal45/simple-todo-list
    ```
2. **Masuk ke Direktori Proyek**
   Pindah ke direktori proyek yang telah di-clone:
    ```bash
    cd simple-todo-list
    ```
3. **Instalasi Dependensi**
   Jalankan perintah berikut untuk menginstal dependensi menggunakan Composer:
    ```bash
    composer install
    ```
4. **Buat File .env**
   Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    //langsung migrate aja
5. **Konfigurasi .env**
   Buka file `.env` dan sesuaikan konfigurasi database dan pengaturan lainnya sesuai kebutuhan Anda:
    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=task_manager
    DB_USERNAME=root
    DB_PASSWORD=
    ```
6. **Generate Kunci Aplikasi**
   Jalankan perintah berikut untuk menghasilkan kunci aplikasi:
    ```bash
    php artisan key:generate
    ```
7. **Migrasi Database**
   Jalankan migrasi untuk membuat tabel-tabel yang diperlukan di database:

    ```bash
    php artisan migrate
    ```

8. **Jalankan Server**
   Anda dapat menjalankan server lokal menggunakan perintah berikut:
    ```bash
    php artisan serve
    ```
    Akses aplikasi Anda melalui browser di `http://localhost:8000`.
