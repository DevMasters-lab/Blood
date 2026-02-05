<p align="center">
  <a href="http://localhost:8000" target="_blank">
    <img src="https://cdn-icons-png.flaticon.com/512/2069/2069843.png" width="100" alt="BloodShare KH Logo">
  </a>
</p>

<h1 align="center">BloodShare KH 🩸</h1>

<p align="center">
    <strong>Connecting Blood Donors with Patients in Need across Cambodia.</strong>
</p>

<p align="center">
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11-red" alt="Laravel 11"></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3-blue" alt="PHP 8.3"></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind-CSS-38bdf8" alt="Tailwind CSS"></a>
    <a href="https://mysql.com"><img src="https://img.shields.io/badge/Database-MySQL-orange" alt="MySQL"></a>
</p>

---

## 📖 About The Project

**BloodShare KH** is a web-based platform designed to solve the critical shortage of blood during emergencies. It allows hospitals and individuals to post urgent blood requests, while donors can search for matches and submit proof of donation for verification.

This system was built to streamline the donation process, ensuring transparency and speed when lives are at stake.

## ✨ Key Features

### 👤 For Donors & Public
- **Real-Time Requests:** View urgent blood needs on a live feed.
- **Smart Filtering:** Search requests by Blood Type (A+, O-, etc.).
- **Donation Tracking:** Submit proof of donation (images) and track approval status.
- **Profile Management:** Manage contact info and upload profile avatars.
- **Email Notifications:** Get notified via email when a donation is approved.

### 🛡️ For Admins
- **Admin Dashboard:** Visual overview of Total Users, Pending Requests, and Donations.
- **Moderation System:** Approve or Reject donation proofs.
- **User Management:** Monitor registered users and ban spam accounts.
- **Request Management:** Delete outdated or fake blood requests.

## 🛠️ Technology Stack

- **Backend:** Laravel 11 Framework
- **Frontend:** Blade Templates, Tailwind CSS
- **Database:** MySQL
- **Icons:** FontAwesome 6
- **Features:** - `Intervention Image` (or native Storage) for file uploads.
  - `Mailable` for email notifications.
  - `Middleware` for Role-Based Access Control (Admin/User).

## 🚀 Installation Guide

Follow these steps to run the project locally:

1. **Clone the Repository**
   ```bash
   git clone [https://github.com/your-username/bloodshare-kh.git](https://github.com/your-username/bloodshare-kh.git)
   cd bloodshare-kh