-- Clean Laboratory Booking System schema
DROP TABLE IF EXISTS USAGE_LOG;
DROP TABLE IF EXISTS LAB_BOOKING;
DROP TABLE IF EXISTS LAB_EQUIPMENT;
DROP TABLE IF EXISTS LAB_SCHEDULE;
DROP TABLE IF EXISTS LAB;
DROP TABLE IF EXISTS TECHNICAL_OFFICER;
DROP TABLE IF EXISTS INSTRUCTOR;

CREATE TABLE TECHNICAL_OFFICER (
  TO_id INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  Email VARCHAR(100) UNIQUE,
  Password VARCHAR(255)
);

CREATE TABLE INSTRUCTOR (
  Instructor_id INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  Email VARCHAR(100) UNIQUE,
  Department VARCHAR(100),
  Password VARCHAR(255)
);

CREATE TABLE LAB (
  Lab_id INT AUTO_INCREMENT PRIMARY KEY,
  Lab_name VARCHAR(100),
  Lab_type VARCHAR(50),
  Capacity INT,
  Availability BOOLEAN DEFAULT TRUE,
  TO_id INT,
  FOREIGN KEY (TO_id) REFERENCES TECHNICAL_OFFICER(TO_id)
    ON DELETE SET NULL
);

CREATE TABLE LAB_SCHEDULE (
  Schedule_id INT AUTO_INCREMENT PRIMARY KEY,
  Lab_id INT,
  Date DATE,
  Start_time TIME,
  End_time TIME,
  Is_available BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (Lab_id) REFERENCES LAB(Lab_id) ON DELETE CASCADE,
  CONSTRAINT chk_schedule_time CHECK (End_time > Start_time)
);

CREATE TABLE LAB_EQUIPMENT (
  Equipment_id INT AUTO_INCREMENT PRIMARY KEY,
  Lab_id INT,
  Name VARCHAR(100),
  Quantity INT,
  Availability BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (Lab_id) REFERENCES LAB(Lab_id) ON DELETE CASCADE
);

CREATE TABLE LAB_BOOKING (
  Booking_id INT AUTO_INCREMENT PRIMARY KEY,
  Course_name VARCHAR(100),
  Instructor_id INT,
  Lab_id INT,
  Booking_Date DATE,
  Start_time TIME,
  End_time TIME,
  Status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
  FOREIGN KEY (Instructor_id) REFERENCES INSTRUCTOR(Instructor_id) ON DELETE CASCADE,
  FOREIGN KEY (Lab_id) REFERENCES LAB(Lab_id) ON DELETE CASCADE,
  CONSTRAINT chk_booking_time CHECK (End_time > Start_time)
);

CREATE TABLE USAGE_LOG (
  Log_id INT AUTO_INCREMENT PRIMARY KEY,
  Booking_id INT,
  Lab_id INT,
  Date DATE,
  CheckInTime TIME,
  CheckOutTime TIME,
  FOREIGN KEY (Booking_id) REFERENCES LAB_BOOKING(Booking_id) ON DELETE CASCADE,
  FOREIGN KEY (Lab_id) REFERENCES LAB(Lab_id) ON DELETE CASCADE
);

INSERT INTO TECHNICAL_OFFICER (Name, Email, Password) VALUES
('Tech Officer 1','to1@example.com','to123'),
('Tech Officer 2','to2@example.com','to234');

INSERT INTO INSTRUCTOR (Name, Email, Department, Password) VALUES
('Instructor A','ins1@example.com','Computer','ins123'),
('Instructor B','ins2@example.com','Electrical','ins234');

INSERT INTO LAB (Lab_name, Lab_type, Capacity, TO_id) VALUES
('Hydro Power','Mechanical',30,1),
('Software Lab','Computer',40,2),
('Machine Lab','Electrical',35,1);
