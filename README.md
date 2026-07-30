# 🎂 Luxe Cake - Online Cake Ordering System

A modern PHP and MySQL based Online Cake Ordering System that allows customers to browse cakes, add them to the cart, place orders, and securely manage their accounts. The project also includes an admin dashboard for managing cakes, orders, and customers.

---

## 📌 Project Overview

Luxe Cake is a full-stack web application developed using PHP, MySQL, HTML, CSS, and JavaScript. The application provides a complete online cake shopping experience with user authentication, shopping cart management, secure checkout, and an administrative dashboard.

---

## ✨ Features

### 👤 User Module
- User Registration
- Secure Login & Logout
- Email Verification
- Browse Cake Catalog
- View Cake Details
- Add to Cart
- Update/Remove Cart Items
- Place Orders
- Order Confirmation

### 🛠 Admin Module
- Admin Dashboard
- Manage Cake Products
- Manage Customer Orders
- Update Order Status
- Manage Users

---

## 🛠 Tech Stack

| Technology | Used |
|------------|------|
| PHP | Backend |
| MySQL | Database |
| HTML5 | Frontend |
| CSS3 | Styling |
| JavaScript | Client-side |
| Bootstrap | Responsive UI |
| XAMPP | Local Server |

---

## 📂 Project Structure

```
Luxe-Cake/
│
├── admin/
│   ├── actions/
│   ├── css/
│   └── index.php
│
├── actions/
│
├── css/
│
├── images/
│
├── includes/
│   └── db.php
│
├── index.php
├── login.php
├── register.php
├── cart.php
├── checkout.php
├── verify_email.php
│
└── README.md
```

---

## ⚙ Installation

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/Luxe-Cake.git
```

### 2. Move Project

Copy the project into:

```
C:\xampp\htdocs\
```

### 3. Create Database

Create a database named:

```
cake_shop
```

### 4. Import Database

Import the provided SQL file:

```
database/cake_shop.sql
```

### 5. Configure Database

Open

```
includes/db.php
```

Update your credentials.

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "cake_shop";
```

### 6. Start Server

Start:

- Apache
- MySQL

using XAMPP.

### 7. Open Browser

```
http://localhost/Luxe-Cake
```

---

## 📸 Screenshots

> Add screenshots inside a folder named **screenshots**

Example:

```
screenshots/
    home.png
    login.png
    cart.png
    checkout.png
    admin-dashboard.png
```

Then display them:

```md
## Home Page

![Home](screenshots/home.png)

## Shopping Cart

![Cart](screenshots/cart.png)

## Admin Dashboard

![Admin](screenshots/admin-dashboard.png)
```

---

## 🔒 Security Features

- Password Hashing
- Session Management
- Input Validation
- Prepared Statements
- Email Verification

---

## 🚀 Future Improvements

- Online Payment Gateway
- Order Tracking
- Wishlist
- Product Reviews
- Coupon System
- Inventory Management
- Email Notifications
- Responsive Admin Dashboard

---

## 📖 Learning Outcomes

- PHP CRUD Operations
- Session Handling
- Authentication
- Database Design
- Shopping Cart Logic
- Admin Dashboard Development
- MySQL Relationships

---

## 👨‍💻 Author

**Ameenul Lumin**

Software Developer

Interested in:

- Java Backend Development
- Web Development
- Cybersecurity
- Computer Vision

---

## ⭐ Show Your Support

If you found this project useful, give it a ⭐ on GitHub.
