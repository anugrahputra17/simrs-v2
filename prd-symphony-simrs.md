# PRODUCT REQUIREMENT DOCUMENT (PRD)
## SYMPHONY SIMRS V2.0 (Fasyankes Academic Simulation Engine)

## 1. Project Overview
* **Name:** SYMPHONY SIMRS v2.0
* **Platform:** Web-based Application (Laravel 11, Tailwind CSS, Alpine.js / Livewire)
* **Main Tech:** Laravel Framework, MySQL, Chart.js for data visualization.
* **Description:** Sebuah prototipe Rekam Medis Elektronik (RME) sederhana untuk mensimulasikan alur kerja klinis nyata di fasilitas pelayanan kesehatan (fasyankes). Sistem ini mengintegrasikan pelacakan berkas rekam medis *hybrid*, kodifikasi terminologi SNOMED CT yang dipetakan ke ICD-10, serta dasbor biostatistik deskriptif.

## 2. UI/UX & Design Guidelines (Stitch Aesthetic)
* **Theme:** "Google Stitch" inspired aesthetic — bersih, minimalis, berpusat pada manusia (*human-centric*), serta antarmuka profesional yang membangun kepercayaan tinggi.
* **Palette:** Latar belakang netral yang lembut dan desaturasi (*warm off-white/cream* `#faf8f5` atau *soft slate light blue* `#f1f5f9`). Header menggunakan warna *dark-slate deep indigo* (`#1e293b`). Elemen aktif/sukses utama menggunakan warna *emerald* atau *teal* yang halus, serta aksen *crimson/coral* yang jelas untuk peringatan klinis (*miscoding alerts*).
* **Typography:** Hierarki sans-serif yang bersih, ruang kosong (*whitespace*) yang lega, serta struktur kartu dengan sudut melengkung halus (`rounded-xl`) dan bayangan lembut (*soft drop-shadows*). Tidak menggunakan warna hitam pekat (*pure-black*) atau warna neon yang tajam.

## 3. Core Modules & Specifications

### Module 1: Admission Desk (Registration Rawat Jalan & Smart Search)
Modul ini berfungsi untuk mengelola pendaftaran kunjungan Rawat Jalan dengan alur pencarian data pasien (*Existing vs New Patient*) dan pencatatan Lembar Identitas Umum yang komprehensif.

* **Alur Pendaftaran & Verifikasi Pasien (Smart Search Logic):**
    1. **Gerbang Pencarian:** Sebelum membuka formulir, petugas loket wajib melakukan pencarian di komponen *Smart Search Bar* berdasarkan **NIK** atau **Nomor BPJS**.
    2. **Kondisi Data Ditemukan (Existing Patient):** Jika data pasien sudah terekam di dalam database, layar akan otomatis menampilkan ringkasan profil pasien (*Existing Data Mode*). Sistem menandai entitas ini sebagai `Kunjungan Pasien Lama`. Petugas cukup memilih Poliklinik Tujuan, Dokter (DPJP), dan Jenis Penjamin untuk langsung membuat nomor antrean kunjungan baru tanpa mengisi ulang data sosial dari awal.
    3. **Kondisi Data Tidak Ditemukan (New Patient Mode):** Jika hasil pencarian kosong, sistem mengaktifkan *Formulir Input Pasien Baru* dan menandainya sebagai `Kunjungan Pasien Baru`. Nilai NIK atau Nomor BPJS yang dicari tadi otomatis dipindahkan ke kolom input terkait untuk mempercepat entri data.

* **Identitas Umum & Tambahan Pasien Baru:**
    * **Gelar Kehormatan:** Karakter teks bebas untuk mencatat gelar adat, keagamaan, atau kehormatan pasien (misal: Haji, Datuk, Nyimas, Prof, Dr). Kolom ini dipisahkan dari kolom nama utama.
    * **Nama Lengkap:** Karakter teks bebas sesuai dokumen resmi (KTP/KK). Sistem otomatis mengubah input menjadi huruf kapital (*UPPERCASE*) saat disimpan.
    * **Nomor Rekam Medis (No RM):** Dihasilkan otomatis oleh sistem penomoran unit secara berurutan, unik, dan *read-only*.
    * **Nomor Induk Kependudukan (NIK):** Data numerik 16 digit. Jika tidak ada (kasus WNA), menggunakan nilai *fallback* default `9999999999999999` dan mengaktifkan kolom *No Identitas Lain* (Paspor/KITAS).
    * **Nomor BPJS:** Karakter numerik 13 digit (Nullable, wajib diisi dan divalidasi 13 digit jika jenis penjamin memilih BPJS Kesehatan).
    * **Status Merokok:** Data pilihan biner untuk rekam jejak gaya hidup (`0` = Tidak Merokok, `1` = Merokok). *Data ini akan ditarik secara relasional ke dalam Module 2 dan Module 5*.
    * **Nama Ibu Kandung:** Karakter teks bebas sesuai kartu identitas resmi wajib diisi untuk kebutuhan verifikasi berlapis.
    * **Tempat & Tanggal Lahir:** Karakter teks untuk kota lahir, dan tipe data tanggal dengan format tampilan `DD/MM/YYYY`.
    * **Real-Time Age Calculator:** JavaScript interaktif yang otomatis menghitung usia pasien dalam satuan (Tahun, Bulan, Hari) secara langsung saat tanggal lahir diisi.
    * **Jenis Kelamin & Agama (Kodifikasi Numerik):** 
        * Jenis Kelamin: `0`=Tidak diketahui, `1`=Laki-laki, `2`=Perempuan, `3`=Tidak ditentukan, `4`=Tidak mengisi.
        * Agama: `1`=Islam, `2`=Protestan, `3`=Katolik, `4`=Hindu, `5`=Budha, `6`=Konghucu, `7`=Penghayat, `8`=Lainnya (menyediakan free text).
    * **Suku & Bahasa yang Dikuasai:** Karakter teks bebas untuk mencatat preferensi komunikasi kultural.

* **Blok Alamat Terstruktur (Standar Kemendagri):**
  Sistem memisahkan entri antara **Alamat KTP** dan **Alamat Domisili** (dilengkapi fitur *copy* alamat otomatis "Sama dengan KTP") yang terbagi atas kolom: Alamat Jalan, RT/RW (3 digit), Kelurahan ID, Kecamatan ID, Kabupaten ID, Provinsi ID, Kode Pos, dan Negara (ISO 3166).

* **Kontak Komunikasi:** Nomor telepon rumah dan nomor HP (format internasional `+62`).

* **Demografi Klinis Tambahan:** Pendidikan resmi terakhir (skema ISCED 0-8), Pekerjaan (skema KBBI 0-5), dan Status Pernikahan (1-4).

* **Blok Data Darurat (Emergency Contact Data):**
  Kolom wajib untuk mencatat data pihak terdekat pasien yang dapat dihubungi dalam kondisi darurat klinis:
    * **Nama Kontak Darurat:** Karakter teks bebas nama perwakilan keluarga/kerabat.
    * **Hubungan Keluarga:** Pilihan hubungan (misal: Suami, Istri, Orang Tua, Anak, Saudara Kandung, Lainnya).
    * **No KTP (NIK Kontak Darurat):** Data numerik 16 digit identitas penanggung jawab.
    * **No HP Kontak Darurat:** Data numerik nomor ponsel aktif (format internasional).
    * **Alamat Kontak Darurat:** Karakter teks bebas / alamat lengkap tempat tinggal penanggung jawab.

### Module 2: Clinical Workstation (SOAP / CPPT)
* **Queue Gatekeeper System:** Formulir entri pemeriksaan klinis terkunci secara default. Pintu input hanya akan terbuka otomatis saat klinisi/dokter memilih salah satu antrean pasien aktif yang dikirim dari Module 1.
* **Relational Patient Header:** Saat data pasien dipilih dari antrean, header klinis dokter secara otomatis menampilkan *Gelar Kehormatan, Nama Lengkap (UPPERCASE), No RM, No BPJS*, serta memunculkan **Badge Peringatan Merah** jika data `status_merokok` bernilai `1` (Merokok) sebagai peringatan preventif klinis.
* **Structured Clinical Input:**
    * Pencatatan Tanda-Tanda Vital (Vitals): Tekanan Darah/Tensi (`mmHg`), Denyut Nadi (`bpm`), dan Suhu Tubuh (`°C`).
    * Dokumentasi naratif terstruktur yang mengikuti metode **SOAP** (Subjective, Objective, Assessment, Plan) secara ketat.

### Module 3: Medical Coding Unit (SNOMED CT & ICD-10 API Integration)
* **Automated Medical Resume:** Menampilkan ringkasan SOAP dan keluhan utama secara otomatis dari data Module 2 sebagai referensi unit rekam medis/coder.
* **Relational Validation:** Coder dapat melihat data penjamin pasien (BPJS / Umum) dari Module 1. Jika penjamin adalah BPJS, sistem memberikan validasi tambahan bahwa pengisian Kode Diagnosis Utama ICD-10 tidak boleh kosong agar tidak terjadi kegagalan klaim.
* **Terminology Search Engine (Proxy-backed API):** Pencatatan kode diagnosis menggunakan input autocomplete yang terhubung ke Laravel Backend Proxy untuk menarik *SNOMED CT Concept ID* dan *Preferred Term*.
* **Automated ICD-10 Cross-Mapping:** Sistem secara cerdas mengekstrak objek `Map Target` berupa kode ICD-10 dari payload respon SNOMED CT yang dipilih.
* **CDSS - Miscoding Alert:** Sistem pendukung keputusan klinis. Jika diagnosis penyakit kronis/ringan salah diposisikan sebagai diagnosis utama di atas kondisi akut/berat, sistem memicu **banner peringatan berwarna merah tua (crimson)**.

### Module 4: Hybrid Chart Tracker
* **Transitional Medical Record Management:** Menyediakan manajemen dasbor untuk memantau transisi berkas rekam medis dari fisik ke digital:
    * Sistem otomatis membuat 1 baris record baru di tabel `hybrid_trackers` sesaat setelah pasien baru berhasil didaftarkan pada Module 1.
    * Pelacakan mencakup: Alokasi nomor rak fisik penyimpanan berkas rekam medis lama (Nomor Rak), Status digitalisasi dokumen (Boolean: Sudah Scan PDF / Pending), dan Indikator kelengkapan berkas medis (Kontrol KLPCM).

### Module 5: Director & Epidemiology Dashboard (Biostatistics)
* **Real-Time Descriptive Statistics & Extended Analytics:** Agregasi data riil yang terelasi penuh dengan parameter baru di Module 1:
    * Total kunjungan pasien unik (Angka morbiditas).
    * Rata-rata hitung (Mean) usia pasien yang mengidap penyakit visceral.
    * Persentase Kelengkapan Berkas Rekam Medis (KLPCM %).
    * **Rasio Kunjungan Baru vs Lama:** Menampilkan grafik perbandingan jumlah pasien yang mendaftar via jalur input baru vs jalur *Smart Search* (Existing).
* **Interactive Visualization:** 
    * Grafik lingkaran (**Pie Chart** dinamis menggunakan Chart.js) proporsi persebaran penyakit visceral teratas berdasarkan kode ICD-10.
    * Grafik batang (**Bar Chart**) hubungan antara pasien yang memiliki `status_merokok = 1` dengan tren diagnosis penyakit visceral tertentu (misal: Gastritis/GERD).

### Module 6: System Audit Trail (Forensics & Security)
* **Forensic Security Logs:** Listener latar belakang sistem yang merekam setiap aksi mutasi data (Create, Update, Delete) pada tabel pasien dan rekam medis lengkap dengan microsecond Timestamp dan User ID.
* **Smart Search Logging:** Sistem wajib merekam setiap aktivitas pencarian NIK/BPJS di Module 1 yang dilakukan oleh petugas, termasuk log pencarian yang menghasilkan data *"Not Found"* guna mendeteksi potensi *brute-force scanning* data kependudukan.

---

## 4. DB Migration & Blueprint Mapping

Struktur skema database diperluas untuk mengakomodasi data gaya hidup, nomor asuransi sosial, gelar, dan entitas data darurat (*emergency contact*):

* **users**: `id`, `username`, `password`, `role` (admin, doctor, coder, director), `timestamps`
* **patients**: 
    * `id` (BigInt, Primary Key)
    * `no_rm` (string, unique)
    * `gelar_kehormatan` (string, nullable)
    * `nama_lengkap` (string) — *Wajib UPPERCASE via controller*
    * `nik` (char(16), default: '9999999999999999')
    * `no_bpjs` (char(13), nullable, unique)
    * `no_identitas_lain` (string, nullable) — *Paspor/KITAS*
    * `status_merokok` (boolean, default: false) — *0=Tidak, 1=Ya*
    * `nama_ibu_kandung` (string)
    * `tempat_lahir` (string)
    * `tanggal_lahir` (date)
    * `jenis_kelamin` (tinyInteger) — *0 s.d 4*
    * `agama` (tinyInteger) — *1 s.d 8*, `agama_lainnya` (string, nullable)
    * `suku` (string), `bahasa_dikuasai` (string)
    * **-- BLOK KTP ALAMAT IDENTITAS --**
    * `alamat_ktp` (text), `rt_ktp` (char(3)), `rw_ktp` (char(3))
    * `kelurahan_id_ktp` (bigInteger), `kecamatan_id_ktp` (bigInteger), `kabupaten_id_ktp` (bigInteger), `provinsi_id_ktp` (bigInteger)
    * `kode_pos_ktp` (string), `negara_ktp` (string)
    * **-- BLOK ALAMAT DOMISILI --**
    * `alamat_domisili` (text), `rt_domisili` (char(3)), `rw_domisili` (char(3))
    * `kelurahan_id_domisili` (bigInteger), `kecamatan_id_domisili` (bigInteger), `kabupaten_id_domisili` (bigInteger), `provinsi_id_domisili` (bigInteger)
    * `kode_pos_domisili` (string), `negara_domisili` (string)
    * **-- BLOK KONTAK & DEMOGRAFI SOSIAL --**
    * `no_telepon_rumah` (string, nullable), `no_hp` (string)
    * `pendidikan` (tinyInteger), `pekerjaan` (tinyInteger), `pekerjaan_lainnya` (string, nullable), `status_pernikahan` (tinyInteger)
    * **-- BLOK EMERGENCY DATA (KONTAK DARURAT) --**
    * `emergency_nama` (string)
    * `emergency_hubungan` (string)
    * `emergency_no_ktp` (char(16))
    * `emergency_no_hp` (string)
    * `emergency_alamat` (text)
    * `timestamps`
* **registrations**: `id`, `patient_id`, `type_kunjungan` (enum: 'Baru', 'Lama'), `klinik_tujuan`, `id_penjamin`, `status_antrean` (waiting, treating, done), `timestamps`
* **medical_records**: `id`, `registration_id`, `subjektif`, `objektif`, `asesmen`, `plan`, `tensi`, `nadi`, `suhu`, `timestamps`
* **codings**: `id`, `medical_record_id`, `snomed_concept_id`, `snomed_term`, `icd10_mapped_code`, `is_primary_diagnosis`, `miscoding_status`, `timestamps`
* **hybrid_trackers**: `id`, `patient_id`, `nomor_rak`, `status_scan`, `is_lengkap`, `timestamps`
* **audit_trails**: `id`, `user_id`, `action`, `table_name`, `search_query_logged` (string, nullable), `timestamp`