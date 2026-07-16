# PHP Multi-Database Authentication System

A secure authentication system built with **PHP**, **MySQL**, **MongoDB**, and **Redis**.

##  Features

- User Registration & Login
- Password Hashing
- Profile Image Upload
- MySQL Authentication
- MongoDB User Profiles
- Redis Session Management

##  Tech Stack

- PHP
- HTML, CSS, JavaScript
- MySQL
- MongoDB
- Redis

##  Database Usage

- **MySQL** – Authentication (Email & Password)
- **MongoDB** – User Profile Data
- **Redis** – Session Tokens

 ##  Workflow

1. Register a new account
2. Credentials are stored in MySQL
3. User details are stored in MongoDB
4. Login verifies credentials
5. Redis creates a session token
6. Dashboard validates the session
7. Logout removes the session token

## ▶️ Run

```bash
composer install
```

Start **Apache**, **MySQL**, **MongoDB**, and **Redis**, then open:


```
http://localhost/login-project/register.html
```

##  Author

**Shri Nithi M.A.**
