# AI Proxy Web

Simple PHP web application for forwarding AI API requests.

## Setup

1. Deploy files under a PHP-enabled web server.
2. Access `/public/setup.php` on first run to configure database and admin account.
3. After setup, login via `/public/index.php`.

## Features

- Manage third-party API keys.
- Generate site-specific `One_Key` identifiers.
- Stateless chat API at `/public/chat.php`.
- Basic function test panel.

This is a minimal demonstration and uses MD5 for password storage as requested.
