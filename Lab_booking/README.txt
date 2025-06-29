Lab Booking System (clean build)

1. Create database:
   CREATE DATABASE lab_booking_system;
   USE lab_booking_system;

2. Import schema.sql (via phpMyAdmin or CLI):
   mysql -u root -p lab_booking_system < schema.sql

3. Place PHP files in htdocs/Lab_booking (or similar).
   Edit config.php with your DB credentials if needed.

4. Login credentials (from sample data):
   - Instructor: ins1@example.com / ins123
   - Lab TO:     to1@example.com  / to123

5. Start at http://localhost/Lab_booking/login.php
