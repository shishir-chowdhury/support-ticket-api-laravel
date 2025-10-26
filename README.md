# Support Ticket System

This is a **Support Ticket System** built with **Laravel (Backend)** and **React (Frontend)**. It allows users (Customers) to create tickets, and Admins to manage tickets. It also supports **live chat** on tickets using **Pusher & Laravel Echo**.

---

## **Table of Contents**

- [Features](#features)
- [System Requirements](#system-requirements)
- [Installation](#installation)
    - [Backend (Laravel)](#backend-laravel)
    - [Frontend (React)](#frontend-react)
- [API Documentation](#api-documentation)
    - [Authentication](#authentication)
    - [Tickets](#tickets)
    - [Comments](#comments)
    - [Broadcasting](#broadcasting)
- [Environment Variables](#environment-variables)
- [License](#license)

---

## **Features**

- User registration & login (Customer & Admin)
- Ticket management (Create, View, Update, Delete)
- Comment system on tickets
- Live chat with Pusher & Laravel Echo
- File attachments in tickets
- Role-based access control

---

## **System Requirements**

- PHP >= 8.1
- Composer
- Node.js >= 18.x
- npm or yarn
- MySQL or other supported DB
- Redis (optional, for caching/broadcasting)
- Pusher account (for real-time chat)

---

## **Installation**

### **Backend (Laravel)**

1. Clone the repository:
```bash
git clone <your-backend-repo-url>
cd <your-backend-repo-folder>
Install dependencies:

bash
Copy code
composer install
Copy .env file and configure environment variables:

bash
Copy code
cp .env.example .env
Configure .env:

env
Copy code
APP_NAME=SupportTicket
APP_ENV=local
APP_KEY=base64:GENERATE_KEY
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=support_ticket
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-app-key
PUSHER_APP_SECRET=your-pusher-app-secret
PUSHER_APP_CLUSTER=mt1
Generate application key:

bash
Copy code
php artisan key:generate
Run migrations:

bash
Copy code
php artisan migrate
(Optional) Seed the database:

bash
Copy code
php artisan db:seed
Start the Laravel server:

bash
Copy code
php artisan serve
API is available at https://support-ticket-api.shishirchowdhury.com/.

API Endpoints
Authentication

Register: POST /api/register

Login: POST /api/login

Tickets

Create Ticket: POST /api/tickets (with Authorization header)

Get Ticket Details: GET /api/tickets/{id}

List Tickets: GET /api/tickets

Comments / Chat

Get Comments: GET /api/tickets/{ticket_id}/comments

Add Comment: POST /api/tickets/{ticket_id}/comments

Real-time updates via Pusher channel: ticket.{id}
