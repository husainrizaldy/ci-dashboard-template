# DASHBOARD TEMPLATE

## Persyaratan Sistem
1. PHP 8 atau versi lebih baru
2. MySQL Database
3. Web Server (Contoh: Apache)
4. Composer (untuk instalasi dependensi)

## Tech Stack
- codeigniter 3
- bootstrap 5
- jquery

## Library 
- mpdf
- phpspreadsheet
- phpdotenv

## fitur default 
- Authentication
- profile (identity, account, layout)
- Data User
- User Role
- Role Access Menu
- Management Menu (tree view : Modul -> menu -> submenu)

## Instalasi

1. Clone repositori ini ke direktori web server Anda:

```bash
git clone https://github.com/husainrizaldy/ci-dashboard-template.git
```
2. instalasi depedensi 

```bash
composer install
```
3. buat file .env dari .env.example, masukan detail koneksi ke database

```bash
DB_HOSTNAME="localhost"
DB_USERNAME=""
DB_PASSWORD=""
DB_DATABASE=""
DB_DRIVER="mysqli"
```

4. pastikan envirotnment pada mode 'development', cek pada file index.php

```bash
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
```

5. buka browser dan jalankan aplikasi

format :
```bash
http://hostname/project-dir
```
contoh :
```bash
http://localhost/project-01
```
