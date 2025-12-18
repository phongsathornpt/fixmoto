# FixMoto - Motorcycle Repair Management System

## Overview

FixMoto is a motorcycle repair shop management system built with PHP using Object-Oriented Programming (OOP) principles and the MVC (Model-View-Controller) architectural pattern.

## Features

- **Customer Management**: Track customer information and repair history
- **Repair Management**: Create and manage repair jobs
- **Parts Inventory**: Manage parts stock and pricing
- **Purchase Orders**: Create and track purchase orders from suppliers
- **User Authentication**: Secure login system

## Project Structure

```
fixmoto/
├── app/
│   ├── Core/              # Core framework classes
│   │   ├── Router.php     # URL routing
│   │   ├── Database.php   # Database singleton
│   │   ├── Controller.php # Base controller
│   │   └── Model.php      # Base model
│   ├── Controllers/       # Application controllers
│   │   ├── AuthController.php
│   │   ├── FixController.php
│   │   ├── PartController.php
│   │   └── PurchaseController.php
│   ├── Models/           # Data models
│   │   ├── Customer.php
│   │   ├── Fix.php
│   │   ├── Part.php
│   │   ├── Supplier.php
│   │   └── Purchase.php
│   └── Views/            # View templates
│       ├── layouts/
│       ├── auth/
│       ├── fix/
│       ├── part/
│       └── purchase/
├── config/
│   ├── database.php      # Database configuration
│   └── routes.php        # Route definitions
├── public/
│   ├── index.php         # Application entry point
│   ├── .htaccess         # URL rewriting
│   └── assets/           # Static assets
└── .htaccess             # Root URL rewriting

```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- PDO PHP Extension

## Installation

1. **Clone or download the project**

   ```bash
   cd /path/to/your/webserver/
   ```

2. **Configure database**

   - Import the database schema from `db.sql`
   - Update database credentials in `config/database.php`

3. **Configure Apache**

   - Ensure mod_rewrite is enabled
   - Point your virtual host to the `public/` directory
   - Or access via `http://localhost/fixmoto/public/`

4. **Set permissions** (if needed)
   ```bash
   chmod -R 755 /path/to/fixmoto
   ```

## Default Credentials

The system comes with a default administrator account:

- **Username**: `admin`
- **Password**: `admin`

> **Note**: For security, please change this password immediately after logging in.

## Configuration

### Database Configuration

Edit `config/database.php`:

```php
return [
    'host' => 'localhost',
    'database' => 'fixmoto',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

## URL Structure

The application uses clean URLs via `.htaccess`:

- `/` - Login page
- `/home` - Dashboard
- `/fix` - Repair list
- `/fix/create` - Add new repair
- `/fix/{id}` - Repair details
- `/parts` - Parts list
- `/parts/create` - Add new part
- `/purchase` - Purchase orders list
- `/purchase/create` - Create purchase order

## Architecture

### MVC Pattern

- **Models**: Handle database operations and business logic
- **Views**: Display data to users (PHP templates)
- **Controllers**: Handle requests and coordinate between models and views

### Core Components

#### Router (`app/Core/Router.php`)

- Handles URL routing
- Supports route parameters (e.g., `/fix/{id}`)
- Maps routes to controller methods

#### Database (`app/Core/Database.php`)

- Singleton pattern for single PDO instance
- Provides query helper methods
- Manages transactions

#### Controller (`app/Core/Controller.php`)

- Base class for all controllers
- Provides view rendering
- Handles redirects and authentication

#### Model (`app/Core/Model.php`)

- Base class for all models
- Provides basic CRUD operations
- Database access abstraction

## Adding New Features

### Create a New Route

Edit `config/routes.php`:

```php
$router->get('/your-path', 'YourController@method');
$router->post('/your-path', 'YourController@method');
```

### Create a New Controller

Create `app/Controllers/YourController.php`:

```php
<?php
require_once __DIR__ . '/../Core/Controller.php';

class YourController extends Controller {
    public function method() {
        $this->requireAuth(); // If authentication needed
        $this->view('your-view', ['data' => $data]);
    }
}
```

### Create a New Model

Create `app/Models/YourModel.php`:

```php
<?php
require_once __DIR__ . '/../Core/Model.php';

class YourModel extends Model {
    protected $table = 'your_table';

    public function customMethod() {
        $sql = "SELECT * FROM your_table WHERE condition = :value";
        return $this->fetchAll($sql, ['value' => $value]);
    }
}
```

### Create a New View

Create `app/Views/your-folder/your-view.php`:

```php
<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>

<div class="container mt-4">
    <!-- Your content here -->
</div>

</body>
</html>
```

## Security

- Passwords are stored in the database (consider using password_hash() for production)
- SQL injection protection via PDO prepared statements
- Session-based authentication
- CSRF protection should be added for production use

## Development vs Production

For production deployment:

1. Change database credentials in `config/database.php`
2. Implement proper password hashing
3. Add CSRF protection
4. Enable error logging instead of display
5. Use HTTPS
6. Add input validation and sanitization

## Troubleshooting

### 404 Errors

- Ensure mod_rewrite is enabled
- Check `.htaccess` files are present
- Verify Apache configuration allows `.htaccess` overrides

### Database Connection Errors

- Verify database credentials in `config/database.php`
- Ensure MySQL service is running
- Check database exists and user has permissions

### Blank Pages

- Check PHP error logs
- Enable error display temporarily: `ini_set('display_errors', 1);`

## License

This project is for educational purposes.

## Support

For issues or questions, please refer to the documentation or contact the development team.
