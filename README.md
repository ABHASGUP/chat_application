# Real-Time Chat Application

A modern, real-time chat application built with PHP, MySQL, and JavaScript. Features include emoji support, file sharing, and real-time message updates.

## Features

- Real-time messaging
- User authentication (login/signup)
- Emoji picker with multiple categories
- File sharing (images, documents)
- Location sharing
- Online/offline status
- Message history
- Responsive design
- WhatsApp-like interface

## Technologies Used

- PHP 7.4+
- MySQL 5.7+
- JavaScript (ES6+)
- HTML5
- CSS3
- AJAX
- Font Awesome 5
- XAMPP Server

## Prerequisites

- XAMPP (or similar PHP development environment)
- Web browser
- Git

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/chat_application.git
```

2. Move the project to your XAMPP's htdocs folder:
```bash
mv chat_application /path/to/xampp/htdocs/
```

3. Create a new MySQL database named 'chat':
```sql
CREATE DATABASE chat;
```

4. Import the database structure:
- Open phpMyAdmin
- Select the 'chat' database
- Import the provided SQL file from the 'database' folder

5. Configure the database connection:
- Copy `config.php.example` to `config.php`
- Update the database credentials in `config.php`

6. Start your XAMPP server (Apache and MySQL)

7. Access the application:
```
http://localhost/chat_application
```

## Usage

1. Register a new account or login with existing credentials
2. Start chatting with other users
3. Use the emoji picker to add emojis to your messages
4. Share files and location using the attachment menu
5. View online/offline status of other users

## Directory Structure

```
chat_application/
├── css/
│   └── style.css
├── javascript/
│   ├── chat.js
│   ├── login.js
│   ├── signup.js
│   └── users.js
├── php/
│   ├── config.php
│   ├── login.php
│   ├── signup.php
│   └── ...
├── images/
├── index.php
└── README.md
```

## Security Features

- Password hashing
- SQL injection prevention
- XSS protection
- Session management
- Secure file upload handling

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Font Awesome for icons
- XAMPP development team
- PHP development community
