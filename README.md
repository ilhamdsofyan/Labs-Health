# 🏥 Labs Health
**School Health Unit (UKS) & Medical Record Management System**

> Sistem manajemen kesehatan sekolah berbasis **CodeIgniter 3**, dirancang buat ngatur **kunjungan UKS**, **rekam medis**, **stok obat**, dan **RBAC (Role-Based Access Control)** secara rapi, scalable, dan audit-friendly.

---

## ✨ Fitur Utama

### 👩‍⚕️ Kesehatan & Medis
- 📋 Rekam kunjungan UKS (Visits)
- 🧠 Master diagnosis (ISPA, Migrain, Gastritis, dll)
- 💊 Pemberian obat per kunjungan
- 📊 Audit stok obat (IN / OUT / ADJ)
- 🧾 Riwayat kesehatan siswa per semester & tahun ajaran

### 🎓 Data Akademik & Relasi
- 👨‍🎓 Data siswa lengkap (identitas, keluarga, alamat)
- 🏫 Kelas & riwayat kelas (class history)
- 👩‍🏫 Guru & karyawan sekolah
- 👥 Visitor fleksibel (siswa, guru, karyawan, orang tua, dll) via `persons`

### 🔐 Security & RBAC
- 👤 Multi-role user (admin, petugas_uks, dokter, dll)
- 🔑 Role-Based Access Control (RBAC)
- 🧩 RBAC helper custom (CI3-friendly)
- 🚫 Proteksi controller berbasis role

### 🧠 Data Integrity & Audit
- 🕒 `created_at`, `updated_at`
- 🗑 Soft delete (`deleted_at`)
- 👁️ Audit user (`created_by`, `updated_by`, `deleted_by`)
- 📦 Stock log terpisah dari current stock (best practice)

---

## 🛠 Tech Stack

| Layer | Tech |
|------|------|
| Backend | PHP 8.x |
| Framework | CodeIgniter 3 |
| Database | MySQL / MariaDB |
| Auth | Session-based |
| Access Control | RBAC (Custom) |
| DB Design | DBML (dbdiagram.io) |

---

## 🚀 Instalasi

```
git clone https://github.com/ilhamdsofyan/Labs-Health.git
```

## 🤝 Contributing

Pull request welcome.
