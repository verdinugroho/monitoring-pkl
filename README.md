# 📚 Sistem Monitoring Praktik Kerja Lapangan (PKL)

Sistem Monitoring Praktik Kerja Lapangan (PKL) merupakan aplikasi berbasis web yang dikembangkan menggunakan **Framework CodeIgniter 4** dengan menerapkan arsitektur **Model-View-Controller (MVC)**. Sistem ini bertujuan untuk mempermudah proses monitoring kegiatan PKL mahasiswa mulai dari pengisian logbook harian, bimbingan dosen, pengelolaan dokumen, hingga penilaian akhir.

---

## 👥 Anggota Kelompok

| Nama | NIM |
|------|------|
| Verdi Nugroho | 2023018034 |
| Dimas Aditya  | 2023018049 |
| Galih Dede B  | 2023018036 |

---

## 🎯 Tujuan Sistem

Sistem ini dibangun untuk membantu proses monitoring Praktik Kerja Lapangan secara digital sehingga proses pelaporan, bimbingan, dan penilaian dapat dilakukan dengan lebih efektif, efisien, dan terdokumentasi dengan baik.

---

## 🛠️ Teknologi yang Digunakan

- Framework CodeIgniter 4
- PHP 8
- MySQL
- Bootstrap 5
- HTML5
- CSS3
- JavaScript
- Font Awesome
- RESTful API
- Postman (API Testing)

---

## ✨ Fitur Utama

### 👨‍💼 Admin
- Login
- Dashboard
- Kelola Data Mahasiswa
- Kelola Data Dosen
- Rekap Penilaian

### 👨‍🏫 Dosen
- Dashboard
- Melihat Data Mahasiswa Bimbingan
- Review Logbook
- Memberikan Komentar
- Mengubah Status Logbook
- Memberikan Penilaian
- Export Laporan PDF

### 👨‍🎓 Mahasiswa
- Login
- Dashboard
- Setup Data PKL
- CRUD Logbook Harian
- Upload Dokumen
- Melihat Hasil Review Dosen

---

## 🗄️ Database

Database yang digunakan bernama:

```
monitoring_pkl
```

Tabel yang digunakan:

- users
- internships
- logbooks
- documents
- comments
- assessments
- migrations

---

## 📡 REST API

| Method | Endpoint | Fungsi |
|---------|----------|--------|
| GET | `/api/logbooks` | Menampilkan seluruh data logbook |
| GET | `/api/logbooks/{id}` | Menampilkan detail logbook |
| POST | `/api/logbooks` | Menambahkan logbook |
| PUT | `/api/logbooks/{id}` | Mengubah logbook |
| DELETE | `/api/logbooks/{id}` | Menghapus logbook |

Selain itu tersedia endpoint:

- `/api/internships`
- `/api/documents`
- `/api/assessments`

---

## 🔒 Keamanan Aplikasi

- Login Authentication
- Session Authentication
- Role Based Access Control (Admin, Dosen, Mahasiswa)
- Password Hashing
- CSRF Protection
- Validasi Input
- Query Builder CodeIgniter

---

## 📂 Struktur MVC

```
app/
│
├── Controllers/
├── Models/
├── Views/
│
└── Config/
```

---

## 🚀 Cara Menjalankan Project

### 1. Clone Repository

```bash
git clone https://github.com/verdinugroho/monitoring-pkl.git
```

### 2. Masuk Folder Project

```bash
cd monitoring-pkl
```

### 3. Install Dependency

```bash
composer install
```

### 4. Konfigurasi Database

Buat database

```
monitoring_pkl
```

Kemudian ubah file `.env`

```env
database.default.hostname = localhost
database.default.database = monitoring_pkl
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 5. Jalankan Migration

```bash
php spark migrate
```

### 6. Jalankan Server

```bash
php spark serve
```

Buka browser

```
http://localhost:8080
```

---

## 📸 Tampilan Aplikasi

Tambahkan screenshot berikut:

- Login
- Dashboard Admin
- Dashboard Dosen
- Dashboard Mahasiswa
- Halaman Logbook
- Halaman Penilaian
- Halaman Kelola Mahasiswa

---

## 🧪 Pengujian REST API

REST API telah diuji menggunakan **Postman** dengan method:

- GET
- POST
- PUT
- DELETE

Seluruh endpoint berhasil berjalan sesuai dengan fungsinya.

---

## 📄 Lisensi

Project ini dibuat untuk memenuhi tugas **UAS Pemrograman Web Lanjut** Program Studi Informatika, Fakultas Teknik, Universitas Sarjanawiyata Tamansiswa.

