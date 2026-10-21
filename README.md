# Masjid Nurul Haqq

![Home Page Masjid Nurul Haqq](/public/image-readme.png)

## Instalasi

Clone Repo ini

```bash
  git clone https://github.com/bagusardin25/pengmas-masjid-nurul-haqq.git
```

Pindah direktori

```bash
  cd pengmas-masjid-nurul-haqq
```

Install depedensi

```bash
  composer install
  npm install
```

Salin file .env.example

```bash
  cp .env.example .env
```

Generate app key laravel

```bash
  php artisan key:generate
```

Buat database dan hubungkan di file .env

```bash
  DB_DATABASE=nurul_haqq
```

Jalankan migrasi

```bash
  php artisan migrate
  # atau
  php artisan migrate --seed
  # atau
  php artisan migrate:fresh --seed
```

Jalankan server

```bash
  php artisan serve
  # start di terminal lain
  npm run dev
```
