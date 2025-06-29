# 🧪 Laboratory Booking System

A web-based Laboratory Booking System for universities and academic institutions to manage lab reservations, equipment usage, and usage reports. It streamlines coordination between Instructors and Technical Officers (TOs), ensuring lab schedules are optimized and resource usage is logged efficiently.

---

## 📌 Features

- 👩‍🏫 **Instructor Portal**:  
  - Register and login  
  - Request lab bookings for specific time slots  
  - View approved or pending bookings  

- 🧑‍🔧 **Technical Officer (TO) Portal**:  
  - Manage lab and equipment inventory  
  - Approve or reject booking requests  
  - Log lab usage and assign equipment used  

- 🧪 **Lab & Equipment Management**:  
  - Each lab has a schedule and associated equipment  
  - Log real-time lab usage and check-in/check-out  
  - Multi-equipment logging per booking  

- 📊 **Usage Reports**:  
  - Generate filtered usage reports by date or lab  
  - Export both lab and equipment usage as CSV files  

- 🎨 **Attractive Interface**:  
  - Mobile-responsive design with Bootstrap  
  - Clean UI for better user experience  

---

## 🛠 Tech Stack

| Layer       | Technology               |
|-------------|---------------------------|
| Frontend    | HTML5, CSS3, Bootstrap 5  |
| Backend     | PHP 8+                    |
| Database    | MySQL (MariaDB)           |
| Auth        | PHP Session-based Login   |

---

## 🗃️ Database Structure

Includes the following main tables:

- `LAB` – Lab information  
- `TO` – Technical Officers  
- `INSTRUCTOR` – Registered instructors  
- `LAB_BOOKING` – Booking requests  
- `LAB_EQUIPMENT` – Equipment data  
- `LAB_SCHEDULE` – Availability records  
- `USAGE_LOG` – Usage records  
- `USAGE_EQUIPMENT` – Equipment used during log

---

## 🔐 User Roles

- `Instructor`:  
  - Can register/login  
  - Can request lab bookings  
  - Can view status of their bookings

- `Technical Officer (TO)`:  
  - Can register/login  
  - Can view and manage lab bookings  
  - Can log lab usage and assign equipment  
  - Can export lab/equipment usage reports

---

## 📈 Workflow

1. **Instructor** registers and books a lab  
2. **TO** approves the booking request  
3. On booking date, **TO logs actual usage**, check-in/out time, and equipment  
4. **Reports** can be filtered by date and exported as CSV

---

## 🧰 Setup Instructions

1. Clone this repository:
   ```bash
   git clone https://github.com/MAHESHISAGARIKA/lab-booking-system.git
