# Student Management System

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-orange.svg)](https://laravel.com/)
[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A web-based **Student Management System** built with **Laravel 12**, designed to manage courses, teachers, students, and notifications efficiently.

---

## 💻 Demo

Live demo: [Insert your demo link here](#)

---

## 📸 Screenshots

![Dashboard](https://via.placeholder.com/800x400?text=Dashboard+Screenshot)
![Course Management](https://via.placeholder.com/800x400?text=Course+Management+Screenshot)
![Notifications](https://via.placeholder.com/800x400?text=Notifications+Screenshot)

---

## 📂 Table of Contents
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Usage](#usage)
- [Database Structure](#database-structure)
- [Notifications](#notifications)
- [License](#license)
- [Author](#author)

---

## 🌟 Features

- **User Authentication**
  - Admin, Teacher, Student login
  - Role-based access control

- **Course Management**
  - Create, read, update, delete courses
  - Assign and re-assign teachers to courses
  - Remove teacher assignments

- **Teacher Management**
  - Assign teachers to courses based on category expertise
  - Notifications for teacher assignments and removals

- **Notifications**
  - Database notifications for teachers
  - Unread notifications displayed in dashboard
  - Notifications timestamp in user's timezone

- **Dashboard**
  - Visual charts and summaries using Chart.js
  - Quick overview of courses, students, and teachers

- **Responsive UI**
  - Built with **Bootstrap 5**
  - Works on desktop, tablet, and mobile

---

## 🛠 Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)  
- **Frontend:** Bootstrap 5, jQuery, Select2, Summernote  
- **Database:** MySQL  
- **Notifications:** Laravel Database Notifications  
- **Charts:** Chart.js  
- **Data Tables:** Simple-DataTables  

---

## ⚙️ Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/student-management.git
cd student-management
