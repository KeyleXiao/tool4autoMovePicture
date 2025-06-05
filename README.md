# AI Proxy Web

Simple PHP web application for forwarding AI API requests.
Provides a JSON API with basic rate limiting and weighted key rotation.

## Setup

1. Deploy files under a PHP-enabled web server.
2. Access `/public/setup.php` on first run to configure database and admin account.
3. After setup, login via `/public/index.php`.

## Features

- Manage third-party API keys.
- Generate site-specific `One_Key` identifiers.
- Stateless chat API at `/public/api_chat.php` returning JSON with `status`, `data` and `message` fields.
- Basic OpenAPI description provided in `openapi.yaml`.
- Basic function test panel.

This is a minimal demonstration and uses MD5 for password storage as requested.
