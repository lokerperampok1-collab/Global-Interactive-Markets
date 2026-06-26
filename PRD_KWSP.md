# 📋 PRD — KWSP Malaysia Investment Platform

> **Versi:** 1.0  
> **Tarikh:** 25 Jun 2026  
> **Status:** Production-Ready Blueprint  

---

## 1. Gambaran Keseluruhan Produk

**KWSP Malaysia** adalah platform pelaburan web-based yang membolehkan pengguna mendaftar, melakukan deposit dana, melabur dalam pelan pelaburan bertingkat (tiered investment plans), dan mengeluarkan keuntungan. Platform ini mempunyai dua peranan utama: **User (Ahli)** dan **Admin**.

### 1.1 Objektif Produk
- Menyediakan platform pelaburan yang kelihatan profesional dan dipercayai
- Sistem wallet digital dengan aliran deposit → invest → profit → withdraw
- Pengurusan pengguna dan transaksi oleh admin
- KYC (Know Your Customer) verification flow
- Pelan pelaburan bertingkat dengan auto-maturity

### 1.2 Target Pengguna
| Peranan | Penerangan |
|---------|------------|
| **User (Ahli)** | Pelabur yang mendaftar, deposit, melabur, dan keluarkan dana |
| **Admin** | Pengurus platform yang mengawal KYC, transaksi, dan pengguna |

---

## 2. Tech Stack

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| **Backend Framework** | Laravel | ^13.0 |
| **PHP** | PHP | ^8.3 |
| **Auth Scaffolding** | Laravel Breeze | ^2.4 |
| **Frontend Template** | Blade Templates | - |
| **CSS Framework** | TailwindCSS | ^3.1.0 |
| **JS Framework** | Alpine.js | ^3.4.2 |
| **Build Tool** | Vite | ^8.0.0 |
| **Database** | SQLite | (default) |
| **HTTP Client** | Axios | ^1.11.0 |
| **Fonts** | Google Fonts (Poppins, Plus Jakarta Sans) | - |
| **Icons** | Font Awesome | 4.7.0 |
| **Alerts** | SweetAlert | (custom dev) |
| **Chart** | TradingView Embed Widget | - |

### 2.1 Arsitektur Aplikasi
- **Pattern:** MVC (Model-View-Controller) standard Laravel
- **Session Driver:** Database
- **Queue Driver:** Database
- **Cache Driver:** Database
- **File Storage:** Local disk
- **Currency:** MYR (Malaysian Ringgit) — hardcoded default

---

## 3. Modul & Fitur

### 3.1 🔐 Authentication (Laravel Breeze)

| Fitur | Route | Method |
|-------|-------|--------|
| Register | `/register` | GET/POST |
| Login | `/login` | GET/POST |
| Logout | `/logout` | POST |
| Forgot Password | `/forgot-password` | GET/POST |
| Reset Password | `/reset-password/{token}` | GET/POST |
| Email Verification | `/verify-email` | GET |
| Confirm Password | `/confirm-password` | GET/POST |

**Registration Fields:**
- `name` — Nama Penuh (required)
- `email` — E-mel (required, unique)
- `phone` — No. Telefon (required)
- `country_name` — Negara (readonly, default: "Malaysia")
- `currency_code` — Mata Wang (readonly, default: "MYR/RM")
- `password` + `password_confirmation` (required)

**Saat register**, wallet otomatis TIDAK dibuat. Wallet dibuat lazily saat pertama kali user mengakses Dashboard.

---

### 3.2 📊 Dashboard (User)

**Route:** `GET /dashboard`  
**Middleware:** `auth`, `verified`  
**Controller:** `DashboardController@index`

**Fungsi utama:**
1. **Auto-create wallet** jika belum ada (`currency: MYR`, `balance: 0.00`)
2. **Auto-maturity check** — cek investasi yang sudah matang dan settle otomatis
3. Paparkan ringkasan kewangan:

| Metrik | Kalkulasi |
|--------|-----------|
| **Baki Semasa** | `wallet.balance` |
| **Total Keuntungan** | SUM transaksi `type=profit, status=approved` |
| **Total Deposit** | SUM transaksi `type=deposit, status=approved` |
| **Total Pengeluaran** | SUM transaksi `type=withdraw, status=approved` |
| **Total Pelaburan** | SUM transaksi `type=investment, status=approved` |
| **Pelaburan Aktif** | SUM `user_investments` dimana `status=active` |

4. **TradingView Chart** — Random symbol dari: `BTCUSDT, ETHUSDT, EURUSD, USDJPY, GOLD, SILVER, USOIL`

---

### 3.3 💰 Wallet Module

#### 3.3.1 Deposit
**Route:** `GET/POST /wallet/deposit`  
**Controller:** `WalletController@deposit` / `@depositPost`

- User submit jumlah deposit (minimum RM 10)
- Transaksi dibuat dengan `status: pending`
- Admin perlu approve secara manual
- Setelah admin approve, `wallet.balance` ditambah

#### 3.3.2 Withdraw (Pengeluaran)
**Route:** `GET/POST /wallet/withdraw`  
**Controller:** `WalletController@withdraw` / `@withdrawPost`

**Business Rules:**
1. User MESTI `is_withdraw_unlocked = true` (dikunci by default, admin perlu buka)
2. User MESTI ada `bank_name` dan `bank_account` di profil
3. Minimum withdrawal: RM 10
4. Baki MESTI mencukupi
5. Wallet di-lock (`lockForUpdate`) untuk prevent race condition
6. Baki **langsung ditolak** saat request dibuat (sebelum admin approve)
7. Jika admin reject, baki dikembalikan
8. Note transaksi menyimpan info bank: `"Bank: {bank_name} | Akaun: {bank_account}"`

#### 3.3.3 Transfer (Pindahan Dana)
**Route:** `GET/POST /wallet/transfer`  
**Controller:** `WalletController@transfer` / `@transferPost`

**Business Rules:**
1. Tidak boleh transfer ke diri sendiri
2. Penerima dicari berdasarkan email
3. **Caj transfer: 30%** (kadar tetap `FEE_RATE = 0.30`)
4. Total tolakan = `amount + (amount × 0.30)`
5. Penerima hanya terima `amount` (tanpa fee)
6. Kedua wallet di-lock (`lockForUpdate`)
7. 3 rekod transaksi dibuat:
   - **Sender:** `type=transfer_out`, jumlah = total (amount + fee)
   - **Receiver:** `type=transfer_in`, jumlah = amount
   - **Fee:** `type=fee`, jumlah = fee amount
8. Transfer **auto-approved** (tiada pending admin)

---

### 3.4 🆔 KYC (Know Your Customer)

**Routes:**
- `GET /kyc` — `KycController@index`
- `POST /kyc` — `KycController@store`

**Proses:**
1. User muat naik 3 gambar (maks 5MB setiap satu):
   - `id_front` — Kad pengenalan depan
   - `id_back` — Kad pengenalan belakang
   - `selfie` — Foto selfie
2. Gambar disimpan di `storage/app/public/kyc/`
3. Status KYC user dikemas kini ke `pending`
4. Admin boleh `approve` atau `reject`

**Status Flow:**
```
none → pending → approved
                → rejected → (boleh submit semula) → pending
```

---

### 3.5 📈 Investment Module

**Routes:**
- `GET /investment` — `InvestmentController@index`
- `POST /investment` — `InvestmentController@invest`

**Aliran:**
1. Jika user SUDAH ada pelaburan aktif → paparkan halaman **investment_active** (senarai pelaburan aktif)
2. Jika user TIADA pelaburan aktif → paparkan katalog pelan pelaburan

#### Pelan Pelaburan (Tiered System)

| Tier | Pelan | Harga (RM) | Target Return (RM) | Durasi |
|------|-------|------------|---------------------|--------|
| **BASIC** | BASIC 1 | 500 | 15,000 | 7 hari |
| **BASIC** | BASIC 2 | 1,000 | 31,000 | 7 hari |
| **BASIC** | BASIC 3 | 1,300 | 39,000 | 7 hari |
| **BASIC** | BASIC 4 | 1,500 | 45,000 | 7 hari |
| **GOLD** | GOLD 1 | 2,000 | 70,000 | 7 hari |
| **GOLD** | GOLD 2 | 3,000 | 105,000 | 7 hari |
| **GOLD** | GOLD 3 | 4,000 | 140,000 | 7 hari |
| **DIAMOND** | DIAMOND 1 | 5,000 | 200,000 | 7 hari |
| **DIAMOND** | DIAMOND 2 | 7,000 | 280,000 | 7 hari |
| **DIAMOND** | DIAMOND 3 | 10,000 | 400,000 | 7 hari |
| **VVIP** | VVIP LUXURY | 15,000 | 580,000 | 7 hari |
| **VVIP** | VVIP ELITE | 20,000 | 600,000 | 7 hari |

**Proses Investasi:**
1. User pilih pelan → klik invest
2. Sistem cek baki wallet mencukupi
3. Baki ditolak sebanyak `plan.price`
4. Rekod `wallet_transaction` dibuat (type: `investment`, status: `approved`)
5. Rekod `user_investments` dibuat dengan:
   - `start_at` = now()
   - `end_at` = now() + random(3–6 jam) ← **PENTING: sebenarnya jam, bukan hari**
   - `status` = `active`

#### Auto-Maturity (Trait: `AutoMaturity`)

**Trigger:** Setiap kali user mengakses Dashboard atau halaman Investment

**Logik:**
1. Cari semua investasi user dimana `status=active` DAN `end_at <= now()`
2. Untuk setiap investasi matang:
   - Update `status` → `completed`
   - Buat transaksi `type=profit`, `status=approved`, `amount=target_return`
   - Tambah `wallet.balance` dengan `target_return`
3. Flash message: "Tahniah! {n} pelaburan telah matang dan RM {total} telah dikreditkan"

---

### 3.6 👤 Profile Management

**Routes:**
- `GET /profile` — Edit profil
- `PATCH /profile` — Update profil
- `DELETE /profile` — Hapus akaun

**Fields yang boleh diedit:**
- Nama, Email, Telefon
- Nama Bank, No. Akaun Bank
- Password

---

### 3.7 🛡️ Admin Panel

**Middleware:** `auth` + `is_admin` (custom middleware `IsAdmin`)  
**Prefix:** `/admin`  
**Guard:** Cek `user.role === 'admin'`

#### 3.7.1 Admin Dashboard
**Route:** `GET /admin`

Statistik ringkas:
- Jumlah pengguna
- KYC pending
- Transaksi pending

#### 3.7.2 User Management
| Route | Fungsi |
|-------|--------|
| `GET /admin/users` | Senarai semua pengguna (paginated, 20/page) |
| `GET /admin/users/{id}/edit` | Edit profil pengguna |
| `POST /admin/users/{id}/update` | Kemas kini profil |
| `POST /admin/users/{id}/balance` | Adjust baki wallet (credit/debit) |
| `POST /admin/users/{id}/reset-password` | Reset password (default: `12345678`) |
| `POST /admin/users/{id}/toggle-withdraw` | Toggle kunci/buka pengeluaran |
| `GET /admin/users/{id}/impersonate` | Log masuk sebagai user |
| `GET /admin/leave-impersonate` | Kembali ke akaun admin |

**Balance Adjustment Logic:**
- Jumlah positif → `wallet.increment('balance')` + transaksi `type=profit, note=Admin adjustment (Credit)`
- Jumlah negatif → `wallet.decrement('balance')` + transaksi `type=profit, note=Admin adjustment (Debit)`

**Impersonate:**
- Simpan `admin_id` dalam session key `impersonate`
- Login sebagai user target
- Banner kuning dipaparkan di atas halaman member
- Klik "Kembali ke Admin" untuk revert

#### 3.7.3 KYC Management
| Route | Fungsi |
|-------|--------|
| `GET /admin/kyc` | Senarai KYC pending |
| `POST /admin/kyc/{id}/approve` | Approve KYC |
| `POST /admin/kyc/{id}/reject` | Reject KYC |

#### 3.7.4 Wallet Transaction Management
| Route | Fungsi |
|-------|--------|
| `GET /admin/wallet` | Senarai transaksi pending |
| `POST /admin/wallet/{id}/approve` | Approve transaksi (deposit/profit → increment balance) |
| `POST /admin/wallet/{id}/reject` | Reject transaksi (withdraw → refund balance) |

#### 3.7.5 Investment Plan CRUD
| Route | Fungsi |
|-------|--------|
| `GET /admin/plans` | Senarai semua pelan |
| `GET /admin/plans/create` | Form cipta pelan baru |
| `POST /admin/plans` | Simpan pelan baru |
| `GET /admin/plans/{plan}/edit` | Form edit pelan |
| `PUT /admin/plans/{plan}` | Kemas kini pelan |
| `DELETE /admin/plans/{plan}` | Hapus pelan |

**Plan Fields:**
- `tier` — BASIC / GOLD / DIAMOND / VVIP (enum validation)
- `name` — Nama pelan (max 120 chars)
- `description` — Penerangan (nullable)
- `price` — Harga pelaburan (decimal)
- `target_return` — Pulangan sasaran (decimal)
- `duration_days` — Tempoh pelaburan dalam hari (integer)
- `sort_order` — Susunan paparan (integer)
- `status` — Aktif/Tidak aktif (boolean)

---

## 4. Skema Database

### 4.1 `users`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint (PK) | Auto-increment |
| `name` | string | Required |
| `email` | string | Unique |
| `email_verified_at` | timestamp | Nullable |
| `password` | string | Hashed (bcrypt) |
| `phone` | string | Nullable |
| `bank_name` | string | Nullable |
| `bank_account` | string | Nullable |
| `bank_locked_at` | timestamp | Nullable |
| `status_kyc` | string | Default: `none` |
| `is_disabled` | boolean | Default: `false` |
| `disabled_at` | timestamp | Nullable |
| `is_withdraw_unlocked` | boolean | Default: `false` |
| `country_code` | string(2) | Nullable |
| `country_name` | string | Nullable |
| `currency_code` | string(10) | Default: `MYR` |
| `currency_symbol` | string(12) | Default: `RM` |
| `role` | string | Default: `user` (admin/user) |
| `remember_token` | string | Laravel default |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### 4.2 `wallets`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint (PK) | |
| `user_id` | FK → users | Cascade delete |
| `currency` | string(8) | Default: `MYR` |
| `balance` | decimal(18,2) | Default: `0.00` |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### 4.3 `wallet_transactions`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint (PK) | |
| `user_id` | FK → users | Cascade delete |
| `currency` | string(8) | Default: `MYR` |
| `type` | string(32) | `deposit`, `withdraw`, `profit`, `investment`, `transfer_in`, `transfer_out`, `fee` |
| `status` | string(32) | Default: `pending` — Values: `pending`, `approved`, `rejected` |
| `amount` | decimal(18,2) | Default: `0.00` |
| `note` | string | Nullable |
| `idempotency_key` | string(64) | Unique, Nullable |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### 4.4 `kyc_requests`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint (PK) | |
| `user_id` | FK → users | Unique, Cascade delete |
| `id_front_path` | string | Nullable |
| `id_back_path` | string | Nullable |
| `selfie_path` | string | Nullable |
| `status` | string(20) | Default: `pending` |
| `note` | string | Nullable |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### 4.5 `investment_plans`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint (PK) | |
| `tier` | string(20) | Default: `BASIC` — Values: `BASIC`, `GOLD`, `DIAMOND`, `VVIP` |
| `name` | string | |
| `description` | text | Nullable |
| `price` | decimal(18,2) | Default: `0.00` |
| `target_return` | decimal(18,2) | Default: `0.00` |
| `duration_days` | integer | |
| `status` | boolean | Default: `true` |
| `sort_order` | integer | Default: `0` |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### 4.6 `user_investments`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint (PK) | |
| `user_id` | FK → users | Cascade delete |
| `plan_id` | FK → investment_plans | Cascade delete |
| `plan_name` | string(120) | Denormalized |
| `amount` | decimal(18,2) | Default: `0.00` |
| `target_return` | decimal(18,2) | Default: `0.00` |
| `duration_days` | integer | Default: `0` |
| `start_at` | datetime | |
| `end_at` | datetime | |
| `status` | string(20) | Default: `active` — Values: `active`, `completed`, `cancelled` |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### 4.7 Tabel Pendukung Laravel (Auto)

- `password_reset_tokens` — Reset password tokens
- `sessions` — Session storage (driver: database)
- `cache` — Cache storage (driver: database)
- `cache_locks` — Cache locks
- `jobs` — Queue jobs
- `job_batches` — Job batching
- `failed_jobs` — Failed queue jobs

---

## 5. Business Rules Penting

### 5.1 Aliran Wang (Money Flow)

```
User Deposit (pending) → Admin Approve → wallet.balance ↑
User Invest → wallet.balance ↓ (immediate) → Maturity → wallet.balance ↑ (auto)
User Withdraw → wallet.balance ↓ (immediate) → Admin Approve/Reject
User Transfer → wallet.balance ↓ sender (immediate + 30% fee) → wallet.balance ↑ receiver
```

### 5.2 Kaedah Keselamatan
- **Withdraw** dikunci secara default (`is_withdraw_unlocked = false`)
- **Wallet locking** (`lockForUpdate`) pada operasi withdraw dan transfer
- **Bank account masking** untuk keselamatan (contoh: `*******6789`)
- **Idempotency key** pada transaksi (nullable, untuk prevent duplicate)
- **CSRF protection** pada semua form POST

### 5.3 Bahasa Antara Muka
- Semua UI messages, labels, dan flash messages dalam **Bahasa Melayu**
- Contoh: "Permintaan deposit telah dihantar", "Baki tidak mencukupi", "Pengeluaran anda masih dikunci"

---

## 6. UI/UX Specification

### 6.1 Design System
- **Theme:** Corporate Light (`corporate-light` body class)
- **Design Tokens:** Custom CSS variables di `public/css/design_tokens.css`
- **Branding:** Logo KWSP EPF, warna biru (#00458C) dan kuning (#FFCC00)
- **Typography:** Poppins (body), Plus Jakarta Sans (headings)
- **Icons:** Font Awesome 4.7

### 6.2 Layout Structure

**Landing Page** (`welcome.blade.php`):
- Standalone page, bukan layout extend
- Topbar dengan logo + CTA buttons
- Hero section dengan statistik palsu (5,200+ ahli, RM 12M+, 99.9% uptime)
- Section kelebihan (3 cards)
- CTA section
- Footer

**Member Area** (`layouts/member.blade.php`):
- Top header bar (logo + user menu dropdown)
- Content area (yield)
- Bottom navigation bar (5 tabs): Deposit, Invest, Home, Withdraw, Transfer
- Animated background partial
- Impersonate banner (if active)
- Flash message display

**Auth Pages** (`layouts/guest.blade.php`):
- Split layout: Left (branding) + Right (form)
- Minimal, clean design

**Admin Pages** (`layouts/app.blade.php`):
- Standard Laravel Breeze layout (extended)

### 6.3 Static Assets Location

```
public/
├── css/design_tokens.css          ← Design system variables
├── custom_ui.css                  ← Global custom styles
├── myasset/
│   ├── css/style.css              ← Landing page styles
│   ├── image/
│   │   ├── main_logo.png          ← KWSP EPF logo
│   │   └── pc_main.svg            ← Hero illustration
│   └── js/
├── user/
│   ├── css/
│   │   ├── gmtd_member_v2.css     ← Member dashboard styles
│   │   └── sweetalert.css
│   └── js/
│       └── sweetalert-dev.js
├── assets/vendor_components/
│   └── font-awesome/
├── logintheme/                    ← Auth page theme assets
└── build/                         ← Vite compiled output
```

---

## 7. Seeder / Default Data

### 7.1 Admin Account (DatabaseSeeder)
```
Email:    admin@kwsp.my
Password: admin123
Role:     admin
Balance:  RM 5,000.00
```

### 7.2 Investment Plans (InvestmentPlanSeeder)
12 pelan pelaburan merentas 4 tier (BASIC, GOLD, DIAMOND, VVIP) — rujuk jadual di Section 3.5.

---

## 8. Middleware & Security

| Middleware | Alias | Fungsi |
|-----------|-------|--------|
| `auth` | Built-in Laravel | Pastikan user logged in |
| `verified` | Built-in Laravel | Pastikan email verified |
| `guest` | Built-in Laravel | Hanya untuk pengguna yang belum login |
| `is_admin` | Custom `IsAdmin` | Cek `user.role === 'admin'`, abort 403 jika tidak |

### Middleware Registration
`IsAdmin` middleware perlu didaftarkan dalam `bootstrap/app.php` atau `app/Http/Kernel.php` dengan alias `is_admin`.

---

## 9. Environment Variables Penting

```env
APP_NAME=Laravel
DB_CONNECTION=sqlite
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local
MAIL_MAILER=log
```

---

## 10. Setup Instructions (Untuk Agent Baru)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database
php artisan migrate

# 4. Seed data
php artisan db:seed
php artisan db:seed --class=InvestmentPlanSeeder

# 5. Development server
composer dev
# Atau secara terasing:
php artisan serve
npm run dev
```

---

## 11. Nota Penting untuk Agent

> [!CAUTION]
> **Maturity Timing:** `end_at` pada investasi ditetapkan sebagai `now()->addHours(rand(3, 6))` — bukan hari. Ini mungkin sengaja untuk demo/testing.

> [!WARNING]
> **Transfer Fee 30%** — kadar ini sangat tinggi dan dikod keras sebagai constant `FEE_RATE = 0.30`.

> [!IMPORTANT]
> **Withdraw langsung tolak baki** — Baki ditolak saat request dibuat, bukan saat admin approve. Jika admin reject, baki dikembalikan.

> [!NOTE]
> **Wallet dibuat lazily** — Wallet tidak dibuat saat registration, tetapi saat pertama kali user akses Dashboard.
