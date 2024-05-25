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

7. Configure your mail driver and SMTP settings in the `.env` file for sending reminder emails:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Replace `your_mailtrap_username`, `your_mailtrap_password`, and `your_email@example.com` with your actual Mailtrap credentials or other SMTP server details.

8. Set up your queue driver in the `.env` file to enable queue processing:

```bash
QUEUE_CONNECTION=database
```
Ensure your database is properly configured for queue processing. If using the `database` queue driver, run the following command to create the necessary jobs table:

```bash
php artisan queue:table
```
9. Run migrations to create database tables

```bash
php artisan migrate
```


## Usage

1. Start the Laravel development server:

```bash
php artisan serve
```
2. Start the queue worker to process jobs in the background:

```bash
php artisan queue:work
```
3. Access the application in your web browser at `http://127.0.0.1:8000`.

4. Register a new account or log in with existing credentials.

5. Create, update, delete, and view event reminders as needed. Reminder emails will be sent to specified recipients based on the event details.

## Additional Configuration

- For advanced mail configuration, refer to the Laravel documentation: [Mail Configuration](https://laravel.com/docs/mail).
- For configuring different queue drivers and advanced queue configuration, refer to the Laravel documentation: [Queue Configuration](https://laravel.com/docs/queues).

## Contributing

Contributions are welcome! Please feel free to submit any issues or pull requests.

## License

This project is open-source and available under the [MIT License](LICENSE).



