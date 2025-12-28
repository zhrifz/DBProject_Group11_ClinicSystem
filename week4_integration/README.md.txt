# Doctor Appointment Management System

This is a web-based Doctor Appointment Management System designed to help a **clinic staff** manage appointments and patients efficiently. Doctors have restricted access to view their own appointments and add comments, while staff handle the full management of patients and appointments.

## Features

### For Staff
- Full access to all appointments
- Manage patient records
- Add, edit, or delete appointments
- Dashboard overview of total appointments, upcoming appointments, etc.

### For Doctors (Ongoing)
- Access their **own dashboard**
- View **total appointments for today**
- See **next upcoming appointment**
- Clickable card to view **today’s appointments** in detail
- Full **calendar view** of appointments
- Click an appointment to **view patient details** and **add comments**

## Authentication

- **Staff Login**: Full access to the system  
- **Doctor Login**: Limited access to their own appointments  

### Example Staff Account for Testing
- **Username:** zharif  
- **Password:** 12345  

### Example Doctor Account for Testing
- **Username:** doctor 
- **Password:** doctor  

> Note: Only staff can log in using this account. Doctor accounts have their own credentials.

## Installation

1. Clone the repository to your local server (XAMPP/WAMP/LAMP). 
2. Copy and paste all the folder to new folder call Clinic_System 
2. Import the provided database SQL file into MySQL.  
3. Update `config/db.php` with your database credentials.  
4. Access the system via your browser, e.g., `http://localhost/Clinic_System.

## Usage

- **Doctors**:(Upcoming)
  - Log in to view the dashboard.
  - Click on "Total Appointments Today" to see all appointments for the day.
  - Click on any appointment to view patient details and add comments.

- **Staff**:
  - Log in to manage all appointments and patients.
  - Can add, edit, delete appointments and view full patient history.

## Notes

- PHP sessions are used for authentication. Ensure sessions are enabled.  
- FullCalendar library is used for the calendar interface. JS and CSS files must be correctly linked.  
- Comments added by doctors are stored per appointment for staff review.

