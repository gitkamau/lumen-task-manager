## Task Management API

### Requirements
- PHP 7.4+
- PostgreSQL
- Composer

### Setup
1. Clone the repository.
2. Run `composer install` to install dependencies.
3. Create a `.env` file from the `.env.example` and update the database configuration.
4. Run `php artisan migrate` to create the database tables.
5. Run `php -S localhost:8000 -t public` to start the application.

### Endpoints
- POST `/api/tasks`: Create a new task.
- GET `/api/tasks`: Get all tasks (with filtering and pagination).
- GET `/api/tasks/{id}`: Get a specific task.
- PUT `/api/tasks/{id}`: Update a task.
- DELETE `/api/tasks/{id}`: Delete a task.
- requestBody:
`{
  "title": "Task Title",
  "description": "Task description",
  "due_date": "YYYY-MM-DD"
}