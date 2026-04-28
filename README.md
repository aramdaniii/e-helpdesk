# IT Service Desk - Diaspora

A modern IT Service Desk application built with Laravel 11 and Filament v3 for efficient ticket management and issue tracking.

## Description

IT Service Desk - Diaspora is a comprehensive helpdesk system designed to streamline the process of reporting, tracking, and resolving technical issues. It provides role-based access control, real-time statistics, and a user-friendly interface for both administrators and end-users.

## Tech Stack

- **Backend:** Laravel 11
- **Admin Panel:** Filament PHP v3
- **Database:** MySQL / SQLite
- **Authentication:** Filament Shield (Spatie Permission)
- **UI Components:** TailwindCSS

## Features

### Core Functionality
- **Ticket Management:** Create, view, edit, and delete support tickets
- **Dashboard Statistics:** Real-time overview of total tickets, open/closed status, and category distribution
- **Role-Based Access Control:** 
  - **Admin:** Full access to all tickets, users, and system settings
  - **Anggota (User):** Can only view and manage their own tickets
- **File Upload:** Attach photos/screenshots to tickets for better issue documentation
- **Ticket Categories:** Software, Hardware, Jaringan, Printer, Aplikasi
- **Priority Levels:** Rendah, Sedang, Tinggi
- **Status Tracking:** Open, In Progress, Pending, Resolved, Closed
- **Rating System:** Users can rate solutions after ticket resolution

### UI/UX Features
- **Top Navigation Layout:** Modern navigation bar instead of sidebar
- **Custom Typography:** Lexend font for improved readability
- **Color Theme:** Zinc background with Violet primary accent
- **Responsive Design:** Works seamlessly on desktop and mobile devices
- **Lazy Loading Images:** Optimized image loading for better performance

## Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL or SQLite
- Node.js & NPM (for asset compilation)

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/aramdaniii/e-helpdesk.git
   cd e-helpdesk
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=e_helpdesk_it
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   APP_URL=http://127.0.0.1:8000
   FILESYSTEM_DISK=public
   ```

5. **Run migrations and seed**
   ```bash
   php artisan migrate --seed
   ```

6. **Create storage link**
   ```bash
   php artisan storage:link
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   - Admin Panel: http://127.0.0.1:8000
   - Login with default credentials (see below)

## Default Credentials

After running the database seeder, you can login with:

**Admin Account:**
- Email: `admin@ehelpdesk.com`
- Password: `password123`

**Anggota (User) Account:**
- Email: `anggota@ehelpdesk.com`
- Password: `password123`

## Project Structure

```
app/
├── Filament/
│   ├── Pages/          # Custom Filament pages
│   ├── Resources/      # Filament resources (Tiket, User)
│   └── Widgets/        # Dashboard widgets
├── Models/            # Eloquent models
├── Providers/         # Service providers
database/
├── migrations/        # Database migrations
└── seeders/          # Database seeders
```

## Usage

### For Admins
1. Login with admin credentials
2. View dashboard statistics
3. Manage all tickets (view, edit, resolve)
4. Manage users and roles
5. Monitor ticket categories and priorities

### For Users (Anggota)
1. Login with user credentials
2. Create new support tickets
3. View and manage own tickets
4. Track ticket status
5. Rate solutions after resolution

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the MIT license.

## Support

For support, please open an issue in the GitHub repository or contact the development team.
