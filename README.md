# 🌟 LorDane's Place - Resort & Venue Booking System

LorDane's Place is a dedicated resort and event venue management application built to stream-line bookings, showcase our beautiful spaces, and provide clients with an unforgettable digital experience. This platform offers clients the ability to explore our facilities (including an interactive 360° virtual tour), reserve dates for their stay, and manage their bookings.

## 🚀 Key Features

* **Beautiful UI/UX**: Completely designed using Tailwind CSS (v4) with an elegant, mobile-responsive light theme, utilizing smooth transitions and accessible typography.
* **Property Showcase**: Detailed presentations of all packages (Rooms, Cottages, and Venue Hall) available at the resort.
* **360° Virtual Tour**: Fully integrated immersive 360-degree panorama viewer allowing guests to "walk" the resort before they arrive.
* **Online Booking System**: Seamless reservation system allowing users to easily pick a package and request a booking.
* **Admin Dashboard**: Secure backend that empowers administrators to oversee reservations, handle user data, set up pricing, and block out dates on the calendar.
* **User Authentication**: Secure user login and registration portal integrated specifically for returning clients to track their past or upcoming bookings.

---

## 🛠️ Technology Stack

This application takes advantage of modern web development frameworks for speed, security, and scalability.

- **Backend Framework**: Laravel 11 (PHP 8.2+)
- **Frontend Styling**: Tailwind CSS
- **Asset Bundler**: Vite
- **Database**: MySQL
- **Templating Engine**: Laravel Blade

---

## 💻 Installation & Setup

If you are a developer looking to run LorDane's Place on your local machine using **XAMPP**, follow these precise steps:

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- XAMPP (with Apache & MySQL running)

### Setup Steps
1. **Clone or Extract the Project**
   Place the project folder inside your XAMPP `htdocs` directory. For example: `C:\xampp\htdocs\dashboard\LordanesPlace`

2. **Install PHP Dependencies**
   Open your terminal inside the project folder and run:
   ```bash
   composer install
   ```

3. **Install & Build Frontend Assets**
   Compile all the stunning Tailwind styles by running:
   ```bash
   npm install
   npm run build
   ```

4. **Environment Configuration**
   Copy `.env.example` and rename it to `.env`. Create a MySQL database (e.g., `lordanesplace` in phpMyAdmin) and update your `.env` credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lordanesplace
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate Keys & Migrate**
   Set up your application key and push the table structure to your database:
   ```bash
   php artisan key:generate
   php artisan migrate
   ```

6. **Start the Development Server**
   Start Laravel's built-in server (Recommended over XAMPP apache routing for this project):
   ```bash
   php artisan serve
   ```
   *Your site will now be accessible at `http://127.0.0.1:8000` !*
   
---

## 🗺️ Project Structure Highlights
* `routes/web.php` – Houses all public, auth, and admin URLs.
* `resources/views/` – Contains all the visual pages (Blade files). Includes the `partials/` folder for shared components like the Navigation Bar and Footer.
* `public/lordanes360view/` – Houses the immersive Javascript-based 360 visual tour plugin.
* `resources/css/app.css` – Central repository for Tailwind's config and UI styling tokens.

## 🤝 Support
If you encounter any bugs, unexpected routing issues (like a 404), or UI glitches during the setup, be sure to use `php artisan optimize:clear` to clear stale cache structures.
