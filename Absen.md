## 📝 Tugas Git: Remote & Push (WAJIB COBA)

Setiap anggota harus membuktikan kalau Git kalian aman di laptop.  
Lakukan langkah berikut (Jangan ubah step nya ya):

1. **Cek remote**  
   Pastikan remote `origin` sudah ada dan pakai HTTPS.

    ```bash
    git remote -v

    harusnya muncul
    ```

    Kalau belum ada, tambahkan:

    ```bash
    git remote add origin https://github.com/IniRalfi/tiket-com.git
    ```

2. **Update branch main**

    ```bash
    git checkout main
    git pull origin main
    ```

3. **Buat branch baru sesuai nama kalian**  
   Contoh:

    ```bash
    git checkout -b namakamu-test-push
    ```

4. **Tambahkan Nama kamu dibaris paling bawah di file ini**

5. **Commit perubahan**

    ```bash
    git add .
    git commit -m "Tes push dari NAMA_KAMU"
    ```

6. **Push ke GitHub**

    ```bash
    git push origin nama-branch-kamu
    ```

---

### Buat Nama Mu Disini ( Nama - Message (bebas)):

-   Rafli Pratama - Mantap Bosh Rawrr
-   Caca - Bisaa
-   Fadil - oKe gas Done
-   Rasyid - halo halo halo
-   Asa - hhi
-   Ghina - Tes
