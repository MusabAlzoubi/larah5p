# LaraH5P - Advanced H5P Integration for Laravel

LaraH5P is a Laravel package that provides seamless integration with **H5P**, allowing you to **create, manage, and interact with interactive content** directly within your Laravel application.

---

## 📌 Features

- Full integration with **H5P Core** and **H5P Editor**.
- Content management (create, edit, delete).
- Library management with AJAX support.
- Export and embed H5P content.
- Permission-based access control.
- Fully customizable.

---

## 📥 Installation

### 1. Install the package

Run the following command in your Laravel project:

```sh
composer require larah5p/larah5p
```

### 2. Publish the package assets, config, migrations, and views

```sh
php artisan vendor:publish --provider="LaraH5P\Providers\LaraH5PServiceProvider" --force
```

This will publish:
- **Config file:** `config/larah5p.php`
- **Migrations:** `database/migrations`
- **Views:** `resources/views/vendor/larah5p`
- **Language files:** `resources/lang/vendor/larah5p`
- **Assets:** `public/vendor/larah5p`

### 3. Run migrations

```sh
php artisan migrate
```

### 4. Generate an application key

If you haven't generated an application key yet, run:

```sh
php artisan key:generate
```

### 5. Clear and cache configurations

```sh
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear
```

---

## 🔧 Configuration

LaraH5P provides a configuration file that allows you to **customize the package settings**.

You can find it at:

```sh
config/larah5p.php
```

### Key Settings:
- Default layout
- Storage paths
- Debugging options
- User permissions

---

## 🌍 Routes

LaraH5P registers various routes for managing H5P content and libraries. To list them, run:

```sh
php artisan route:list
```

### Main Routes:

| Route | Controller | Description |
|--------|-----------|-------------|
| `/h5p` | `H5pController` | Manage H5P content |
| `/library` | `LibraryController` | Manage H5P libraries |
| `/ajax` | `AjaxController` | Handle AJAX requests |
| `/h5p/embed/{id}` | `EmbedController` | Embed H5P content |
| `/h5p/export/{id}` | `DownloadController` | Export H5P content |

---

## 🎨 Optional Packages

LaraH5P supports additional Laravel packages for **extended functionality**.

### 1. **Spatie Laravel Permissions**

If you need **role-based permissions**, install Spatie's **Laravel Permission** package:

```sh
composer require spatie/laravel-permission
```

Then, publish and run migrations:

```sh
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 2. **Filament Admin Panel**

If you want to use **Filament** for managing H5P content from an admin panel, install:

```sh
composer require filament/filament
```

Then, publish Filament assets:

```sh
php artisan vendor:publish --tag=filament-config
```

---

## 🛠 Customization

You can override package views by modifying the files in:

```sh
resources/views/vendor/larah5p
```

If you want to extend functionality, create your own controllers:

```sh
php artisan make:controller MyCustomH5PController
```

---

## 🚀 Contributing

Contributions are **welcome**! If you find any issues or have suggestions, feel free to **open a pull request** or create an **issue**.

---

## 📄 License

This package is **open-source** and licensed under the **MIT License**.

---

## 📧 Contact

For questions or support, contact:

📧 **Email:** musab.m.alzoubii@gmail.com

---

## 🎯 Now you're ready to use LaraH5P in your Laravel project! 🚀
