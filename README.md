## Renhead API Setup

    1. Clone repository localy
    2. Use composer to install all dependencies
            -> composer install
    4. Change .env file with your database details (file is located in root directory)
    5. Migrate and seed database
            -> php artisan migrate // run command to make table migrations
            -> php artisan db:seed // run command to seed database with dummy data
    6. You can find Swagger Documentation on {siteurl}/api/documentation
