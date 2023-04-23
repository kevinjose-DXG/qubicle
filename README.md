<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Machine Test

This test is done using PHP Version 8.1.10 and laravel framework 8.75

### Steps To Follow

- **1.Git Clone the task to your local system**
- **2.Make an env file, generate key and set database credentials**
- **3.Composer Update**
- **4.Migrate The Tables**
- **5.Run The command php artisan db:seed --class=UserSeeder**
- **6.Projectname/ can access the user registration page**
- **7.Projectname/admin can access the Admin Panel**
- **8.Admin creditentials will get after run step-5 from the UserSeeder**
- **9.Run The Query in Level Table INSERT INTO levels (id, title, points, status, created_at, updated_at,deleted_at) VALUES (NULL, 'Level 10', '1', 'active', NULL, NULL, NULL), (NULL, 'Level 9', '2', 'active', NULL, NULL, NULL), (NULL, 'Level 8', '3', 'active', NULL, NULL, NULL), (NULL, 'Level 7', '4', 'active', NULL, NULL, NULL), (NULL, 'Level 6', '5', 'active', NULL, NULL, NULL), (NULL, 'Level 5', '6', 'active', NULL, NULL, NULL), (NULL, 'Level 4', '7', 'active', NULL, NULL, NULL), (NULL, 'Level 3', '8', 'active', NULL, NULL, NULL), (NULL, 'Level 2', '9', 'active', NULL, NULL, NULL), (NULL, 'Level 1', '10', 'active', NULL, NULL, NULL)`**
