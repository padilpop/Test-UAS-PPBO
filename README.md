# Tiket.com Clone Backend Project

Selamat datang di repositori proyek Backend Clone Tiket.com. Panduan ini dibuat untuk membantu tim melakukan setup proyek baik menggunakan **Laragon** (Windows) maupun **Docker**.

## 📋 Prasyarat (Prerequisites)

Sebelum memulai, pastikan kamu sudah menginstal tools berikut sesuai lingkungan kerjamu:

-   **Git** (Wajib untuk semua)
-   **Pengguna Laragon (Windows):**
    -   Laragon (PHP 8.2+, MySQL, Nginx/Apache)
    -   Composer (biasanya sudah include di Laragon)
    -   Node.js & NPM
-   **Pengguna Docker:**
    -   Docker Desktop & Docker Compose

---

## 📥 Langkah 0: Clone Repositori (Wajib)

Lakukan ini jika kamu baru pertama kali mengambil kodenya.

1.  **Buka Terminal** (Git Bash, PowerShell, atau Terminal di VS Code).
2.  Arahkan ke folder di mana kamu ingin menyimpan project ini (misal: `www`).
3.  Jalankan perintah berikut untuk mendownload kodenya:

    ```bash
    # Ganti URL di bawah dengan URL repository GitHub kita
    git clone https://github.com/IniRalfi/tiket-com.git
    ```

4.  **PENTING:** Masuk ke dalam folder project yang baru saja di-download:
    ```bash
    cd tiket-com
    ```
    _(Pastikan terminal kamu sudah berada di dalam folder `tiket-com` sebelum lanjut ke langkah berikutnya)._

---

## ⚙️ Langkah 1: Konfigurasi Awal (Environment)

Lakukan langkah ini setelah berhasil masuk ke folder project:

1.  **Copy File Environment**
    Duplikat file `.env.example` dan ubah namanya menjadi `.env`.

    ```bash
    cp .env.example .env
    ```

    _(Atau copy-paste manual file `.env.example` di file explorer lalu rename jadi `.env`)_

2.  **Konfigurasi Database di `.env`**
    Buka file `.env` yang baru dibuat dengan text editor. Pastikan isinya sesuai standar Laragon (Localhost).

    > **⚠️ PENTING UNTUK PENGGUNA DOCKER:**
    > Jangan ubah `DB_HOST` menjadi `db` di file ini. Biarkan tetap `127.0.0.1`. Docker kita sudah disetting canggih untuk otomatis menangani ini tanpa mengganggu teman pengguna Laragon.

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=tiketcom
    DB_USERNAME=root >sesuaikan
    DB_PASSWORD= > sesuaikan
    ```

    _(Sesuaikan `DB_DATABASE`, `USERNAME`, dan `PASSWORD` dengan database lokalmu jika berbeda)_.

---

## 🐘 Opsi A: Cara Jalanin Pakai Laragon (Windows)

Ini cara untuk teman-teman yang menggunakan Laragon.

1.  **Nyalakan Laragon:** Klik tombol "Start All".
2.  **Buka Terminal:** Klik tombol "Terminal" di Laragon (Atau bisa langsung pake terminal Vs Code) (pastikan sudah di folder project).
3.  **Install Dependencies PHP:**
    ```bash
    composer install
    ```
4.  **Generate App Key:**
    ```bash
    php artisan key:generate
    ```
5.  **Migrasi Database:**
    Pastikan kamu sudah membuat database kosong (sesuai nama di `.env`, misal: `laravel`) di HeidiSQL/phpMyAdmin, lalu jalankan:
    ```bash
    php artisan migrate
    ```
6.  **Install & Build Aset Frontend:**
    ```bash
    npm install
    npm run dev
    ```
7.  **Jalankan Server:**
    Kamu bisa akses via url cantik Laragon (misal `http://tiket-com.test`) atau jalankan manual:
    ```bash
    php artisan serve
    ```

---

## 🐳 Opsi B: Cara Jalanin Pakai Docker

Ini cara untuk yang menggunakan Docker. Pastikan port 8000, 3306, dan 5173 di laptopmu tidak sedang dipakai aplikasi lain.

1.  **Jalankan Container:**

    ```bash
    docker compose up -d --build
    ```

    _(Tunggu sampai selesai. Docker akan otomatis menunggu database siap)_.

2.  **Install Dependencies (Di dalam Container):**
    Kita perlu menjalankan perintah ini lewat container `app`.

    > **Catatan:** Jalankan perintah `docker compose exec ...` ini di terminal baru atau terminal yang sama (karena container jalan di background).

    ```bash
    docker compose exec app composer install
    ```

3.  **Generate Key & Migrate:**

    ```bash
    docker compose exec app php artisan key:generate
    docker compose exec app php artisan migrate
    ```

4.  **Nyalakan Vite (Frontend):**

    ```bash
    docker compose exec node npm install
    docker compose exec node npm run dev
    ```

5.  **Akses Website:**
    Buka browser dan akses: `http://localhost:8000`

---

## 🛠 Troubleshooting Umum

### 1. CSS/Tampilan Berantakan?

Kemungkinan besar Vite belum dijalankan atau aset belum di-build.

-   **Laragon:** Pastikan `npm run dev` sedang berjalan di terminal.
-   **Docker:** Jalankan `docker compose exec node npm run build` (sekali jalan) atau `docker compose exec node npm run dev` (untuk development).

### 2. Docker: "Connection Refused" Database?

Pastikan kamu **tidak** mengubah `DB_HOST` di file `.env` menjadi `db`. Biarkan tetap `127.0.0.1`. Konfigurasi `docker-compose.yml` kita sudah otomatis melakukan _override_ koneksi database khusus untuk container Docker.

### 3. Permission Denied (Docker)?

Jika ada error permission pada folder `storage` atau `bootstrap/cache`, Docker kita sudah punya script auto-fix. Cukup restart container:

```bash
docker compose down
docker compose up -d
```

---

# 🌳 Panduan Git & Workflow (Anti Konflik)

Agar kerja tim tetap rapi dan tidak bentrok.

## ⛔ Aturan Emas

-   Jangan commit atau push langsung ke **main**.
-   Selalu update **main** lokal sebelum membuat fitur baru.
-   Setiap fitur = **branch baru**.

---

## 🔧 Menambahkan Remote Git (HTTPS)

Kalau kamu baru clone project atau remote belum terset, kamu bisa menambahkannya dengan:

```bash
git remote add origin https://github.com/IniRalfi/tiket-com.git
```

Untuk cek apakah remote sudah terset:

```bash
git remote -v
```

Kalau sebelumnya sudah ada remote tapi salah alamat, hapus dulu:

```bash
git remote remove origin
```

Lalu tambahkan ulang:

```bash
git remote add origin https://github.com/IniRalfi/tiket-com.git
```

---

## 🔁 Workflow (Buat kita kerjakan nanti)

### 1. Update dulu

```bash
git checkout main
git pull origin main
```

### 2. Buat branch baru

```bash
git checkout -b nama-fitur-kamu
# contoh: git checkout -b fitur-login-page
```

### 3. Mulai coding

Kerjakan fitur di branch tersebut.

### 4. Simpan perubahan (commit)

```bash
git add .
git commit -m "Menambahkan fitur login page"
```

### 5. Push ke GitHub

```bash
git push origin nama-fitur-kamu
```

### 6. Buat Pull Request

-   Buka repo di GitHub.
-   Klik tombol **Compare & pull request**.
-   Jelaskan fitur yang dikerjakan.
-   Minta teman untuk review sebelum merge ke main.

## 🗄️ Panduan Database & Akun Default (Seeder)

Project ini sudah dilengkapi dengan **Seeder** agar database terisi otomatis dengan akun yang seragam untuk seluruh tim.

### 1. Cara Reset & Isi Database (Wajib Dilakukan)

Jalankan perintah ini setiap kali kamu ingin mereset database ke kondisi awal (bersih).

-   **Untuk Pengguna Docker:**

    ```bash
    docker compose exec app php artisan migrate:fresh --seed
    ```

-   **Untuk Pengguna Laragon:**
    ```bash
    php artisan migrate:fresh --seed
    ```

### 2. Akun Login Siap Pakai

Setelah menjalankan perintah di atas, gunakan akun berikut untuk login:

| Role            | Email             | Password   | Kegunaan                                          |
| :-------------- | :---------------- | :--------- | :------------------------------------------------ |
| **SUPER ADMIN** | `admin@tiket.com` | `admin123` | Untuk testing kelola Master Data (Pesawat/Kereta) |
| **CUSTOMER**    | `user@tiket.com`  | `user123`  | Untuk testing fitur Search & Booking (Sisi User)  |

> **Catatan:** Sistem juga akan membuat **10 user random** tambahan secara otomatis untuk keperluan testing data yang lebih banyak.
