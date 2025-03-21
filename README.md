# Course Registration System

A PHP MVC application for managing student information and course registration.

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server with mod_rewrite enabled
- MySQL Workbench (recommended for database management)

## Installation

1. Clone the repository to your web server's document root:
```bash
git clone <repository-url> ktsangt6
```

2. Database Setup:
   - Open MySQL Workbench
   - Connect to your MySQL server
   - The application uses a database named `Test1`
   - Ensure your MySQL user has appropriate permissions for the `Test1` database

3. Configure your web server:
   - Point the document root to the `public` directory
   - Ensure mod_rewrite is enabled
   - Make sure the `uploads` directory is writable:
```bash
chmod 777 public/uploads
```

4. Update the configuration in `app/config/config.php`:
   - Set your database credentials
   - Update URLROOT to match your server setup
   - Default database name is `Test1`

## Features

- Student Management (CRUD)
  - List view
  - Add new student
  - Edit student information
  - Delete student
  - View student details

- Course Registration
  - View available courses
  - Register for courses
  - View registered courses
  - Unregister from courses
  - Course capacity management

## Directory Structure

```
ktsangt6/
├── app/
│   ├── config/
│   ├── controllers/
│   ├── core/
│   ├── helpers/
│   ├── models/
│   └── views/
├── public/
    ├── css/
    ├── js/
    ├── uploads/
    └── index.php

```

## Usage

1. Access the application through your web browser:
```
http://localhost:8080
```

2. Use the navigation menu to:
   - Manage students
   - View and register for courses
   - View registration history 