# Vipasa Yoga — Live Yoga Booking & Management Platform

A modern, high-performance appointment booking and studio management SaaS platform for live 1:1 and group yoga sessions. Built with native PHP 8 MVC (zero heavy framework overhead), MySQL / MariaDB, and modern vanilla ES6 JavaScript & CSS3 design tokens.

---

## 🌟 Key Features

### 🧘 Public Marketing & Discovery
- **High-Converting Landing Page**: Responsive hero with live scheduling, certified instructor spotlights, customer transformations, verified reviews, and interactive FAQ.
- **Service Packages**: 1:1 drop-in sessions, weekly packages, and monthly multi-session plans with transparent pricing.
- **Instructors Directory**: Browse verified yoga trainers, AYUSH/Yoga Alliance credentials, specializations (Hatha, Vinyasa, Restorative, Prenatal), and bios.
- **Public Gallery**: Responsive category-filtered photography showcase with masonry/grid view and full-screen lightbox.

### 📋 Intelligent Pre-Training Health & Safety Intake
- **5-Step Categorized Wizard**: Tailored health questions based on participant life stage:
  - `Adult`, `Child`, `Teenager`, `Prenatal`, `Postnatal`, and `Senior Citizen`.
- **Dynamic Risk Screening**: Medical conditions, injury/surgery history, lifestyle patterns, and doctor clearance restrictions.
- **Interactive Review Step**: Clients can review all answers, navigate backwards with a persistent **Back** button to make adjustments, and confirm before proceeding to booking.

### 📅 Real-Time Slot Scheduling & Conflict Prevention
- **Dynamic Slot Materialization**: Automatically materializes available booking slots based on instructor recurring schedules and blocked dates.
- **Double-Booking Prevention**: When a client books a slot, that slot is instantly marked as **Not Available** with a visible disabled state across all visitors in real time.
- **Integrated Video Classroom**: Support for Google Meet and Zoom meeting links attached directly to confirmed bookings.

### 💳 Flexible Multi-Gateway Payments
- **UPI QR Direct Transfer**: Generates dynamic studio QR codes, accepts transaction UTR / Reference numbers, and accepts client proof screenshot uploads.
- **Razorpay & Stripe**: Seamless card, netbanking, and wallet checkout via secure API webhooks.
- **Admin Verification**: Two-click payment proof inspection and approval pipeline.

### 📊 Management Command Center & Dashboards
- **Admin Analytics Dashboard**: Real-time verified revenue sums, booking pipeline velocity, payment fulfillment rates, and customer growth trends.
- **Manage Bookings**: Live filterable list by date range, trainer, and status (`Confirmed`, `Pending Payment`, `Completed`, `Cancelled`).
- **Manage Customers**: Segmentation by booking activity (`All Clients`, `Signed Up Not Booked`, `Booked a Session`) with instant Excel / CSV export.
- **Manage Payments**: Financial audit logs, transaction IDs, proof viewers, and approval actions.
- **Trainer Dashboard**: Dedicated portal for yoga instructors to review assigned students, medical alerts, upcoming schedules, and earnings.

---

## 🛠️ Technology Stack

| Layer | Technology | Details |
| :--- | :--- | :--- |
| **Backend** | PHP 8.2+ | Native MVC pattern, OOP, PSR-4 autoloading, zero framework bloat |
| **Database** | MySQL 8.0+ / MariaDB | InnoDB, strict foreign key constraints, parameterized PDO statements |
| **Frontend** | Vanilla JS (ES6+) & CSS3 | CSS Custom Properties (Design Tokens), SVG executive charts, responsive grids |
| **Export Engines** | PhpSpreadsheet & Dompdf | Automated financial reports and invoice generation |
| **Security** | CSRF / Bcrypt / Rate Limiter | Secure HTTP-only cookies, session regeneration, input sanitization |

---

## 🚀 Getting Started

### 1. Prerequisites
- **PHP 8.2 or higher** with extensions: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `gd`, `zip`
- **MySQL 8.0+** or **MariaDB 10.4+**
- **Composer** (optional; dependencies are pre-vendored in `vendor/`)

### 2. Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/vipasayoga-lgtm/Website.git
   cd Website
   ```

2. **Configure environment settings:**
   ```bash
   cp .env.example .env
   ```
   Open `.env` and set your database connection, site URL, and payment gateway keys:
   ```env
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=vipasa_yoga
   DB_USER=root
   DB_PASS=
   ```

3. **Import the database schema:**
   ```bash
   mysql -u root -p vipasa_yoga < database/vipasa_yoga_production.sql
   ```
   *(Or import `database/schema.sql` followed by `database/seed.sql`).*

4. **Start the local development server:**
   ```bash
   php -S localhost:8000 -t public
   ```
   Then navigate to **http://localhost:8000** in your web browser.

---

## 📁 Project Structure

```
├── app/
│   ├── Config/          # Database, application, and route configurations
│   ├── Controllers/     # Admin, Trainer, Client, API, and Public controllers
│   ├── Core/            # Database connection, Router, Auth, Mailer, View, Payments
│   ├── Middleware/      # Auth, Guest, Role, CSRF, and RateLimit middlewares
│   ├── Models/          # Active Record models (User, Booking, Payment, Instructor, etc.)
│   └── Views/           # Semantic HTML5 views, layouts, and partials
├── bin/                 # CLI utilities (scheduled reminders, database backups)
├── database/            # Schema definitions, seed data, and SQL exports
├── public/              # Public web root (index.php, CSS, JS, brand assets, uploads)
├── storage/             # Application logs, session cache, and backups
└── docs/                # Architecture and technical design documentation
```

---

## 🔒 Production Deployment Checklist

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
2. Generate a secure random string for `APP_KEY`.
3. Enable SSL (`HTTPS`) and set `SESSION_SECURE_COOKIE=true`.
4. Configure live payment credentials (`RAZORPAY_KEY_ID` / `STRIPE_SECRET_KEY`) or customize your UPI VPA in Admin Settings.
5. Set up a cron job or scheduled task for automated session reminders:
   ```bash
   */15 * * * * /usr/bin/php /path/to/project/bin/send-reminders.php
   ```

---

## 📄 License
This project is proprietary and confidential. All rights reserved.
