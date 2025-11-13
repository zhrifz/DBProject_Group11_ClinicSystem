	Week 2 – Logical Schema & SQL Implementation

	Database Name
	clinic_db

	Tables Created
	1. Staff – Stores login and contact information for clinic administrators or staff users.  
	2. Doctor – Contains doctor details including specialization, working days, and assigned room.  
	3. Patient – Stores patient personal information and emergency contact details.  
	4. Appointment – Links doctors and patients; stores appointment number, date/time, reason, and status.

	Relationships
	- One Doctor → Many Appointments  
	- One Patient → Many Appointments  
	- One Staff (Admin) → Many Appointments (created or managed by staff)

	Normalization
	All tables are normalized up to Third Normal Form (3NF):
	- 1NF: No repeating groups; each field holds atomic values.  
	- 2NF: Every non-key attribute depends entirely on the primary key.  
	- 3NF: No transitive dependencies between non-key attributes.

	Tools Used
	- Database: MySQL (via laragon phpMyAdmin)  
	- ERD Design: Draw.io


