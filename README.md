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

-   Add Data Roles, Departments and Divisions

```bash
php artisan db:seed
```

-   Run localhost

```bash
php artisan serve
```

<!-- -   Run Queue
```bash
php artisan queue:work
``` -->

### Visit Localhost (http://localhost:8000/) api

### Visit Localhost (http://localhost:3000) client

### NextJS Clone (Auto Synch)

https://github.com/laravel/breeze-next

### API Documentation

Visit Postman Docs ... belum
