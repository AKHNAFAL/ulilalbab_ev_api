# BACA PANDUAN BERIKUT UNTUK MENGGUNAKAN REPOSITORY INI

### Usage

-   Clone repository
-   Clone `.env` file from `.env.example`
-   Setting `.env` variable with your local/production setup
-   Add location of dump binary mysql database to `MYSQL_DUMP_PATH` variable on `.env` file.
-   Update Composer

```bash
composer update
```

-   Generate Key

```bash
php artisan key:generate
```

-   Migrate database structure

```bash
php artisan migrate
```

-   Add Static Data

```bash
php artisan db:seed
```

-   Run localhost

```bash
php artisan serve
```

### Akun

#### User
Name: test
Email: test@test
Password: 123123123

#### Admin
Name: admin
Email: admin@test
Password: 123123123

<!-- -   Run Queue
```bash
php artisan queue:work
``` -->

### Localhost

(http://localhost:8000/) for BACKEND and (http://localhost:3000) for FRONTEND

### NextJS Clone (Already Connected to API http://localhost:8000/)

https://github.com/laravel/breeze-next

### API Documentation

[Visit Postman Docs (Not Final)](https://documenter.getpostman.com/view/31499252/2sAXjM3BXm)
