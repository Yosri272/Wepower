# Laravel Project 

## Introduction
This is a Laravel-based project named **WePower**. It follows the MVC structure and includes various modules and dependencies for web development.

## Requirements
Before installing, ensure your system meets the following requirements:
- PHP >= 8.0
- Composer
- Node.js and npm
- MySQL or any other supported database
- Laravel dependencies installed

## Installation
Follow these steps to set up the project:

1. **Clone the Repository** (if applicable):
   ```sh
   git clone <repository-url>
   cd wepower
   ```

2. **Install Composer Dependencies:**
   ```sh
   composer install
   ```

3. **Install NPM Dependencies:**
   ```sh
   npm install && npm run dev
   ```

4. **Set Up Environment File:**
   ```sh
   cp .env.example .env
   ```
   Then configure the `.env` file with database credentials and other required settings.

5. **Generate Application Key:**
   ```sh
   php artisan key:generate
   ```

6. **Run Database Migrations:**
   ```sh
   php artisan migrate
   ```

7. **Run Database Seeder (if available):**
   ```sh
   php artisan db:seed
   ```

8. **Start the Local Development Server:**
   ```sh
   php artisan serve
   ```

## Additional Commands
- **Clear Cache:**
  ```sh
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```
- **Run Tests:**
  ```sh
  php artisan test
  ```

## Deployment
- Use `php artisan optimize` to optimize the app before deployment.
- Make sure to set up the necessary environment variables in production.
- Run `composer install --no-dev --optimize-autoloader` for production-ready dependencies.

## License
This project is licensed under a proprietary license. Contact the project owner for more details.

## Contact
For any issues or inquiries, reach out to the development team or open an issue in the repository.

