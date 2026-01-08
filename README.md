This was the first prototype repo of this project:
https://github.com/Lumineskz/AnonFeedbackWall

# Anonymous Feedback Wall

A simple web application to collect and display **anonymous feedback** from users. This tool allows people to submit feedback without revealing their identity, and administrators to view or manage those submissions.

## 🧠 System Overview

The **Anonymous Feedback Wall** system enables:

- **Anonymous submission** of feedback by users  
- **Storage and retrieval** of feedback in a database  
- **Admin interface** to view or moderate submissions  
- Simple UI built with PHP, HTML, and CSS

This project is ideal for surveys, classroom feedback, product comments, or anytime you want *honest input without requiring login*.

---

## 🚀 Features

✔ Anonymous feedback submission  
✔ Feedback list display  
✔ Admin moderation (view / delete)  
✔ Lightweight and easy to deploy  

---

## 🛠 Tech Stack

| Component | Technology |
|-----------|------------|
| Backend | PHP |
| Frontend | HTML, CSS |
| Database | MySQL / MariaDB |
| Server | Apache / XAMPP / LAMP |

Languages used in the repo include **PHP, CSS, HTML**. :contentReference[oaicite:0]{index=0}

---

## 📁 Repository Structure
├── index.php # Main user interface
├── anonfeedbackwall.sql # Database schema & seed
├── assets/ # CSS / JS / Images
├── includes/ # Reusable PHP modules
├── pages/ # Secondary page views
└── README.md


---

## 📦 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/Lumineskz/AnonymousFeedbackWall.git
cd AnonymousFeedbackWall
```
### 2. Setup Database

1. Start your web server (e.g., XAMPP / WAMP / LAMP) with Apache and MySQL.

2. Open phpMyAdmin at http://localhost/phpmyadmin

3. Create a database named:

```anonfeedbackwall```

4. Import the SQL file:

```anonfeedbackwall.sql```
### 3. Configure Database Connection

If required, update the database credentials in your PHP config file (e.g., includes/db.php).

Example:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "anonfeedbackwall";
```

### 4. Run the App

Visit in your browser:

```http://localhost/AnonymousFeedbackWall/```


You should see the feedback submission form.

## 🧪 Usage
### 🗨️ As a User

1. Navigate to the app URL

2. Enter your feedback

### 👤 As Admin

1. Some apps include an admin view to:

2. View all feedback

3. Delete inappropriate entries

If your version includes an admin page, navigate to that URL (e.g., admin.php) and use the credentials configured in the database.

### 🧩 How It Works (High-Level)

1. User opens feedback form (index.php)

2. Feedback is submitted via POST

3. Server stores feedback in the database

4. Feedback listings are pulled and displayed without user identity

5. This simple design protects anonymity while still organizing feedback for analysis.

### ✨ Contributing

Contributions are welcome!
To contribute:

Fork the repo

Create a new branch (feature-name)

Commit your changes

Push and open a Pull Request
