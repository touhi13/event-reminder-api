# Event Reminder App

This is a simple Event Reminder App developed using Laravel. It allows users to create, update, delete, and view event reminders. The app also supports sending reminder emails to specified recipients.

## Installation

1. Clone the repository to your local machine:

```bash
git clone https://github.com/touhi13/event-reminder-api.git
```

2. Navigate to the project directory:

```bash
cd event-reminder-api
```

3. Install composer dependencies:

```bash
composer install
```

4. Copy the `.env.example` file and rename it to `.env`:

```bash
cp .env.example .env
```

5. Generate an application key:

```bash
php artisan key:generate
```

6. Configure your database in the `.env` file:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
