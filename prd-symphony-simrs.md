# PRODUCT REQUIREMENT DOCUMENT (PRD)
## SYMPHONY SIMRS V2.0 (Fasyankes Academic Simulation Engine)

## 1. Project Overview
* **Name:** SYMPHONY SIMRS v2.0
* **Platform:** Web-based Application (Laravel 11, Tailwind CSS, Alpine.js / Livewire)
* **Main Tech:** Laravel Framework, MySQL, Chart.js for data visualization.
* **Description:** A simple Electronic Medical Record (RME) prototype to simulate real-world clinical workflows in a healthcare facility (fasyankes), integrating hybrid chart tracking, SNOMED CT terminology codes mapped to ICD-10, and descriptive biostatistics dashboard.

## 2. UI/UX & Design Guidelines (Stitch Aesthetic)
* **Theme:** "Google Stitch" inspired aesthetic — clean, minimalist, human-centric, high-trust healthcare professional interface.
* **Palette:** Soft, desaturated neutral backgrounds (warm off-white/cream `#faf8f5` or soft slate light blue `#f1f5f9`), dark-slate deep indigo for headers (`#1e293b`), smooth emerald or teal for primary success/active elements, and clear crimson/coral accents for warnings/miscoding alerts.
* **Typography:** Clean sans-serif hierarchy, generous whitespace, card structures with subtle rounded corners (`rounded-xl`) and soft drop-shadows. No harsh pure-blacks or neon colors.

## 3. Core Modules & Specifications

### Module 1: Admission Desk (Registration)
* **Social Data Entry Form:** Collects Name, NIK (strictly 16 digits), DOB, Gender, Insurance Type, and Destination Clinic (following Indonesian KMK HK.01.07/MENKES/1936/2022 guidelines).
* **Real-Time Age Calculator:** Automatically calculates and displays the patient's age in years/months dynamically as soon as the Date of Birth is picked.
* **Automated Master Patient Index:** Generates a unique, sequential Medical Record Number (Nomor RM) automatically and adds the patient to the Active Electronic Queue table.

### Module 2: Clinical Workstation (SOAP / CPPT)
* **Queue Gatekeeper System:** The clinical entry form remains locked by default. It opens automatically only when a clinician clicks and selects an active patient from the admission queue.
* **Structured Clinical Input:**
    * Vital Signs tracking: Blood Pressure (Tensi), Pulse (Nadi), and Temperature (Suhu).
    * Structured narrative documentation based strictly on the **SOAP** format (Subjective, Objective, Assessment, Plan).

### Module 3: Medical Coding Unit (SNOMED CT & ICD-10 API Integration)
* **Automated Medical Resume:** Automatically mirrors the clinical SOAP summaries and primary complaints entered by the doctor in Module 2 for coding reference.
* **Terminology Search Engine (Proxy-backed API):** Autocomplete search input hitting a Laravel Backend Proxy route that safely requests external Terminology Servers (e.g., SATUSEHAT Kemenkes / Snowstorm) to search SNOMED CT Concept IDs and Preferred Terms (focusing on visceral/digestive conditions like Appendicitis, Gastritis, GERD).
* **Automated ICD-10 Cross-Mapping:** Safely extracts the associated *Map Target* (ICD-10 code) from the selected SNOMED CT response payload and maps it into the form.
* **Clinical Decision Support System (CDSS) - Miscoding Alert:** Evaluates diagnosis priority logic. If a mild/chronic visceral condition is incorrectly set as primary over an acute condition, render a high-visibility **crimson alert banner** warning the coder of potential miscoding.

### Module 4: Hybrid Chart Tracker
* **Transitional Medical Record Management:** Displays live metadata status to track physical and digital charts:
    * Physical storage shelf number allocation (Nomor Rak).
    * Document digitization status (Boolean: Scanned PDF / Pending).
    * Document completion checklist indicator (Completeness control).

### Module 5: Director & Epidemiology Dashboard (Biostatistics)
* **Real-Time Descriptive Statistics:** Aggregates database rows into functional health administrative indicators:
    * Total unique patient visits (Morbidity count).
    * Arithmetic Mean (Average Age) of patients affected by visceral conditions.
    * Completeness Rate of Medical Records (KLPCM %).
* **Interactive Visualization:** High-fidelity, smooth **Pie Chart** (via Chart.js) depicting the distribution and proportions of top visceral diseases based on mapped ICD-10 codes.

### Module 6: System Audit Trail (Forensics & Security)
* **Forensic Security Logs:** System-level background listener recording all state-mutating actions (Create, Update, Delete) against patient data tables.
* **Log Composition:** Every audit row must capture precise microsecond Timestamps, the targeted DB entity, the action type, and the Authenticated User's account handle.

---

## 4. DB Migration & Blueprint Mapping

* **users**: `id`, `username`, `password`, `role` (admin, doctor, coder, director), `timestamps`
* **patients**: `id`, `nomor_rm`, `nama`, `nik`, `tanggal_lahir`, `jenis_kelamin`, `penjamin`, `timestamps`
* **registrations**: `id`, `patient_id`, `klinik_tujuan`, `status_antrean` (waiting, treating, done), `timestamps`
* **medical_records**: `id`, `registration_id`, `subjektif`, `objektif`, `asesmen`, `plan`, `tensi`, `nadi`, `suhu`, `timestamps`
* **codings**: `id`, `medical_record_id`, `snomed_concept_id`, `snomed_term`, `icd10_mapped_code`, `is_primary_diagnosis`, `miscoding_status`, `timestamps`
* **hybrid_trackers**: `id`, `patient_id`, `nomor_rak`, `status_scan`, `is_lengkap`, `timestamps`
* **audit_trails**: `id`, `user_id`, `action`, `table_name`, `timestamp`
